@extends('layouts.backend')

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
                                    <label class="form-label" for="current_password">Mật khẩu hiện tại<span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control form-control-lg form-control-alt @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password" required>
                                    @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <x-password id="password" name="password" placeholder="">
                                    <label class="form-label" for="password">Mật khẩu <span
                                            class="text-danger">*</span></label>
                                </x-password>

                                <x-password id="password-confirm" name="password_confirmation" placeholder="">
                                    <label class="form-label" for="password-confirm">Nhập lại mật khẩu <span
                                            class="text-danger">*</span></label>
                                </x-password>

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
