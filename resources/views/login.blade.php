<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shri Tech</title>
    <link rel="stylesheet" type="text/css" href="{{url('bootstrap3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css')}}">
</head>
<body>

<div style="height: 100vh;display: flex;align-content: center;justify-content:center;background: url('assets/img/indianrailway1.jpeg');no-repeat;background-size: cover;background-blend-mode: multiply;">
    <div class="container">
        <div class="row justify-content-center" >

            <div class="col-md-6 col-md-offset-3 login-box">
                <div>
                    <span style="font-size: 18px;font-weight: bold;font-style: italic;margin-bottom: 2px;text-align: center;display: block;">Shri Tech</span> 
                    
                    <div class="panel panel-default">
                        <div class="panel-body" style="box-shadow:0 1px 6px 0 rgba(0, 0, 0, 0.3);padding: 28px;width: 500px;">
                            <div class="">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                {{ Form::open(array('url' => '/login','class' => 'user check-form',"method"=>"POST")) }}

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

                                
                                    <div class="form-group">
                                        <label>Email</label>
                                        {{Form::text('email','',["class"=>"form-control form-control-user","id"=>"exampleInputEmail","autocomplete"=>"off","placeholder"=>"Enter Email Address...",'required'=>"required"])}}
                                        <span class="error">{{$errors->first('email')}}</span>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                       
                                        {{Form::password('password',["class"=>"form-control form-control-user","required"=>"true","id"=>"exampleInputPassword","placeholder"=>"Enter Password"])}}
                                    </div>
                                   
                                   
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-user btn-block" style="margin:auto;">Login</button>
                                    </div>
                                   
                                {{Form::close()}}
                                
                            
                            </div> 
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
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