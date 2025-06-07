@extends('layouts.web')

@section('content')
    <!-- Content -->
    <div class="content content-full">

        @include('components.alert')

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách sản phẩm theo đơn hàng</h3>
            </div>
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
                            <th class="text-center" style="width: 100px;">Mã sản phẩm</th>
                            <th class="text-center" style="width: 100px;">Serial</th>
                            <th class="text-center">Tên sản phẩm</th>
                            <th class="text-center">Mã đơn hàng</th>
                            <th class="text-center">Thời gian bảo hành</th>
                            <th class="text-center">Bảo hành định kỳ</th>
                            <th class="text-center">Ngày mua</th>
                            <th class="text-center">Ngày bảo hành định kỳ</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data ?? [] as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('orders.history',['email' => request()->email, 'id'=>  $item->id])}}">
                                        <strong>{{$item->product?->code}}</strong>
                                    </a>
                                </td>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->product?->serial}}</strong>
                                </td>

                                <td class="text-center fs-sm">
                                    <strong>{{$item->product?->name}}</strong>
                                </td>

                                <td class="text-center fs-sm">
                                    <strong>{{$item->code}}</strong>
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

                                <td class="d-none d-sm-table-cell fs-sm">
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
                                           href="{{route('services.request', ['email' => request()->email, 'orderId' => $item->id])}}"
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

                {{ $data?->links('layouts.inc.pagination') }}
            </div>
        </div>
    </div>
    <!-- END Content -->
@endsection
