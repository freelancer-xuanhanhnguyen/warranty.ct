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
    <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <!-- Page JS Code -->
    <script type="module">
        One.helpersOnLoad(['jq-select2', 'jq-maxlength', 'jq-masked-inputs']);

        $(() => {
            $('#order_code').change(function () {
                $('#order_id').find('option').remove();
                $("#order_id").trigger('change');

                const orderCode = $(this).val();
                if (!orderCode) {
                    $("#order_id").prop("disabled", true).trigger('change');
                    return;
                }
                $.get(`/admin/products/${orderCode}`).then((data) => {
                    data?.map((v, i) => {
                        let newOption = new Option(`${v?.product?.code} - ${v?.product?.name}`, v?.id, i === 0, false);
                        $('#order_id').append(newOption).trigger('change');
                    })

                    $("#order_id").prop("disabled", false);
                })
            })
        })
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Thêm phiếu bảo hành - sửa chữa
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
                        <form action="{{route('admin.services.store')}}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="order_code">Mã đơn hàng<span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_code"
                                        name="order_code"
                                        data-placeholder="#######" required>
                                    <option value=""></option>
                                    @foreach($orderCodes as $code)
                                        <option value="{{$code}}"
                                                @if(old('order_code', request()->order_code) == $code) selected @endif >{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="order_id">Sản phẩm <span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_id"
                                        name="order_id" @if(!isset($orders)) disabled @endisset
                                        data-placeholder="Mã sản phẩm - tên sản phẩm" required>
                                    @isset($orders)
                                        @foreach($orders as $order)
                                            <option value="{{$order->id}}"
                                                    @if(old('order_id') == $order->id || request()->order_code == $order->code) selected @endif >{{$order->product?->code}}
                                                - {{$order->product?->name}}</option>
                                        @endforeach
                                    @else
                                        <option value=""></option>
                                    @endisset
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="type">Loại phiếu</label>
                                <select class="form-select" id="type"
                                        name="type"
                                        data-placeholder="Choose one..">
                                    @foreach(\App\Models\Service::TYPE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('type', request()->type) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="content">Vấn đề sửa chữa <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control js-maxlength" maxlength="500" id="content"
                                          name="content" rows="4" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="fee_total">Phụ phí (đ)</label>
                                <input type="text" class="form-control" id="fee_total"
                                       name="fee_total" value="">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="fee_detail">Chi tiết phụ
                                    phí</label>
                                <textarea class="form-control js-maxlength" maxlength="500" id="fee_detail"
                                          name="fee_detal" rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="repairman_id">Kỹ thuật viên</label>
                                <select class="js-select2 form-select" id="repairman_id"
                                        name="repairman_id"
                                        data-placeholder="Tự động gán kỹ thuật viên tương ứng trên sản phẩm">
                                    <option value=""></option>
                                    <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                                @if(old('repairman_id') === $user->id) selected @endif>{{"$user->id - $user->name"}}</option>
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
