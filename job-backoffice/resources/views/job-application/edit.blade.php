<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Applicant Status') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-5xl mx-auto p-6 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <form action="{{ route('job-applications.update', ['job_application' => $jobApplication->id, 'redirectToList' => request('redirectToList')]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Header Section --}}
                    <div class="border-b px-8 py-2">
                        <h3 class="text-xl font-semibold text-black">Job Application Details</h3>
                    </div>
                    {{-- Content Section --}}
                    <div class="px-8 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Applicant Info --}}
                            <div class="space-y-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">Applicant Name</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $jobApplication->user->name }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">Job Vacancy</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $jobApplication->jobVacancy->title }}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">Company</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $jobApplication->jobVacancy->company->name }}</p>
                                </div>
                            </div>

                            {{-- AI Analysis --}}
                            <div class="space-y-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-blue-600">AI Generated Score</label>
                                    <p class="mt-1 text-2xl font-bold text-blue-700">{{ $jobApplication->aiGeneratedScore }}</p>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <label class="text-sm font-medium text-blue-600">AI Generated Feedback</label>
                                    <p class="mt-1 text-gray-900">{{ $jobApplication->aiGeneratedFeedback }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Status Selection --}}
                        <div class="mt-8">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Application Status</label>
                            <select id="status" name="status" required 
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <option value="pending" {{ old('status', $jobApplication->status) == 'pending' ? 'selected' : '' }}
                                    class="text-yellow-600">
                                    Pending
                                </option>
                                <option value="accepted" {{ old('status', $jobApplication->status) == 'accepted' ? 'selected' : '' }}
                                    class="text-green-600">
                                    Accepted
                                </option>
                                <option value="rejected" {{ old('status', $jobApplication->status) == 'rejected' ? 'selected' : '' }}
                                    class="text-red-600">
                                    Rejected
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
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