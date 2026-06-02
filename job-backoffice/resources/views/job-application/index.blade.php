<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Applications') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6"> 
       {{-- Success Message --}}
       <x-toast-notification />

       <div class="flex justify-between items-center">
            {{-- Search Bar --}}
            <div class="w-80">
                <x-search-bar action="{{ route('job-applications.index') }}" placeholder="Search by any field..."/>    
            </div>

            {{-- Buttons --}}
            <div class="flex space-x-6">
                @if(request()->input('archived') == 'true')
                    {{-- Active Button --}}
                    <x-button-link href="{{ route('job-applications.index')}}">Active Jobs</x-button-link>
                @else
                    {{-- Archive Button --}}
                    <x-button-link href="{{ route('job-applications.index', ['archived' => 'true']) }}">View Archive ({{ $archivedCount ?? 0 }})</x-button-link>
                @endif
            </div>
        </div>

        {{-- Job Applications Table --}}
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Applicant Name</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Position (Job Title)</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                    @endif
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">AI Score</th>
                    <th class="rounded-r-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Action</th>    
                    @if(request()->input('archived') == 'true')
                        <th class="rounded-r-lg px-1 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Archive Date</th>    
                    @endif
                </tr>  
            </thead>
            <tbody>  
                @forelse ($jobApplications as $jobApplication)
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4  font-medium text-gray-900">
                            @if(request()->input('archived') == 'true')
                                {{ $jobApplication->user->name }}
                            @else    
                                <x-link :href="route('job-applications.show', $jobApplication->id)">{{ $jobApplication->user->name }}</x-link>
                            @endif    
                        </td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $jobApplication->jobVacancy->title }}</td>
                        @if(auth()->user()->role == 'admin')
                            <td class="px-6 py-4  font-medium text-gray-900">{{ $jobApplication->jobVacancy->company->name }}</td>
                        @endif
                        <td class="px-6 py-4  font-medium text-gray-900"><x-status :status="$jobApplication->status">{{ $jobApplication->status }}</x-status></td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $jobApplication->aiGeneratedScore }}%</td>
                        <td>
                            <div class="flex space-x-1">
                                @if(request()->input('archived') == 'true')
                                    {{-- Restore Button --}}
                                    <form action="{{ route('job-applications.restore', $jobApplication->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="this.disabled=true; this.form.submit();" class="text-blue-400 hover:text-blue-600  font-bold py-2 px-4 rounded transition-colors duration-200">🔄 Restore</button>
                                    </form>
                                @else
                                    {{-- Edit Button --}}
                                    <a href="{{ route('job-applications.edit', $jobApplication->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">✒ Edit</a>
                                    
                                    {{-- Archive Button --}}
                                    <form action="{{ route('job-applications.destroy', $jobApplication->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the user ' + '{{ $jobApplication->user->name }}' + '?');">
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
                            {{ $jobApplication->deleted_at->format('M d, Y') }}
                        </td>
                        @endif
                    </tr>
            @empty
                <x-message-empty colspan="{{ request()->input('archived') == 'true' ? 7: 6 }}">
                     @if(request()->input('archived') == 'true')
                        No archived job applications found
                    @else
                        No job applications found
                    @endif
                </x-message-empty>
            @endforelse
            </tbody>
        </table>     
        
        <div class="mt-4">
            {{ $jobApplications->links() }}
        </div>
    </div>
</x-app-layout>
