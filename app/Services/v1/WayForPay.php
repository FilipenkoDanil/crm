<?php

namespace App\Services\v1;

use App\Contracts\Payment;
use App\Events\SaleCreatedEvent;
use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WayForPay implements Payment
{
    use HandlesMovements;

    public function merchant(Sale $sale, StoreSaleRequest $request = null): JsonResponse
    {
        $resultData = [
            'productName' => [],
            'productPrice' => [],
            'productCount' => [],
        ];

        $data = $request->data;

        $productNameString = '';
        $productPriceString = '';
        $productCountString = '';
        foreach ($data as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $resultData['productName'][] = $product->title;
                $resultData['productPrice'][] = $product->selling_price;
                $resultData['productCount'][] = $item['quantity'];

                $productNameString .= $product->title . ';';
                $productPriceString .= $product->selling_price . ';';
                $productCountString .= $item['quantity'] . ';';
            }
        }

        $merchantAccount = env('MERCHANT_ACCOUNT');
        $merchantDomainName = env('APP_URL');
        $orderReference = $sale->order_reference;
        $orderDate = time();
        $amount = $sale->total_amount;
        $currency = 'UAH';

        $merchantSignature = $merchantAccount . ';' . $merchantDomainName . ';' . $orderReference . ';' . $orderDate . ';' . $amount . ';' . $currency . ';' . $productNameString . $productCountString . $productPriceString;
        $merchantSignature = substr($merchantSignature, 0, -1);
        $hash = hash_hmac('md5', $merchantSignature, env('MERCHANT_SECRET'));

        return response()->json([
            $resultData,
            'merchantAccount' => $merchantAccount,
            'merchantDomainName' => $merchantDomainName,
            'orderReference' => $orderReference,
            'orderDate' => $orderDate,
            'amount' => $amount,
            'currency' => $currency,
            'merchantSignature' => $hash,
            'serviceUrl' => env('APP_URL') . '/api/v1/payment',
            'payment' => 'card',
            'system' => 'WayForPay'
        ]);
    }

    public function service(Request $request): JsonResponse
    {
        $json = $request->getContent();
        $obj = json_decode($json, true);

        $time = time();
        $string = $obj['orderReference'] . ';accept;' . $time;
        $hash = hash_hmac('md5', $string, env('MERCHANT_SECRET'));

        $sale = Sale::where('order_reference', $obj['orderReference'])->first();

        switch ($obj['transactionStatus']) {
            case 'Approved':
                if (!$sale->isPaid) {
                    $sale->isPaid = true;
                    $this->updateStock($sale->movements, 'subtract');
                    $sale->save();
                }
                break;

            case 'Declined':
            case 'Expired':
            case 'Refunded':
                if ($sale->isPaid) {
                    $this->updateStock($sale->movements);
                    $sale->isPaid = false;
                    $sale->save();
                }
                break;
        }

        broadcast(new SaleCreatedEvent());

        return response()->json([
            'orderReference' => $obj['orderReference'],
            'status' => 'accept',
            'time' => $time,
            'signature' => $hash
        ]);
    }
}
