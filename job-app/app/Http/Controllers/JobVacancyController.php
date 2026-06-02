<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ResumeAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use OpenAI\Laravel\Facades\OpenAI;
use HosseinHezami\LaravelGemini\Facades\Gemini;

class JobVacancyController extends Controller
{
    protected $resumeAnalysisService;

    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }
    public function show(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    public function apply(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumes = Auth::user()->resumes; 
        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    public function processApplication(ApplyJobRequest $request, string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumeId = null;
        $extractedInfo = null;

        // New resume
        if ($request->input('resume_option') === 'new_resume') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();
            $fileName = 'resume_' . time() . '.' . $extension;

            // Store in Laravel Cloud Storage
            $path = $file->storeAs('resumes', $fileName, 'cloud');

            $fileUrl = config('filesystems.disks.cloud.url') . '/' . $path;

            $extractedInfo = $this->resumeAnalysisService->extractResumeInformation($fileUrl);  

            $resume = Resume::create([
                'filename' => $originalFileName,
                'fileUrl' => $path,
                'userId' => Auth::id(),
                'contactDetails' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]),
                'summary' => $extractedInfo['summary'],
                'skills' => $extractedInfo['skills'],
                'experience' => $extractedInfo['experience'],
                'education' => $extractedInfo['education'],
            ]);

            $resumeId = $resume->id;

        }
        // Select existing resume
        else {
            $resumeId = $request->input('resume_option');
            $resume = Resume::findOrFail($resumeId);

            $extractedInfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
            ];
        }
        // Evaluate Job Application
        $evaluation = $this->resumeAnalysisService->analyzeResume($jobVacancy, $extractedInfo);
        JobApplication::create([
            'status' => 'pending',
            'jobVacancyId' => $id,
            'resumeId' => $resumeId,
            'userId' => Auth::id(),
            'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
            'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
        ]);

        return redirect()->route('job-applications.index', $id)->with('success', 'Application submitted successfully.');

    }
}
