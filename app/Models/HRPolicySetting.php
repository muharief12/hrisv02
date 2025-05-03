<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRPolicySetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_policy_settings';
    protected $guarded = ['id'];
}
