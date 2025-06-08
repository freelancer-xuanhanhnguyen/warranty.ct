<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{the_website_name()}}</title>

    <meta name="description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="OneUI">
    <meta property="og:description"
          content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/logo/cropped-logo-tmdt-32x32.jpg') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/logo/cropped-logo-tmdt-192x192.jpg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/logo/cropped-logo-tmdt-180x180.jpg') }}">

    <!-- Stylesheets -->
    <!-- Modules -->
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
    @yield('js')
</head>

<body>
<!-- Page Container -->
<!--
  Available classes for #page-container:

  GENERIC

    'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                - Theme helper buttons [data-toggle="theme"],
                                                - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                - ..and/or One.layout('dark_mode_[on/off/toggle]')

  SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header

  HEADER STYLE

    ''                                          Light themed Header
    'page-header-dark'                          Dark themed Header

  MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

  DARK MODE

    'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
-->
<div id="page-container" class="sidebar-dark side-scroll page-header-fixed page-header-dark main-content-boxed">

    <!-- Sidebar -->
    @include('layouts.inc.web.sidebar')
    <!-- END Sidebar -->

    <!-- Header -->
    @include('layouts.inc.web.header')
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    @include('layouts.inc.web.footer')
    <!-- END Footer -->
</div>
<!-- END Page Container -->

</body>
</html>
