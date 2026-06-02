<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6"> 
       {{-- Success Message --}}
       <x-toast-notification />

       <div class="flex justify-between items-center">
            {{-- Search Bar --}}
            <div class="w-80">
                <x-search-bar action="{{ route('users.index') }}" placeholder="Search users..."/>    
            </div>

            {{-- Buttons --}}
            <div class="flex space-x-6">
                @if(request()->input('archived') == 'true')
                    {{-- Active Button --}}
                    <x-button-link href="{{ route('users.index')}}">Active Users</x-button-link>
                @else
                    {{-- Archive Button --}}
                    <x-button-link href="{{ route('users.index', ['archived' => 'true']) }}">View Archive ({{ $archivedCount ?? 0 }})</x-button-link>
                @endif
            </div>
        </div>

        {{-- Job Applications Table --}}
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="rounded-l-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="rounded-r-lg px-6 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Action</th>    
                    @if(request()->input('archived') == 'true')
                        <th class="rounded-r-lg px-1 py-3 bg-gray-50 text-left text-md leading-4 font-semibold text-gray-500 uppercase tracking-wider">Archive Date</th>    
                    @endif
                </tr>  
            </thead>
            <tbody>  
                @forelse ($users as $user)
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4  font-medium text-gray-900">{{ $user->role }}</td>
                        <td>
                            <div class="flex space-x-1">
                                @if(request()->input('archived') == 'true')
                                    {{-- Restore Button --}}
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="this.disabled=true; this.form.submit();" class="text-blue-400 hover:text-blue-600  font-bold py-2 px-4 rounded transition-colors duration-200">🔄 Restore</button>
                                    </form>
                                @else
                                    {{-- If Admin don't allow edit or archive --}}
                                    @if($user->role != 'admin')
                                        {{-- Edit Button --}}
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700  font-bold py-2 px-4 rounded transition-colors duration-200">✒ Edit</a>
                                        
                                        {{-- Archive Button --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to archive the user ' + '{{ $user->name }}' + '?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" this.form.submit(); class="text-red-400 hover:text-red-600  font-bold py-2 px-4 rounded transition-colors duration-200">🗂 Archive</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                        {{-- Archive Date --}}
                        @if(request()->input('archived') == 'true')
                        <td class="px-6 py-4  font-medium text-gray-900">
                            {{ $user->deleted_at->format('M d, Y') }}
                        </td>
                        @endif
                    </tr>
            @empty
                <x-message-empty colspan="{{ request()->input('archived') == 'true' ? 5: 4 }}">
                     @if(request()->input('archived') == 'true')
                        No archived users found
                    @else
                        No job users found
                    @endif
                </x-message-empty>
            @endforelse
            </tbody>
        </table>     
        
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
