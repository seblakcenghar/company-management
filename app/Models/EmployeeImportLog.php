<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeImportLog extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'file_name',
        'total_rows',
        'success_rows',
        'failed_rows',
        'status',
        'failed_data',
    ];

    protected $casts = [
        'failed_data' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
