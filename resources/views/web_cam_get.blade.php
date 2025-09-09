<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<div class="container">
    <h2>Saved Webcam Photos</h2>

    @foreach($photos as $photo)
	    <div style="margin:10px; display:inline-block;">
            <img src="{{ asset($photo->file_path)}}" width="200" class="img-thumbnail">
        </div>
    @endforeach
</div>

</body>
</html>