@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <!-- Page JS Code -->
    <script type="module">
        One.helpersOnLoad(['jq-select2', 'jq-masked-inputs']);
    </script>

    <script>
        const data = {!! json_encode($accessories->toArray(), JSON_UNESCAPED_UNICODE) !!};

        $(() => {
            $('#order_code').change(function () {
                $('#order_id').find('option').remove();
                $("#order_id").trigger('change');

                const orderCode = $(this).val();
                if (!orderCode) {
                    $("#order_id").prop("disabled", true).trigger('change');
                    return;
                }
                $.get(`/admin/products/${orderCode}`).then((data) => {
                    data?.map((v, i) => {
                        let newOption = new Option(`${v?.product?.code} - ${v?.product?.name}`, v?.id, i === 0, false);
                        $('#order_id').append(newOption).trigger('change');
                    })

                    $("#order_id").prop("disabled", false);
                })
            })

            const body = $('body');
            const $tbody = $('#accessory-tbody');
            const $tfoot = $('#accessory-tfoot');
            const $tbodyEmpty = $('#accessory-tbody-empty');

            function renderMoney($price) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format($price)
            }

            body.on('click', '#btn-add-accessory', function () {
                $tbodyEmpty.hide();
                const vals = [];
                $('#accessory-tbody').find('tr').each((i, v) => {
                    const value = $(v).data('value');
                    if (value) vals.push(Number(value));
                })

                const options = data.map(v => `<option value="${v.id}" data-price="${v.unit_price}" data-quantity="${v.quantity}" ${vals?.includes(v.id) ? "disabled" : ""}>${v.name}</option>`);

                $tbody.append(`<tr>
                    <td class="accessory">
                        <select class="item-select2 js-select2 form-select"
                                name="accessories[id][]"
                                data-placeholder="Chọn linh kiện" required>
                            <option value=""></option>
                            ${options}
                        </select>
                    </td>
                    <td style="width: 120px">
                        <input style="width: 100px;" data-price="0" class="item-quantity form-control" type="number" value="1" min="1" name="accessories[quantity][]" required/>
                    </td>
                    <td style="width: 150px"><input class="item-total" hidden name="accessories[total][]" value="" /><span class="item-total"></span></td>
                    <td style="width: 120px" class="text-center">
                        <button type="button" class="btn-remove-accessory btn btn-sm btn-alt-danger">
                            <i class="fa fa-w fa-trash"></i>
                        </button>
                    </td>
                </tr>`);

                $('.js-select2').select2();
                $tfoot.show();
            })

            body.on('click', '.btn-remove-accessory', function () {
                $(this).closest('tr').remove();

                if ($('tr.accessory').length <= 0) {
                    $('#accessory-tbody-empty').show();
                    $tfoot.hide();
                }

                renderTotal();
            })

            body.on('change', '.item-select2', function () {
                const value = $(this).val();
                const option = $(this).find(`option[value=${value}]`);
                const data = option.data();
                const item = $(this).closest('tr');
                const totalText = renderMoney(data.price);

                item.find('.item-quantity')
                    .data('price', data.price?.toString())
                    .data('max', data.quantity)
                    .attr('max', data.quantity).val(1);

                item.find('span.item-total').data('total', data.price).text(totalText);
                item.find('input.item-total').val(data.price);
                renderTotal();
                const preVal = item.data('value');

                if (preVal) {
                    $(`option[value=${preVal}]`)
                        .attr('disabled', false);
                }

                item.data('value', value);
                $('.item-select2')
                    .not(this)
                    .find(`option[value=${value}]`)
                    .attr('disabled', true);
            })

            body.on('change', '.item-quantity', function () {
                const data = $(this).data();
                let value = $(this).val();

                if (value < 1) {
                    $(this).val(1);
                    value = 1;
                } else if (value > data.max) {
                    $(this).val(data.max);
                    value = data.max;
                }

                const total = data.price * value;
                const totalText = renderMoney(data.price * value);

                const item = $(this).closest('tr');
                item.find('span.item-total').data('total', total).text(totalText);
                item.find('input.item-total').val(total);
                renderTotal();
            })

            function renderTotal() {
                let total = 0;
                $('.item-total').each((i, v) => {
                    const value = Number($(v).data('total'));
                    if (value) total += value;
                })

                $('input[name=fee_total]').val(total);
                $('.total').text(renderMoney(total));
            }
        })
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Thêm phiếu bảo hành - sửa chữa
                    </h1>
                </div>
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
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{route('admin.services.store')}}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label" for="order_code">Mã đơn hàng<span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_code"
                                        name="order_code"
                                        data-placeholder="#######" required>
                                    <option value=""></option>
                                    @foreach($orderCodes as $item)
                                        <option value="{{$item->code}}"
                                                @if(old('order_code', request()->order_code) == $item->code) selected @endif >
                                            {{$item->code}} - {{$item->customer->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="passwordHelpBlock" class="form-text">
                                    Tìm kiếm theo mã đơn hàng hoặc tên khách hàng.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="order_id">Sản phẩm <span
                                        class="text-danger">*</span></label>
                                <select class="js-select2 form-select" id="order_id"
                                        name="order_id" @if(!isset($orders)) disabled @endisset
                                        data-placeholder="Mã sản phẩm - tên sản phẩm" required>
                                    @isset($orders)
                                        @foreach($orders as $order)
                                            <option value="{{$order->id}}"
                                                    @if(old('order_id') == $order->id || request()->order_code == $order->code) selected @endif >{{$order->product?->code}}
                                                - {{$order->product?->name}}</option>
                                        @endforeach
                                    @else
                                        <option value=""></option>
                                    @endisset
                                </select>

                                <div id="passwordHelpBlock" class="form-text">
                                    Chọn 1 sản phẩm trong đơn hàng được chọn.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="type">Loại phiếu</label>
                                <select class="form-select" id="type"
                                        name="type"
                                        data-placeholder="Choose one..">
                                    @foreach(\App\Models\Service::TYPE as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('type', request()->type) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="content">Vấn đề sửa chữa <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control js-maxlength" maxlength="500" id="content"
                                          name="content" rows="4" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="repairman_id">Kỹ thuật viên</label>
                                <select class="js-select2 form-select" id="repairman_id"
                                        name="repairman_id"
                                        data-placeholder="Tự động gán kỹ thuật viên tương ứng trên sản phẩm">
                                    <option value=""></option>
                                    <!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                                @if(old('repairman_id') === $user->id) selected @endif>{{"$user->id - $user->name"}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="table-responsive table-responsive-md">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Tên linh kiện</th>
                                        <th style="width: 120px">Số lượng</th>
                                        <th style="width: 150px">Tổng tiền</th>
                                        <th style="width: 120px" class="text-center">
                                            <button type="button" id="btn-add-accessory"
                                                    class="btn btn-sm btn-alt-primary">Thêm
                                            </button>
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody id="accessory-tbody">
                                    <tr id="accessory-tbody-empty" class="text-center">
                                        <td colspan="4">Không có linh kiện nào</td>
                                    </tr>
                                    </tbody>
                                    <tfoot id="accessory-tfoot">
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <input hidden name="fee_total" value=""/>
                                            <span class="total"></span>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="note">Ghi chú</label>
                                <textarea class="form-control js-maxlength" maxlength="500" id="note"
                                          name="note" rows="4"></textarea>
                            </div>

                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Thêm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
