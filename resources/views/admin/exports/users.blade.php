<table class="table table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th class="text-center" style="width: 100px;">ID</th>
        <th>Tên nhân viên</th>
        <th>Email</th>
        <th class="text-center">Ngày sinh</th>
        <th class="text-center">Giới tính</th>
        <th class="text-center">Chức vụ</th>
        <th class="text-center">Trạng thái</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data ?? [] as $item)
        <tr>
            <td class="text-center fs-sm">
                <strong>{{ $item->id }}</strong>
            </td>
            <td class="fs-sm">
                @if ($item->role === \App\Models\User::ROLE_REPAIRMAN)
                    <a class="fw-semibold" href="{{ route('admin.repairman.show', $item->id) }}">
                        {{ $item->name }}
                    </a>
                @else
                    {{ $item->name }}
                @endif
            </td>
            <td class="fs-sm">
                {{ $item->email }}
            </td>
            <td class="text-center fs-sm">
                {{ $item->birthday }}
            </td>
            <td class="text-center fs-sm">
                {{ \App\Models\User::GENDER[$item->gender] }}
            </td>
            <td class="text-center fs-sm">
                    <span
                        class="badge bg-{{ \App\Models\User::ROLE_CLASS[$item->role] }}">{{ \App\Models\User::ROLE[$item->role] }}</span>
            </td>
            <td class="text-center fs-sm">
                    <span
                        class="badge bg-{{ \App\Models\User::STATUS_CLASS[$item->status] }}">{{ \App\Models\User::STATUS[$item->status] }}</span>
            </td>
            <td class="text-center text-nowrap">
                <div class="btn-group btn-group-sm" role="group" aria-label="Small Horizontal Primary">
                    <a class="btn btn-sm btn-alt-warning" href="{{ route('admin.users.edit', $item->id) }}"
                       data-bs-toggle="tooltip" title="Sửa">
                        <i class="fa fa-fw fa-pen"></i>
                    </a>

                    @if (auth()->id() !== $item->id)
                        <form id="destroy-{{ $item->id }}"
                              action="{{ route('admin.users.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('delete')

                            <button class="btn btn-sm btn-alt-danger" type="submit" data-bs-toggle="tooltip"
                                    title="Xóa"
                                    onclick="if(!confirm('Xóa nhân viên {{ $item->id }}?')) event.preventDefault();">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
