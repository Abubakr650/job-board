<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
       {{-- Success Message --}}
       <x-toast-notification /> 

       <div class="flex justify-between items-center">
       {{-- Back Button --}}
            <div class="w-80">
                <x-button-back href="{{ route('job-vacancies.index') }}"></x-button-back>
            </div>
            {{-- Edit & Archive Buttons --}}
            <div class="flex space-x-4 mb-4">
                <x-button-link href="{{ route('job-vacancies.edit', [ 'job_vacancy' => $jobVacancy->id,'redirectToList' => 'false']) }}">Edit</x-button-link>
                <form action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the job vacancy ' + '{{ $jobVacancy->title }}' + '?');">
                    @csrf
                    @method('DELETE')
                    <x-button-archive>Archive</x-button-archive>
                </form>
            </div>
        </div>
        {{-- Wrapper --}}
       <div class="max-w-full mx-auto p-6 bg-white rounded-lg shadow-md">
            {{-- Job Vacancy Details --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Job Vacancy Information</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <strong>Category:</strong> {{ $jobVacancy->jobCategory->name }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <strong>Company:</strong> {{ $jobVacancy->company->name }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-industry mr-2"></i>
                            <strong>Location:</strong> {{ $jobVacancy->location }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-industry mr-2"></i>
                            <strong>Type:</strong> {{ $jobVacancy->type }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-industry mr-2"></i>
                            <strong>Salary:</strong> ${{ number_format($jobVacancy->salary, 2) }}
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-industry mr-2"></i>
                            <strong>Description:</strong> {{ $jobVacancy->description }}
                        </p>
                    </div>
                </div>

            {{-- Tabs Navigation --}}
            <div class="border-b border-gray-200 mb-6 mt-4">
                <nav class="-mb-px flex space-x-8">
                    <a class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-md border-blue-500 text-blue-600">
                        <i class="fas fa-file-alt mr-2"></i>Applications
                    </a>
                </nav>
            </div>
            {{-- Tab Content --}} 
            <div>
                {{-- Applications Tab --}}
                <div id="applications" class="block">
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
                            @forelse($jobVacancy->jobApplications as $application)
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
       </div>
    </div> 

</x-app-layout>