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
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

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
                        Chỉnh sửa nhân viên
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        {{$data->name}}
                    </h2>
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
                        <form action="{{route('admin.users.update', $data->id)}}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label" for="name">Tên nhân viên <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="255"
                                       name="name" value="{{old('name', $data->name)}}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="js-maxlength form-control" id="email"
                                       maxlength="255" disabled
                                       name="email" value="{{$data->email}}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="role">Chức vụ</label>
                                <select class="form-select" id="role" name="role">
                                    @foreach(\App\Models\User::ROLE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('role', $data->role) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="birthday">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthday"
                                       name="birthday" value="{{old('birthday', $data->birthday)}}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="gender">Giới tính</label>
                                <select class="form-select" id="gender" name="gender">
                                    @foreach(\App\Models\User::GENDER as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('gender', $data->gender) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="phone">Điện thoại</label>
                                <input type="text" class="js-masked-phone-vn form-control"
                                       id="phone" name="phone" value="{{old('phone', $data->phone)}}"
                                       placeholder="0383 999 999">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">Địa chỉ</label>
                                <textarea type="text" class="js-maxlength form-control" id="address"
                                          name="address" rows="4"
                                          maxlength="255">{{old('address', $data->address)}}</textarea>
                            </div>

                            @if(auth()->id() != $data->id)
                                <div class="mb-4">
                                    <label class="form-label" for="status">Trạng thái</label>
                                    <select class="form-select" id="status"
                                            name="status">
                                        @foreach(\App\Models\User::STATUS as $key => $value)
                                            <option value="{{$key}}"
                                                    @if(old('status', $data->status) === $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Cập nhật</button>
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
