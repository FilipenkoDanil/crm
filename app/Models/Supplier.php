<?php

namespace App\Models;

use App\Models\Traits\Restorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Restorable;

    protected $fillable = ['name', 'phone', 'company'];


}
