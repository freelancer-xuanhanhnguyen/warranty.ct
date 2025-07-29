@extends('layouts.backend')

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
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Thêm khách hàng
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
                        <form action="{{route('admin.customers.store')}}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="code">Mã khách hàng <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       class="js-maxlength form-control @error('code') is-invalid @enderror" id="code"
                                       name="code" value="{{old('code')}}" placeholder="######" maxlength="20" required>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="name">Tên khách hàng <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="250"
                                       name="name" value="{{old('name')}}" required>
                                <x-invalid-feedback name="name"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="js-maxlength form-control @error('email') is-invalid @enderror" id="email"
                                       maxlength="250"
                                       name="email" value="{{old('email')}}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="birthday">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthday"
                                       name="birthday" value="{{old('birthday')}}">
                                <x-invalid-feedback name="birthday"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="gender">Giới tính</label>
                                <select class="form-select" id="gender" name="gender">
                                    @foreach(\App\Models\Customer::GENDER as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('gender') == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                                <x-invalid-feedback name="gender"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="phone">Điện thoại</label>
                                <input type="text" class="js-masked-phone-vn form-control"
                                       id="phone" name="phone" value="{{old('phone')}}"
                                       placeholder="0383 999 999">
                                <x-invalid-feedback name="phone"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">Địa chỉ</label>
                                <textarea type="text" class="js-maxlength form-control" id="address"
                                          name="address" rows="4" maxlength="250">{{old('address')}}</textarea>
                                <x-invalid-feedback name="address"/>
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
