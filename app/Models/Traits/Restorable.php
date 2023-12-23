<?php

namespace App\Models\Traits;

use Illuminate\Http\JsonResponse;

trait Restorable
{
    public function restoreItem(): JsonResponse
    {
        $this->restore();

        return response()->json(['message' => ucfirst(class_basename($this)).' restored.']);
    }
}
