<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Edit User Password') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-2xl mx-auto p-6 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <form action="{{ route('users.update', [ 'user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Header Section --}}
                    <div class="border-b px-8 py-2">
                        <h3 class="text-xl font-semibold text-black">User Details</h3>
                    </div>
                    {{-- Content Section --}}
                    <div class="px-8 py-6">
                            {{-- User Info --}}
                            <div class="space-y-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">User Name</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">User Email</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">User Role</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->role }}</p>
                                </div>

                                <div class="mt-4">
                                    <label for="password" class="block text-gray-700 font-bold mb-2">Change User Password</label>
                                    <div class="relative" x-data="{ showPassword: false }">    
                                        <x-text-input id="password" class="block mt-1 w-full"
                                                    name="password"
                                                    autocomplete="new-password" x-bind:type="showPassword ? 'text' : 'password'" />
                                        <x-button-show-password/>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />            
                            </div>
                           
                    </div>

                <div class="flex justify-end space-x-4 px-8 py-6">
                    <x-button-cancel/>
                    <x-button-submit>Update</x-button-submit>
                </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>