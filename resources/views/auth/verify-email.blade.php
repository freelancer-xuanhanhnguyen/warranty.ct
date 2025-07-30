@extends('layouts.simple')

@section('content')
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    @include('components.alert')
                    <!-- Sign Up Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Xác thực tài khoản</h3>

                            <div class="block-options">
                                <form id="logout-form" action="{{route('logout')}}" method="POST">
                                    @csrf
                                </form>
                                <a class="btn-block-option" data-bs-toggle="tooltip"
                                   data-bs-placement="left" title="Đăng xuất"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit()"
                                   href="#">
                                    <i class="fa fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <div class="text-center">
                                    <x-logo/>
                                    <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                    <p>Vui lòng kiểm tra email của bạn để xác minh tài khoản.</p>
                                </div>

                                <form class="text-center" method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Gửi lại email xác minh</button>
                                </form>
                                <!-- END Sign Up Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Sign Up Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>BẢN QUYỀN</strong> <span data-toggle="year-copy"></span> {{the_website_name()}}
            </div>
        </div>
    </div>
@endsection
