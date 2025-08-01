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
                        Thêm linh kiện
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
                        <form action="{{route('admin.accessories.store')}}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="code">Mã linh kiện</label>
                                <input type="text" class="js-maxlength form-control" id="code" maxlength="20"
                                       name="code" value="{{old('code', $data->code)}}">
                                <x-invalid-feedback name="code"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="name">Tên linh kiện <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="250"
                                       name="name" value="{{old('name', $data->name)}}" required>
                                <x-invalid-feedback name="name"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="quantity">Số lượng <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="js-maxlength form-control" id="quantity" maxlength="5"
                                       name="quantity" value="{{old('quantity', $data->quantity)}}" required>
                                <x-invalid-feedback name="quantity"/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="unit_price">Giá tiền <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="js-maxlength form-control" id="unit_price" min="0" maxlength="10"
                                       name="unit_price" value="{{old('unit_price', $data->unit_price)}}" required>
                                <x-invalid-feedback name="unit_price"/>
                            </div>

                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Cập nật</button>
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
