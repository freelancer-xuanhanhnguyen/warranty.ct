<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="d-flex align-items-center">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout"
                    data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Open Search Section (visible on smaller screens) -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
                    data-action="header_search_on">
                <i class="fa fa-fw fa-search"></i>
            </button>
            <!-- END Open Search Section -->

            <!-- Search Form (visible on larger screens) -->
            {{--<form class="d-none d-md-inline-block" action="/dashboard" method="POST">
                @csrf
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-alt" placeholder="Search.."
                           id="page-header-search-input2" name="page-header-search-input2">
                    <span class="input-group-text border-0">
                        <i class="fa fa-fw fa-search"></i>
                      </span>
                </div>
            </form>--}}
            <!-- END Search Form -->
        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <img class="rounded-circle" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="Header Avatar"
                         style="width: 21px;">
                    <span class="d-none d-sm-inline-block ms-2">{{auth()->user()->name}}</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                     aria-labelledby="page-header-user-dropdown">
                    <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                        <img class="img-avatar img-avatar48 img-avatar-thumb"
                             src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                        <p class="mt-2 mb-0 fw-medium">{{auth()->user()->name}}</p>
                        <p class="mb-0 text-muted fs-sm fw-medium">
                            @switch(auth()->user()->role)
                                @case(\App\Models\User::ROLE_CSKH)
                                CSKH
                                @break
                                @case(\App\Models\User::ROLE_REPAIRMAN)
                                Kỹ thuật viên
                                @break
                                @default
                                Admin
                                @break
                            @endswitch
                        </p>
                    </div>
                    <div>
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           href="{{route('admin.profile.index')}}">
                            <span class="fs-sm fw-medium">Hồ sơ</span>
                        </a>
                    </div>
                    <div>
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           href="{{route('admin.change-password.index')}}">
                            <span class="fs-sm fw-medium">Đổi mật khẩu</span>
                        </a>
                    </div>
                    <div role="separator" class="dropdown-divider m-0"></div>
                    <div>
                        <form id="logout-form" action="{{route('logout')}}" method="POST">
                            @csrf
                        </form>
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit()"
                           href="#">
                            <span class="fs-sm fw-medium">Đăng xuất</span>
                        </a>

                    </div>
                </div>
            </div>
            <!-- END User Dropdown -->
            @php($count = auth()->user()->unreadNotifications->count())
            {{--@php($count = count($unreadNotifications))--}}
            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-bell"></i>
                    @if($count)
                        <span class="text-primary">•</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm"
                     aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                        <h5 class="dropdown-header text-uppercase">Thông báo</h5>
                    </div>
                    <ul class="nav-items mb-0" style="max-height: 80vh;overflow: auto">
                        @php($notifications = auth()->user()
                                                    ->notifications()
                                                    ->orderBy('read_at', 'asc')
                                                    ->orderBy('created_at', 'desc')
                                                    ->limit(10)
                                                    ->get())
                        @foreach ($notifications as $notification)
                            <li @if(!isset($notification->read_at)) class="bg-black-10" @endif>
                                @if(!isset($notification->read_at))
                                    <form id="notify-{{$notification->id}}" method="POST"
                                          action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input name="redirect_url" type="text"
                                               @if(isset($notification->data['service']))
                                                   value="{{route('admin.services.show', $notification->data['service']['id'])}}"
                                               @elseif(isset($notification->data['user']))
                                                   value="{{route('admin.users.edit', $notification->data['user']['id'])}}"
                                               @endif
                                               hidden>
                                    </form>
                                @endif

                                <a class="text-dark d-flex py-2"
                                   @if(isset($notification->data['service']))
                                       href="{{route('admin.services.show', $notification->data['service']['id'])}}"
                                   @elseif(isset($notification->data['user']))
                                       href="{{route('admin.users.edit', $notification->data['user']['id'])}}"
                                   @endif

                                   @if(!isset($notification->read_at))
                                       onclick="event.preventDefault();document.getElementById('notify-{{$notification->id}}').submit()"
                                    @endif
                                >
                                    <div class="flex-shrink-0 me-2 ms-3">
                                        @isset($notification->read_at)
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        @else
                                            <span class="text-primary fs-lg">•</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 pe-2">
                                        <div class="fw-semibold"
                                             @if($notification->data['type'] === "update_fee")
                                                 data-bs-toggle="tooltip"
                                             title="{{ $notification->data['service']['fee_detail'] }}"
                                            @endif
                                        >
                                            {{ $notification->data['message'] }}
                                        </div>
                                        <small
                                            class="fw-medium text-muted">{{ $notification->data['created_by']['email'] ?? $notification->data['user']['email'] ?? null }}
                                            • {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if($count)
                        <div class="p-2 border-top text-center">
                            <form id="notify-read-all" method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                @method('PATCH')
                            </form>
                            <a class="d-inline-block fw-medium" href="#"
                               onclick="event.preventDefault();document.getElementById('notify-read-all').submit()">
                                <i class="fa fa-fw fa-check-circle me-1 text-success"></i> Đã đọc tất cả
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- END Notifications Dropdown -->

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary d-none ms-2" data-toggle="layout"
                    data-action="side_overlay_toggle">
                <i class="fa fa-fw fa-list-ul fa-flip-horizontal"></i>
            </button>
            <!-- END Toggle Side Overlay -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-body-extra-light">
        <div class="content-header">
            <form class="w-100" action="{{ route('dashboard') }}" method="POST">
                @csrf
                <div class="input-group">
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                            data-action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.."
                           id="page-header-search-input" name="page-header-search-input">
                </div>
            </form>
        </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-body-extra-light">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>
