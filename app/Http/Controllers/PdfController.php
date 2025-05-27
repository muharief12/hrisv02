<?php

namespace App\Http\Controllers;

use App\Models\HRPolicySetting;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    // public function index()
    // {
    //     return view('pdf');
    // }


    public function __invoke(Salary $salary)
    {
        $salary->load('user');
        $policy = HRPolicySetting::first();


        return Pdf::loadView('pdf', ['record' => $salary, 'policy' => $policy])
            ->stream('Payroll Report for_' . '_' . Carbon::parse($salary->date)->format('Y-m') . '.pdf');
    }
}
