<x-app-layout>
    <x-slot name="header">
        <div class="relative w-full">
            <!-- خلفية متدرجة -->
            <div class="absolute inset-0"></div>
            
            <div class="relative z-10 py-6 flex">
                <x-button-back href="{{ route('dashboard') }}"></x-button-back>
                <h1 class="font-semibold text-2xl text-white leading-tight mx-4">
                    {{ $jobVacancy->title }} - Job Details
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
                    
                    <a href="{{ Auth::user() ? route('job-vacancies.apply', $jobVacancy->id) : route('login') }}" 
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-blue-500/25 transition duration-300 ease-in-out transform hover:scale-105">
                        <span class="font-semibold">Apply Now</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-12">
                    <div class="lg:col-span-2">
                        <div class="bg-gray-800/30 backdrop-blur-lg rounded-xl p-6 border border-white/10">
                            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Job Description
                            </h2>
                            <div class="prose prose-lg prose-invert">
                                <p class="text-gray-300 leading-relaxed">{!! $jobVacancy->description !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-gray-800/30 backdrop-blur-lg rounded-xl p-6 border border-white/10 sticky top-6">
                            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Job Overview
                            </h2>
                            
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Published Date</p>
                                        <p class="text-white font-medium">{{ $jobVacancy->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Company</p>
                                        <p class="text-white font-medium">{{ $jobVacancy->company->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Location</p>
                                        <p class="text-white font-medium">{{ $jobVacancy->location }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Salary</p>
                                        <p class="text-white font-medium">{{ '$' . number_format($jobVacancy->salary) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Type</p>
                                        <p class="text-white font-medium">{{ $jobVacancy->type }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gray-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-gray-400">Category</p>
                                        <p class="text-white font-medium">{{ $jobVacancy->jobCategory->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>