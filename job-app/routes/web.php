<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// if(Auth::user()){
//     Route::get('/', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified', 'role:job-seeker'])->name('dashboard');
// }
// else{
//     Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
// }

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/job-vacancies/{id}', [JobVacancyController::class, 'show'])->name('job-vacancies.show');

Route::middleware(['auth', 'verified', 'role:job-seeker'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/job-vacancies/{id}/apply', [JobVacancyController::class, 'apply'])->name('job-vacancies.apply');
    Route::post('/job-vacancies/{id}/apply', [JobVacancyController::class, 'processApplication'])->name('job-vacancies.process-application');
    Route::get('job-applications/{id}', [JobApplicationController::class, 'index'])->name('job-applications.index');
});



require __DIR__.'/auth.php';
 