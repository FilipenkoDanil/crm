<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Traits\HandlesMovements;
use Illuminate\Http\JsonResponse;

class WayForPayController extends Controller
{
    use HandlesMovements;

    public function service(): JsonResponse
    {
        $json = file_get_contents('php://input');
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

        return response()->json([
            'orderReference' => $obj['orderReference'],
            'status' => 'accept',
            'time' => $time,
            'signature' => $hash
        ]);
    }
}
