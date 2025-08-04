@extends('layouts.backend')

@section('js')
    <!-- Page JS Plugins -->
    <script src="{{asset('js/plugins/chart.js/chart.umd.js')}}"></script>
    <!-- Page JS Code -->
    {{--    <script src="{{asset('js/pages/be_pages_dashboard.js')}}"></script>--}}

    <script>
        $(() => {
            Chart.defaults.color = '#818d96';
            Chart.defaults.scale.grid.lineWidth = 0;
            Chart.defaults.scale.beginAtZero = true;
            Chart.defaults.datasets.bar.maxBarThickness = 45;
            Chart.defaults.elements.bar.borderRadius = 4;
            Chart.defaults.elements.bar.borderSkipped = false;
            Chart.defaults.elements.point.radius = 0;
            Chart.defaults.elements.point.hoverRadius = 0;
            Chart.defaults.plugins.tooltip.radius = 3;
            Chart.defaults.plugins.legend.labels.boxWidth = 10;

            let chartEarningsCon = document.getElementById('js-chartjs-earnings');
            let chartTotalOrdersCon = document.getElementById('js-chartjs-total-orders');
            let chartTotalEarningsCon = document.getElementById('js-chartjs-total-earnings');
            let chartNewCustomersCon = document.getElementById('js-chartjs-new-customers');

            // Set Chart and Chart Data variables
            let chartEarnings, chartTotalOrders, chartTotalEarnings, chartNewCustomers;

            // Init Chart Earnings
            if (chartEarningsCon !== null) {
                new Chart(chartEarningsCon, {
                    type: 'bar',
                    data: {
                        labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
                        datasets: [
                            {
                                label: 'Phiếu mới',
                                fill: true,
                                backgroundColor: 'rgba(100, 116, 139, .15)',
                                borderColor: 'transparent',
                                pointBackgroundColor: 'rgba(100, 116, 139, 1)',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
                                data: {{json_encode($stats['created'], JSON_UNESCAPED_UNICODE)}}
                            },
                            {
                                label: 'Phiếu hoàn thành',
                                fill: true,
                                backgroundColor: 'rgba(100, 116, 139, .7)',
                                borderColor: 'transparent',
                                pointBackgroundColor: 'rgba(100, 116, 139, 1)',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
                                data: {{json_encode($stats['completed'], JSON_UNESCAPED_UNICODE)}}
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: false,
                                grid: {
                                    drawBorder: false
                                }
                            },
                            y: {
                                display: false,
                                grid: {
                                    drawBorder: false
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    boxHeight: 10,
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.parsed.y + ' ' + context.dataset.label.toLowerCase();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
    </script>
@endsection

@section('content')
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
                            <dt class="fs-3 text-white fw-bold">{{$reportUsers[\App\Models\User::ROLE_CSKH]?->today_total ?? 0}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-white mb-0">Chăm sóc khách hàng mới</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa fa-phone-volume fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.users.index')}}?role={{\App\Models\User::ROLE_CSKH}}">
                            <span>{{$reportUsers[\App\Models\User::ROLE_CSKH]?->total ?? 0}} Chăm sóc khách hàng</span>
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
                            <dt class="fs-3 fw-bold">{{$reportUsers[\App\Models\User::ROLE_REPAIRMAN]?->today_total ?? 0}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Kỹ thuật viên mới</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="far fa-user fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.users.index')}}?role={{\App\Models\User::ROLE_REPAIRMAN}}">
                            <span>{{$reportUsers[\App\Models\User::ROLE_REPAIRMAN]?->total ?? 0}} kỹ thuật viên</span>
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
                            <dt class="fs-3 fw-bold">{{$reportService->today_total}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Phiếu bảo hành mới</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa-solid fa-screwdriver-wrench text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.services.index')}}">
                            <span>{{$reportService->total}} phiếu bảo hành</span>
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
                            <dt class="fs-3 fw-bold">{{$reportCustomer->today_total}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium mb-0">Khách hàng mới</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fa fa-users-gear fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                           href="{{route('admin.customers.index')}}">
                            <span>{{$reportCustomer->total}} khách hàng</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Conversion Rate-->
            </div>
        </div>
        <!-- END Overview -->

        <!-- Statistics -->
        <div class="row">
            <div class="col-xl-8 col-xxl-9 d-flex flex-column">
                <!-- Earnings Summary -->
                <div class="block block-rounded flex-grow-1 d-flex flex-column">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Earnings Summary</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option"
                                    data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                        <!-- Earnings Chart Container -->
                        <!-- Chart.js Chart is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                        <canvas id="js-chartjs-earnings"></canvas>
                    </div>
                    <div class="block-content bg-body-light">
                        <div class="row items-push text-center w-100">
                            <div class="col-sm-6">
                                <dl class="mb-0">
                                    <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                                        <i class="fa fa-caret-up fs-base text-success"></i>
                                        <span>2.5%</span>
                                    </dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">Phiếu mới</dd>
                                </dl>
                            </div>
                            <div class="col-sm-6">
                                <dl class="mb-0">
                                    <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                                        <i class="fa fa-caret-up fs-base text-success"></i>
                                        <span>3.8%</span>
                                    </dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">Phiếu hoàn thành</dd>
                                </dl>
                            </div>
                            {{--                            <div class="col-sm-4">--}}
                            {{--                                <dl class="mb-0">--}}
                            {{--                                    <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">--}}
                            {{--                                        <i class="fa fa-caret-down fs-base text-danger"></i>--}}
                            {{--                                        <span>1.7%</span>--}}
                            {{--                                    </dt>--}}
                            {{--                                    <dd class="fs-sm fw-medium text-muted mb-0"></dd>--}}
                            {{--                                </dl>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <!-- END Earnings Summary -->
            </div>
            <div class="col-xl-4 col-xxl-3 d-flex flex-column">
                <!-- Last 2 Weeks -->
                <!-- Chart.js Charts is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
                <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                <div class="row items-push flex-grow-1">
                    <div class="col-md-6 col-xl-12">
                        <div class="block block-rounded d-flex flex-column h-100 mb-0">
                            <div class="block-content flex-grow-1 d-flex justify-content-between">
                                <dl class="mb-0">
                                    <dt class="fs-3 fw-bold">570</dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">Nhân viên mới</dd>
                                </dl>
                                <div>
                                    <div
                                        class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-danger bg-danger-light">
                                        <i class="fa fa-caret-down me-1"></i>
                                        2.2%
                                    </div>
                                </div>
                            </div>
                            <div class="block-content p-1 text-center overflow-hidden">
                                <!-- Total Orders Chart Container -->
                                <canvas id="js-chartjs-total-orders" style="height: 90px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-12">
                        <div class="block block-rounded d-flex flex-column h-100 mb-0">
                            <div class="block-content flex-grow-1 d-flex justify-content-between">
                                <dl class="mb-0">
                                    <dt class="fs-3 fw-bold">$5,234.21</dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">Khách hàng mới</dd>
                                </dl>
                                <div>
                                    <div
                                        class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                                        <i class="fa fa-caret-up me-1"></i>
                                        4.2%
                                    </div>
                                </div>
                            </div>
                            <div class="block-content p-1 text-center overflow-hidden">
                                <!-- Total Earnings Chart Container -->
                                <canvas id="js-chartjs-total-earnings" style="height: 90px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="block block-rounded d-flex flex-column h-100 mb-0">
                            <div class="block-content flex-grow-1 d-flex justify-content-between">
                                <dl class="mb-0">
                                    <dt class="fs-3 fw-bold">264</dt>
                                    <dd class="fs-sm fw-medium text-muted mb-0">Phiếu bảo hành</dd>
                                </dl>
                                <div>
                                    <div
                                        class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                                        <i class="fa fa-caret-up me-1"></i>
                                        9.3%
                                    </div>
                                </div>
                            </div>
                            <div class="block-content p-1 text-center overflow-hidden">
                                <!-- New Customers Chart Container -->
                                <canvas id="js-chartjs-new-customers" style="height: 90px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Last 2 Weeks -->
            </div>
        </div>
        <!-- END Statistics -->

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="block block-rounded block-link-shadow" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <div class="py-3">
                                    <div class="item item-circle bg-body-light mx-auto">
                                        <i class="fa-solid fa-screwdriver-wrench text-primary"></i>
                                    </div>
                                    <dl class="mb-0">
                                        <dt class="h3 fw-extrabold mt-3 mb-0">
                                            {{$reportServices?->total_under_warranty}}
                                        </dt>
                                        <dd class="fs-sm fw-medium text-muted mb-0">
                                            Thiết bị đang bảo hành
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="py-3">
                                    <div class="item item-circle bg-body-light mx-auto">
                                        <i class="fa-solid fa-gears text-primary"></i>
                                    </div>
                                    <dl class="mb-0">
                                        <dt class="h3 fw-extrabold mt-3 mb-0">
                                            {{$reportServices?->total_under_repair}}
                                        </dt>
                                        <dd class="fs-sm fw-medium text-muted mb-0">
                                            Thiết bị đang sửa
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="py-3">
                                    <div class="item item-circle bg-body-light mx-auto">
                                        <i class="fa-solid fa-list-check text-primary"></i>
                                    </div>
                                    <dl class="mb-0">
                                        <dt class="h3 fw-extrabold mt-3 mb-0">
                                            {{$reportServices?->total_services}}
                                        </dt>
                                        <dd class="fs-sm fw-medium text-muted mb-0">
                                            Tổng thiết bị đang sửa
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Statistics -->

        <!-- Statistics -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Kỹ thuật viên đang sửa nhiều thiết bị
                    nhất</h3>
            </div>
            <div class="block-content">
                <!-- All Orders Table -->
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center " data-name="services__repairman_id" style="width: 100px;">
                                ID
                            </th>
                            <th class="" data-name="users__name">Tên nhân viên</th>
                            <th class="" data-name="users__email">Email</th>
                            <th class=" text-center" data-name="total_under_warranty">Thiết bị đang bảo hành
                            </th>
                            <th class=" text-center" data-name="total_under_repair">Thiết bị đang sửa</th>
                            <th class=" text-center" data-name="total_services">Tổng thiết bị đang sửa</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reportRepairman as $item)
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

                        <x-empty :data="$reportRepairman"/>

                        </tbody>
                    </table>
                </div>
                <!-- END All Orders Table -->
            </div>
        </div>
        <!-- END Statistics -->
    </div>
    <!-- END Page Content -->
@endsection
