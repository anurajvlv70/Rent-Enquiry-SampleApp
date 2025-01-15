<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rental extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rentals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
}

