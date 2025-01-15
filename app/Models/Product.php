<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $fillable = [
        'name',
        'sku',
        'available_stock',
        'image',
    ];

    /**
     * Accessor for the image URL.
     * 
     * Assuming the `image` field stores the file path, 
     * this method returns a full URL for the image.
     *
     * @return string
     */
    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }
    // Define the relationship with the EnquiryProduct model
    public function enquiries()
    {
        return $this->belongsToMany(Enquiry::class, 'enquiry_products')->withPivot('quantity')->withTimestamps();
    }
}
