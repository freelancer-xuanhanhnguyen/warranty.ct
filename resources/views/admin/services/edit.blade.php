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
                        Chỉnh sửa phiếu bảo hành - sửa chữa
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
        <form action="{{route('admin.services.update', $data->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="block block-rounded">
                        <div class="block-content">
                            <div class="mb-4">
                                <label class="form-label" for="order_code">Mã đơn hàng<span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_code"
                                        name="order_code" disabled="{{count($data->statuses) > 1 ? 'true' : 'false'}}"
                                        data-placeholder="#######" required>
                                    <option value=""></option>
                                    @foreach($orderCodes as $code)
                                        <option value="{{$code}}"
                                                @if(old('order_code', $data->order->code) == $code) selected @endif >{{$code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="order_id">Sản phẩm <span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_id"
                                        name="order_id" disabled="{{count($data->statuses) > 1 ? 'true' : 'false'}}"
                                        data-placeholder="Mã sản phẩm - tên sản phẩm" required>
                                    @foreach($orders as $order)
                                        <option value="{{$order->id}}"
                                                @if(old('order_id', $data->order_id) == $order->id) selected @endif >{{$order->product?->code}}
                                            - {{$order->product?->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="type">Loại phiếu</label>
                                <select class="form-select" id="type"
                                        name="type" @if(count($data->statuses) > 1) disabled @endif>
                                    @foreach(\App\Models\Service::TYPE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('type', $data->type) === $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="content">Vấn đề sửa chữa <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control js-maxlength" maxlength="500" id="content"
                                          name="content" rows="4" required>{{old('content', $data->content)}}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="fee_total">Phụ phí (đ)</label>
                                <input type="text" class="form-control" id="fee_total"
                                       name="fee_total" value="{{old('fee_total', $data->fee_total)}}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="fee_detail">Chi tiết phụ
                                    phí</label>
                                <textarea class="form-control js-maxlength" maxlength="500" id="fee_detail"
                                          name="fee_detail" rows="4">{{old('fee_detail', $data->fee_detail)}}</textarea>
                            </div>

                            @if(hasRole([\App\Models\User::ROLE_CSKH]))
                                <div class="mb-4">
                                    <label class="form-label" for="repairman_id">Kỹ thuật viên</label>
                                    <select class="js-select2 form-select" id="repairman_id"
                                            name="repairman_id" data-placeholder="Vui lòng chọn">
                                        <option value=""></option>
                                        <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}"
                                                    @if(old('repairman_id', $data->repairman_id) === $user->id) selected @endif>{{"$user->id - $user->name"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>


                    </div>
                </div>

                <div class="col-md-2 col-lg-4">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Trạng thái phiếu {{\App\Models\Service::TYPE[$data->type]}}</h3>
                        </div>
                        <div class="block-content">
                            <ul class="order-timeline">
                                @foreach($data->statuses as $status)
                                    <li @if($loop->last) class="completed" @endif>
                                        <time>{{$status->created_at}}</time>
                                        <div
                                            class="status-title">{{\App\Models\ServiceStatus::STATUS[$status->code]}}</div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mb-4">
                                <label class="form-label" for="status">Trạng thái</label>
                                <select class="form-select" id="status"
                                        name="status">
                                    @foreach(\App\Models\ServiceStatus::STATUS as $key => $status)
                                        <option value="{{$key}}"
                                                @if(old('status', $data->status?->code) === $key) selected @endif>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(hasRole([\App\Models\User::ROLE_CSKH]))
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Đánh giá dịch vụ {{\App\Models\Service::TYPE[$data->type]}}</h3>
                            </div>
                            <div class="block-content">
                                <div class="mb-4">
                                    <label class="form-label" for="status">Đánh giá</label>
                                    <div class="js-rating" data-score="{{$data->evaluate}}" aria-required="true"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="evaluate_note">Đánh giá chi tiết</label>
                                    <textarea id="evaluate_note" class="form-control js-maxlength" maxlength="500"
                                              name="evaluate_note"
                                              rows="4">{{old('evaluate_note', $data->evaluate_note ?: "Sản phẩm sau bảo hành:
Dịch vụ sửa chữa:
Dịch vụ CSKH:
Kỹ thuật viên:")}}</textarea>
                                    <small class="text-muted">Đánh giá phải từ 1 <i class="fa fa-star text-warning"></i></small>
                                </div>
                            </div>
                        </div>
                    @endif
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
