@extends('layouts.apps')
@section('title','Home Page')
@section('content')

    
    
        <div class="main-content right-chat-active">
            
            <div class="middle-sidebar-bottom">
                <div class="middle-sidebar-left">
                    <!-- loader wrapper -->
                    <div class="preloader-wrap p-3">
                        <div class="box shimmer">
                            <div class="lines">
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                            </div>
                        </div>
                        <div class="box shimmer mb-3">
                            <div class="lines">
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                            </div>
                        </div>
                        <div class="box shimmer">
                            <div class="lines">
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                                <div class="line s_shimmer"></div>
                            </div>
                        </div>
                    </div>
                    <!-- loader wrapper -->
                    <div class="row feed-body">
                        <div class="col-xl-8 col-xxl-9 col-lg-8">


                            
                           

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
                        



                           
@foreach($posts as $post)
@php
                    $timestamp = strtotime($post->updated_at ?? $post->created_at);
                    $dateWithDayName = date("l, F j, Y \\A\\t h:i:s A", $timestamp);
                    @endphp
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

                                    @if($post->user->id==Auth::user()->id)
                                    <a href="#" class="ms-auto" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-bars text-primary fa-2x"></i></a>
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
                                    <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">{{$post->content}} <a href="{{route('singlepost',$post->id)}}" class="fw-600 text-primary ms-2">See more</a></p>
                                </div>
                                <div class="card-body d-block p-0">
                                    <div class="row ps-2 pe-2">
                                       <img class="w-100" src="{{url('images/'.$post->image)}}" alt="">
                                    </div>
                                </div>
                                </div>
                            @endforeach   
                            
                            
                            
 

                            <div class="card w-100 text-center shadow-xss rounded-xxl border-0 p-4 mb-3 mt-3">
                                <div class="snippet mt-2 ms-auto me-auto" data-title=".dot-typing">
                                    <div class="stage">
                                        <div class="dot-typing"></div>
                                    </div>
                                </div>
                            </div>


                        </div>               
                        <div class="col-xl-4 col-xxl-3 col-lg-4 ps-lg-0">
    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
       
        <div class="card-body d-flex align-items-center p-4">
            <h4 class="fw-700 mb-0 font-xssss text-grey-900">Recommended Friends</h4>
        </div>
        @foreach($recommendeds as $recommended)
        <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0">

           <figure class="avatar me-3"><a href="{{route('profile_user',$recommended->id)}}"><img src="{{url('images/'.$recommended->picture)}}" alt="image" class="shadow-sm rounded-circle w45"></a></figure>
           <a class="text-decoration-none"href="{{route('profile_user',$recommended->id)}}" > <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{$recommended->name}}</h4></a>
       </div>
        <div class="card-body d-flex align-items-center pt-0 ps-4 pe-4 pb-4">
            <form action="{{route('sendRequest')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{$recommended->id}}" id="">
                <button type="submit"class="p-2 lh-20 w100 btn btn-primary me-2 text-white text-center font-xssss fw-600 ls-1 rounded-xl">Send</button>
            </form>
            
           
        </div>

       @endforeach

     
      
    </div>
    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
       
        <div class="card-body d-flex align-items-center p-4">
            <h4 class="fw-700 mb-0 font-xssss text-grey-900"> Friendly Requests</h4>
        </div>
        
        @forelse($friendRequests as $friend)
        <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0">

           <figure class="avatar me-3"><a href="{{route('profile_user',$friend->id)}}"><img src="{{url('images/'.$friend->picture)}}" alt="image" class="shadow-sm rounded-circle w45"></a></figure>
           <a class="text-decoration-none"href="{{route('profile_user',$friend->id)}}" > <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{$friend->name}}</h4></a>
       </div>
        <div class="card-body d-flex align-items-center pt-0 ps-4 pe-4 pb-4">
        <form action="{{route('acceptRequest')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{$friend->id}}" id="">
                <button type="submit"class="p-2 lh-20 w100 btn btn-primary me-2 text-white text-center font-xssss fw-600 ls-1 rounded-xl">Accept</button>
            </form>
            <form action="{{route('rejectRequest')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{$friend->id}}" id="">
                <button type="submit"class="p-2 lh-20 w100 btn btn-secondary me-2 text-white text-center font-xssss fw-600 ls-1 rounded-xl">Reject</button>
            </form>
        </div>
@empty
<div class="text-center font-xssss text-grey-500">No Friendly Requests Yet</div>
       @endforelse

     
      
    </div>
</div>



                    </div>
                </div>
                
            </div>            
        </div>
        <!-- main content -->

        <!-- right chat -->
        <div class="right-chat nav-wrap mt-2 right-scroll-bar">
            <div class="middle-sidebar-right-content bg-white shadow-xss rounded-xxl">

                <!-- loader wrapper -->
                <div class="preloader-wrap p-3">
                    <div class="box shimmer">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                    <div class="box shimmer mb-3">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                    <div class="box shimmer">
                        <div class="lines">
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                            <div class="line s_shimmer"></div>
                        </div>
                    </div>
                </div>
                <!-- loader wrapper -->

             
              
              

            </div>
        </div>

        
        <!-- right chat -->
        
       

        <div class="app-header-search">
            <form class="search-form">
                <div class="form-group searchbox mb-0 border-0 p-1">
                    <input type="text" class="form-control border-0" placeholder="Search...">
                    <i class="input-icon">
                        <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                    </i>
                    <a href="#" class="ms-1 mt-1 d-inline-block close searchbox-close">
                        <i class="ti-close font-xs"></i>
                    </a>
                </div>
            </form>
        </div>

    </div> 

@endsection
    

     
    

   


