<?php

namespace App\Services\v1;

use App\Contracts\Payment;
use App\Events\SaleCreatedEvent;
use App\Http\Requests\v1\Sale\StoreSaleRequest;
use App\Models\Sale;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiqPay implements Payment
{
    use HandlesMovements;

    public function merchant(Sale $sale, StoreSaleRequest $request = null): JsonResponse
    {
        $data = [
            'version' => "3",
            'public_key' => env('LIQPAY_PUBLIC'),
            'private_key' => env('LIQPAY_SECRET'),
            'action' => 'pay',
            'amount' => $sale->total_amount,
            'currency' => 'UAH',
            'description' => 'Test payment',
            'order_id' => $sale->order_reference,
            'server_url' => env('APP_URL') . '/api/v1/payment',
        ];

        $data = base64_encode(json_encode($data));
        $signature = base64_encode(sha1(env('LIQPAY_SECRET') . $data . env('LIQPAY_SECRET'), true));

        return response()->json([
            'data' => $data,
            'signature' => $signature,
            'server_url' => env('APP_URL') . '/api/v1/payment',
            'payment' => 'card',
            'system' => 'LiqPay'
        ]);
    }

    public function service(Request $request): void
    {
        $encodedData = $request->data;
        $signature = $request->signature;

        $sign = base64_encode(sha1(
            env('LIQPAY_SECRET') .
            $encodedData .
            env('LIQPAY_SECRET')
            , 1));

        if ($sign == $signature) {
            $base64Data = str_pad(strtr($encodedData, '-_', '+/'), strlen($encodedData) % 4, '=');
            $data = json_decode(base64_decode($base64Data));

            $sale = Sale::where('order_reference', $data->order_id)->first();

            if (!$sale->isPaid) {
                $sale->isPaid = true;
                $this->updateStock($sale->movements, 'subtract');
                $sale->save();

                broadcast(new SaleCreatedEvent());
            }
        }
    }
}
