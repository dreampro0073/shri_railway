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
			height: 600px;
		}
		h4{
			
			font-size: 14px;
		}
		h4,h5,p{
			text-align: center;
			margin: 0;
		}
		.m-space{
			margin: 4px 0;
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
			font-size: 13px;
			font-weight: bold;
		}
		.text-right{
			text-align: right;
		}
		.name{
			text-align: left;
		}
	</style>
</head>
<body>
	<div class="main" id="printableArea">
		<h4>
			{{Session::get('client_name')}}
		</h4>
		<h5>
			GST : {{Session::get('gst_no')}}
		</h5>
		<h5>
			Cloakroom
		</h5>
		<h5>
			<span class="text">Slip No: <b style="font-size:18px;">{{ $print_data->slip_id }}</b></span>
		</h5>
		<div style="text-align:center;">
			<svg id="barcode"></svg>
		</div>
		

		
		<div class="table-div">
			<div class="w-50">

				<span class="text">Name: <b>{{ $print_data->name }}</b></span>

			</div>
			<div class="w-50">
				<span class="text">Mobile:<b>{{$print_data->mobile_no}}</b></span>
			</div>

		</div>
		
		<div class="table-div">
			<div class="w-50">
				<span class="text">PNR/ID No.: <b>{{$print_data->pnr_uid}}</b></span>
			</div>
			<div class="w-50">
				<span class="text">Paid Amount: <b>{{ $print_data->paid_amount }}</b></span>
			</div>
		</div>

		<div style="margin-bottom:10px;">
			<div>
				<span class="text">In Time: <b style="font-size: 18px;">{{date("h:i a, d M y",strtotime($print_data->checkin_date))}}</b></span>
			</div>
			<div>
				@if($print_data->is_late == 0) 
					<span class="text">Valid Upto: <b style="font-size: 18px;">{{date("h:i a, d M y",strtotime($print_data->checkout_date))}}</b></span>
				@else
					<span class="text">Valid Upto: <b style="font-size: 18px;">{{date("h:i a, d M y",strtotime($print_data->checkout_time))}}</b></span>
				@endif
			</div>
		</div>

		<table style="width:100%;margin: -1;" border="1" cellpadding="4" cellspacing="0" >
			<tbody>
				<tr>
					<td class="w-46">Description</td>
					<td class="w-20">Fee Type</td>
					<td class="w-16">No of luggage</td>
					<td class="w-16">Time Duration</td>
					<td class="w-16">Amount</td>
				</tr>
				<tr>
					<td class="w-46">For 12 hours or part there of</td>
					<td class="w-20">{{$rate_list->first_rate / 2}}/- Per Package</td>
					<td class="w-16"><strong>{{$print_data->no_of_bag}}</strong></td>
					<td class="w-16"><strong>{{$print_data->total_day * 24 }} Hours</strong></td>
					<td class="w-16">{{$print_data->total_amount}}</td>
				</tr>
			</tbody>
		</table>
	

		<div style="margin-top:10px;text-align: right;">
			<span style="text-align:right;font-weight: bold;">Note: We are not responsible for keeping eatable items inside your bag. Rats can be destroy your food and bags</span>
		</div>
		<div style="margin-top:10px;text-align: right;">
			Authorised Signatory : {{Auth::user()->name}}
		</div>

		<div style="margin-top:10px;text-align:center;">
			<p style="margin-top:10px;font-size: 16px;">
				<strong>Thanks Visit Again</strong>
			</p>
		</div>
	</div>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
	<script type="text/javascript">
		var bill_no = "{{$print_data->unique_id}}";
		console.log(bill_no);
		JsBarcode("#barcode", bill_no, {
			// format: "pharmacode",
			lineColor: "#000",
			width: 1,
			height: 40,
			displayValue: false
		});
		
	</script>
	<script type="text/javascript">
		
		window.onload = function(e){ 
		    var printContents = document.getElementById("printableArea").innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents; 
			// window.close();
		}
		document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'p') {
                location.reload();
            }else{
            	console.log('no');

            }
        });
	</script>
</body>
</html>


