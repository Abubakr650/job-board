<x-app-layout>
    <x-slot name="header">
        <div class="relative w-full">
            <!-- خلفية متدرجة -->
            <div class="absolute inset-0"></div>
            
            <div class="relative z-10 py-6 flex">
                <x-button-back href="{{ route('job-vacancies.show', $jobVacancy->id) }}"></x-button-back>
                <h1 class="font-semibold text-2xl text-white leading-tight mx-4">
                    {{ $jobVacancy->title }} - Apply
                </h1>
            </div>
            
        </div>
    </x-slot> 
    <div class="py-12 bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/10 shadow-2xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold text-white">{{ $jobVacancy->title }}</h1>
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-500/20 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-lg font-semibold text-blue-400">{{ $jobVacancy->company->name }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2 bg-gray-800/50 px-4 py-2 rounded-full">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-300">{{ $jobVacancy->location }}</p>
                            </div>
                            
                            <div class="flex items-center gap-2 bg-gray-800/50 px-4 py-2 rounded-full">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-300">{{ '$' . number_format($jobVacancy->salary) }}</p>
                            </div>
                            
                            <div class="bg-blue-500/20 px-4 py-2 rounded-full">
                                <p class="text-sm font-medium text-blue-400">{{ $jobVacancy->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('job-vacancies.process-application', $jobVacancy->id) }}" method="POST" class="space-y-6"
                    enctype="multipart/form-data">
                    @csrf

                    @if($errors->any())
                        <div class="bg-red-500 text-white rounded-lg p-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Resume Selection --}}
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>
                        <div class="mb-6">
                            <x-input-label for="resume" :value="__('Select from your resumes:')" />
                            {{-- List of resumes --}}
                            <div class="space-y-4">
                                @forelse($resumes as $resume)
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="resume_option" id="{{ $resume->id }}" value="{{ $resume->id }}"
                                            @error('resume_option') class="border-red-500" @else class="border-gray-600" @enderror>
                                        <x-input-label for="existing_{{ $resume->id }}" class="text-white cursor-pointer">
                                            {{ $resume->filename }}
                                            <span class="text-sm text-gray-400">(Last updated: {{ $resume->updated_at->diffForHumans() }})</span>
                                        </x-input-label>
                                    </div>
                                @empty   
                                    <span class="text-sm text-gray-400">No resumes found</span>
                                @endforelse
                            </div>
                        </div>
                    </div> 

                    {{-- Upload New Resume --}}
                    <div x-data="{ fileName: '', hasError:  {{ $errors->has('resume_file') ? 'true' : 'false' }} }">
                        <div class="flex items-center gap-2">
                            <input x-ref="newResumeRadio" type="radio" name="resume_option" id="new_resume" value="new_resume"
                                @error('resume_option') class="border-red-500" @else class="border-gray-600" @enderror>
                            <x-input-label class="text-white cursor-pointer" for="new_resume" :value="__('Upload a new resume:')" />
                        </div>
                        <div class="flex items-center">
                            <div class="flex-1">
                                <label for="new_resume_file" class="block text-sm font-medium text-gray-700 cursor-pointer">
                                    <div 
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 mt-2 transtion"
                                        :class="{ 'border-blue-500': fileName, 'border-red-500': hasError }">
                                        <input @change="fileName = $event.target.files[0].name; $refs.newResumeRadio.checked = true" type="file" id="new_resume_file" 
                                        name="resume_file" class="hidden" accept=".pdf" />
                                        <div class="text-center">
                                            <template x-if="!fileName">
                                                <p class="text-gray-400">Upload PDF (MAX 5MB)</p>
                                            </template>
                                            <template x-if="fileName">
                                                <div>
                                                    <p x-text="fileName" class="mt-2 text-blue-400"></p>
                                                    <p class="text-gray-400 text-sm mt-1">Click to change file</p>
                                                </div>    
                                            </template>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <x-primary-button class="w-full">
                            Apply Now
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>