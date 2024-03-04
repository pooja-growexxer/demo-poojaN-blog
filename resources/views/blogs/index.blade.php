
<x-app-layout>

<x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Blogs') }}
        </h2>
    </x-slot>
    <div class="container mt-4">

        <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 800)">
            @if (session()->has('status'))
                <div class="p-3 text-green-700 bg-green-300 rounded">
                    {{ session()->get('status') }}
                </div>
            @endif

            @if (session()->has('del_status'))
                <div class="p-3 text-red-700 bg-red-300 rounded">
                    {{ session()->get('del_status') }}
                </div>
            @endif
        </div>
            <div class="mt-1 mb-4">
                <a class="px-2 py-2 text-sm text-white bg-blue-600 rounded"
                    href="{{ route('blogs.create') }}">{{ __('Add Blog') }}</a>
            </div>
        <h1>Search</h1>

        <form method="get" action="{{ route('blogs.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for blogs or categories">
                <button type="submit"  style="background: purple" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
        @csrf
        @if($blogs->count() > 0)

            @foreach ($blogs as $blog)
                <div class="card mb-3">
                    <div class="card-body">
                    <p <h5 class="card-title text-primary"> <a href="{{ route('blogs.show',$blog->id) }}"><b>Title:</b> </a> </h5> {{ $blog->blog_title }}</p>
                        <p class="card-text">
                            <b>Categories:</b>
                            @foreach($blog->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            @endforeach

            @else
                <div class="card mb-3">
                    <div class="card-body">
                    <p>No results found.</p>
                    </div>
                </div> 
            @endif

            <nav aria-label="Page navigation example">
                                {{$blogs->links('pagination::bootstrap-5')  }}
            </nav>
    </div>

</x-app-layout>