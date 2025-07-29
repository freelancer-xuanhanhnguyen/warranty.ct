@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->

    <style>
        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('js')


    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>


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
                                        {{$report?->total_under_warranty}}
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
                                        {{$report?->total_under_repair}}
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
                                        {{$report?->total_services}}
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
                <form class="search-form" action="" method="GET">
                    <div class="row mb-4 align-content-end">
                        <div class="col-md-4">
                            <label class="form-label" for="status">Mã phiếu</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-alt" id="q"
                                       name="q" value="{{request()->q}}">
                                <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"  title="Tìm kiếm">
                                    <i class="fa fa-search me-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="status">Trạng thái</label>
                            <select class="form-select" id="status"
                                    name="status">
                                <option value="">Tất cả</option>
                                @foreach(\App\Models\ServiceStatus::STATUS as $key => $status)
                                    <option value="{{$key}}"
                                            @if(request()->status === "$key") selected @endif>{{$status}}</option>
                                @endforeach
                            </select>
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
                            <th class="text-center">Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th class="text-center">Loại phiếu</th>
                            <th>Tên sản phẩm</th>
                            <th>Vấn đề bảo hành</th>
                            <th class="text-center">Tổng phí</th>
                            <th>Kỹ thuật viên</th>
                            <th class="text-center">Đánh giá</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ngày tạo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data ?? [] as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('admin.services.show', $item->id)}}">
                                        <strong>{{$item->code}}</strong>
                                    </a>
                                </td>

                                <td class="text-center fs-sm">
                                    {{$item->order->code ?? $item->order_id}}
                                </td>
                                <td class="fs-sm">
                                    @if($item->order?->customer?->code)
                                        <small class="text-muted">({{$item->order?->customer?->code}})</small>
                                        <br>
                                    @endif
                                    {{$item->order?->customer?->name}}
                                    <br>
                                    <small class="text-muted">({{$item->order?->customer?->email}})</small>
                                </td>
                                <td class="text-center fs-sm">
                                    <span
                                        class="badge bg-{{\App\Models\Service::TYPE_CLASS[$item->type]}}">
                                        {{\App\Models\Service::TYPE[$item->type]}}
                                    </span>
                                </td>
                                <td class="fs-sm">
                                    <small class="text-muted">({{$item->order?->product?->code}})</small>
                                    <br>
                                    <div class="text-line-2" style="min-width: 150px" data-bs-toggle="tooltip"
                                         title="{{$item->order?->product?->name}}">
                                        {{$item->order?->product?->name}}
                                    </div>
                                </td>
                                <td class="fs-sm">
                                    <div class="text-line-3" style="min-width: 150px" data-bs-toggle="tooltip"
                                         title="{{$item->content}}">{{$item->content}}</div>
                                </td>
                                <td class="fs-sm">
                                    <strong data-bs-toggle="tooltip"
                                            title="{{$item->fee_detail}}">{{format_money($item->fee_total)}}</strong>
                                </td>
                                <td class="text-nowrap fs-sm">
                                    {{$item?->repairman?->name}}
                                    <br>
                                    <small class="text-muted">{{$item?->repairman?->email}}</small>
                                </td>
                                <td class="text-center fs-sm text-nowrap">
                                    @include('components.evaluate_star', ['star' => $item->evaluate])
                                </td>
                                <td class="text-center fs-sm">
                                    <span
                                        class="badge bg-{{\App\Models\ServiceStatus::STATUS_CLASS[$item->status->code ?? 0]}}">
                                        {{\App\Models\ServiceStatus::STATUS[$item->status->code ?? 0]}}
                                    </span>
                                </td>
                                <td class="text-center fs-sm" style="min-width: 140px">
                                    {{$item->created_at}}
                                </td>
                            </tr>
                        @endforeach
                        <x-empty :data="$data"/>
                        </tbody>
                    </table>
                </div>
                <!-- END All Orders Table -->

                {{ $data?->links('layouts.inc.pagination') }}
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
