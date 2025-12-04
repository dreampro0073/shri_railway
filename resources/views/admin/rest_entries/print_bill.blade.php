<?php $print_name = Session::has('print_name')?Session::get('print_name'):0; ?>
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
		@if($print_name == 0)
            <h4>{{ Session::get('client_name') }}</h4>
        @endif
		<p style="padding:0 15px;text-align: center;">
			{!! $print_data->client_address !!}
		</p>
		@if($print_name == 0)
            <h5>{{ Session::get('gst_no') }}</h5>
        @endif
		<h5>
			Detail
		</h5>
		<div style="text-align: center;">
			<b style="font-size: 18px;">
				Slip ID : {{ $print_data->slip_id }}
			</b>
		</div>
		

		<div style="text-align:center;">
			<span class="name">Date: {{$print_data->date}}</span>
		</div>
		<div class="table-div">
			<div class="w-50">
				<span class="text text-right">Check In: {{$print_data->check_in}} </span>	

			</div>
			<div class="w-50">
				<span class="text">Valid Up To: {{$print_data->check_out}}</span>
			</div>
			
		</div>
		<div class="table-div">
			<div class="w-50">
				<span class="text text-right">No. of Hours: {{$print_data->no_of_hours}} Hr </span>	

			</div>
			<div class="w-50">
				<span class="text">No. of Person: {{$print_data->no_of_people}}</span>
			</div>
			
		</div>
		<div class="table-div">
			<div class="w-50">
				<span class="text text-right">Payment: {{$print_data->show_pay_type}}  </span>	
			</div>
			<div class="w-50">
				<span class="text">Paid Amount: {{$print_data->paid_amount}}</span>
			</div>
			
		</div>

		
	</div>
	
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
