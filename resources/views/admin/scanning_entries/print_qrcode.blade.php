<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		@page { margin: 0; }
		body { margin: 0;margin-top:5px !important; }
		.main{
			width: 302.36px;
		}
		/*.qrcode{
			width: 302.36px;
			height: 151.18px;
			margin-top:15px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding-top: 13px;
			text-align: center;
		}
		
		.qrcode img{
			margin:auto;
		}*/

		.qrcode{
			width: 302.36px;
			height: 151.18px;
			position: relative;
			margin-bottom: 10p;
		}
		
		.qrcode img{
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-48%,-53%);
		}

	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<div style="text-align:center;margin-top: 10px;">
			<?php for ($i=0; $i < $print_data->no_of_item ; $i++) {  ?>
				<div class="qrcode" id="qrcode"></div>
			<?php } ?>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

	<script>
		var bill_no = "{{$print_data->print_url}}";
        document.addEventListener("DOMContentLoaded", function () {  
            document.querySelectorAll(".qrcode").forEach(function (element) {
                new QRCode(element, {
                    text: bill_no,
                    width: 130,
                    height: 130
                });
            });
        });
    </script>
	<script type="text/javascript">
		window.onload = function(e){ 
		    var printContents = document.getElementById("printableArea").innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents; 
		}
	</script>
</body>
</html>
