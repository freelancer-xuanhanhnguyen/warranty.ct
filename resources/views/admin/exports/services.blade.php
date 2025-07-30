<table class="table table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th class="sortable text-center" data-name="services__code" style="width: 100px;">Mã phiếu</th>
        <th class="sortable text-center" data-name="orders__code">Mã đơn hàng</th>
        <th>Mã Khách hàng</th>
        <th>Tên Khách hàng</th>
        <th>Email khách hàng</th>
        <th class="text-center">Loại phiếu</th>
        <th class="sortable" data-name="products__name">Mã sản phẩm</th>
        <th class="sortable" data-name="products__name">Tên sản phẩm</th>
        <th>Vấn đề bảo hành</th>
        <th class="text-center">Tổng phí</th>
        <th colspan="2">Mã kỹ thuật viên</th>
        <th colspan="2">Kỹ thuật viên</th>
        <th colspan="2" class="text-center">Đánh giá</th>
        <th class="text-center">Trạng thái</th>
        <th class="sortable text-center" data-name="services__created_at">Ngày tạo</th>
        <th class="sortable text-center" data-name="services__created_at">Ngày cập nhật</th>
        <th></th>
    </tr>
    <tr>

    </tr>
    </thead>
    <tbody>
    @foreach ($data ?? [] as $item)
        <tr>
            <td class="text-center fs-sm">
                {{ $item->code }}
            </td>

            <td class="text-center fs-sm">
                {{ $item->order->code ?? $item->order_id }}
            </td>
            <td class="fs-sm">
                {{ $item->order?->customer?->code }}
            </td>
            <td class="fs-sm">
                {{ $item->order?->customer?->name }}
            </td>

            <td class="fs-sm">
                {{ $item->order?->customer?->email }}
            </td>
            <td class="text-center fs-sm">
                {{ \App\Models\Service::TYPE[$item->type] }}
            </td>
            <td class="fs-sm">
                {{ $item->order?->product?->code }}
            </td>

            <td class="fs-sm">
                {{ $item->order?->product?->name }}
            </td>

            <td class="fs-sm">
                {{ $item->content }}
            </td>
            <td class="fs-sm">
                {{$item->fee_total}}
            </td>
            <td class="text-nowrap fs-sm">
                {{ $item?->repairman?->code }}
            </td>
            <td class="text-nowrap fs-sm">
                {{ $item?->repairman?->name }}
            </td>
            <td class="text-center fs-sm text-nowrap">
                {{$item->evaluate}}
            </td>
            <td class="text-center fs-sm text-nowrap">
                {{$item->evaluate_note}}
            </td>

            <td class="text-center fs-sm">
                {{ \App\Models\ServiceStatus::STATUS[$item->status->code ?? 0] }}
            </td>
            <td class="text-center fs-sm">
                {{ $item->created_at->format(FORMAT_DATETIME) }}
            </td>
            <td class="text-center fs-sm">
                {{ $item->updated_at->format(FORMAT_DATETIME) }}
            </td>
        </tr>
    @endforeach

    <x-empty :data="$data"/>

    </tbody>
</table>
