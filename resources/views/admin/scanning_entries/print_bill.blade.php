<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		@page { margin: 0; }
		body { margin: 0; }
		.main{
			width: 300px;
		}
		
		.qrcode img{
			margin: auto;
		}
	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<div style="text-align:center;margin-top: 10px;">
			<div class="qrcode" id="qrcode"></div>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
	<script type="text/javascript">
		var bill_no = "{{$print_url}}";
		// console.log(bill_no);
		// let text = document.getElementById("text").value;
        document.getElementById("qrcode").innerHTML = "";
        new QRCode(document.getElementById("qrcode"), {
            text: bill_no,
            width: 120,
            height: 120
        });
	</script>
</body>
</html>
