<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryProduct extends Model
{
    
    protected $fillable = [
        'enquiry_id',
        'product_id',
        'quantity'
    ];

   
    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
