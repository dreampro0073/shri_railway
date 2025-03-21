<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aadhyasri Web Solutions</title>
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >
    <link rel="stylesheet" type="text/css" href="{{url('bootstrap3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css')}}">
</head>
<body>

<div style="height: 100vh;display: flex;align-content: center;justify-content:center;background: url('assets/img/indianrailway1.jpeg');no-repeat;background-size: cover;background-blend-mode: multiply;">
    <div class="container">
        <div class="row justify-content-center" >

            <div class="col-md-6 col-md-offset-3 login-box">
                <div>
                    
                    
                    <div class="panel panel-default">
                        <div class="panel-body" style="box-shadow:0 1px 6px 0 rgba(0, 0, 0, 0.3);padding: 28px;width: 500px;">
                            <div class="">
                                <div class="text-center">
                                    <span style="font-size: 32px;font-weight: bold;margin-bottom: 2px;text-align: center;display: block;">Aadhyasri Web Solutions</span> 
                                    <h1 class="h4 text-gray-900 mb-4" style="font-size:20px;">Oops!</h1>
                                    <p style="font-size:16px;">
                                        This service is not available to your account Kindly Contact to System Admin. Thanks!
                                    </p>
                                    <a class="btn btn-primary" href="{{url('/')}}">Login</a>
                                </div>           
                            </div> 
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <span style="position: absolute;top: 10px;right: 10px;text-align: right;">
        <img src="{{url('assets/img/aadh1.png')}}" style="height:50px;width: auto;">
        <br>
        <a href="mailto:aadhyasriwebsolutions@gmail.com" style="text-align:right;">aadhyasriwebsolutions@gmail.com</a>

    </span>
</div>

<script type="text/javascript">
    var base_url = "{{url('/')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.validate.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/custom.js')}}"></script>
    <script type="text/javascript">
        $(".check-form").validate();
    </script>
</body>
</html>