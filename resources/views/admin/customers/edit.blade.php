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
    <script src="{{asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <script type="module">
        One.helpersOnLoad(['jq-maxlength', 'jq-masked-inputs']);
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Chỉnh sửa khách hàng ({{$data->code}})
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
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{route('admin.customers.update', $data->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="form-label" for="code">Mã khách hàng <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="code"
                                       name="code" value="{{old('code', $data->code)}}" placeholder="######"
                                       maxlength="20" readonly required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="name">Tên khách hàng <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="js-maxlength form-control" id="name" maxlength="255"
                                       name="name" value="{{old('name', $data->name)}}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="js-maxlength form-control @error('email') is-invalid @enderror" id="email" maxlength="255"
                                       name="email" value="{{old('emaill', $data->email)}}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="birthday">Ngày sinh</label>
                                <input type="date" class="form-control" id="birthday"
                                       name="birthday" value="{{old('birthday', $data->birthday)}}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="gender">Giới tính</label>
                                <select class="form-select" id="gender" name="gender">
                                    @foreach(\App\Models\Customer::GENDER as $key => $type)
                                        <option value="{{$key}}"
                                                @if(old('gender', $data->gender) == $key) selected @endif>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">Địa chỉ</label>
                                <textarea type="text" class="js-maxlength form-control" id="address"
                                          name="address" rows="4"
                                          maxlength="255">{{old('address', $data->address)}}</textarea>
                            </div>
                            <div class="mb-4 text-center">
                                <button type="submit" class="btn btn-alt-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Info -->
    </div>
    <!-- END Page Content -->
@endsection
