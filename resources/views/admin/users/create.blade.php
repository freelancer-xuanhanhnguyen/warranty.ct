@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <script type="module">
        jQuery('.js-masked-phone-vn:not(.js-masked-enabled)').mask('0999 999 999');

        One.helpersOnLoad(['jq-maxlength', 'jq-masked-inputs']);
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Thêm nhân viên
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @include('components.alert')
        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-content">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{route('admin.users.store')}}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="name">Tên nhân viên <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="255"
                                       name="name" value="{{old('name')}}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="js-maxlength form-control @error('email') is-invalid @enderror" id="email"
                                       maxlength="255"
                                       name="email" value="{{old('email')}}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="role">Chức vụ</label>
                                <select class="form-select" id="role" name="role">
                                    @foreach(\App\Models\User::ROLE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('role') == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password">Mật khẩu <span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                       class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password">Nhập lại mật khẩu <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-lg form-control-alt"
                                       id="password-confirm" name="password_confirmation" required>
                            </div>

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
                                          name="address" rows="4" maxlength="255">{{old('address')}}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="status">Trạng thái</label>
                                <select class="form-select" id="status"
                                        name="status">
                                    @foreach(\App\Models\User::STATUS as $key => $value)
                                        <option value="{{$key}}"
                                                @if(old('status') === $key) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Thêm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
