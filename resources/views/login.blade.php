<!DOCTYPE html>
<html style="height:100%;">
<head >
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Aadhyasri Web Solutions | Web Development & Digital Services</title>
    <meta name="description" content="Aadhyasri Web Solutions provides professional web development, website design, SEO and digital solutions for businesses.">
    <meta name="keywords" content="Aadhyasri Web Solutions, web development, website design, SEO services, digital marketing">
    <meta name="author" content="Aadhyasri Web Solutions">
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/bootstrap.min.css')}}">


    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/style.css')}}">
    <style>
        .min-wid{
            min-width: 400px;
        }
        @media only screen and (max-width: 668px) {
            .min-wid{
                min-width: 80%;
            }
        }
        label.error{
            color: #f00 !important;
            font-size: 14px !important;
        }
    </style>
</head>
<body class="h-100">


<div class="d-lg-flex bg-white h-100">
    <div class="w-50 d-lg-flex d-none overflow-hidden h-100">
        <img src="{{url('assets/img/railway1.jpg')}}" alt="Login Image" class="w-100 h-100 object-fit-cover">
    </div>
    <div class="lg-w-50 px-24 py-32 d-flex justify-content-center align-items-center h-100">
        <div class="max-w-540-px min-wid mx-auto">
            <a href="{{url('/')}}" class="">
                <img src="{{url('front-end/images/AashyaFinal1.png')}}" style="height:50px;width: auto;">
            </a>
            <div class="mt-32 mb-32">
                <h1 class="h6 fw-bold text-primary-light mb-8">
                    Welcome Back 👋
                </h1>
                <p class="text-sm text-secondary-light mb-0">
                    Log in to your account to continue
                </p>
            </div>
            {{ Form::open(array('url' => '/login','class' => 'submit-form check-form',"method"=>"POST")) }}

                @if(Session::has('failure'))
                    <div class="alert alert-danger" style="margin-top: 10px;">
                        <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                    </div>
                @endif

                @if(Session::has('success'))
                    <div class="alert alert-success">
                       <i class="fa fa-ban-circle"></i><strong>success!</strong> {{Session::get('success')}}
                     </div>    
                @endif
                <div class="d-flex flex-column">
                    <div>
                        <label for="email" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Email Address
                            <span class="text-danger-600">*</span>
                        </label>
                        

                        {{Form::text('email','',["class"=>"form-control form-control-user","id"=>"email","autocomplete"=>"off","placeholder"=>"Enter your email Address...",'required'=>"required"])}}
                        
                    </div>

                    <div class="mt-3">
                        <label for="password" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Password
                            <span class="text-danger-600">*</span>
                        </label>
                        <div class="position-relative">
                            
                            {{Form::password('password',["class"=>"password-field form-control","required"=>"true","id"=>"password","placeholder"=>"Enter Password"])}}
                            <!-- <button type="button"
                                class="toggle-password btn p-0 border-0 bg-transparent position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light cursor-pointer ri-eye-line"
                                data-toggle="#password" aria-label="Toggle password visibility">
                            </button> -->
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-success btn-user btn-block min-wid" style="margin:auto;">Login</button>
                    </div>
                </div>
              
                
            {{Form::close()}}
            
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = "{{url('/')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.validate.js')}}"></script>
    <script type="text/javascript">
        $(".check-form").validate();
    </script>
</body>
</html>