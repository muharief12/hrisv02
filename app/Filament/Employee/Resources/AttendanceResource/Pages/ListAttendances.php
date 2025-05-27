<?php

namespace App\Filament\Employee\Resources\AttendanceResource\Pages;

use App\Filament\Employee\Resources\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Action::make('Presensi')
                ->extraAttributes(['class' => 'text-center'])
                ->label('Presence Today')
                ->icon('heroicon-m-finger-print')
                ->color('success')
                ->form([
                    Placeholder::make('info')
                        ->content(fn() => self::getPresensiStatus())
                ])
                ->action(function () {
                    $userId = Auth::id();
                    $today = Carbon::today()->toDateString();
                    $now = Carbon::now();

                    $attendance = Attendance::where('user_id', $userId)
                        ->where('date', $today)
                        ->first();

                    if (!$attendance) {
                        Attendance::create([
                            'user_id' => $userId,
                            'date' => $today,
                            'start_time' => $now->toTimeString(),
                            'performance' => 0,
                        ]);
                        Notification::make()
                            ->title('The Work Attendance Successfully Recorded.')
                            ->success()
                            ->send();
                    } elseif (!$attendance->end_time) {
                        $attendance->update([
                            'end_time' => $now->toTimeString(),
                        ]);
                        Notification::make()
                            ->title('The End of Work Attendance Successfully Recorded.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sorry, The attendance has been completed.')
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading('Konfirmasi Presensi')
                ->modalDescription('Klik "Simpan" untuk mencatat waktu presensi saat ini.')
                ->modalSubmitActionLabel('Simpan'),
        ];
    }

    protected static function getPresensiStatus(): string
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return 'Anda belum presensi hari ini. Klik simpan untuk presensi masuk.';
        } elseif (!$attendance->end_time) {
            return 'Anda sudah presensi masuk. Klik simpan untuk presensi pulang.';
        }

        return 'Anda sudah presensi masuk dan pulang hari ini.';
    }
}
