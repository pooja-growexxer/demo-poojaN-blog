
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Posts') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 5000)">
                @if (session()->has('status'))
                    <div class="p-3 text-green-700 bg-green-300 rounded">
                        {{ session()->get('status') }}
                    </div>
                @endif
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div x-data="{ showMessage: true }" x-show="showMessage" class="flex justify-center" x-init="setTimeout(() => showMessage = false, 5000)">
                        @if (session()->has('status'))
                        <div
                            class="flex items-center justify-between max-w-xl p-4 bg-white border rounded-md shadow-sm">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="ml-3 text-sm font-bold text-green-600">{{ session()->get('status') }}</p>
                            </div>
                            <span @click="showMessage = false" class="inline-flex items-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-1 mb-4">
                        <a class="px-2 py-2 text-sm text-white bg-blue-600 rounded"
                            href="{{ route('blogs.create') }}">{{ __('Add Post') }}</a>
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Blog Title
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Blog Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                    Created By
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Edit
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Show
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @csrf
                                @foreach ($blogs as $blog)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{$blog->id}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$blog->blog_title}}

                                    </td>
                                    <td class="px-6 py-4">
                                        {{$blog->blog_description}}

                                    </td>
                                    <td class="px-6 py-4">
                                        {{$blog->created_by}}

                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('blogs.show',$blog->id) }}">Show</a>
                                    </td>
                                    @if (Auth::user()->id  == $blog->created_by) 
                                    <td class="px-6 py-4">
                                        <a href="{{ route('blogs.edit',$blog->id) }}">Edit</a>
                                    </td>
                                   
                                    <td class="px-6 py-4">
                                        <form action="{{ route('blogs.destroy',$blog->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('are You Sure ? ') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="px-4 py-2 text-white bg-red-700 rounded"
                                                value="Delete">
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>