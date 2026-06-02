<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Job Vacancy') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('job-vacancies.store') }}" method="POST">
                @csrf

                {{-- Job Vacancy --}}
                <div class="mb-4 p-6 bg-gray-50 border border-gray-100 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Job Vacancy Details</h3>
                    <p class="text-sm text-gray-600 mb-2">Enter the job vacancy details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                class="{{ $errors->has('title') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Location --}}
                        <div class="mb-4">
                            <label for="location" class="block text-gray-700 font-bold mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required
                                class="{{ $errors->has('location') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        {{-- Expected Salary --}}
                        <div class="mb-4">
                            <label for="salary" class="block text-gray-700 font-bold mb-2">Expected Salary (USD)</label>
                            <input type="number" id="salary" name="salary" value="{{ old('salary') }}"
                                class="{{ $errors->has('salary') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                        </div>

                        {{-- Job Type --}}
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 font-bold mb-2">Type</label>
                            <select id="type" name="type" required 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Full-Time" {{ old('type') == 'Full-Time' ? 'selected' : '' }}>Full Time</option>
                                <option value="Part-Time" {{ old('type') == 'Part-Time' ? 'selected' : '' }}>Part Time</option>
                                <option value="Contract" {{ old('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="remote" {{ old('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                                <option value="Hybrid" {{ old('type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        {{-- Company Select Dropdown --}}
                        @if (Auth::user()->role == 'admin')
                            <div class="mb-4">
                                <label for="companyId" class="block text-gray-700 font-bold mb-2">Company</label>
                                <select id="companyId" name="companyId" required 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('companyId') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('companyId')" class="mt-2" />
                            </div>
                        @else
                             <input type="hidden" name="companyId" value="{{ old('companyId', Auth::user()->company->id) }}">
                        @endif
                        {{-- Job Category Select Dropdown --}}
                        <div class="mb-4">
                            <label for="jobCategoryId" class="block text-gray-700 font-bold mb-2">Job Category</label>
                            <select id="jobCategoryId" name="jobCategoryId" required 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @foreach ($jobCategories as $jobCategory)
                                    <option value="{{ $jobCategory->id }}" {{ old('jobCategoryId') == $jobCategory->id ? 'selected' : '' }}>
                                        {{ $jobCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('jobCategoryId')" class="mt-2" />
                        </div>
                    </div>   
                    
                    {{-- Job Description --}}
                    <div class="mb-4 py-6">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Job Description</label>
                        <textarea id="description" name="description" rows="5" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <x-button-cancel/>
                    <x-button-submit>Save</x-button-submit>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>