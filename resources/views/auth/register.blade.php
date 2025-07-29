@extends('layouts.simple')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')

    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script type="module">
        jQuery('.js-masked-phone-vn:not(.js-masked-enabled)').mask('0999 999 999');

        One.helpersOnLoad(['jq-masked-inputs']);
    </script>
@endsection

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
                            <h3 class="block-title">Đăng ký tài khoản</h3>
                            <div class="block-options">
                                {{--<a class="btn-block-option fs-sm" href="javascript:void(0)" data-bs-toggle="modal"
                                   data-bs-target="#one-terms">View Terms</a>--}}
                                <a class="btn-block-option" href="{{route('login')}}" data-bs-toggle="tooltip"
                                   data-bs-placement="left" title="Đăng nhập">
                                    <i class="fa fa-sign-in-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 px-xxl-5 pt-sm-0 pb-lg-5">
                                <div class="text-center">
                                    <x-logo/>
                                    <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                    <p class="fw-medium text-muted">
                                        Vui lòng điền các thông tin sau để tạo tài khoản mới.
                                    </p>
                                </div>

                                <!-- Sign Up Form -->
                                <!-- jQuery Validation (.js-validation-signup class is initialized in js/pages/op_auth_signup.min.js which was auto compiled from _js/pages/op_auth_signup.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signup" action="{{route('register.request')}}" method="POST">
                                    @csrf
                                    <div class="py-3">
                                        <div class="mb-4">
                                            <input type="text"
                                                   class="form-control form-control-lg form-control-alt @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{old('name')}}"
                                                   placeholder="Họ & tên" required>
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <input type="email"
                                                   class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{old('email')}}" placeholder="Email" required>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <select class="form-select @error('role') is-invalid @enderror"
                                                    name="role">
                                                @foreach(\App\Models\User::ROLE as $key => $item)
                                                    <option value="{{$key}}"
                                                            @if(old('$item') === $key) selected @endif>{{$item}}</option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <x-password name="password" placeholder="Mật khẩu"/>

                                        <x-password name="password_confirmation" placeholder="Nhập lại mật khẩu"/>

                                        <div class="mb-4">
                                            <label class="form-label" for="birthday">Ngày sinh</label>
                                            <input type="date" class="form-control" id="birthday"
                                                   name="birthday" value="{{old('birthday')}}">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="gender">Giới tính</label>
                                            <select class="form-select" id="gender" name="gender">
                                                @foreach(\App\Models\User::GENDER as $key => $type)
                                                    <option value="{{$key}}"
                                                            @if(old('gender') == $key) selected @endif>{{$type}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="phone">Điện thoại</label>
                                            <input type="text" class="js-masked-phone-vn form-control"
                                                   id="phone" name="phone" value="{{old('phone')}}"
                                                   placeholder="0383 999 999">
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="address">Địa chỉ</label>
                                            <textarea type="text" class="js-maxlength form-control" id="address"
                                                      name="address" rows="4"
                                                      maxlength="250">{{old('address')}}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="terms" name="terms" required>
                                                <label class="form-check-label" for="terms">Tôi đồng ý với Điều khoản &
                                                    Điều kiện</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <button type="submit" class="btn w-100 btn-alt-success">
                                                <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Đăng ký
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
                <strong>BẢN QUYỀN</strong> <span data-toggle="year-copy"></span> {{the_website_name()}}
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
