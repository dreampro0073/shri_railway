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

		.rel{
			width: 302.36px;
			height: 151.18px;
			position: relative;
			margin-bottom: 15.11px;
		}
		.abs{
			/*position: absolute;
			top: 10px;
			left: 10px;
			right: 10px;
			bottom: 10px;
			width:292;
			height: 131px;
			width: 302.36px;*/
		}
		.tab{
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			height: 100%;
		}
	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<div style="text-align:center;margin-top: 10px;">
			<?php for ($i=0; $i < $print_data->no_of_item ; $i++) {  ?>
				<div class="rel">
					<div class="tab">
						<div style="padding-right: 20px;">
							<img src="{{url('assets/img/rail.png')}}" style="width:100px;height:100px;">
						</div>
						<div class="qrcode" id="qrcode"></div>
					</div>
				</div>
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
                    width: 100,
                    height: 100
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
