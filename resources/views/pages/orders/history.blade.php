@extends('layouts.web')

@section('css')
    <style>
        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <!-- Content -->
    <div class="content content-full">
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
                            <th>Kỹ thuật viên</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data ? [$data] : [] as $item)
                            <tr>
                                <td class="fs-sm">
                                    <strong>{{$item->product?->code}}</strong>
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
                    <div class="row mb-4 align-content-end">
                        <div class="col-md-4">
                            <label class="form-label" for="status">Mã phiếu</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-alt" id="q"
                                       name="q" value="{{request()->q}}">
                                <button type="submit" class="btn btn-primary">
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
                                            @if(request()->has('status') && request()->status === "$key") selected @endif>{{$status}}</option>
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
                        @foreach($services ?? [] as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('services.detail', ['email' => request()->email, 'id' => $item->id])}}">
                                        <strong>{{$item->code}}</strong>
                                    </a>
                                </td>

                                <td class="text-center fs-sm">
                                    {{$item->order->code ?? $item->order_id}}
                                </td>
                                <td class="fs-sm">
                                    <small class="text-muted">({{$item->order?->customer?->code}})</small>
                                    <br>
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
                        </tbody>
                    </table>
                </div>
                <!-- END All Orders Table -->

                {{ $services?->links('layouts.inc.pagination') }}
            </div>
        </div>
    </div>
    <!-- END Content -->
@endsection
