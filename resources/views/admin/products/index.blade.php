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
                        Thiết bị
                    </h1>
                </div>

                {{--<a class="btn btn-sm btn-primary" href="{{route('admin.services.create')}}">Thêm mới</a>--}}
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
                <!-- Search Form -->
                <form action="" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-alt" id="q"
                                   name="q" value="{{request()->q}}" placeholder="Tìm kiếm">
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
                            <th style="width: 100px;">Mã sản phẩm</th>
                            <th class="text-center">Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Thời gian bảo hành</th>
                            <th class="text-center">Bảo hành định kỳ</th>
                            <th class="text-end">Ngày mua</th>
                            <th class="text-center">Ngày bảo hành định kỳ</th>
                            <th>Kỹ thuật viên</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="fs-sm">
                                    <a class="fw-semibold" href="{{route('admin.products.history', $item->id)}}">
                                        <strong>{{$item->product?->code}}</strong>
                                    </a>
                                    <small class="text-muted">{{$item->product?->serial}}</small>
                                </td>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->code}}</strong>
                                </td>

                                <td class="fs-sm">
                                    <small class="text-muted">({{$item->customer?->code}})</small>
                                    <br>
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

                                @php($status = checkWarrantyStatus($item->purchase_date, $item->product?->warranty_period, $item->product?->warranty_period_unit, $item->service?->created_at))
                                @php($isWarrantyExpired = $status['expired'])

                                <td class="text-nowrap text-center fs-sm">
                                    {{$status['next_warranty_check_date']}}
                                </td>

                                <td class="text-nowrap fs-sm">
                                    {{$item->product?->repairman?->name}}
                                    <br>
                                    <small class="text-muted">{{$item->product?->repairman?->email}}</small>
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

                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group"
                                         aria-label="Small Horizontal Primary">
                                        <a class="btn btn-sm btn-alt-{{$isWarrantyExpired ? 'warning':'info'}}"
                                           href="{{route('admin.services.create')}}?order_code={{$item->code}}&type={{$isWarrantyExpired ? \App\Models\Service::TYPE_REPAIR : \App\Models\Service::TYPE_WARRANTY}}"
                                           data-bs-toggle="tooltip"
                                           title="{{$isWarrantyExpired ? 'Sửa chữa' : 'Bảo hành'}}">
                                            <i class="fa fa-fw fa-screwdriver-wrench"></i>
                                        </a>
                                    </div>
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
