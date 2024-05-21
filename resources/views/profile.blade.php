@extends('layouts.apps')
@section('title','Profile Page')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="row gap-4">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card w-100 border-0 p-0 bg-white shadow-xss rounded-xxl">
                            <div class="card-body h250 p-0 rounded-xxl overflow-hidden m-3">
                                <img src="{{ url('images/' . $user->picture) }}" alt="image">
                            </div>
                            <div class="card-body p-0 position-relative">
                                <figure class="avatar position-absolute w100 z-index-1" style="top:-40px; left: 30px;">
                                    <img src="{{ url('images/' . $user->picture) }}" alt="image" class="float-right p-1 bg-white rounded-circle w-100">
                                </figure>
                                <h4 class="fw-700 font-sm mt-2 mb-lg-5 mb-4 pl-15">
                                    {{ $user->name }} 
                                    <span class="fw-500 font-xssss text-grey-500 mt-1 mb-3 d-block">{{ $user->email }}</span>
                                </h4>
                                @if($user->id == Auth::user()->id)
                                <form method="POST" action="{{ route('edit_picture') }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2 justify-content-center position-absolute-md right-15 top-0 me-2">
                                    @csrf
                                    <div class="file-input-wrapper">
                                        <input type="file" id="file-input" class="file-input" name="picture">
                                        <label for="file-input" class="file-label">
                                            <i class="fas fa-upload file-label-icon"></i>
                                            <span class="file-label-text">Choose a file</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-success text-light">Edit Your Image</button>
                                    @error('picture')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
                            <div class="card-body d-flex align-items-center p-4">
                                <h4 class="fw-700 mb-0 font-xssss text-grey-900">Friends Lists</h4>
                            </div>
                            <div class="card-body d-flex align-items-center p-4">
                                <form class="d-flex w-100">
                                    <input class="form-control me-2" type="text" id="search-query" placeholder="Search" aria-label="Search">
                                    <input type="hidden" id="user" name="user" value="{{ $user->id }}">
                                </form>
                            </div>
                            <div class="w-100" id="search-results">
                                @forelse($friendsList as $friend)
                                <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0">
                                    <figure class="avatar me-3">
                                        <a href="{{ route('profile_user', $friend->id) }}">
                                            <img src="{{ url('images/' . $friend->picture) }}" alt="image" class="shadow-sm rounded-circle w45">
                                        </a>
                                    </figure>
                                    <a class="text-decoration-none" href="{{ route('profile_user', $friend->id) }}">
                                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{ $friend->name }}</h4>
                                    </a>
                                </div>
                                @empty
                                <div class="text-center font-xssss text-grey-500">No Friend Yet</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($user->id == Auth::user()->id)
<div class="col-12">
@if(session('id'))

@php
$post =DB::table('posts')->where('id',session('id'))->first();
@endphp
<div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
    <div class="card-body p-0">
        <a href="#" class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">Edit Post</a>
    </div>
    <form method="POST" action="{{ route('updatepost',$post->id) }}" enctype="multipart/form-data" class="d-flex flex-column align-items-start gap-3">
        @csrf
        @method('PUT')
        <div class="card-body p-0 mt-3 position-relative">
            <textarea name="content" class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="80" rows="40" placeholder="What's on your mind?">{{$post->content}}</textarea>
            @error('content')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="card-body d-flex p-0 mt-0">
            <div class="file-input-wrapper">
                <input type="file" id="file-input" class="file-input" name="image">
                <label for="file-input" class="file-label">
                    <i class="fas fa-upload file-label-icon"></i>
                    <span class="file-label-text">Choose a file</span>
                </label>
            </div>
            @error('image')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="card-body d-flex p-0 mt-3">
            <button type="submit" class="btn btn-success text-light"> Edit Post </button>
        </div>
       
    </form>
</div>
@else
<div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
    <div class="card-body p-0">
        <a href="#" class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">Create Post</a>
    </div>
    <form method="POST" action="{{ route('addpost') }}" enctype="multipart/form-data" class="d-flex flex-column align-items-start gap-3">
        @csrf
        <div class="card-body p-0 mt-3 position-relative">
            <textarea name="content" class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="80" rows="40" placeholder="What's on your mind?"></textarea>
            @error('content')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="card-body d-flex p-0 mt-0">
            <div class="file-input-wrapper">
                <input type="file" id="file-input" class="file-input" name="image">
                <label for="file-input" class="file-label">
                    <i class="fas fa-upload file-label-icon"></i>
                    <span class="file-label-text">Choose a file</span>
                </label>
            </div>
            @error('image')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="card-body d-flex p-0 mt-3">
            <button type="submit" class="btn btn-success text-light"> Add Post </button>
        </div>
       
    </form>
</div>
@endif
</div>
@endif
            <div class="col-lg-12">
                <div class="row">
                    @foreach($posts as $post)
                    @php
                    $timestamp = strtotime($post->updated_at ?? $post->created_at);
                    $dateWithDayName = date("l, F j, Y \\A\\t h:i:s A", $timestamp);
                    @endphp
                    <div class="col-lg-12">
                        <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3">
                            <div class="card-body p-0 d-flex">
                                <a href="{{ route('profile_user', $post->user->id) }}" class="text-decoration-none">
                                    <figure class="avatar me-3">
                                        <img src="{{ url('images/' . $post->user->picture) }}" alt="image" class="shadow-sm rounded-circle w45">
                                    </figure>
                                </a>
                                <a href="{{ route('profile_user', $post->user->id) }}" class="text-decoration-none">
                                    <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                                        {{ $post->user->name }}
                                        <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">{{ $dateWithDayName }}</span>
                                    </h4>
                                </a>
                                @if($post->user->id == Auth::user()->id)
                                <a href="#" class="ms-auto" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-bars text-primary fa-2x"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg" aria-labelledby="dropdownMenu2">
                                <form action="{{route('editpost')}}"class="card-body p-0 d-flex" method="POST">
                                            
                                            @csrf
                                            <input type="hidden" value="{{$post->id}}" name="id">
                                            
                                            <button type="submit" class="btn">
                                            <i class="fa-regular fa-pen-to-square text-primary fa-2x "></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0   text-primary">Edit Post</h4>
                                            </button>
                                  
                                        </form>
                                    
                                    <br>
                                    <div class="card-body p-0 d-flex">
                                    <form action="{{route('deletepost',$post->id)}}"class="card-body p-0 d-flex" method="POST">
                                            
                                            @csrf
                                            @method('DELETE')
                                            
                                            
                                            <button type="submit" class="btn">
                                            <i class="fa-solid fa-trash text-primary fa-2x "></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0  text-primary">Delete Post</h4>
                                            </button>
                                  
                                        </form>
                                        
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-body p-0 me-lg-5">
                                <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">{{ $post->content }} 
                                    <a href="{{route('singlepost',$post->id)}}" class="fw-600 text-primary ms-2">See more</a>
                                </p>
                            </div>
                            <div class="card-body d-block p-0">
                                <div class="row ps-2 pe-2">
                                    <img class="w-100" src="{{ url('images/' . $post->image) }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach   
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
