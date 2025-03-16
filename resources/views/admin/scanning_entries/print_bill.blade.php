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
		<h4>
			{!! $print_data->client_name !!}
		</h4>
		<p style="padding:0 15px;text-align: center;">
			{{ $print_data->client_address }}

		</p>
		<h5>
			{{ $print_data->gst }}
		</h5>
		<h5>
			Scanning Detail
		</h5>
		<div style="text-align: center;">
			<b style="font-size: 18px;">
				Slip ID : {{ $print_data->slip_id }}
			</b>
		</div>

		<div class="table-div">
			<div class="w-50">
				<span class="name">Name : {{$print_data->name}}</span>
			</div>
			<div class="w-50">
				<span class="text text-right">Date: {{date('d-m-Y',strtotime($print_data->date))}} </span>	

			</div>
		</div>
		<div class="table-div">
			<div class="w-50">
				<span class="text">Item Type: {{$print_data->item_type_name}}</span>
			</div>
			<div class="w-50">
				<span class="text">Type: {{$print_data->incoming_type}}</span>
			</div>
		</div>

		<div class="table-div bot-border">
			<div class="w-50">
				<span class="text">No Of Item: {{$print_data->no_of_item}}</span>
			</div>
			<div class="w-50">
				<span class="text">Paid Amount: {{$print_data->paid_amount}}</span>
			</div>
		</div>

		<div style="text-align:center;margin-top: 10px;">
			<?php for ($i=0; $i <$print_data->no_of_item ; $i++) {  ?>
				<div class="qrcode bot-border" id="qrcode" ></div>
			<?php } ?>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
	<!-- <script type="text/javascript">
        document.getElementById("qrcode").innerHTML = "";
        new QRCode(document.getElementById("qrcode"), {
            text: bill_no,
            width: 120,
            height: 120
        });
	</script> -->

	<script>
		var bill_no = "{{$print_data->print_url}}";
        document.addEventListener("DOMContentLoaded", function () {  
            document.querySelectorAll(".qrcode").forEach(function (element) {
                new QRCode(element, {
                    text: bill_no,
                    width: 120,
                    height: 120
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
