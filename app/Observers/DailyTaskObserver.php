<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\DailyTask;

class DailyTaskObserver
{
    /**
     * Handle the DailyTask "created" event.
     */
    public function created(DailyTask $dailyTask): void
    {
        $this->updatePerformanceRate($dailyTask);
    }

    /**
     * Handle the DailyTask "updated" event.
     */
    public function updated(DailyTask $dailyTask): void
    {
        $this->updatePerformanceRate($dailyTask);
    }

    /**
     * Handle the DailyTask "deleted" event.
     */
    public function deleted(DailyTask $dailyTask): void
    {
        $this->updatePerformanceRate($dailyTask);
    }

    /**
     * Handle the DailyTask "restored" event.
     */
    public function restored(DailyTask $dailyTask): void
    {
        //
    }

    /**
     * Handle the DailyTask "force deleted" event.
     */
    public function forceDeleted(DailyTask $dailyTask): void
    {
        //
    }


    private function updatePerformanceRate(DailyTask $dailyTask)
    {
        $attendance = Attendance::find($dailyTask->attendance_id);
        if ($attendance) {
            $totalTasks = $attendance->dailyTasks()->count();
            $completedTasks = $attendance->dailyTasks()->where('status', true)->count();
            $performanceRate = ($completedTasks / $totalTasks) * 100;
            $attendance->pefomance_rate = $performanceRate;
            $attendance->save();
        }
    }
}
