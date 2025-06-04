@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <script type="module">
        jQuery('.js-masked-phone-vn:not(.js-masked-enabled)').mask('0999 999 999');

        One.helpersOnLoad(['jq-maxlength', 'jq-masked-inputs']);
    </script>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Info -->
        <div class="row justify-content-center push">
            <div class="col-md-8 col-lg-6">
                @include('components.alert')
                <!-- Sign In Block -->
                <div class="block block-rounded mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Đổi mật khẩu</h3>
                    </div>
                    <div class="block-content">
                        <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                            <form action="{{route('admin.change-password.update', auth()->id())}}" method="POST">
                                @csrf
                                @method('put')

                                <div class="mb-4">
                                    <label class="form-label" for="password">Mật khẩu <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="password-confirm">Nhập lại mật khẩu <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-lg form-control-alt"
                                           id="password-confirm" name="password_confirmation" required>
                                </div>
                                <div class="mb-4 text-center">
                                    <button type="submit" class="btn btn-alt-primary">Đổi mật khẩu</button>
                                </div>
                            </form>
                            <!-- END Sign In Form -->
                        </div>
                    </div>
                </div>
                <!-- END Sign In Block -->
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
