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

@section('js')
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('js/plugins/raty-js/jquery.raty.js')}}"></script>
    <!-- Page JS Code -->
    <script type="module" src="{{asset('js/pages/be_comp_rating.js')}}"></script>
    <script type="module">
        One.helpersOnLoad(['jq-maxlength']);

        function updateReviewSearch(isDelete = false) {
            const params = new URLSearchParams(window.location.search);
            if (!isDelete)
                params.set('review', '');
            else
                params.delete('review');

            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.pushState({}, '', newUrl);
        }

        $(() => {
            const reviewModal = $('#review-modal');

            @if(request()->has('review'))
            reviewModal.modal('show');
            @endif

            // reviewModal.on('shown.bs.modal', function () {
            //     updateReviewSearch();
            // })

            reviewModal.on('hide.bs.modal', function () {
                if (window.location.search.indexOf('review') >= 0)
                    updateReviewSearch(true);
            })
        })
    </script>
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
                                    <th style="width: 100px;">Mã sản phẩm</th>
                                    <th class="text-center">Mã đơn hàng</th>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-center">Thời gian bảo hành</th>
                                    <th class="text-center">Bảo hành định kỳ</th>
                                    <th class="text-end">Ngày mua</th>
                                    <th class="text-center">Ngày bảo hành định kỳ</th>
                                    <th>Kỹ thuật viên</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data?->order ? [$data?->order] : [] as $item)
                                    <tr>
                                        <td class="fs-sm">
                                            <a class="fw-semibold"
                                               href="{{route('orders.history',['email' => request()->email, 'id'=>  $item->id])}}">
                                                <strong>{{$item->product?->code}}</strong>
                                            </a>
                                            <br>
                                            <small class="text-muted">{{$item->product?->serial}}</small>
                                        </td>
                                        <td class="text-center fs-sm">
                                            <strong>{{$item->code}}</strong>
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
            </div>
        </div>

        <!-- Info -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thông tin chi tiết</h3>

                @if($data->evaluate < 1 && \App\Models\ServiceStatus::STATUS[$data?->status?->code ?? 0] === \App\Models\ServiceStatus::STATUS_COMPLETED)
                    <div class="block-options">
                        <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#review-modal">
                            <i class="fa fa-user-tag me-1"></i> Đánh giá
                        </button>
                    </div>
                @endif
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
                            <span class="fw-semibold">{!! nl2br(e($data->evaluate_note)) !!}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Content -->

    @if($data->evaluate < 1)
        <!-- Review Modal --->
        <div class="modal fade" id="review-modal" tabindex="-1" aria-labelledby="modal-block-popin"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-transparent mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Đánh giá dịch vụ bảo hành - sửa chữa</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">

                            <form
                                action="{{route('services.review', ['email' => request()->email, 'id' => $data->id])}}"
                                method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label" for="status">Đánh giá</label>
                                    <div class="js-rating" data-score="5"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="evaluate_note">Đánh giá chi tiết</label>
                                    <textarea id="evaluate_note" class="form-control js-maxlength" maxlength="500"
                                              name="evaluate_note"
                                              rows="4">Sản phẩm sau bảo hành:
Dịch vụ sửa chữa:
Dịch vụ CSKH:
Kỹ thuật viên:
                            </textarea>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="btn w-100 btn-alt-primary d-block">
                                        Gửi đánh giá
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Review Modal --->
    @endif
@endsection
