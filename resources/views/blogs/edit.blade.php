<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Category Edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mt-1 mb-4 right">
                        <a class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" href="{{ route('blogs.index') }}">{{ __('Go Back') }}</a>
                    </div>

                    <div class="p-6 bg-white border-b border-gray-200">
                        <form method="POST" action="{{ route('blogs.update',$blog->id) }}">
                            @csrf
                            @method('put')
                            <div class="mb-6">
                                <label class="block">
                                    <span class="text-gray-700">Blog Title</span>
                                    <input type="text" name="blog_title"
                                        class="block w-full @error('blog_title') border-red-500 @enderror mt-1 rounded-md"
                                        placeholder="" value="{{old('blog_title',$blog->blog_title)}}" />
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
                                        placeholder="" value="{{old('blog_description',$blog->blog_description)}}" />
                                </label>
                                @error('blog_description')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900">
                            Select category
                            </label>
                                <select name="category[]" id="category" class="form-control" multiple>
                                    @foreach($category as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, $blog->categories->pluck('id')->toArray()) ? 'selected' : '' }}  @class([
                                'bg-purple-600 text-white' => $blog->categories->contains($tag->id)
                                ])>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                @error('blog_description')
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
    </div>
</x-app-layout>