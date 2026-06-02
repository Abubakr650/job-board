<x-app-layout>
    <x-slot name="header">
        <div class="relative w-full">
            <!-- خلفية متدرجة -->
            <div class="absolute inset-0"></div>
            
            <div class="relative z-10 py-6">

                <!-- قسم البحث والفلترة -->
                <div class="w-full">
                    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 ">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <!-- شريط البحث -->
                            <div class="w-full md:w-2/3">
                                <x-search-bar 
                                    action="{{ route('dashboard') }}"
                                    placeholder="Search jobs, companies, or locations..." 
                                    class="w-full"
                                />
                            </div>
                            
                            <!-- الفلتر -->
                            {{-- <div class="flex w-4 md:w-1/5">
                                @php
                                $jobTypes = [
                                    ['id' => 1, 'name' => 'Full-Time', 'value' => 'Full-time'],
                                    ['id' => 2, 'name' => 'Part-Time', 'value' => 'Part-time'],
                                    ['id' => 3, 'name' => 'Remot', 'value' => 'Remot'],
                                    ['id' => 4, 'name' => 'Hybrid', 'value' => 'Hybrid'],
                                    ['id' => 5, 'name' => 'Contract', 'value' => 'Contract'],
                                ];

                                $jobCategoy = [
                                    ['id' => 1, 'name' => 'Development', 'value' => 'Development'],
                                    ['id' => 2, 'name' => 'Finance/Accounting', 'value' => 'Finance/Accounting'],
                                    ['id' => 3, 'name' => 'Healthcare', 'value' => 'Healthcare'],
                                    ['id' => 4, 'name' => 'Administration', 'value' => 'Administration'],
                                    ['id' => 5, 'name' => 'Engineering', 'value' => 'Engineering'],
                                ];
                                @endphp

                                <x-filters 
                                    :categories="$jobTypes"
                                    title="Job Type"
                                    apply-filter-route="{{ route('dashboard') }}"
                                />

                                <x-filters 
                                    :categories="$jobCategoy"
                                    title="Job Categotry"
                                    apply-filter-route="{{ route('dashboard') }}"
                                />
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- بطاقة الترحيب مع تأثير زجاجي -->
            <div class="bg-gradient-to-r from-gray-900/50 to-gray-800/50 backdrop-blur-lg rounded-2xl p-8 mb-8 border border-white/10 shadow-xl">
                <h3 class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                    @if(Auth::user())
                        {{ __('Welcome back,') }} {{ Auth::user()->name }}!
                    @else
                        {{ __('Welcome') }}
                    @endif
                </h3>
            </div>

            <!-- قائمة الوظائف -->
            <div class="space-y-4">
                @forelse($jobs as $job)
                    <div class="bg-gray-900/30 hover:bg-gray-800/50 backdrop-blur-lg rounded-xl p-6 border border-white/10 shadow-lg transition duration-300 ease-in-out transform hover:scale-[1.02]">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="space-y-2">
                                <a href="{{ route('job-vacancies.show', $job->id) }}" class="text-xl font-bold text-blue-400 hover:text-blue-300 transition duration-300">
                                    {{ $job->title }}
                                </a>
                                
                                <div class="flex flex-wrap gap-2 items-center text-sm">
                                    <span class="bg-gray-700/50 px-3 py-1 rounded-full text-white">
                                        {{ $job->company->name }}
                                    </span>
                                    <span class="bg-gray-700/50 px-3 py-1 rounded-full text-white">
                                        {{ $job->location }}
                                    </span>
                                    <span class="bg-blue-600/50 px-3 py-1 rounded-full text-white">
                                        {{ $job->type }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap gap-4 text-sm text-gray-300">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $job->jobCategory->name }}
                                    </div>
                                    
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{'$' . number_format($job->salary) . ' / Year' }}
                                    </div>
                                    
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $job->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <x-message-empty>
                        No jobs found !
                    </x-message-empty>
                @endforelse
            </div>

            <!-- الترقيم -->
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>