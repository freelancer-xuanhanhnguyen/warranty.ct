@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">

@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Nhân viên
                    </h1>
                </div>

                <a class="btn btn-sm btn-primary" href="{{route('admin.users.create')}}">Thêm mới</a>
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
                <!-- Search Form -->
                <form action="" method="GET">
                    <div class="row mb-4 align-content-end">
                        <div class="col-md-4">
                            <label class="form-label" for="status">Tìm kiếm</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-alt" id="q"
                                       name="q" value="{{request()->q}}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="role">Chức vụ</label>
                            <select class="form-select" id="role"
                                    name="role">
                                <option value="">Tất cả</option>
                                @foreach(\App\Models\User::ROLE as $key => $value)
                                    <option value="{{$key}}"
                                            @if(request()->role === "$key") selected @endif>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                <!-- END Search Form -->

                <!-- All Orders Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th>Tên nhân viên</th>
                            <th>Email</th>
                            <th class="text-center">Ngày sinh</th>
                            <th class="text-center">Giới tính</th>
                            <th class="text-center">Chức vụ</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->id}}</strong>
                                </td>
                                <td class="fs-sm">
                                    @if($item->role === \App\Models\User::ROLE_REPAIRMAN)
                                        <a class="fw-semibold"
                                           href="{{route('admin.repairman.show', $item->id)}}">
                                            {{$item->name}}
                                        </a>
                                    @else
                                        {{$item->name}}
                                    @endif
                                </td>
                                <td class="fs-sm">
                                    {{$item->email}}
                                </td>
                                <td class="text-center fs-sm">
                                    {{$item->birthday}}
                                </td>
                                <td class="text-center fs-sm">
                                    {{\App\Models\User::GENDER[$item->gender]}}
                                </td>
                                <td class="text-center fs-sm">
                                    <span
                                        class="badge bg-{{\App\Models\User::ROLE_CLASS[$item->role]}}">{{\App\Models\User::ROLE[$item->role]}}</span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group"
                                         aria-label="Small Horizontal Primary">
                                        <a class="btn btn-sm btn-alt-warning"
                                           href="{{route('admin.users.edit', $item->id)}}"
                                           data-bs-toggle="tooltip" title="Sửa">
                                            <i class="fa fa-fw fa-pen"></i>
                                        </a>

                                        @if(auth()->id() !== $item->id)
                                            <form id="destroy-{{$item->id}}"
                                                  action="{{route('admin.users.destroy', $item->id)}}" method="post">
                                                @csrf
                                                @method('delete')

                                                <button class="btn btn-sm btn-alt-danger" type="submit"
                                                        data-bs-toggle="tooltip" title="Xóa"
                                                        onclick="if(!confirm('Xóa nhân viên {{$item->id}}?')) event.preventDefault();">
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
                </div>
                <!-- END All Orders Table -->

                {{ $data->links('layouts.inc.pagination') }}
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
