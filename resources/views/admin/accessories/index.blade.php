@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
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
                        Danh sách linh kiện
                    </h1>
                </div>

                <a class="btn btn-primary" href="{{ route('admin.accessories.create') }}">Thêm mới</a>
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
                <form class="search-form mb-4" action="" method="GET">
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-alt" id="q" name="q"
                                       value="{{ request()->q }}" placeholder="Tìm kiếm">

                                <x-sort-input/>
                                <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" title="Tìm kiếm">
                                    <i class="fa fa-search me-1"></i>
                                </button>
                            </div>
                        </div>

                        <x-btn-export/>
                    </div>
                </form>
                <!-- END Search Form -->

                <!-- All Orders Table -->
                <div class="table-responsive">
                    @include('admin.exports.accessories')
                </div>
                <!-- END All Orders Table -->

                {{ $data->links('layouts.inc.pagination') }}
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
