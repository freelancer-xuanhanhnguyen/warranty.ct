@extends('layouts.simple')

@section('content')
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    @include('components.alert')
                    <!-- Sign In Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Đăng nhập</h3>
                            <div class="block-options">
                                <a class="btn-block-option fs-sm" href="{{route('password.forgot')}}">Quên mật khẩu?</a>
                                <a class="btn-block-option" href="{{route('register')}}" data-bs-toggle="tooltip"
                                   data-bs-placement="left" title="Đăng ký tài khoản">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 pt-sm-0 pb-lg-5">
                                <div class="text-center">
                                    <img width="100" src="{{asset('media/logo/logo.png')}}" alt="">
                                    <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                    <p class="fw-medium text-muted">
                                        Xin chào, vui lòng nhập tài khoản của bạn.
                                    </p>
                                </div>

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin" action="{{route('login')}}" method="POST">
                                    @csrf
                                    <div class="py-3">
                                        <div class="mb-4">
                                            <input type="text"
                                                   class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{old('email')}}" placeholder="Email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <input type="password"
                                                   class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror"
                                                   id="password" name="password" placeholder="Mật khẩu">
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="remember" name="remember">
                                                <label class="form-check-label" for="remember">Nhớ mật khẩu?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Đăng nhập
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Sign In Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>BẢN QUYỀN</strong> <span data-toggle="year-copy"></span> {{the_website_name()}}
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
