@extends('layouts.web')

@section('css')
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

@section('content')
    <!-- Content -->
    <div class="content content-full">
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
                            <i class="fa-solid fa-person-half-dress"></i> {{\App\Models\Customer::GENDER[$data->order?->customer?->gender]}}
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
                                @foreach([$data->order] as $item)
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
            </div>
        </div>

        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thông tin chi tiết</h3>
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
                            Chi tiết phụ phí
                        </td>
                        <td>
                            <span class="fw-semibold">{{$data->fee_detail}}</span>
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
                            <span class="fw-semibold">{{$data->evaluate_note}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Content -->
@endsection
