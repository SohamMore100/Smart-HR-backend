<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class edu_details extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'edu_details';

    protected $fillable = [
        'user_id',
        'ssc_schoole',
        'ssc_per',
        'ssc_passout_year',
        'ssc_board',
        'hsc_school',
        'hsc_per',
        'hsc_passout_year',
        'hsc_board',
        'hsc_stream',
        'graduation_college',
        'graduation_cgpa',
        'graduation_start_year',
        'graduation_passout_year',
        'graduation_university',
        'PG_college',
        'pg_cgpa',
        'pg_start_year',
        'pg_passout_year',
        'pg_university',
        'doc_ssc',
        'doc_hsc',
        'doc_graduation',
        'doc_pg'
    ];

}
