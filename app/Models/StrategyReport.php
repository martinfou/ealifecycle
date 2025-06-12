<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'strategy_id',
        'file_path',
        'original_filename',
        'uploaded_by',
    ];

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
