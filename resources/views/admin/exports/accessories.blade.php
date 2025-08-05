<table class="table table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th class="sortable text-center" data-name="code" style="width: 100px;">Mã linh kiện</th>
        <th class="sortable" data-name="name">Tên linh kiện</th>
        <th class="sortable text-center" data-name="quantity">Số lượng</th>
        <th class="sortable text-end" data-name="unit_price">Giá tiền</th>
        <th class="sortable text-center" data-name="created_at">Ngày tạo</th>
        <th class="sortable text-center" data-name="updated_at">Cập nhật gần nhất</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data ?? [] as $item)
        <tr>
            <td class="text-center fs-sm">
                <strong>{{ $item->code }}</strong>
            </td>
            <td class="fs-sm">
                {{$item->name}}
            </td>
            <td class="fs-sm text-center">
                {{ $item->quantity }}
            </td>

            <td class="text-end fs-sm">
                {{ format_money($item->unit_price) }}
            </td>

            <td class="text-center fs-sm">
                {{ $item->created_at->format(FORMAT_DATETIME) }}
            </td>

            <td class="text-center fs-sm">
                {{ $item->updated_at->diffForHumans() }}
            </td>

            <td class="text-center text-nowrap">
                <div class="btn-group btn-group-sm" role="group" aria-label="Small Horizontal Primary">
                    <a class="btn btn-sm btn-alt-warning" href="{{ route('admin.accessories.edit', $item->id) }}"
                       data-bs-toggle="tooltip" title="Sửa">
                        <i class="fa fa-fw fa-pen"></i>
                    </a>

                    <form id="destroy-{{ $item->id }}"
                          action="{{ route('admin.accessories.destroy', $item->id) }}" method="post">
                        @csrf
                        @method('delete')

                        <button class="btn btn-sm btn-alt-danger" type="submit" data-bs-toggle="tooltip"
                                title="Xóa"
                                onclick="if(!confirm(`Xóa linh kiện '{{ $item->name }}'?`)) event.preventDefault();">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach

    <x-empty :data="$data"/>

    </tbody>
</table>
