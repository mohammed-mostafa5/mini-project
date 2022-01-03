<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'category_id',
        'subcategory_id',
        'payer_id',
        'amount',
        'due_on',
        'vat',
        'is_vat_inclusive',
        'status',
    ];


    ############################### Relations ###############################

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
