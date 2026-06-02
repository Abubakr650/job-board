<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job Categories') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <form action="{{ route('job-categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Category Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required 
                         class="{{ $errors->has('name') ? 'outline-red-500 outline outline-1' : '' }} shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="flex justify-end space-x-4">
                   <x-button-cancel />
                    <x-button-submit>Update</x-button-submit>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>