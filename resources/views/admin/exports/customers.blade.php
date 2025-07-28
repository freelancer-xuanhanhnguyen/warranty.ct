<table class="table table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th class="text-center" style="width: 100px;">Mã khách hàng</th>
        <th>Tên Khách hàng</th>
        <th>Email</th>
        <th class="text-center">Ngày sinh</th>
        <th class="text-center">Giới tính</th>
        <th class="text-center">Địa chỉ</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data ?? [] as $item)
        <tr>
            <td class="text-center fs-sm">
                <a class="fw-semibold" href="{{ route('admin.customers.show', $item->id) }}">
                    <strong>{{ $item->code }}</strong>
                </a>
            </td>
            <td class="fs-sm">
                @if($item->code)
                    {{ $item->name }}
                @else
                    <a class="fw-semibold" href="{{ route('admin.customers.show', $item->id) }}">
                        {{ $item->name }}
                    </a>
                @endif
            </td>
            <td class="fs-sm">
                {{ $item->email }}
            </td>
            <td class="text-center fs-sm">
                {{ $item->birthday }}
            </td>
            <td class="text-center fs-sm">
                {{ \App\Models\Customer::GENDER[$item->gender] ?? null }}
            </td>

            <td class="fs-sm">
                {{ $item->address }}
            </td>
            <td class="text-center text-nowrap">
                <div class="btn-group btn-group-sm" role="group" aria-label="Small Horizontal Primary">
                    <a class="btn btn-sm btn-alt-warning" href="{{ route('admin.customers.edit', $item->id) }}"
                       data-bs-toggle="tooltip" title="Sửa">
                        <i class="fa fa-fw fa-pen"></i>
                    </a>
                    <form id="destroy-{{ $item->id }}"
                          action="{{ route('admin.customers.destroy', $item->id) }}" method="post">
                        @csrf
                        @method('delete')

                        <button class="btn btn-sm btn-alt-danger" type="submit" data-bs-toggle="tooltip"
                                title="Xóa"
                                onclick="if(!confirm('Xóa khách hàng {{ $item->code }}?')) event.preventDefault();">
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
