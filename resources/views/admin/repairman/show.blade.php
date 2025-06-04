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
                        {{$user->name}} ({{$user->email}})
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Kỹ thuật viên
                    </h2>
                </div>

            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @include('components.alert')

        <div class="col-md-12">
            <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <div class="py-3">
                                <div class="item item-circle bg-body-light mx-auto">
                                    <i class="fa-solid fa-screwdriver-wrench text-primary"></i>
                                </div>
                                <dl class="mb-0">
                                    <dt class="h3 fw-extrabold mt-3 mb-0">
                                        {{$report->total_under_warranty}}
                                    </dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">
                                        Thiết bị đang bảo hành
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="py-3">
                                <div class="item item-circle bg-body-light mx-auto">
                                    <i class="fa-solid fa-gears text-primary"></i>
                                </div>
                                <dl class="mb-0">
                                    <dt class="h3 fw-extrabold mt-3 mb-0">
                                        {{$report->total_under_repair}}
                                    </dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">
                                        Thiết bị đang sửa
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="py-3">
                                <div class="item item-circle bg-body-light mx-auto">
                                    <i class="fa-solid fa-list-check text-primary"></i>
                                </div>
                                <dl class="mb-0">
                                    <dt class="h3 fw-extrabold mt-3 mb-0">
                                        {{$report->total_services}}
                                    </dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">
                                        Tổng thiết bị
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-content">
                <!-- Search Form -->
                {{--<form action="" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-alt" id="one-ecom-orders-search"
                                   name="one-ecom-orders-search" placeholder="Search all orders..">
                            <span class="input-group-text bg-body border-0">
                      <i class="fa fa-search"></i>
                    </span>
                        </div>
                    </div>
                </form>--}}
                <!-- END Search Form -->

                <!-- All Orders Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã phiếu</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell text-center">Mã đơn hàng</th>
                            <th class="d-none d-xl-table-cell">Khách hàng</th>
                            <th class="d-none d-sm-table-cell text-center">Loại phiếu</th>
                            <th class="d-none d-xl-table-cell text-center">Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell text-center">Nội dung</th>
                            <th class="d-none d-sm-table-cell text-center">Tổng phí</th>
                            <th class="d-none d-sm-table-cell text-end">Đánh giá</th>
                            <th class="d-none d-sm-table-cell text-center">Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $service)
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

                {{ $data->links('layouts.inc.pagination') }}
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
