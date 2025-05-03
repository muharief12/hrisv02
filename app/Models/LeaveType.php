<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_types';
    protected $guarded = ['id'];

    // relationship db
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'leave_type_id');
    }
}
