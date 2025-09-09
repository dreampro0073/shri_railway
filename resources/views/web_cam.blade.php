<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<div class="container">
    <h2>Capture Photo</h2>

    <img src="{{ asset('storage\webcam_1754637563.jpg') }}" width="150">

    <!-- Camera Display -->
    <div id="my_camera" style="width:320px; height:240px; border:1px solid black;"></div>

    <br>
    <button onclick="take_snapshot()" class="btn btn-primary">Take Snapshot</button>

    <!-- Result -->
    <div id="results" style="margin-top:10px;"></div>

    <form id="webcamForm" method="POST" action="{{ url('webcam/store') }}">
        @csrf
        <input type="hidden" name="image" id="image_data">
        <button type="submit" class="btn btn-success" style="margin-top:10px;">Save Image</button>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    // Setup camera
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach('#my_camera');

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            // Show preview
            document.getElementById('results').innerHTML = 
                '<img src="'+data_uri+'" class="img-thumbnail"/>';
            // Store in hidden input
            document.getElementById('image_data').value = data_uri;
        });
    }
</script>
</body>
</html>