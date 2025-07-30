@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->

    <style>
        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('js')

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>


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
                        Kỹ thuật viên
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
                <!-- Search Form -->
                <form class="search-form" action="" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-alt" id="q"
                                   name="q" value="{{request()->q}}" placeholder="Tìm kiếm">
                            <span class="input-group-text bg-body border-0">
                              <i class="fa fa-search"></i>
                            </span>
                        </div>

                        <x-sort-input/>
                    </div>
                </form>
                <!-- END Search Form -->

                <!-- All Orders Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center sortable" data-name="services__repairman_id" style="width: 100px;">ID</th>
                            <th class="sortable" data-name="users__name">Tên nhân viên</th>
                            <th class="sortable" data-name="users__email">Email</th>
                            <th class="sortable text-center" data-name="total_under_warranty">Thiết bị đang bảo hành</th>
                            <th class="sortable text-center" data-name="total_under_repair">Thiết bị đang sửa</th>
                            <th class="sortable text-center" data-name="total_services">Tổng thiết bị đang sửa</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center fs-sm">
                                    <strong>{{$item->repairman_id}}</strong>
                                </td>
                                <td class="fs-sm">
                                    <a class="fw-semibold"
                                       href="{{route('admin.repairman.show', $item->repairman_id)}}">
                                        <strong>{{$item->name}}</strong>
                                    </a>
                                </td>
                                <td class="d-none d-sm-table-cell fs-sm">
                                    {{$item->email}}
                                </td>

                                <td class="text-center fs-sm">
                                    {{$item->total_under_warranty}}
                                </td>

                                <td class="text-center fs-sm">
                                    {{$item->total_under_repair}}
                                </td>

                                <td class="text-center fs-sm">
                                    {{$item->total_services}}
                                </td>
                            </tr>
                        @endforeach

                        <x-empty :data="$data"/>

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
