<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exp_details extends Model
{
    use HasFactory;

    protected $table = 'exp_details'; // table name

    protected $fillable = [
        'user_id',
        'last_company',
        'exp_start_date',
        'exp_end_date',
        'last_designation',
        'last_salary',
        'current_exp',
        'current_salary',
        'total_exp',
        'payslip1',
        'payslip2',
        'payslip3',
        'offer_letter',
        'exp_letter',
        'inc_letter',
        'UAN',
    ];


    public function reportingManager()
    {
        return $this->belongsTo(User::class, 'reporting_manager_id', 'id');
    }

}
