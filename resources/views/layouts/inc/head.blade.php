<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{the_website_name()}}</title>

    <meta name="description" content="{{the_website_name()}}">
    <meta name="author" content="Nam Việt Nam">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/logo/cropped-logo-tmdt-32x32.jpg') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/logo/cropped-logo-tmdt-192x192.jpg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/logo/cropped-logo-tmdt-180x180.jpg') }}">

    <!-- Modules -->
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <x-style/>

    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <x-custom-js/>

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
    @yield('js')
</head>
