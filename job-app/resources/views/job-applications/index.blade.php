<x-app-layout>
    <x-slot name="header">
        <div class="relative w-full">
            <div class="absolute inset-0"></div>
            <div class="relative z-10 py-6">
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400">
                    My Applications
                </h1>
                <x-toast-notification />
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($jobApplications as $jobApplication)
                    <div class="group relative bg-gradient-to-br from-gray-900/80 to-gray-800/80 backdrop-blur-xl rounded-2xl p-8 border border-white/10 shadow-2xl transition-all duration-300 hover:shadow-blue-500/10 hover:border-blue-500/20">
                        <!-- Decorative Elements -->
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 rounded-2xl opacity-0 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                <div class="space-y-4 flex-1">
                                    <!-- Job Title -->
                                    <a href="{{ route('job-vacancies.show', $jobApplication->jobVacancy->id) }}" 
                                       class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400 hover:from-blue-300 hover:to-purple-300 transition duration-300">
                                        {{ $jobApplication->jobVacancy->title }}
                                    </a>

                                    <!-- Company Tags -->
                                    <div class="flex flex-wrap gap-3 items-center justify-between w-full">
                                        <span class="bg-gray-700/40 backdrop-blur-sm px-4 py-2 rounded-lg text-white font-medium">
                                            {{ $jobApplication->jobVacancy->company->name }}
                                        </span>
                                        <span class="bg-gray-700/40 backdrop-blur-sm px-4 py-2 rounded-lg text-white">
                                            {{ $jobApplication->jobVacancy->location }}
                                        </span>
                                        <span class="bg-blue-600/30 backdrop-blur-sm px-4 py-2 rounded-lg text-blue-300 font-medium">
                                            {{ $jobApplication->jobVacancy->type }}
                                        </span>
                                        <div class="ml-auto">
                                            <!-- Application Status -->
                                            <x-status :status="$jobApplication->status" class="text-lg">
                                                {{ $jobApplication->status }}
                                            </x-status>
                                        </div>
                                    </div>
                                    <!-- Job Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-300">
                                        <div class="flex items-center gap-2 bg-white/5 rounded-xl p-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $jobApplication->jobVacancy->jobCategory->name }}
                                        </div>

                                        <div class="flex items-center gap-2 bg-white/5 rounded-xl p-3">
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{'$' . number_format($jobApplication->jobVacancy->salary) . ' / Year' }}
                                        </div>

                                        <div class="flex items-center gap-2 bg-white/5 rounded-xl p-3">
                                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Applied: {{ $jobApplication->created_at->format('d M Y') }}
                                        </div>
                                    </div>

                                    <!-- Resume Section -->
                                    <div class="bg-white/5 rounded-xl p-4 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-300">Applied with: {{ $jobApplication->resume->filename }}</span>
                                            <x-link :href="Storage::disk('cloud')->url($jobApplication->resume->fileUrl)" target="_blank" rel="noopener noreferrer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Resume
                                            </x-link>
                                        </div>
                                    </div>

                                    <!-- AI Score & Feedback -->
                                    <div class="bg-white/5 rounded-xl p-4 space-y-3">
                                        <div class="flex items-center gap-3">
                                            <span class="text-gray-300">AI Score:</span>
                                            <span class="bg-blue-600/30 px-4 py-1 rounded-lg text-blue-300 font-medium">
                                                {{ $jobApplication->aiGeneratedScore }}
                                            </span>
                                        </div>
                                        <div class="space-y-2">
                                            <h4 class="text-md font-semibold text-gray-200">AI Feedback:</h4>
                                            <p class="text-sm text-gray-400 leading-relaxed">{{ $jobApplication->aiGeneratedFeedback }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 bg-gray-900/30 backdrop-blur-lg rounded-2xl border border-white/10">
                        <x-message-empty class="text-xl text-gray-400">
                            No applications found
                        </x-message-empty>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $jobApplications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>