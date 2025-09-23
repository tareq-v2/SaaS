<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'filename',
        'total_rows',
        'processed_rows',
        'successful_rows',
        'failed_rows',
        'status',
        'error_message'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
