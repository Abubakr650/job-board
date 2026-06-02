<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Company') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('companies.store') }}" method="POST">
                @csrf

                {{-- Company Details --}}
                <div class="mb-4 p-6 bg-gray-50 border border-gray-100 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Company Details</h3>
                    <p class="text-sm text-gray-600 mb-2">Enter the company details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Company Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                class="{{ $errors->has('name') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" required
                                class="{{ $errors->has('address') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="industry" class="block text-gray-700 font-bold mb-2">Industry</label>
                            <select id="industry" name="industry"  value="{{ old('industry') }}" required 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry }}">{{ $industry }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="website" class="block text-gray-700 font-bold mb-2">Website (Optional)</label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}"
                                class="{{ $errors->has('website') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                    </div>    
                </div>

                {{-- Company Owner --}}
               <div class="mb-4 p-6 bg-gray-50 border border-gray-100 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Company Owner</h3>
                    <p class="text-sm text-gray-600 mb-2">Enter the company owner details</p>

                    <div class="mb-4">
                        <label for="owner_name" class="block text-gray-700 font-bold mb-2">Owner Name</label>
                        <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required
                            class="{{ $errors->has('owner_name') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <x-input-error :messages="$errors->get('owner_name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="owner_email" class="block text-gray-700 font-bold mb-2">Owner Email</label>
                        <input type="email" id="owner_email" name="owner_email"  value="{{ old('owner_email') }}" required
                            class="{{ $errors->has('owner_email') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <x-input-error :messages="$errors->get('owner_email')" class="mt-2" />            
                    </div>

                      <!-- Password -->
                    <div class="mt-4">
                        <label for="owner_password" class="block text-gray-700 font-bold mb-2">Passeord</label>
                        <div class="relative" x-data="{ showPassword: false }">    
                            <x-text-input id="owner_password" class="block mt-1 w-full"
                                        name="owner_password"
                                        required autocomplete="new-password" x-bind:type="showPassword ? 'text' : 'password'" />
                            <x-button-show-password/>
                        </div>
                        <x-input-error :messages="$errors->get('owner_password')" class="mt-2" />            
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                 <x-button-cancel />
                 <x-button-submit>Save</x-button-submit>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>