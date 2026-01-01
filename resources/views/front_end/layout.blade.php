<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta -->
    <title>Aadhyasri Web Solutions | Web Development & Digital Services</title>
    <meta name="description" content="Aadhyasri Web Solutions provides professional web development, website design, SEO and digital solutions for businesses.">
    <meta name="keywords" content="Aadhyasri Web Solutions, web development, website design, SEO services, digital marketing">
    <meta name="author" content="Aadhyasri Web Solutions">

    <!-- Open Graph (Social Media) -->
    <meta property="og:title" content="Aadhyasri Web Solutions">
    <meta property="og:description" content="Professional web development & digital solutions for your business.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('assets/img/favicon.png') }}">

    <!-- Favicon -->
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{ url('assets/img/favicon.png') }}">

    <link href="{{ url('front-end/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('front-end/plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('front-end/plugins/owlcarousel/assets/owl.carousel.min.css') }}">
    <link href="{{ url('front-end/css/custom.css?v=1.0.5') }}" rel="stylesheet">

    @yield('header_scripts')
</head>

<body>  
    @include('front_end.header')

    @yield('main')

    @include('front_end.footer')

    <!-- Bootstrap core JavaScript-->
    <script src="{{url('front-end/js/jquery.min.js')}}"></script>
    <script src="{{url('front-end/plugins/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{url('front-end/js/custom.js?v=1.0.5')}}"></script>

    @yield('footer_scripts')

</body>
</html>