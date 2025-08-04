<div class="table-responsive">
    <table class="table table-borderless table-striped table-vcenter">
        <thead>
        <tr>
            <th class="sortable" data-name="products__code" style="width: 100px;">Mã sản phẩm</th>
            <th class="sortable text-center" data-name="orders__code">Mã đơn hàng</th>
            <th>Khách hàng</th>
            <th class="sortable" data-name="products__name">Tên sản phẩm</th>
            <th class="text-center">Thời gian bảo hành</th>
            <th class="text-center">Bảo hành định kỳ</th>
            <th class="sortable text-center" data-name="purchase_date">Ngày mua</th>
            <th class="text-center">Ngày bảo hành định kỳ</th>
            <th>Kỹ thuật viên</th>
            <th class="text-center">Trạng thái</th>
            <th class="text-center"></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data ?? [] as $item)
            <tr>
                <td class="fs-sm">
                    <a class="fw-semibold" href="{{ route('admin.products.history', $item->id) }}">
                        <strong>{{ $item->product?->code }}</strong>
                    </a><br/>
                    <small class="text-muted">{{ $item->product?->serial }}</small>
                </td>
                <td class="text-center fs-sm">
                    <strong>{{ $item->code }}</strong>
                </td>

                <td class="fs-sm">
                    @if($item->customer?->code)
                        <small class="text-muted">({{ $item->customer?->code }})</small>
                        <br>
                    @endif
                    {{ $item->customer?->name }}
                    <br>
                    <small class="text-muted">({{ $item->customer?->email }})</small>
                </td>

                <td class="fs-sm" style="min-width: 200px">
                    <strong>{{ $item->product?->name }}</strong>
                </td>

                <td class="text-center fs-sm">
                    {{ $item->product?->warranty_period }}
                    {{ \App\Models\Product::WARRANTY_UNIT[$item->product?->warranty_period_unit] }}
                </td>

                <td class="text-center fs-sm">
                    {{ $item->product?->periodic_warranty }}
                    {{ \App\Models\Product::WARRANTY_UNIT[$item->product?->periodic_warranty_unit] }}
                </td>

                <td class="text-nowrap fs-sm">
                    {{ $item->purchase_date->format(FORMAT_DATE) }}
                </td>

                @php($status = checkWarrantyStatus($item->purchase_date, $item->product?->warranty_period, $item->product?->warranty_period_unit))
                @php($isWarrantyExpired = $status['expired'])

                <td class="text-nowrap text-center fs-sm">
                    {{ $status['next_warranty_check_date'] }}
                </td>

                <td class="text-nowrap fs-sm">
                    {{ $item->product?->repairman?->name }}
                    <br>
                    <small class="text-muted">{{ $item->product?->repairman?->email }}</small>
                </td>

                <td class="fs-sm">
                    @if ($isWarrantyExpired)
                        <span class="badge bg-warning" data-bs-toggle="tooltip"
                              title="Đã hết bảo hành vào ngày {{ $status['warranty_end_date'] }}">Hết bảo hành</span>
                    @else
                        <span class="badge bg-info" data-bs-toggle="tooltip"
                              title="Ngày bảo hành tiếp theo là {{ $status['next_warranty_check_date'] }} (tính từ ngày {{ $status['used_base_date'] }})">Còn
                            bảo hành</span>
                    @endif
                </td>

                <td class="text-center text-nowrap">
                    @if (!request()->has('export'))
                        <div class="btn-group btn-group-sm" role="group" aria-label="Small Horizontal Primary">
                            <a class="btn btn-sm btn-alt-{{ $isWarrantyExpired ? 'danger' : 'info' }}"
                               href="{{ route('admin.services.create') }}?order_code={{ $item->code }}&type={{ $isWarrantyExpired ? \App\Models\Service::TYPE_REPAIR : \App\Models\Service::TYPE_WARRANTY }}"
                               data-bs-toggle="tooltip" title="{{ $isWarrantyExpired ? 'Sửa chữa' : 'Bảo hành' }}">
                                <i class="fa fa-fw fa-screwdriver-wrench"></i>
                            </a>

                            <a class="btn btn-sm btn-alt-warning"
                               href="{{ route('admin.products.edit', $item->product_id) }}"
                               data-bs-toggle="tooltip" title="Sửa">
                                <i class="fa fa-fw fa-pen"></i>
                            </a>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach

        <x-empty :data="$data"/>

        </tbody>
    </table>
</div>

{{ $data?->links('layouts.inc.pagination') }}
