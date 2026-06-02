<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $company->name }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
       {{-- Success Message --}}
       <x-toast-notification /> 

       <div class="flex justify-between items-center">
       {{-- Back Button --}}
            <div class="w-80">
                @if(auth()->user()->role == 'admin')
                    <x-button-back href="{{ route('companies.index') }}"></x-button-back>
                @endif
            </div>
            {{-- Edit & Archive Buttons --}}
            <div class="flex space-x-4 mb-4">
                @if(auth()->user()->role == 'admin')
                    <x-button-link href="{{ route('companies.edit', [ 'company' => $company->id,'redirectToList' => 'false']) }}">Edit</x-button-link>
                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the company ' + '{{ $company->name }}' + '?');">
                        @csrf
                        @method('DELETE')
                        <x-button-archive>Archive</x-button-archive>
                    </form>    
                @endif
                @if(auth()->user()->role == 'company-owner')
                    <x-button-link href="{{ route('my-company.edit') }}">Edit</x-button-link>
                @endif    
            </div>
        </div>
        {{-- Wrapper --}}
       <div class="max-w-full mx-auto p-6 bg-white rounded-lg shadow-md">
            {{-- Company Details --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Company Information</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <strong>Owner:</strong> {{ $company->owner->name }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <strong>Email:</strong> {{ $company->owner->email }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <strong>Address:</strong> {{ $company->address }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-industry mr-2"></i>
                            <strong>Industry:</strong> {{ $company->industry }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-globe mr-2"></i>
                            <strong>Website:</strong>
                            @if($company->website)
                                <x-link :href="$company->website" target="_blank" rel="noopener noreferrer">
                                    {{ $company->website }}
                                </x-link>
                            @else
                                <span class="text-gray-400">No website</span>
                            @endif
                        </p>
                    </div>
                </div>
            @if(auth()->user()->role == 'admin')
            {{-- Tabs Navigation --}}
                <div class="border-b border-gray-200 mb-6 mt-4">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'jobs']) }}" 
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-md {{ in_array(request('tab'), [null, '', 'jobs']) ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-briefcase mr-2"></i>Jobs
                        </a>
                        <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'applications']) }}" 
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-md {{ request('tab') == 'applications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-file-alt mr-2"></i>Applications
                        </a>
                    </nav>
                </div>
                {{-- Tab Content --}}
                <div>
                    {{-- Job Tab --}}
                    <div id="jobs" class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                        <table class="min-w-full rounded-lg shadow divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="rounded-tl-lg px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Location</th>
                                    <th class="rounded-tr-lg px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-50">
                                @forelse($company->jobVacancies as $job)
                                    <tr>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="text-md text-gray-900">
                                                {{ $job->title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="text-md text-gray-900">
                                                {{ $job->type }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="text-md text-gray-900">
                                                {{ $job->location }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <a href="{{ route('job-vacancies.show', $job->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">View</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-message-empty colspan="4">No jobs found</x-message-empty>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Applications Tab --}}
                    <div id="applications" class="{{ request('tab') == 'applications' ? 'block' : 'hidden' }}">
                        <table class="min-w-full rounded-lg shadow divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="rounded-tl-lg px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">ِِApplicant Name</th>
                                    <th class="px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Job Tile</th>
                                    <th class="px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Status</th>
                                    <th class="rounded-tr-lg px-6 py-3 text-left text-lg leading-4 font-bold text-gray-500 tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-50">
                                @forelse($company->jobApplications as $application)
                                    <tr>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="text-md text-gray-900">
                                                {{ $application->user->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="text-md text-gray-900">
                                                {{ $application->jobVacancy->title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                        <div class="text-md">
                                                <x-status :status="$application->status">{{ $application->status }}</x-status>
                                            </div> 
                                        </td>
                                        <td class="px-6 py-0 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <a href="{{ route('job-applications.show', $application->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">View</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <x-message-empty colspan="4">No applications found</x-message-empty>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
       </div>
    </div> 

</x-app-layout>