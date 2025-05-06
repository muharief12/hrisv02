<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyTask extends Model
{
    use HasFactory;

    protected $table = 'daily_tasks';
    protected $guarded = ['id'];


    protected static function booted()
    {
        static::saved(function ($dailyTask) {
            $attendance = $dailyTask->attendance;
            if ($attendance) {
                $totalTasks = $attendance->dailyTasks()->count();
                $completedTasks = $attendance->dailyTasks()->where('status', true)->count();
                $performanceRate = ($completedTasks / $totalTasks) * 100;
                $attendance->pefomance_rate = $performanceRate;
                $attendance->save();
            }
        });
    }


    // Relationship db
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }
}
