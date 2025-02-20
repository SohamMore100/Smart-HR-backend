<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class emp_details extends Model
{
    use HasFactory;

    protected $table = 'emp_details'; // Specify the table name if it's different

    protected $fillable = [
        'user_id',
        'reporting_manager_id',
        'aadhar',
        'pan',
        'dob',
        'gender',
        'alternate_mobile',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'pin_code',
        'photo',
    ];
}