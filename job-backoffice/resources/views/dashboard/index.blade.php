<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6 flex flex-col gap-6 bg-gray-50">
        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Total Active Users --}}
            <div class="p-6 bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Active Users</h3>
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-indigo-600">{{ $analytics['activeUsers'] }}</p>
                    <p class="text-base font-medium text-gray-500">Last 30 days</p>
                </div>
            </div>

            {{-- Total Jobs --}}
            <div class="p-6 bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Total Jobs</h3>
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-emerald-600">{{ $analytics['totalJobs'] }}</p>
                    <p class="text-base font-medium text-gray-500">All time</p>
                </div>
            </div>

            {{-- Total Applicants --}}
            <div class="p-6 bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Total Applicants</h3>
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-purple-600">{{ $analytics['totalApplications'] }}</p>
                    <p class="text-base font-medium text-gray-500">All time</p>
                </div>
            </div>
        </div>

        {{-- Most Applied Jobs --}}
        <div x-data="{ showAllJobs: false }" class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Most Applied Jobs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Job Title</th>
                                @if(Auth::user()->role == 'admin')
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                                @endif
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Applications</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($analytics['mostAppliedJobs'] as $index => $job)
                                <tr
                                    x-show="showAllJobs || {{ $index }} < 5"
                                    x-cloak
                                    x-transition>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $job->title }}</td>
                                    @if (Auth::user()->role == 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">{{ $job->company->name }}</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                        <span class="px-4 py-2 bg-indigo-100 text-indigo-600 rounded-full font-medium">
                                            {{ $job->totalCount }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($analytics['mostAppliedJobs'] && $analytics['mostAppliedJobs']->count() > 5)
                        <div class="mt-4 text-right">
                            <x-button-view-all :toggleVar="'showAllJobs'" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div x-data="{ showAllRates: false }" class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Conversion Rate</h3>
                    {{-- Button Report --}}
                    <a href="{{ route('dashboard.conversion-report') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-base font-medium hover:bg-indigo-100 transition-colors duration-200">
                        Download Report
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Job Title</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Applications</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($analytics['conversionRates'] as $index => $conversionRate)
                                <tr
                                    x-show="showAllRates || {{ $index }} < 5"
                                    x-cloak
                                    x-transition>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $conversionRate->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                        <span class="px-4 py-2 bg-indigo-100 text-indigo-600 rounded-full font-medium">
                                            {{ $conversionRate->viewCount }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                        <span class="px-4 py-2 bg-indigo-100 text-indigo-600 rounded-full font-medium">
                                            {{ $conversionRate->totalCount }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-base">
                                        <span class="px-4 py-2 text-emerald-600">
                                            {{ $conversionRate->conversionRate }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($analytics['conversionRates'] && $analytics['conversionRates']->count() > 5)
                        <div class="mt-4 text-right">
                            <x-button-view-all :toggleVar="'showAllRates'" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>