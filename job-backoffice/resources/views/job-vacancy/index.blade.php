<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Vacancies') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6"> 
       {{-- Success Message --}}
       <x-toast-notification />

       <div class="flex justify-between items-center">
            {{-- Search Bar on the far left --}}
            <div class="w-80">
                <x-search-bar action="{{ route('job-vacancies.index') }}" placeholder="Search job vacancies..."/>    
            </div>

            {{-- Buttons on the right --}}
            <div class="flex space-x-6">
                @if(request()->input('archived') == 'true')
                    {{-- Active Button --}}
                    <x-button-link href="{{ route('job-vacancies.index')}}">Active Job</x-button-link>
                @else
                    {{-- Add Job Vacancy Button --}}
                    <x-button-link href="{{ route('job-vacancies.create') }}">Add Job</x-button-link>
                    {{-- Archive Button --}}
                    <x-button-link href="{{ route('job-vacancies.index', ['archived' => 'true']) }}">View Archive ({{ $archivedCount ?? 0 }})</x-button-link>
                @endif
            </div>
        </div>

        {{-- Job Categories Table --}}
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                    @endif
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Salary</th>
                    <th class="rounded-r-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Action</th>    
                    @if(request()->input('archived') == 'true')
                        <th class="rounded-r-lg px-1 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Archive Date</th>    
                    @endif
                </tr>  
            </thead>
            <tbody>  
                @forelse ($jobVacancies as $jobVacancy)
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4  font-medium text-gray-900">
                            @if(request()->input('archived') == 'true')
                                {{ $jobVacancy->title }}
                            @else    
                                <x-link :href="route('job-vacancies.show', $jobVacancy->id)">{{ $jobVacancy->title }}</x-link>
                            @endif    
                        </td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $jobVacancy->jobCategory->name }}</td>
                        @if(auth()->user()->role == 'admin')
                            <td class="px-6 py-4  font-medium text-gray-900">{{ $jobVacancy->company->name }}</td>
                        @endif
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $jobVacancy->location }}</td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $jobVacancy->type }}</td>
                        <td class="px-6 py-4  font-medium text-gray-900">${{ number_format($jobVacancy->salary, 2) }}</td>
                        <td>
                            <div class="flex space-x-1">
                                @if(request()->input('archived') == 'true')
                                    {{-- Restore Button --}}
                                    <form action="{{ route('job-vacancies.restore', $jobVacancy->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="this.disabled=true; this.form.submit();" class="text-blue-400 hover:text-blue-600  font-bold py-2 px-4 rounded transition-colors duration-200">🔄 Restore</button>
                                    </form>
                                @else
                                    {{-- Edit Button --}}
                                    <a href="{{ route('job-vacancies.edit', $jobVacancy->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">✒ Edit</a>
                                    
                                    {{-- Archive Button --}}
                                    <form action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the Job Vacancy ' + '{{ $jobVacancy->title }}' + '?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" this.form.submit(); class="text-red-400 hover:text-red-600  font-bold py-2 px-4 rounded transition-colors duration-200">🗂 Archive</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        {{-- Archive Date --}}
                        @if(request()->input('archived') == 'true')
                        <td class="px-6 py-4  font-medium text-gray-900">
                            {{ $jobVacancy->deleted_at->format('M d, Y') }}
                        </td>
                        @endif
                    </tr>
            @empty
                <x-message-empty colspan="{{ request()->input('archived') == 'true' ? 8: 7 }}">
                     @if(request()->input('archived') == 'true')
                        No archived job vacancies found
                    @else
                        No job vacancies found
                    @endif
                </x-message-empty>
            @endforelse
            </tbody>
        </table>     
        
        <div class="mt-4">
            {{ $jobVacancies->links() }}
        </div>
    </div>
</x-app-layout>
