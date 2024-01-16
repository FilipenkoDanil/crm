<?php

namespace App\Http\Controllers;

use App\Contracts\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private Payment $payment) {}

    public function service(Request $request): JsonResponse|null
    {
        return $this->payment->service($request);
    }
}
