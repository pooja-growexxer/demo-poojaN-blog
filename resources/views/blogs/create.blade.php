<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Blog Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mt-1 mb-4 right">
                        <a class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" href="{{ route('blogs.index') }}">{{ __('Go Back') }}</a>
                    </div>
                    <form method="POST" action="{{ route('blogs.store') }}">
                        @csrf
                        <div class="mb-6">
                            <label class="block">
                                <span class="text-gray-700">Blog Title</span>
                                <input type="text" name="blog_title"
                                    class="block w-full @error('blog_title') border-red-500 @enderror mt-1 rounded-md"
                                    placeholder="" value="{{old('blog_title')}}" />
                            </label>
                            @error('blog_title')
                            <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label class="block">
                                <span class="text-gray-700">Blog Description</span>
                                <input type="text" name="blog_description"
                                    class="block w-full @error('blog_description') border-red-500 @enderror mt-1 rounded-md"
                                    placeholder="" value="{{old('blog_description')}}" />
                            </label>
                            @error('blog_description')
                            <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label class="block">
                                <span class="text-gray-700">Blog Category</span>
                                <select name="categories[]" class="block w-full mt-1" multiple>
                                        @foreach ($category as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                </select>
                            </label>
                            @error('category')
                            <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit"
                            class="text-white bg-blue-600  rounded text-sm px-5 py-2.5">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
