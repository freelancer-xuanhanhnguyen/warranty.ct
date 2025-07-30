@extends('layouts.simple')

@section('content')
    <!-- Hero -->
    <div class="hero bg-body-extra-light overflow-hidden">
        <div class="hero-inner">
            <div class="content content-full">
                <div class="row">
                    <div class="offset-lg-4 col-lg-4 offset-md-3 col-md-6">
                        @include('components.alert')

                        <form class="text-center" action="" method="POST">
                            @csrf

                            <div class="text-center">
                                <x-logo/>
                                <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                <p class="fs-base text-muted mb-4">
                                    Theo dõi quá trình bảo hành - sửa chữa thiết bị của bạn tốt hơn!
                                </p>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="email"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       id="email" value="{{old('email')}}"
                                       name="email" placeholder="vui lòng nhập email của bạn" required>
                                <label for="email">Email</label>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text"
                                       class="form-control form-control-sm @error('token') is-invalid @enderror"
                                       id="token"
                                       name="token" placeholder="vui lòng nhập email của bạn" required>
                                <label for="token">Mã xác thực</label>
                                @error('token')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn w-100 btn-alt-primary">
                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Xác thực
                            </button>
                        </form>

                        @if(old('email'))
                            <form class="text-center mt-4" action="{{route('customer.sendToken')}}" method="POST">
                                @csrf

                                <input type="hidden"
                                       class="form-control form-control-sm"
                                       value="{{old('email')}}"
                                       name="email" placeholder="vui lòng nhập email của bạn">

                                <button type="submit" class="btn w-100 btn-alt-info">
                                    <i class="fa fa-fw fa-envelope me-1 opacity-50"></i> Gửi lãi mã xác thực
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->
@endsection
