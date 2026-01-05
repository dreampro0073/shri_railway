<div style="height: 100vh;display: flex;align-content: center;justify-content:center;background: url('assets/img/indianrailway1.jpeg');no-repeat;background-size: cover;background-blend-mode: multiply;">

    <span style="position: absolute;top: 10px;right: 10px;text-align: right;">
        <img src="{{url('assets/img/aadh_new.png')}}" style="height:50px;width: auto;">
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