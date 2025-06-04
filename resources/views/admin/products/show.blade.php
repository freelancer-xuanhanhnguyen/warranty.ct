@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">

    <style>
        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

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
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Lịch sử bảo hành - sửa chữa của thiết bị {{$data->product?->code}}
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        {{$data->product?->name}}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @include('components.alert')

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
                            <th class="text-center" style="width: 100px;">Mã sản phẩm</th>
                            <th class="text-center" style="width: 100px;">Serial</th>
                            <th class="d-none d-sm-table-cell text-center">Mã đơn hàng</th>
                            <th class="d-none d-xl-table-cell">Khách hàng</th>
                            <th class="d-none d-xl-table-cell text-center">Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell text-center">Thời gian bảo hành</th>
                            <th class="d-none d-sm-table-cell text-center">Bảo hành định kỳ</th>
                            <th class="d-none d-sm-table-cell text-end">Ngày mua</th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach([$data] as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->product?->code}}</strong>
                                </td>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->product?->serial}}</strong>
                                </td>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->code}}</strong>
                                </td>

                                <td class="fs-sm">
                                    <small>({{$item->customer?->code}})</small>
                                    <br>
                                    {{$item->customer?->name}}
                                </td>

                                <td class="text-center fs-sm">
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

                                @php($isWarrantyExpired = isWarrantyExpired($item->purchase_date, $item->product?->warranty_period, $item->product?->warranty_period_unit))

                                <td class="d-none d-sm-table-cell fs-sm">
                                    @if($isWarrantyExpired)
                                        <span
                                            class="badge bg-warning">Hết hạn bảo hành</span>
                                    @else
                                        <span
                                            class="badge bg-info">Còn bảo hành</span>
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

        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Lịch sử bảo hành - sửa chữa của thiết bị</h3>
            </div>
            <div class="block-content">
                <!-- Search Form -->
                <form action="" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-alt" id="q"
                                   name="q" placeholder="Tìm kiếm" value="{{request()->q}}">
                            <span class="input-group-text bg-body border-0">
                      <i class="fa fa-search"></i>
                    </span>
                        </div>
                    </div>
                </form>
                <!-- END Search Form -->

                <!-- All Orders Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã phiếu</th>
                            <th>Status</th>
                            <th class="text-center">Mã đơn hàng</th>
                            <th class="text-center">Khách hàng</th>
                            <th class="text-center">Loại phiếu</th>
                            <th class="text-center">Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell text-center">Vấn đề sửa chữa</th>
                            <th class="text-center">Tổng phí</th>
                            <th class="d-none d-sm-table-cell text-end">Đánh giá</th>
                            <th class="d-none d-sm-table-cell text-center">Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td class="text-center fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('admin.services.show', $service->id)}}">
                                        <strong>{{$service->code}}</strong>
                                    </a>
                                </td>
                                <td class="text-center fs-sm">
                                    <span
                                        class="badge bg-{{\App\Models\ServiceStatus::STATUS_CLASS[$service->status->code ?? 0]}}">
                                        {{\App\Models\ServiceStatus::STATUS[$service->status->code ?? 0]}}
                                    </span>
                                </td>
                                <td class="text-center fs-sm">
                                    {{$service->order->code ?? $service->order_id}}
                                </td>
                                <td class="fs-sm">
                                    {{$service->order?->customer?->name}}
                                </td>
                                <td class="text-center fs-sm">
                                    <span
                                        class="badge bg-{{\App\Models\Service::TYPE_CLASS[$service->type]}}">
                                        {{\App\Models\Service::TYPE[$service->type]}}
                                    </span>
                                </td>
                                <td class="fs-sm">
                                    <div class="text-ellipsis" style="max-width: 150px" data-bs-toggle="tooltip"
                                         title="{{$service->order?->product?->name}}">
                                        {{$service->order?->product?->name}}
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell fs-sm">
                                    <div class="text-ellipsis" style="max-width: 150px" data-bs-toggle="tooltip"
                                         title="{{$service->content}}">{{$service->content}}</div>
                                </td>
                                <td class="fs-sm">
                                    <strong data-bs-toggle="tooltip"
                                            title="{{$service->fee_detail}}">{{format_money($service->fee_total)}}</strong>
                                </td>
                                <td class="d-none d-sm-table-cell fs-sm text-nowrap">
                                    @include('components.evaluate_star', ['star' => $service->evaluate])
                                </td>
                                <td class="d-none d-sm-table-cell text-center fs-sm" style="min-width: 140px">
                                    {{$service->created_at}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END All Orders Table -->
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
