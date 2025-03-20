<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		@page { margin: 0; }
		body { margin: 0; }
		.main{
			width: 181px;
		}
		h4{
			
			font-size: 14px;
		}
		h4,h5,p{
			text-align: center;
			margin: 0;
		}
		.m-space{
			margin: 2px 0;
		}
		.table-div{
			display: table;
			width: 100%;
		}
		.table-div > div{
			display: table-cell;
			vertical-align: middle;
			padding: 2px;
		}
		.w-50{
			width: 50%;
		}
		.w-16{
			width: 16.66%;
		}
		td,span,p{
			font-size: 12px;
			font-weight: bold;
		}
		.text-right{
			text-align: right;
		}
		.name{
			text-align: left;
		}
		.qrcode img{
			margin: auto;
		}
		.bot-border{
			margin-bottom:15px;
			padding-bottom: 15px;
			border-bottom: 1px dotted #000;
		}
	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<div style="text-align:center;margin-top: 10px;">
			<?php for ($i=0; $i <$print_data->no_of_item ; $i++) {  ?>
				<div class="qrcode bot-border" id="qrcode" ></div>
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
