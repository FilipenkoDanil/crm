<?php

namespace App\Services\v1;

use App\Events\SaleCreatedEvent;
use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Http\Resources\v1\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SaleService
{
    use HandlesMovements;

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $sale = DB::transaction(function () use ($request, $data) {
                $sale = $this->createSale($data);
                $this->createMovements($sale, $request->input('data'), $data['warehouse_id'], 'selling_price');
                $this->updateTotalAmount($sale);
                $this->calculateProfit($sale);

                if (strtolower($sale->payment->type) !== 'card') {
                    $this->updateStock($sale->movements, 'subtract');
                }

                return $sale;
            });

            broadcast(new SaleCreatedEvent());

            if (strtolower($sale->payment->type) === 'card') {
                return $this->createMerchant($request->input('data'), $sale);
            }

            $sale->isPaid = true;
            $sale->save();

            return response()->json(new SaleResource($sale));
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    protected function createSale(array $data)
    {
        if (auth()->check()) {
            return Sale::create([
                'client_id' => $data['client_id'],
                'user_id' => auth()->id(),
                'payment_id' => $data['payment_id'],
                'order_reference' => 'OR' . time(),
            ]);
        } else {
            throw new \Exception('Unauthorized.', 419);
        }
    }

    protected function calculateProfit(Sale $sale): void
    {
        $sale->profit = $sale->movements->map(function ($item) {
            return ($item->unit_price - $item->product->purchase_price) * $item->quantity;
        })->sum();
        $sale->save();
    }

    protected function createMerchant(array $data, $sale)
    {
        $resultData = [
            'productName' => [],
            'productPrice' => [],
            'productCount' => [],
        ];


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
            'serviceUrl' => env('APP_URL') . '/api/v1/wayforpay',
            'payment' => 'card'
        ]);
    }
}
