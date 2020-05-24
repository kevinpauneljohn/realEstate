<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand-md') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.left-sidebar-link')

        {{-- Configured left links --}}
        @each('adminlte::partials.menuitems.menu-item-top-nav-left', $adminlte->menu(), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto reminder-notification">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-clipboard-list"></i>
                @php
                    if(auth()->user()->hasAnyRole(['super admin','admin']))
                    {
                        $request = \App\Threshold::where([
                            ['status','=','pending']
                        ]);
                    }else{
                        $request = \App\Threshold::where([
                            ['user_id','=',auth()->user()->id],
                            ['status','=','pending'],
                        ]);
                    }

                @endphp
                @if($request->count() > 0)
                    <span class="badge badge-danger navbar-badge">
                    {{$request->count()}}
                </span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{$request->count()}} @if($request->count() > 1)Requests @else Request @endif</span>
                <div class="dropdown-divider"></div>
                @foreach($request->orderBy('id','desc')->limit(10)->get() as $change)
                    <a href="@if(auth()->user()->can('view request')) {{route('requests.show',['request' => $change->id])}} @else # @endif" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <i class="fas fa-bell mr-2 text-info"></i>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{$change->type}} {{$change->storage_name}}
                                    <span class="float-right text-sm text-muted"><i class="far fa-clock mr-1"></i> {{$change->created_at->diffForHumans()}}</span>
                                </h3>
                                <p class="text-sm">Requested by: <span class="text-primary">{{\App\User::find($change->user_id)->fullname}}</span></p>
                                <p class="text-sm text-success">{{$change->priority->name}}</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                @endforeach
                <a href="@if(auth()->user()->can('view request')) {{route('thresholds.index')}} @else # @endif" class="dropdown-item dropdown-footer">See All Requests</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @php
                    $notification = \App\Notification::where([
                        ['user_id','=',auth()->user()->id],
                        ['viewed','=',0],
                        ['type','=','lead activity'],
                    ]);
                @endphp
                @if($notification->count() > 0)
                <span class="badge badge-danger navbar-badge">
                    {{$notification->count()}}
                </span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{$notification->count()}} @if($notification->count() > 1)Reminders @else Reminder @endif</span>
                <div class="dropdown-divider"></div>
                @foreach($notification->orderBy('id','desc')->limit(10)->get() as $notify)
                    <a href="{{route('leads.show',['lead' => $notify->data->lead_id])}}" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <i class="fas fa-bell mr-2 text-info"></i>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{$notify->data->category}}
                                    <span class="float-right text-sm text-muted"><i class="far fa-clock mr-1"></i> {{$notify->created_at->diffForHumans()}}</span>
                                </h3>
                                <p class="text-sm text-primary">{{\App\Lead::find($notify->data->lead_id)->fullname}}</p>
                                <p class="text-sm text-success">{{$notify->data->time_left}}</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
{{--                    <a href="{{$notify->data->link}}" class="dropdown-item">--}}
{{--                        <i class="fas fa-bell mr-2"></i> {{$notify->data->category}}<br/>Test--}}
{{--                        <span class="float-right text-muted text-sm">{{$notify->created_at->diffForHumans()}}</span>--}}
{{--                    </a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
                @endforeach
                <a href="#" class="dropdown-item dropdown-footer">See All Reminders</a>
            </div>
        </li>
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.menuitems.menu-item-top-nav-right', $adminlte->menu(), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.right-sidebar-link')
        @endif
    </ul>

</nav>
