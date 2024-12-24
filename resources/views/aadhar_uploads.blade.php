@include('header')

    <div class="container">
        <div class="row justify-content-center up-file">

            <div class="col-md-6 ">
                <div class="">
                    {{ Form::open(array('url' => '/aadhar/upload-by-mobile/'.$aadhar->id, 'class' => 'user check-form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

                    @csrf
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Front Side<span id="aadhar-span" class="error">*</span> <small>(jpg, png only)</small></label>
                                {{Form::file('aadhar_front',["class"=>"form-control",(!isset($aadhar->aadhar_front))?'required':''])}}
                                <span class="error">{{$errors->first('aadhar_front')}}</span>
                                @if(isset($aadhar->aadhar_front))
                                    @if($aadhar->aadhar_front)
                                    <a style="font-size: 12px" target="_blank" href="{{url($aadhar->aadhar_front)}}">View Current File</a>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-3 form-group" style="margin-top:32px;"> 
                                <label>Back Side<span id="aadhar-span" class="error">*</span> <small>(jpg, png only)</small></label>
                                {{Form::file('aadhar_back',["class"=>"form-control"])}}
                                <span class="error">{{$errors->first('aadhar_back')}}</span>
                                @if(isset($aadhar->aadhar_back))
                                    @if($aadhar->aadhar_back)
                                    <a style="font-size: 12px" target="_blank" href="{{url($aadhar->aadhar_back)}}">View Current File</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div style="text-align:center;">
                            <button type="submit" class="btn btn-primary t-btn"  id="upload_btn">Submit</button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>

        </div>

    </div>


@include('footer')