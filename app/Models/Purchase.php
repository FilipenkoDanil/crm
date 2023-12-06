<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'user_id', 'total_amount', 'isApproved'];

    public function movements()
    {
        return $this->morphMany(Movement::class, 'movementable');
    }
}
