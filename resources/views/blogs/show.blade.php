<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Blogs Details') }}
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
                        <div class="max-w-xl">
                        <strong>Blog Title:</strong>
                                {{ $blog->blog_title }}
                        </div>

                        <div class="max-w-xl">
                        <strong>Blog Description:</strong>
                                {{ $blog->blog_description }}
                        </div>

                        <div class="max-w-xl">
                        <strong>Created By:</strong>
                                {{ $user }}
                        </div>

                        <div class="max-w-xl">
                        <strong>Date:</strong>
                                {{ $blog->created_at }}
                        </div>

                        <div class="max-w-xl">
                        <strong>Category:</strong>
                        @foreach($cat->categories as $category)
                            <span class="badge bg-info">{{ $category->name }}</span>
                        @endforeach
                        </div>

                        @if (Auth::user()->id  == $blog->created_by) 
                                    <div class="col-md-50" style="width: 90%">
                                        <a href="{{ route('blogs.edit',$blog->id) }}" class="btn btn-warning btn-sm rounded-1" style="background: yellow" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="material-icons">&#xE254;</i></a>
                                       <form method="POST" action="{{ route('blogs.destroy', $blog->id) }}"  onsubmit="return confirm('{{ trans('are You Sure ? ') }}');">
                                            @csrf
                                            @method('DELETE')
                                        <div class="col-xs-4">
                                        <button  type="submit" class="btn btn-block btn-danger fa_custom">
                                        Delete
                                        <i class="fa fa-trash"> </i> 
                                        </button>
                                        </form>
                                    </div>
                                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>