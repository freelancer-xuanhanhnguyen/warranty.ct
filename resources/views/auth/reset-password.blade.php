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
                            <h3 class="block-title">Reset Password</h3>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                <div class="text-center">
                                    <x-logo/>
                                    <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                    <p class="fw-medium text-muted">
                                        Vui lòng điền các thông tin sau để thiết lập lại tài khoản.
                                    </p>
                                </div>

                                <!-- Sign Up Form -->
                                <!-- jQuery Validation (.js-validation-signup class is initialized in js/pages/op_auth_signup.min.js which was auto compiled from _js/pages/op_auth_signup.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signup" action="{{route('password.reset.request')}}"
                                      method="POST">
                                    @csrf
                                    <input hidden name="token" value="{{$token}}"/>
                                    <div class="py-3">
                                        <div class="mb-4">
                                            <input type="text"
                                                   class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{old('email', $email)}}"
                                                   placeholder="Email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <input type="password"
                                                   class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror"
                                                   id="password" name="password" placeholder="Password" required>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <input type="password" class="form-control form-control-lg form-control-alt"
                                                   id="password_confirmation" name="password_confirmation"
                                                   placeholder="Confirm Password">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <button type="submit" class="btn w-100 btn-alt-success">
                                                <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Đặt lại mật khẩu
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign Up Form -->
                            </div>
                        </div>
                    </div>
                    <!-- END Sign Up Block -->
                </div>
            </div>
            <div class="fs-sm text-muted text-center">
                <strong>OneUI 5.8</strong> &copy; <span data-toggle="year-copy"></span>
            </div>
        </div>

        <!-- Terms Modal -->
        <div class="modal fade" id="one-terms" tabindex="-1" role="dialog" aria-labelledby="one-terms"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-transparent mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Terms &amp; Conditions</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet
                                adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut
                                metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis
                                purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet
                                adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut
                                metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis
                                purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet
                                adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut
                                metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis
                                purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet
                                adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut
                                metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis
                                purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                            <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet
                                adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut
                                metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis
                                purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        </div>
                        <div class="block-content block-content-full text-end bg-body">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">I Agree
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Terms Modal -->
    </div>
    <!-- END Page Content -->
@endsection
