<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterHistory extends Model{
    use HasFactory;

    protected $fillable =[
        'meter_id',
        'date',
        'count',
        'unit',
        'image',
    ];

    public function fileAttachThumbnail()
    {
        return $this->hasOne(FileManager::class, 'id', 'image')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }
}