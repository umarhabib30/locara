<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meter extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'property_id',
        'unit_id',
        'meter_type',
    ];

    public function latestHistory()
    {
        return $this->hasOne(MeterHistory::class, 'meter_id')->latest('date');
    }

    public function property(){
        return $this->belongsTo(Property::class,'property_id');
    }
    public function unit(){
        return $this->belongsTo(PropertyUnit::class,'property_id');
    }
}
