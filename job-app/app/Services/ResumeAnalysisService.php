<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use HosseinHezami\LaravelGemini\Facades\Gemini;

class ResumeAnalysisService
{
    public function extractResumeInformation(string $fileUrl)
    {
        try {
            // Extract raw text from resume pdf file (read pdf file, and get the text)
            $rawText = $this->extractTextFromPdf($fileUrl);

            Log::debug('Successfully extracted resume text from pdf file ' . strlen($rawText) . ' characters');

            // Use Gemini API to organize the text into structured format (sumary, skills, experience, education) -> JSON
            $response = Gemini::text()
            // ->model('gemini-2.5-pro')
            ->model('gemini-2.5-flash')
            ->system('You are a percise resume parser. Extract information exactly as it appears in the resume without adding any interpretation or additional information. The output shuould be in JSON format.')
            ->structuredSchema([ 
                'type' => 'object',
                'properties' => [
                    'summary' => [
                        'type' => 'string',
                    ],
                    'skills' => [
                        'type' => 'array',
                        'description' => 'List of skills',
                        'items' => ['type' => 'string']
                    ],
                    'experience' => [
                        'type' => 'array',
                        'description' => 'List of work experiences (title, company, duration)',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'title' => ['type' => 'string'],
                                'company' => ['type' => 'string'],
                                'duration' => ['type' => 'string'],
                                'description' => ['type' => 'string']
                            ]
                        ]
                    ],
                    'education' => [
                        'type' => 'array',
                        'description' => 'List of educational degrees and institutions',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'degree' => ['type' => 'string'],
                                'institution' => ['type' => 'string'],
                                'year' => ['type' => 'string']
                            ]
                        ]
                    ],
                ],
                'required' => ['summary', 'skills', 'experience', 'education'] // تحديد الحقول الإلزامية
            ])
            ->prompt("Parse the following resume content and extract the information as JSON object with the exact keys: 'summary', 'skills', 'experience', 'education'. 
                The resume content is: {$rawText}. 
                Return an empty string for the 'summary' key, and an **empty array []** for the 'skills', 'experience', and 'education' keys if content is not found.")
            ->temperature(0.1)
            ->maxTokens(8192)
            ->generate();

            $result = $response->content();
            Log::debug('Gemini respons: ' . $result);

            $parsedResult = json_decode($result, true);

            if(json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini JSON: ' . json_last_error_msg());
                throw new \Exception('Failed to parse Gemini response');
            }

            // Validate the parsed result
            $requiredKeys = ['summary', 'skills', 'experience', 'education'];
            $missingKeys = array_diff($requiredKeys, array_keys($parsedResult));

            if(count($missingKeys) > 0) {
                Log::error('Missing keys in parsed JSON: ' . implode(', ', $missingKeys));
                throw new \Exception('Missing keys in parsed JSON');
            }

            // Return the JSON object
            return [
                'summary' => $parsedResult['summary'] ?? '',
                'skills' => json_encode($parsedResult['skills'] ?? []),
                'experience' => json_encode($parsedResult['experience'] ?? []),
                'education' => json_encode($parsedResult['education'] ?? []),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to extract resume information: ' . $e->getMessage());
            return [
                'summary' => '',
                'skills' => json_encode([]),      
                'experience' => json_encode([]),   
                'education' => json_encode([]), 
            ];
        }    
    }

    public function analyzeResume($jobVacancy, $resumeData)
    {
        try {
            $jobDetails = json_encode([
                'job_title' => $jobVacancy->title,
                'job_description' => $jobVacancy->description,
                'job_location' => $jobVacancy->location,
                'job_type' => $jobVacancy->type,
                'job_salary' => $jobVacancy->salary
            ]);

            $resumeDetails = json_encode($resumeData);

            $response = Gemini::text()
            // ->model('gemini-2.5-pro')
            ->model('gemini-2.5-flash')
            ->system("You are expert HR professional and job recruiter. 
                You are given a job vacancy and a resume.
                Your task is to analyze the resume and determine if the candidate is a good fit for the job.
                You will evaluate the resume based on the job details and the resume details.
                The output should be JSON format.
                Provide a score out of 100 for the candidate's suitability for the job, and a detailed feedback.
                Response should only be JSON that has the following keys: 'aiGeneratedScore', 'aiGeneratedFeedback'.
                Aigenerate feedback should be detailed and specific to the job and candidate's resume.
            ")
            ->structuredSchema([ 
                'type' => 'object',
                'properties' => [
                    'aiGeneratedScore' => [
                        'type' => 'number',
                        'description' => 'AI-generated score out of 100',
                    ],
                    'aiGeneratedFeedback' => [
                        'type' => 'string',
                        'description' => 'List of skills',
                    ],
                ],
                'required' => ['aiGeneratedScore', 'aiGeneratedFeedback'] 
            ])
            ->prompt("Please evalute this job application. Job Details: {$jobDetails}. Resume Details: {$resumeDetails}.")
            ->temperature(0.1)
            ->maxTokens(8192)
            ->generate();

            $result = $response->content();
            Log::debug('Gemini evaluation respons: ' . $result);

            $parsedResult = json_decode($result, true);

            if(json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Gemini JSON: ' . json_last_error_msg());
                throw new \Exception('Failed to parse Gemini response');
            }

            // Validate the parsed result
            if(!isset($parsedResult['aiGeneratedScore']) || !isset($parsedResult['aiGeneratedFeedback'])) {
                Log::error('Missing required keys in parsed result');
                throw new \Exception('Missing required keys in parsed result');
            }

            return $parsedResult;

        } catch (\Exception $e) {  
            Log::error('Error analyzing resume: ' . $e->getMessage());
            return [
                'aiGeneratedScore' => 0,
                'aiGeneratedFeedback' => 'An error occurred while analyzing the resume. Please try again later.',
            ];
        }

      
    }

    public function extractTextFromPdf(string $fileUrl): string 
    {
        // Reading the file from Cloud to local storage in temp folder
        $tempFile = tempnam(sys_get_temp_dir(), 'resume');
        $filePath = parse_url($fileUrl, PHP_URL_PATH);
        if(!$filePath) {
            throw new \Exception('Invalid file URL');
        }

        $fileName = basename($filePath);    
        $storagePath = "resumes/$fileName";

        if(!Storage::disk('cloud')->exists($storagePath)) {
            throw new \Exception('File not found');
        }

        $pdfContent = Storage::disk('cloud')->get($storagePath);
        if(!$pdfContent) {
            throw new \Exception('Failed to read PDF file');
        }

        file_put_contents($tempFile, $pdfContent);
        
        // Check if pdf-to-text is installed
        $pdfToTextPath = ['/opt/homebrew/bin/pdftotext', '/usr/bin/pdftotext', '/usr/local/bin/pdftotext'];
        $pdfToTextAvailable = false;

        foreach ($pdfToTextPath as $path) {
            if (file_exists($path)) {
                $pdfToTextAvailable = true;
                break;
            }
        }

        if(!$pdfToTextAvailable) {
            throw new \Exception('pdf-to-text is not installed');
        }

        // Extract text from PDF
        $instance = new Pdf();
        $instance->setPdf($tempFile);
        $text = $instance->text();

        // Clean up the temp file
        unlink($tempFile);

        return $text;
    }
}