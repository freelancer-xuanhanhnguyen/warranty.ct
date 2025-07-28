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
    @foreach ($data ?? [] as $item)
        <tr>
            <td class="text-center fs-sm">
                <a class="fw-semibold" href="{{ route('admin.services.show', $item->id) }}">
                    <strong>{{ $item->code }}</strong>
                </a>
            </td>

            <td class="text-center fs-sm">
                {{ $item->order->code ?? $item->order_id }}
            </td>
            <td class="fs-sm">
                @if($item->order?->customer?->code)
                    <small class="text-muted">({{ $item->order?->customer?->code }})</small>
                    <br>
                @endif
                {{ $item->order?->customer?->name }}
                <br>
                <small class="text-muted">({{ $item->order?->customer?->email }})</small>
            </td>
            <td class="text-center fs-sm">
                    <span class="badge bg-{{ \App\Models\Service::TYPE_CLASS[$item->type] }}">
                        {{ \App\Models\Service::TYPE[$item->type] }}
                    </span>
            </td>
            <td class="fs-sm">
                <small class="text-muted">({{ $item->order?->product?->code }})</small>
                <br>
                <div class="text-line-2" style="min-width: 150px" data-bs-toggle="tooltip"
                     title="{{ $item->order?->product?->name }}">
                    {{ $item->order?->product?->name }}
                </div>
            </td>
            <td class="fs-sm">
                <div class="text-line-3" style="min-width: 150px" data-bs-toggle="tooltip"
                     title="{{ $item->content }}">{{ $item->content }}</div>
            </td>
            <td class="fs-sm">
                <strong data-bs-toggle="tooltip"
                        title="{{ $item->fee_detail }}">{{ format_money($item->fee_total) }}</strong>
            </td>
            <td class="text-nowrap fs-sm">
                {{ $item?->repairman?->name }}
                <br>
                <small class="text-muted">{{ $item?->repairman?->email }}</small>
            </td>
            <td class="text-center fs-sm text-nowrap">
                @include('components.evaluate_star', ['star' => $item->evaluate])
            </td>
            <td class="text-center fs-sm">
                    <span class="badge bg-{{ \App\Models\ServiceStatus::STATUS_CLASS[$item->status->code ?? 0] }}">
                        {{ \App\Models\ServiceStatus::STATUS[$item->status->code ?? 0] }}
                    </span>
            </td>
            <td class="text-center fs-sm" style="min-width: 140px">
                {{ $item->created_at }}
            </td>
            <td class="text-center text-nowrap">
                <div class="btn-group btn-group-sm" role="group" aria-label="Small Horizontal Primary">
                    <a class="btn btn-sm btn-alt-warning" href="{{ route('admin.services.edit', $item->id) }}"
                       data-bs-toggle="tooltip" title="Sửa">
                        <i class="fa fa-fw fa-pen"></i>
                    </a>
                    @if (hasRole())
                        <form id="destroy-{{ $item->id }}"
                              action="{{ route('admin.services.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('delete')

                            <button class="btn btn-sm btn-alt-danger" type="submit" data-bs-toggle="tooltip"
                                    title="Xóa"
                                    onclick="if(!confirm('Xóa phiếu {{ $item->code }}?')) event.preventDefault();">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach

    <x-empty :data="$data"/>

    </tbody>
</table>
