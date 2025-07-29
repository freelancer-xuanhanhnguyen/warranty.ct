@extends('layouts.backend')

@section('js')
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script type="module">
        jQuery('.js-masked-phone-vn:not(.js-masked-enabled)').mask('0999 999 999');

        One.helpersOnLoad(['jq-masked-inputs']);
    </script>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="content content-boxed">
        @include('components.alert')
        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Chỉnh sửa hồ sơ</h3>
            </div>

            <div class="block-content">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{route('admin.profile.update', $data->id)}}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label" for="name">Họ & tên<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="250"
                                       name="name" value="{{old('name', $data->name)}}" required>
                                <x-invalid-feedback name="name" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email"
                                       class="js-maxlength form-control" id="email"
                                       maxlength="250" disabled
                                       name="email" value="{{$data->email}}">
                                <x-invalid-feedback name="email" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="role">Chức vụ</label>
                                <select class="form-select" id="role" name="role">
                                    @foreach(\App\Models\User::ROLE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('role', $data->role) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                                <x-invalid-feedback name="role" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="birthday">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthday"
                                       name="birthday" value="{{old('birthday', $data->birthday)}}">
                                <x-invalid-feedback name="birthday" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="gender">Giới tính</label>
                                <select class="form-select" id="gender" name="gender">
                                    @foreach(\App\Models\User::GENDER as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('gender', $data->gender) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                                <x-invalid-feedback name="gender" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="phone">Điện thoại</label>
                                <input type="text" class="js-masked-phone-vn form-control"
                                       id="phone" name="phone" value="{{old('phone', $data->phone)}}"
                                       placeholder="0383 999 999">
                                <x-invalid-feedback name="phone" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">Địa chỉ</label>
                                <textarea type="text" class="js-maxlength form-control" id="address"
                                          name="address" rows="4"
                                          maxlength="250">{{old('address', $data->address)}}</textarea>
                                <x-invalid-feedback name="address" />
                            </div>

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
