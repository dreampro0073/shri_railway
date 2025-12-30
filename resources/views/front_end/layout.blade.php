<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhyasri Web Solutions</title>
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Questrial:wght@400&display=swap" rel="stylesheet">
    <link href="{{url('front-end/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('front-end/plugins/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{url('front-end/plugins/owlcarousel/assets/owl.carousel.min.css')}}">
    <link href="{{url('front-end/css/custom.css?v=1.0.1')}}" rel="stylesheet">

    @yield('header_scripts')
</head>
<body>  
    @include('front_end.header')

    @yield('main')

    @include('front_end.footer')

    <!-- Bootstrap core JavaScript-->
    <script src="{{url('front-end/js/jquery.min.js')}}"></script>
    <script src="{{url('front-end/plugins/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{url('front-end/js/custom.js')}}"></script>

    @yield('footer_scripts')

</body>
</html>