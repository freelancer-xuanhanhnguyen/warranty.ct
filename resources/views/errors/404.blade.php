@extends('layouts.simple')

@section('content')
    <!-- Page Content -->
    <div class="hero">
        <div class="hero-inner text-center">
            <div class="bg-body-extra-light">
                <div class="content content-full overflow-hidden">
                    <div class="py-4">
                        <!-- Error Header -->
                        <h1 class="display-1 fw-bolder text-city">
                            404
                        </h1>
                        <h2 class="h4 fw-normal text-muted mb-5">
                            Chúng tôi rất tiếc, vui lòng thử lại sau...
                        </h2>
                        <!-- END Error Header -->
                    </div>
                </div>
            </div>
            <div class="content content-full text-muted fs-sm fw-medium">
                <!-- Error Footer -->
                <a class="link-fx" href="#" onclick="window.history.back();">Quay lại</a>
                <!-- END Error Footer -->
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
