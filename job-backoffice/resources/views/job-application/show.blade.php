<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobApplication->user->name }} Applied to {{ $jobApplication->jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        {{-- Success Message --}}
        <x-toast-notification />

        <div class="flex justify-between items-center">
            {{-- Back Button --}}
            <div class="w-80">
                <x-button-back href="{{ route('job-applications.index') }}"></x-button-back>
            </div>
            {{-- Edit & Archive Buttons --}}
            <div class="flex space-x-4 mb-4">
                <x-button-link href="{{ route('job-applications.edit', ['job_application' => $jobApplication->id,'redirectToList' => 'false']) }}">Edit</x-button-link>
                <form action="{{ route('job-applications.destroy', $jobApplication->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the job Applicant ' + '{{ $jobApplication->user->name }}' + '?');">
                    @csrf
                    @method('DELETE')
                    <x-button-archive>Archive</x-button-archive>
                </form>
            </div>
        </div>
        {{-- Wrapper --}}
        <div class="max-w-full mx-auto p-6 bg-white rounded-lg shadow-md">
            {{-- Application Details --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Application Information</h1>
                    <x-status :status="$jobApplication->status">{{ $jobApplication->status }}</x-status>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Applicant</p>
                                <p class="font-semibold">{{ $jobApplication->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="fas fa-briefcase text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Job Title</p>
                                <p class="font-semibold">{{ $jobApplication->jobVacancy->title }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="fas fa-building text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Company</p>
                                <p class="font-semibold">{{ $jobApplication->jobVacancy->company->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm">
                        <div class="p-2 bg-gray-100 rounded-full">
                            <i class="fas fa-file-pdf text-gray-600"></i>
                        </div>
                        <div class="flex-grow">
                            <p class="text-sm text-gray-500">Resume</p>
                            <a href="{{ config('filesystems.disks.cloud.url') . '/' . $jobApplication->resume->fileUrl }}" target="_blank" rel="noopener noreferrer" 
                                class="inline-flex items-center gap-1 text-blue-500 hover:text-blue-700 transition-colors duration-200 font-medium break-all hover:underline">
                                {{ $jobApplication->resume->filename }}
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    stroke-width="2" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round"
                                >
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tabs Navigation --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'resume']) }}" 
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-md {{ in_array(request('tab'), [null, '', 'resume']) ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-file-alt mr-2"></i>Resume Details
                    </a>
                    <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'AIFeedback']) }}" 
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-md {{ request('tab') == 'AIFeedback' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-robot mr-2"></i>AI Assessment
                    </a>
                </nav>
            </div>
            {{-- Tab Content --}}
            <div class="bg-gray-50 rounded-lg p-6">
                {{-- Resume Tab --}}
                <div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block' : 'hidden' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-blue-100 rounded-full mr-3">
                                    <i class="fas fa-user-circle text-blue-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Summary</h3>
                            </div>
                            <p class="text-gray-600">{{ $jobApplication->resume->summary }}</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-100 rounded-full mr-3">
                                    <i class="fas fa-tools text-green-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Skills</h3>
                            </div>
                            <p class="text-gray-600">{{ $jobApplication->resume->skills }}</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-purple-100 rounded-full mr-3">
                                    <i class="fas fa-briefcase text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Experience</h3>
                            </div>
                            <p class="text-gray-600">{{ $jobApplication->resume->experience }}</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-yellow-100 rounded-full mr-3">
                                    <i class="fas fa-graduation-cap text-yellow-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Education</h3>
                            </div>
                            <p class="text-gray-600">{{ $jobApplication->resume->education }}</p>
                        </div>
                    </div>
                </div>

                {{-- AI Feedback Tab --}}
                <div id="AIFeedback" class="{{ request('tab') == 'AIFeedback' ? 'block' : 'hidden' }}">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="border-b border-gray-200">
                            {{-- Bar Chart --}}
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 rounded-full mr-3">
                                            <i class="fas fa-chart-bar text-blue-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800">AI Score Assessment</h3>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $jobApplication->aiGeneratedScore }}%
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $jobApplication->aiGeneratedScore }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-green-100 rounded-full mr-3">
                                    <i class="fas fa-comment-alt text-green-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">AI Feedback</h3>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">{{ $jobApplication->aiGeneratedFeedback }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>