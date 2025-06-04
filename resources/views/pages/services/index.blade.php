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

        @include('components.alert')

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách yêu cầu bảo hành - sửa chữa</h3>
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
                            <th>Status</th>
                            <th class="text-center">Mã đơn hàng</th>
                            <th class="text-center">Loại phiếu</th>
                            <th class="text-center">Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell text-center">Nội dung</th>
                            <th class="text-center">Tổng phí</th>
                            <th class="d-none d-sm-table-cell text-end">Đánh giá</th>
                            <th class="d-none d-sm-table-cell text-center">Ngày tạo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $service)
                            <tr>
                                <td class="text-center fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('services.detail', ['email' => request()->email, 'id' => $service->id])}}">
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
    </div>
    <!-- END Content -->
@endsection
