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

            <div class="col-md-6 ">
                <div class="">
                    {{ Form::open(array('url' => '/aadhar/upload-by-mobile/'.$aadhar->id, 'class' => 'user check-form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

                    @csrf
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Aadhar Front<span id="aadhar-span" class="error">*</span> <small>(jpg, png only)</small></label>
                                {{Form::file('aadhar_front',["class"=>"form-control",(!isset($aadhar->aadhar_front))?'required':''])}}
                                <span class="error">{{$errors->first('aadhar_front')}}</span>
                                @if(isset($aadhar->aadhar_front))
                                    @if($aadhar->aadhar_front)
                                    <a style="font-size: 12px" target="_blank" href="{{url($aadhar->aadhar_front)}}">View Current File</a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Aadhar Back<span id="aadhar-span" class="error">*</span> <small>(jpg, png only)</small></label>
                                {{Form::file('aadhar_back',["class"=>"form-control",(!isset($aadhar->aadhar_back))?'required':''])}}
                                <span class="error">{{$errors->first('aadhar_back')}}</span>
                                @if(isset($aadhar->aadhar_back))
                                    @if($aadhar->aadhar_back)
                                    <a style="font-size: 12px" target="_blank" href="{{url($aadhar->aadhar_back)}}">View Current File</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    {{Form::close()}}
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