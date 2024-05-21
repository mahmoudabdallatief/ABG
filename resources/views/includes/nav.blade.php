<div class="main-wrapper">

        <!-- navigation top-->
        <div class="nav-header bg-white shadow-xs border-0">
            <div class="nav-top">
                <a href="{{route('home')}}"><span class="d-inline-block fredoka-font ls-3 fw-600 text-current font-xxl logo-text mb-0">ConnectMe. </span> </a>
                
            </div>
            
          
            <a href="{{route('home')}}" class="p-2 text-center ms-3 menu-icon center-menu-icon"><i class="fa-solid fa-house fa-2x"></i></a>
            
            <a href="{{route('profile')}}" class="p-2 text-center ms-0 menu-icon center-menu-icon"><i class="fa-solid fa-user fa-2x"></i></a>

            <a href="#" class="p-2 text-center ms-auto menu-icon" id="dropdownMenu3" data-bs-toggle="dropdown" aria-expanded="false"><span class="dot-count bg-warning"></span><i class="fa-solid fa-bell fa-2x"></i></a>
            <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg" aria-labelledby="dropdownMenu3">
                
                <h4 class="fw-700 font-xss mb-4">Notification</h4>
                @foreach (auth()->user()->unreadNotifications as $notification)
                @if($notification->data['title']=='has sent you a friend request')
                <div class="card bg-transparent-card w-100 border-0 ps-5 mb-3">
<a href="{{route('profile_user',$notification->data['id'])}}"class="text-decoration-none" >
<img src="{{url('images/'.DB::table('users')->where('id',$notification->data['id'])->value('picture'))}}" alt="user" class="w40 position-absolute left-0 rounded-circle">
</a>

<a href="{{route('profile_user',$notification->data['id'])}}"class="text-decoration-none" >
<h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block">{{ $notification->data['user'] }} <span class="text-grey-400 font-xsssss fw-600 float-right mt-1">{{ $notification->created_at }}</span></h5>
</a>

<h6 class="text-grey-500 fw-500 font-xssss lh-4">{{ $notification->data['title'] }}</h6>

</div>
@else
<div class="card bg-transparent-card w-100 border-0 ps-5 mb-3">
<a href="{{route('singlepost',$notification->data['id'])}}"class="text-decoration-none" >
<img src="{{url('images/'.DB::table('users')->where('id',$notification->data['user_id'])->value('picture'))}}" alt="user" class="w40 position-absolute left-0 rounded-circle">
</a>

<a href="{{route('singlepost',$notification->data['id'])}}"class="text-decoration-none" >
<h5 class="font-xsss text-grey-900 mb-1 mt-0 fw-700 d-block">{{ $notification->data['user'] }} <span class="text-grey-400 font-xsssss fw-600 float-right mt-1">{{ $notification->created_at }}</span></h5>
</a>

<h6 class="text-grey-500 fw-500 font-xssss lh-4">{{ $notification->data['title'] }}</h6>

</div>
@endif
                                @endforeach

               
                
            </div>
            
            <div class="p-2 text-center ms-3 position-relative dropdown-menu-icon menu-icon cursor-pointer">
            <i class="fa-solid fa-gear fa-2x text-primary"></i>
                <div class="dropdown-menu-settings switchcolor-wrap">
                    <h4 class="fw-700 font-sm mb-4">Settings</h4>
                    <h6 class="font-xssss text-grey-500 fw-700 mb-3 d-block">Choose Color Theme</h6>
                    <ul>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="red" checked="">
                                <span class="circle-color bg-red" style="background-color: #ff3b30;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="green">
                                <span class="circle-color bg-green" style="background-color: #4cd964;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="blue" checked="">
                                <span class="circle-color bg-blue" style="background-color: #132977;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="pink">
                                <span class="circle-color bg-pink" style="background-color: #ff2d55;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="yellow">
                                <span class="circle-color bg-yellow" style="background-color: #ffcc00;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="orange">
                                <span class="circle-color bg-orange" style="background-color: #ff9500;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="gray">
                                <span class="circle-color bg-gray" style="background-color: #8e8e93;"></span>
                            </label>
                        </li>

                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="brown">
                                <span class="circle-color bg-brown" style="background-color: #D2691E;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="darkgreen">
                                <span class="circle-color bg-darkgreen" style="background-color: #228B22;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="deeppink">
                                <span class="circle-color bg-deeppink" style="background-color: #FFC0CB;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="cadetblue">
                                <span class="circle-color bg-cadetblue" style="background-color: #5f9ea0;"></span>
                            </label>
                        </li>
                        <li>
                            <label class="item-radio item-content">
                                <input type="radio" name="color-radio" value="darkorchid">
                                <span class="circle-color bg-darkorchid" style="background-color: #9932cc;"></span>
                            </label>
                        </li>
                    </ul>
                    
                    <div class="card bg-transparent-card border-0 d-block mt-3">
                        <h4 class="d-inline font-xssss mont-font fw-700">Header Background</h4>
                        <div class="d-inline float-right mt-1">
                            <label class="toggle toggle-menu-color"><input type="checkbox"><span class="toggle-icon"></span></label>
                        </div>
                    </div>
                    <div class="card bg-transparent-card border-0 d-block mt-3">
                        <h4 class="d-inline font-xssss mont-font fw-700">Menu Position</h4>
                        <div class="d-inline float-right mt-1">
                            <label class="toggle toggle-menu"><input type="checkbox"><span class="toggle-icon"></span></label>
                        </div>
                    </div>
                    <div class="card bg-transparent-card border-0 d-block mt-3">
                        <h4 class="d-inline font-xssss mont-font fw-700">Dark Mode</h4>
                        <div class="d-inline float-right mt-1">
                            <label class="toggle toggle-dark"><input type="checkbox"><span class="toggle-icon"></span></label>
                        </div>
                    </div>
                    
                </div>
            </div>
            <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                         <i class="fa-solid fa-arrow-right-from-bracket text-primary"></i> 
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
            <a href="{{route('profile')}}" class="p-0 ms-3 menu-icon rounded-circle"><img src="{{ url('images/' . DB::table('users')->where('id', Auth::user()->id)->value('picture')) }}" alt="user" class="w40 mt--1 rounded-circle">
</a>
            
        </div>