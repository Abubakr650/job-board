<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
//use Barryvdh\DompDF\Facade\Pdf;
class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $analytics = $this->adminDashboard();
        } else if(Auth::user()->role == 'company-owner'){
            $analytics = $this->companyOwnerDashboard();
        }

        return view('dashboard.index', compact('analytics'));
    }

    private function adminDashboard()
    {
        // Last 30 days active users (job-seeker role)
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
            ->where('role', 'job-seeker')->count();
        
        // Total jobs (not archived)
        $totalJobs = JobVacancy::whereNull('deleted_at')->count();

        // Total applications (not archived)
        $totalApplications = JobApplication::whereNull('deleted_at')->count();

        // Most applied jobs
        $mostAppliedJobs = JobVacancy::withCount('jobApplications as totalCount')
            ->whereNull('deleted_at')
            ->having('totalCount', '>', 0)
            ->orderByDesc('totalCount')
            ->get();
       
        // Conversion rates
        $conversionRates = JobVacancy::withCount('jobApplications as totalCount')
            ->whereNull('deleted_at')
            ->having('totalCount', '>', 0)
            ->orderByDesc('totalCount')
            ->get()
            ->map(function ($job){
                if($job->viewCount > 0){
                    $job->conversionRate = round(($job->totalCount / $job->viewCount) * 100, 2);
                } else {
                    $job->conversionRate = 0;
                }
                return $job;
            });

        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates' => $conversionRates,
        ];

        return $analytics;
    }

    private function companyOwnerDashboard()
    {
        $company = Auth::user()->company;

        // Filter active users by applying to jobs of the company
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
            ->where('role', 'job-seeker')
            ->whereHas('jobApplications', function ($query) use ($company) {
                $query->whereIn('jobVacancyId', $company->jobVacancies->pluck('id'));
            })
            ->count();

        // Total jobs of the company (not archived)
        $totalJobs = $company->jobVacancies->count();
        
        // Total applications of the company (not archived)
        $totalApplications = JobApplication::whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))->whereNull('deleted_at')->count();

        // Most applied jobs of the company
        $mostAppliedJobs = JobVacancy::withCount('jobApplications as totalCount')
            ->whereIn('id', $company->jobVacancies->pluck('id'))
            ->whereNull('deleted_at')
            ->having('totalCount', '>', 0)
            ->orderByDesc('totalCount')
            ->get();
       
        // Conversion rates of the company
        $conversionRates = JobVacancy::withCount('jobApplications as totalCount')
            ->whereIn('id', $company->jobVacancies->pluck('id'))
            ->whereNull('deleted_at')
            ->having('totalCount', '>', 0)
            ->orderByDesc('totalCount')
            ->get()
            ->map(function ($job){
                if($job->viewCount > 0){
                    $job->conversionRate = round(($job->totalCount / $job->viewCount) * 100, 2);
                } else {
                    $job->conversionRate = 0;
                }
                return $job;
            });

        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates' => $conversionRates,
        ];

        return $analytics;
    }

    // Report function
     public function downloadConversionReport()
    {
        if(Auth::user()->role == 'admin'){
            $conversionRates = $this->adminDashboard()['conversionRates'];
        } else if(Auth::user()->role == 'company-owner'){
            $conversionRates = $this->companyOwnerDashboard()['conversionRates'];
        }

        $pdf = PDF::loadView('reports.conversion-rates', [
            'conversionRates' => $conversionRates,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
            'user' => Auth::user()
        ]);

        // تعيين خيارات PDF
        $pdf->setPaper('A4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
        ]);

        return $pdf->download('conversion-rates-report-' . now()->format('Y-m-d') . '.pdf');
    }
}


