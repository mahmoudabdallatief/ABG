@extends('layouts.apps')
@section('title','Single Post')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="row gap-4">
            <div class="col-lg-12">
            <div class="row">
                    
                   
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
                                    
                                </p>
                            </div>
                            <div class="card-body d-block p-0">
                                <div class="row ps-2 pe-2">
                                    <img class="w-100 " src="{{ asset('images/' . $post->image) }}" alt="">
                                </div>
                            </div>
                            <div class="card-body d-block p-0">
                                <div class="row ps-2 pe-2">
                                    <div class="col-6">
                                    
                                    <form action="{{route('addlike')}}" method="POST" class="d-flex  gap-2">
                                        @csrf
                                        <input type="hidden" value="{{$post->id}}" name="post_id">
                                        <button type="submit" class ="border-0 bg-transparent">
                                        <i class="fa-solid fa-thumbs-up fa-2x text-primary"></i>  
                                        </button>
                                        <p class="fw-700 text-grey-900 font-xssss mt-4">{{$likes->count()}}</p>
                                    </form>
                                    
                                    

@foreach($likes as $like)
<div class="d-flex">
<figure class="avatar me-3">
                                        <a href="{{ route('profile_user', $like->user->id) }}">
                                            <img src="{{ url('images/' . $like->user->picture) }}" alt="image" class="shadow-sm rounded-circle w45">
                                        </a>
                                    </figure>
<a class="text-decoration-none" href="{{ route('profile_user', $like->user->id) }}">
                                        <h4 class="fw-700 text-grey-900 font-xssss mt-3">{{ $like->user->name }}</h4>
                                    </a>
</div>

@endforeach
                                     
                                    </div>
                                    <div class="col-6">
                                    <p class="fw-700 text-grey-900 font-xssss mt-1">
                                        
                                        <i class="fa-solid fa-comment fa-2x text-primary"></i>
                                        {{$comments->count()}}
                                        <p>
                                  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                      
                </div>
            </div>
            
           
<div class="col-12">
@if(session('id'))

@php
$comment =DB::table('comments')->where('id',session('id'))->first();
@endphp
<div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
    <div class="card-body p-0">
        <a href="#" class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">Edit Comment</a>
    </div>
    <form method="POST" action="{{ route('updatecomment',$comment->id) }}"  class="d-flex flex-column align-items-start gap-3">
        @csrf
        @method('PUT')
        <div class="card-body p-0 mt-3 position-relative">
            <textarea name="content" class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="80" rows="40" placeholder="What's on your mind?">{{$comment->content}}</textarea>
            @error('content')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        
        <div class="card-body d-flex p-0 mt-3">
            <button type="submit" class="btn btn-success text-light"> Edit Comment </button>
        </div>
       
    </form>
</div>
@else
<div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
    <div class="card-body p-0">
        <a href="#" class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">Create Comment</a>
    </div>
    <form method="POST" action="{{ route('addcomment') }}"  class="d-flex flex-column align-items-start gap-3">
        @csrf
        <input type="hidden" value="{{$post->id}}" name="post_id">
        <div class="card-body p-0 mt-3 position-relative">
            <textarea name="content" class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="80" rows="40" placeholder="What's on your mind?"></textarea>
            @error('content')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        
        <div class="card-body d-flex p-0 mt-3">
            <button type="submit" class="btn btn-success text-light"> Add Comment </button>
        </div>
       
    </form>
</div>
@endif
</div>
<div class="col-12">
@foreach($comments as $comment)
@php
                    $timestamp_comment = strtotime($comment->updated_at ?? $comment->created_at);
                    $dateWithDayName_comment = date("l, F j, Y \\A\\t h:i:s A", $timestamp_comment);
                    @endphp
<div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3">
                                <div class="card-body p-0 d-flex">
                                <a href="{{ route('profile_user', $comment->user->id) }}" class="text-decoration-none">
    <figure class="avatar me-3">
        <img src="{{ url('images/' . $comment->user->picture) }}" alt="image" class="shadow-sm rounded-circle w45">
    </figure>
</a>
<a href="{{ route('profile_user', $post->user->id) }}" class="text-decoration-none">
    <h4 class="fw-700 text-grey-900 font-xssss mt-1">
        {{ $comment->user->name }}
        <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">{{ $dateWithDayName_comment }}</span>
    </h4>
</a>

                                    @if($comment->user->id==Auth::user()->id)
                                    <a href="#" class="ms-auto" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-bars text-primary fa-2x"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg" aria-labelledby="dropdownMenu2">
                                        <form action="{{route('editcomment')}}"class="card-body p-0 d-flex" method="POST">
                                            
                                                @csrf
                                                <input type="hidden" value="{{$comment->id}}" name="id">
                                                
                                                <button type="submit" class="btn">
                                                <i class="fa-regular fa-pen-to-square text-primary fa-2x "></i>
                                            <h4 class="fw-600 text-grey-900 font-xssss mt-0   text-primary">Edit Comment</h4>
                                                </button>
                                      
                                            </form>
                                        
                                        <br>
                                        <div class="card-body p-0 d-flex">
                                        <form action="{{route('deletecomment',$comment->id)}}"class="card-body p-0 d-flex" method="POST">
                                            
                                            @csrf
                                            @method('DELETE')
                                            
                                            
                                            <button type="submit" class="btn">
                                            <i class="fa-solid fa-trash text-primary fa-2x "></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0  text-primary">Delete Comment</h4>
                                            </button>
                                  
                                        </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body p-0 me-lg-5">
                                    <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">{{$comment->content}}</p>
                                </div>
                               
                                </div>
                            @endforeach   
</div>
            
        </div>
    </div>
</div>

@endsection
