@extends('layouts.web')

@section('content')
    <!-- Content -->
    <div class="content content-full">
        @include('components.alert')

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Tạo yêu cầu bảo hành - sửa chữa sản phẩm</h3>
            </div>

            <div class="block-content">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form
                            action=""
                            method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="order_code">Mã đơn hàng<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="order_code" disabled
                                       name="order_code" value="{{$order->code}}" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="order_id">Sản phẩm <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="order_id" disabled
                                       name="order_id" value="{{$order->product?->code}} - {{$order->product?->name}}"
                                       readonly>
                            </div>

                            @php($isWarrantyExpired = isWarrantyExpired($order->purchase_date, $order->product?->warranty_period, $order->product?->warranty_period_unit))
                            @php($type = $isWarrantyExpired ? \App\Models\Service::TYPE[\App\Models\Service::TYPE_REPAIR] : \App\Models\Service::TYPE[\App\Models\Service::TYPE_WARRANTY])

                            <div class="mb-4">
                                <label class="form-label" for="type">Yêu cầu</label>
                                <input type="text" class="form-control" id="type" disabled
                                       name="type"
                                       value="{{$type}}"
                                       readonly>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="content">Vấn đề
                                    cần {{strtolower($type)}}
                                    <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control js-maxlength" maxlength="500" id="content"
                                          name="content" rows="4" required></textarea>
                            </div>

                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Tạo yêu
                                    cầu {{strtolower($type)}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Content -->
@endsection
