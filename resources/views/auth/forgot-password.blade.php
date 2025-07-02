@extends('layouts.simple')

@section('content')
    <!-- Page Content -->
    <div class="hero-static d-flex align-items-center">
        <div class="content">
            <div class="row justify-content-center push">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Reminder Block -->
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Quên mật khẩu?</h3>
                            <div class="block-options">
                                <a class="btn-block-option" href="{{route('login')}}" data-bs-toggle="tooltip"
                                   data-bs-placement="left" title="Đăng nhập">
                                    <i class="fa fa-sign-in-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <div class="text-center">
                                    <x-logo :width="100"/>
                                    <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                    <p class="fw-medium text-muted">
                                        Vui lòng cung cấp email hoặc tên người dùng của tài khoản và chúng tôi sẽ gửi
                                        cho bạn mật khẩu.
                                    </p>
                                </div>


                                <!-- Reminder Form -->
                                <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/op_auth_reminder.min.js which was auto compiled from _js/pages/op_auth_reminder.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-reminder mt-4" action="{{route('password.forgot.request')}}"
                                      method="POST">
                                    <div class="mb-4">
                                        <input type="text" class="form-control form-control-lg form-control-alt"
                                               id="email" name="email"
                                               placeholder="Email">
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-envelope me-1 opacity-50"></i> Gửi Mail
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Reminder Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Reminder Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>BẢN QUYỀN</strong> <span data-toggle="year-copy"></span> {{the_website_name()}}
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
