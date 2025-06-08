@extends('layouts.simple')

@section('content')
    <!-- Hero -->
    <div class="hero bg-body-extra-light overflow-hidden">
        <div class="hero-inner">
            <div class="content content-full text-center">
                <div class="row">
                    <div class="offset-lg-4 col-lg-4 offset-md-3 col-md-6">
                        <form action="{{route('track-email')}}" method="POST">
                            @csrf

                            <div class="text-center">
                                <img width="100" src="{{asset('media/logo/logo.png')}}" alt="">
                                <h1 class="h5 mb-1">{{the_website_name()}}</h1>
                                <p class="fs-base text-muted mb-4">
                                    Theo dỗi quá trình bảo hành - sửa chữa thiết bị của bạn tốt hơn!
                                </p>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email"
                                       name="email" placeholder="vui lòng nhập email của bạn" required>
                                <label for="email">Email</label>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary px-3 py-2">
                                Theo dõi ngay
                                <i class="fa fa-fw fa-arrow-right opacity-50 ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->
@endsection
