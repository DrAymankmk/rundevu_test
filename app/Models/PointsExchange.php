<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsExchange extends Model
{
    use HasFactory;

    protected $fillable = ['points', 'price', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
    ];

    public static function latestActive(): ?self
    {
        return static::where('status', 1)->latest('id')->first();
    }

    public function pointsForPrice(float $priceAmount): int
    {
        $ratePrice = (float) $this->price;

        if ($priceAmount <= 0 || $ratePrice <= 0 || (int) $this->points <= 0) {
            return 0;
        }

        return (int) round($priceAmount * ((int) $this->points / $ratePrice));
    }
}
