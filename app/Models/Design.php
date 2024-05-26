<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Design
 *
 * @property $id
 * @property $name
 * @property $price
 * @property $image
 * @property $technique
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Design extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'price', 'image', 'technique'];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
