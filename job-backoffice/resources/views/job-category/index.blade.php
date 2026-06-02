<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ _('Job Categories') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6"> 
        {{-- Success Message --}}
       <x-toast-notification />

       <div class="flex justify-between items-center">
            {{-- Search Bar --}}
            <div class="w-80">
                <x-search-bar action="{{ route('job-categories.index') }}" placeholder="Search categories..."/>    
            </div>

            {{-- Buttons on the right --}}
            <div class="flex space-x-6">
                @if(request()->input('archived') == 'true')
                    {{-- Active Button --}}
                    <x-button-link href="{{ route('job-categories.index')}}">Active Categories</x-button-link>
                @else
                    {{-- Add job Category Button --}}
                    <x-button-link href="{{ route('job-categories.create') }}">Add Job Category</x-button-link>
                    {{-- Archive Button --}}
                    <x-button-link href="{{ route('job-categories.index', ['archived' => 'true']) }}">View Archive ({{ $archivedCount ?? 0 }})</x-button-link>
                @endif
            </div>
        </div>

        {{-- Job Categories Table --}}
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Category Name</th>   
                    <th class="rounded-r-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Action</th>    
                    @if(request()->input('archived') == 'true')
                        <th class="rounded-r-lg px-1 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Archive Date</th>    
                    @endif
                </tr>  
            </thead>
            <tbody>  
                @forelse ($categories as $category)
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4  font-medium text-gray-900">
                            {{ $category->name }}
                        </td> 
                        <td>
                            <div class="flex space-x-1">
                                @if(request()->input('archived') == 'true')
                                    {{-- Restore Button --}}
                                    <form action="{{ route('job-categories.restore', $category->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="this.disabled=true; this.form.submit();" class="text-blue-400 hover:text-blue-600  font-bold py-2 px-4 rounded transition-colors duration-200">🔄 Restore</button>
                                    </form>
                                @else
                                    {{-- Edit Button --}}
                                    <a href="{{ route('job-categories.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">✒ Edit</a>
                                    
                                    {{-- Archive Button --}}
                                    <form action="{{ route('job-categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the category ' + '{{ $category->name }}' + '?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"  this.form.submit(); class="text-red-400 hover:text-red-600  font-bold py-2 px-4 rounded transition-colors duration-200">🗂 Archive</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        {{-- Archive Date --}}
                        @if(request()->input('archived') == 'true')
                        <td class="px-6 py-4  font-medium text-gray-900">
                            {{ $category->deleted_at->format('M d, Y') }}
                        </td>
                        @endif
                    </tr>
              @empty
                {{-- Empty Message --}}
                <x-message-empty colspan="{{ request()->input('archived') == 'true' ? 3 : 2 }}" >
                    @if(request()->input('archived') == 'true')
                        No archived categories found
                    @else
                        No job categories found
                    @endif
                </x-message-empty>
            @endforelse
            </tbody>
        </table>     
        
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
