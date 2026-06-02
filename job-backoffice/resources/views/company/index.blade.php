<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ _('Companies') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6"> 
       {{-- Success Message --}}
       <x-toast-notification />

       <div class="flex justify-between items-center">
            {{-- Search Bar on the far left --}}
            <div class="w-80">
                <x-search-bar action="{{ route('companies.index') }}" placeholder="Search companies..."/>    
            </div>

            {{-- Buttons on the right --}}
            <div class="flex space-x-6">
                @if(request()->input('archived') == 'true')
                    {{-- Active Button --}}
                    <x-button-link href="{{ route('companies.index')}}">Active Companies</x-button-link>
                @else
                    {{-- Add Company Button --}}
                    <x-button-link href="{{ route('companies.create') }}">Add Company</x-button-link>
                    {{-- Archive Button --}}
                    <x-button-link href="{{ route('companies.index', ['archived' => 'true']) }}">View Archive ({{ $archivedCount ?? 0 }})</x-button-link>
                @endif
            </div>
        </div>

        {{-- Companies Table --}}
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Industry</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Website</th>
                    <th class="rounded-r-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Action</th>    
                    @if(request()->input('archived') == 'true')
                        <th class="rounded-r-lg px-1 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Archive Date</th>    
                    @endif
                </tr>  
            </thead>
            <tbody>  
                @forelse ($companies as $company)
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4  font-medium text-gray-900">
                            @if(request()->input('archived') == 'true')
                                {{ $company->name }}
                            @else    
                                <x-link :href="route('companies.show', $company->id)">{{ $company->name }}</x-link>
                            @endif    
                        </td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $company->address }}</td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $company->industry }}</td>
                        <td class="px-6 py-4">
                            @if($company->website)
                                <x-link :href="$company->website" target="_blank" rel="noopener noreferrer">
                                    {{ $company->website }}
                                </x-link>
                            @else
                                <span class="text-gray-400">No website</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-1">
                                @if(request()->input('archived') == 'true')
                                    {{-- Restore Button --}}
                                    <form action="{{ route('companies.restore', $company->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="this.disabled=true; this.form.submit();" class="text-blue-400 hover:text-blue-600  font-bold py-2 px-4 rounded transition-colors duration-200">🔄 Restore</button>
                                    </form>
                                @else
                                    {{-- Edit Button --}}
                                    <a href="{{ route('companies.edit', $company->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">✒ Edit</a>
                                    
                                    {{-- Archive Button --}}
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the company ' + '{{ $company->name }}' + '?');">
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
                            {{ $company->deleted_at->format('M d, Y') }}
                        </td>
                        @endif
                    </tr>
                    @empty
                        <x-message-empty colspan="{{ request()->input('archived') == 'true' ? 6 : 5 }}">
                            @if(request()->input('archived') == 'true')
                                No archived companies found
                            @else
                                No conpanies found
                            @endif
                        </x-message-empty>
                    @endforelse
            </tbody>
        </table>         
        <div class="mt-4">
            {{ $companies->links() }}
        </div>
    </div>
</x-app-layout>
