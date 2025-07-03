@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }

        .order-timeline {
            list-style: none;
            padding-left: 20px;
            border-left: 3px solid #ccc;
            position: relative;
            max-width: 500px;
            margin: auto;
        }

        .order-timeline li {
            margin-bottom: 30px;
            position: relative;
            padding-left: 20px;
        }

        .order-timeline li::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #3498db;
            border-radius: 50%;
            position: absolute;
            left: -8px;
            top: 4px;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #3498db;
        }

        .order-timeline li.completed::before {
            background: #2ecc71;
            box-shadow: 0 0 0 2px #2ecc71;
        }

        .order-timeline li.failed::before {
            background: #e74c3c;
            box-shadow: 0 0 0 2px #e74c3c;
        }

        .order-timeline time {
            display: block;
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }

        .order-timeline .status-title {
            font-weight: bold;
        }

        .order-timeline .note {
            font-size: 13px;
            color: #555;
        }
    </style>
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
    <script src="{{asset('js/plugins/raty-js/jquery.raty.js')}}"></script>
    <!-- Page JS Code -->
    <script type="module" src="{{asset('js/pages/be_comp_rating.js')}}"></script>

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
                        Chỉnh sửa thông tin sản phẩm
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
        <form action="{{route('admin.products.update', $data->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="block block-rounded">
                        <div class="block-content">
                            <div class="mb-4">
                                <label class="form-label" for="code">Mã sản phẩm</label>
                                <input class="form-control" name="code" value="{{$data->code}}" disabled readonly/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="serial">Sku</label>
                                <input class="form-control" name="serial" value="{{$data->serial}}" disabled readonly/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="order_id">Tên thiết bị</label>
                                <input class="form-control" name="order_id" value="{{$data->name}}" disabled readonly/>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="type">Thời gian bảo hành</label>
                                <div class="d-flex gap-3">
                                    <input class="form-control" name="warranty_period" type="number" min="1" max="999"
                                           value="{{old('warranty_period', $data->warranty_period)}}"/>
                                    <select class="form-select"
                                            name="warranty_period_unit">
                                        @foreach(\App\Models\Product::WARRANTY_UNIT as $key => $unit)
                                            <option value="{{$key}}"
                                                    @if(old('warranty_period_unit', $data->warranty_period_unit) === $key) selected @endif>{{$unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="periodic_warranty">Bảo hành định kỳ</label>
                                <div class="d-flex gap-3">
                                    <input class="form-control" name="periodic_warranty" type="number" min="1"
                                           value="{{old('periodic_warranty', $data->periodic_warranty)}}"
                                           max="999"/>
                                    <select class="form-select"
                                            name="periodic_warranty_unit">
                                        @foreach(\App\Models\Product::WARRANTY_UNIT as $key => $unit)
                                            <option value="{{$key}}"
                                                    @if(old('periodic_warranty_unit', $data->periodic_warranty_unit) === $key) selected @endif>{{$unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="repairman_id">Kỹ thuật viên bảo hành - sửa chữa</label>
                                <select class="js-select2 form-select" id="repairman_id"
                                        name="repairman_id" data-placeholder="Vui lòng chọn">
                                    <option value=""></option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                                @if(old('repairman_id', $data->repairman_id) === $user->id) selected @endif>{{"$user->id - $user->name"}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 text-center">
                <button type="submit" class="btn btn-alt-primary">Cập nhật</button>
            </div>
        </form>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
