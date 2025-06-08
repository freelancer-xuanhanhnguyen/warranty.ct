<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="d-flex align-items-center">
            <!-- Logo -->
            <a class="fw-semibold fs-5 tracking-wider text-dual me-3" href="/">
                <img width="50" src="{{asset("/media/logo/logo.png")}}" alt="">
            </a>
            <!-- END Logo -->
        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- Menu -->
            <div class="d-none d-lg-block">
                <ul class="nav-main nav-main-horizontal nav-main-hover">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->routeIs('orders.index', request()->email)) active @endif"
                           href="{{route('orders.index', request()->email)}}">
                            <i class="nav-main-link-icon si si-puzzle"></i>
                            <span class="nav-main-link-name">Sản phẩm</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->routeIs('services.index', request()->email)) active @endif"
                           href="{{route('services.index', request()->email)}}">
                            <i class="nav-main-link-icon si si-support"></i>
                            <span class="nav-main-link-name">Bảo hành - sửa chữa</span>
                        </a>
                    </li>
                    {{--<li class="nav-main-item">
                        <a class="nav-main-link" href="javascript:void(0)">
                            <i class="nav-main-link-icon si si-calendar"></i>
                            <span class="nav-main-link-name">Bảo hành định kỳ</span>
                        </a>
                    </li>--}}
                </ul>
            </div>
            <!-- END Menu -->

            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none ms-1" data-toggle="layout"
                    data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-body-extra-light">
        <div class="content-header">
            <form class="w-100" method="POST">
                <div class="input-group input-group-sm">
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
    <div id="page-header-loader" class="overlay-header bg-primary-lighter">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin text-primary"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>
