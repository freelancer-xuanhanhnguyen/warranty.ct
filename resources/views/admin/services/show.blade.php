@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">

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
        .dataTables_info {
            display: none;
        }
    </style>
@endsection

@section('js')

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])

    <script>
        $(() => {
            jQuery('.js-dataTable-full_').dataTable({
                pageLength: 20,
                lengthMenu: false,
                lengthChange: false,
                autoWidth: false,
                order: [
                    [ 0, 'desc' ]     // cột thứ 4 (index = 3) sẽ được sắp xếp giảm dần
                ],
                columnDefs: [
                    {
                        targets: 'no-sort',
                        orderable: false
                    }
                ],
                language: {
                    info: "",
                    emptyTable: "Không có dữ liệu",
                },
            });
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
                        Phiếu {{\App\Models\Service::TYPE[$data->type]}} {{$data->code}}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @include('components.alert')

        <div class="row">
            <div class="col-md-6">
                <!-- Billing Address -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Thông tin khách hàng</h3>
                    </div>
                    <div class="block-content">
                        <div class="fs-4 mb-1">{{$data->order?->customer?->name}}</div>
                        <address class="fs-sm">
                            <i class="fa-solid fa-person-half-dress"></i> {{\App\Models\Customer::GENDER[$data->order?->customer?->gender] ?? null}}
                            <br>
                            <i class="fa fa-calendar"></i> {{$data->order?->customer?->birthday}}<br>
                            <i class="fa fa-address-book"></i> {{$data->order?->customer?->address}}
                            <br>
                            <br>
                            <i class="fa fa-phone"></i> <a
                                href="tel:{{$data->order?->customer?->phone}}">{{$data->order?->customer?->phone}}</a><br>
                            <i class="fa fa-mail-bulk"></i> <a
                                href="mailto:{{$data->order?->customer?->email}}">{{$data->order?->customer?->email}}</a>
                        </address>
                    </div>
                </div>
                <!-- END Billing Address -->
            </div>
            <div class="col-md-6">
                <!-- Billing Address -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Trạng thái bảo hành - sửa chữa</h3>
                    </div>
                    <div class="block-content">
                        <ul class="order-timeline">
                            @foreach($data->statuses as $status)
                                <li @if($loop->last) class="completed" @endif>
                                    <time>{{$status->created_at}}</time>
                                    <div
                                        class="status-title">{{\App\Models\ServiceStatus::STATUS[$status?->code]}}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- END Billing Address -->
            </div>

            <div class="col">
                <!-- Billing Address -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Thông tin sản phẩm</h3>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive">
                            <table class="table table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th style="width: 100px;">Mã sản phẩm</th>
                                    <th class="text-center">Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-center">Thời gian bảo hành</th>
                                    <th class="text-center">Bảo hành định kỳ</th>
                                    <th class="text-end">Ngày mua</th>
                                    <th class="text-center">Ngày bảo hành định kỳ</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach([$data->order] as $item)
                                    <tr>
                                        <td class="fs-sm">
                                            <a class="fw-semibold"
                                               href="{{route('admin.products.history', $item->id)}}">
                                                <strong>{{$item->product?->code}}</strong>
                                            </a>
                                            <br>
                                            <small class="text-muted">{{$item->product?->serial}}</small>
                                        </td>
                                        <td class="text-center fs-sm">
                                            <strong>{{$item->code}}</strong>
                                        </td>

                                        <td class="fs-sm">
                                            @if($item->customer?->code)
                                                <small class="text-muted">({{$item->customer?->code}})</small>
                                                <br>
                                            @endif
                                            {{$item->customer?->name}}
                                            <br>
                                            <small class="text-muted">({{$item->customer?->email}})</small>
                                        </td>

                                        <td class="fs-sm" style="min-width: 200px">
                                            <strong>{{$item->product?->name}}</strong>
                                        </td>

                                        <td class="text-center fs-sm">
                                            {{$item->product?->warranty_period}} {{\App\Models\Product::WARRANTY_UNIT[$item->product?->warranty_period_unit]}}
                                        </td>

                                        <td class="text-center fs-sm">
                                            {{$item->product?->periodic_warranty}} {{\App\Models\Product::WARRANTY_UNIT[$item->product?->periodic_warranty_unit]}}
                                        </td>

                                        <td class="text-nowrap fs-sm">
                                            {{$item->purchase_date}}
                                        </td>

                                        @php($status = checkWarrantyStatus($item->purchase_date, $item->product?->warranty_period, $item->product?->warranty_period_unit))
                                        @php($isWarrantyExpired = $status['expired'])

                                        <td class="text-nowrap text-center fs-sm">
                                            {{$status['next_warranty_check_date']}}
                                        </td>

                                        <td class="fs-sm">
                                            @if($isWarrantyExpired)
                                                <span
                                                    class="badge bg-warning" data-bs-toggle="tooltip"
                                                    title="Đã hết bảo hành vào ngày {{$status['warranty_end_date']}}">Hết bảo hành</span>
                                            @else
                                                <span
                                                    class="badge bg-info" data-bs-toggle="tooltip"
                                                    title="Ngày bảo hành tiếp theo là {{$status['next_warranty_check_date']}} (tính từ ngày {{$status['used_base_date']}})">Còn bảo hành</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END Billing Address -->
            </div>
        </div>

        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thông tin chi tiết</h3>
                <div class="block-options">
                    <div class="btn-group btn-group-sm" role="group"
                         aria-label="Small Horizontal Primary">
                        <button class="btn btn-sm btn-alt-info" data-bs-toggle="tooltip" title="Lịch sử chỉnh sửa"
                                onclick="$('#log-modal').modal('show')">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>
                        <a class="btn btn-sm btn-alt-warning"
                           href="{{route('admin.services.edit', $data->id)}}"
                           data-bs-toggle="tooltip" title="Sửa">
                            <i class="fa fa-fw fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-borderless table-striped table-vcenter fs-sm">
                    <tbody>
                    <tr>
                        <td class="fs-sm" style="width: 200px;">
                            Mã phiếu {{\App\Models\Service::TYPE[$data->type]}}
                        </td>
                        <td>
                            <span class="fw-semibold">{{$data->code}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Kỹ thuật viên
                        </td>
                        <td>
                            <span class="fw-semibold">{{$data->repairman?->name}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Vấn đề sửa chữa
                        </td>
                        <td>
                            <span class="fw-semibold">{{$data->content}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Phụ phí
                        </td>
                        <td>
                            <span class="fw-semibold">{{format_money($data->fee_total)}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Ghi chú
                        </td>
                        <td>
                            <span class="fw-semibold">{{$data->note}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Trạng thái
                        </td>
                        <td>
                            <span
                                class="badge bg-{{\App\Models\ServiceStatus::STATUS_CLASS[$data?->status?->code ?? 0]}}">{{\App\Models\ServiceStatus::STATUS[$data?->status?->code ?? 0]}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-sm">
                            Đánh giá
                        </td>
                        <td>
                            @include('components.evaluate_star', ['star' => $data->evaluate])
                        </td>
                    </tr>

                    <tr>
                        <td class="fs-sm">
                            Đánh giá chi tiết
                        </td>
                        <td>
                            <span class="fw-semibold">{!! nl2br(e($data->evaluate_note)) !!}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->

    <!-- Modal --->
    <div class="modal fade" id="log-modal" tabindex="-1" aria-labelledby="modal-block-popin"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-popin modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Lịch sử chỉnh sửa phiếu {{$data->code}}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive table-responsive-xxl">
                            <table class="table table-borderless table-striped table-vcenter js-dataTable-full_">
                                <thead>
                                <tr>
                                    <th class="text-center no-sort">Thời gian</th>
                                    <th class="text-center no-sort">Hành động</th>
                                    <th class="no-sort">Người thực hiện</th>
                                    <th class="text-center no-sort">Ngày thực hiện</th>
                                    <th class="text-center no-sort">Mã phiếu</th>
                                    <th class="text-center no-sort">Loại phiếu</th>
                                    <th class="no-sort">Vấn đề sửa chữa</th>
                                    <th class="text-center no-sort">Tổng phí</th>
                                    <th class="no-sort">Ghi chú</th>
                                    <th class="no-sort">Kỹ thuật viên</th>
                                    <th class="text-center no-sort">Đánh giá</th>
                                    <th class="text-center no-sort">Nội dung đánh giá</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($logs as $log)
                                    @php($logData = (object)($log->properties['attributes'] ?? []))
                                    <tr>
                                        <td class="text-center fs-sm">
                                            {{$log->created_at->diffForHumans()}}
                                        </td>

                                        <td class="text-center fs-sm">
                                            {{ \App\Models\Activity::EVENTS[$log->event] }}
                                        </td>
                                        <td class="fs-sm">
                                            {{$log->causer->name}}
                                        </td>
                                        <td class="text-center fs-sm">
                                            {{$log->created_at->format(FORMAT_DATETIME)}}
                                        </td>
                                        <td class="text-center fs-sm">
                                            {{$logData->code}}
                                        </td>
                                        <td class="fs-sm">
                                            <span
                                                class="badge bg-{{ \App\Models\Service::TYPE_CLASS[$logData->type] }}">
                                                {{ \App\Models\Service::TYPE[$logData->type] }}
                                            </span>
                                        </td>
                                        <td class="fs-sm">
                                            {{ $logData->content }}
                                        </td>
                                        <td class="fs-sm">
                                            {{ format_money($logData->fee_total) }}
                                        </td>

                                        <td class="fs-sm">
                                            {{ $logData->note }}
                                        </td>

                                        <td class="text-nowrap fs-sm">
                                            {{ $log?->repairman?->name }}
                                        </td>
                                        <td class="text-center fs-sm text-nowrap">
                                            @include('components.evaluate_star', ['star' => $logData->evaluate])
                                        </td>

                                        <td class="text-center fs-sm text-nowrap">
                                            {{$logData->evaluate_note}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal --->
@endsection
