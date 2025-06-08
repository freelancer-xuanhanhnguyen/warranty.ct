@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Tổng quan
                    </h1>
                    {{--<h2 class="fs-base lh-base fw-medium text-muted mb-0">
                      Welcome Admin, everything looks great.
                    </h2>--}}
                </div>
                {{--<nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                  <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                      <a class="link-fx" href="javascript:void(0)">App</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                      Dashboard
                    </li>
                  </ol>
                </nav>--}}
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Overview -->
        <div class="row items-push">
            <div class="col-sm-6 col-xxl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded bg-primary d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 text-white fw-bold">{{$cskh}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-white mb-0">Chắm sóc khách hàng</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa fa-phone-volume fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.users.index')}}?role={{\App\Models\User::ROLE_CSKH}}">
                            <span>Xem tất cả</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- New Customers -->
                <div class="block block-rounded bg-success text-white d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$repairman}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Kỹ thuật viên</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-user fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.users.index')}}?role={{\App\Models\User::ROLE_REPAIRMAN}}">
                            <span>Xem tất cả</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END New Customers -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Messages -->
                <div class="block block-rounded bg-danger text-white d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$service}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Phiếu bảo hành</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa-solid fa-screwdriver-wrench text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.services.index')}}">
                            <span>Xem tất cả</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Messages -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Conversion Rate -->
                <div class="block block-rounded bg-warning text-white d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{$customer}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Khách hàng</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa fa-users-gear fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.customers.index')}}">
                            <span>Xem tất cả</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Conversion Rate-->
            </div>
        </div>
        <!-- END Overview -->
    </div>
    <!-- END Page Content -->
@endsection
