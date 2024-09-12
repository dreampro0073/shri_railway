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
	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<h4>
			{{Session::get('client_name')}}
		</h4>
		<p style="padding:0 15px;text-align: center;">
			{!! Session::get('address') !!}
		</p>
		<h5>
			{{Session::get('gst_no')}}
		</h5>
		<h5>
			SITTING
		</h5>
		<div style="text-align: center;">
			<b style="font-size: 18px;">
				Slip ID : {{ $print_data->slip_id }}
			</b>
		</div>
		@if($type == 1)
		<div style="text-align:center;">
			<svg id="barcode"></svg>
		</div>
		@endif
		<div class="table-div">
			<div class="w-50">
				<span class="name">Name : {{$print_data->name}}</span>		

			</div>
			<div class="w-50">
				<span class="text text-right">Date: <?php echo date("d-m-Y"); ?></span>	

			</div>
		</div>

		
		<div class="table-div">
			<div class="w-50">
				<span class="text">PNR/ID No.: {{$print_data->pnr_uid}}</span>
			</div>
			<div class="w-50">
				<span class="text">Mobile: {{$print_data->mobile_no}}</span>
			</div>
		</div>
		<div style="margin-bottom: 20px;">
			
				<span class="text" style="font-size: 18px;">In Time: <b>{{date("h:i A",strtotime($print_data->check_in))}}</b></span>
				<br>
			
				<span class="text" style="font-size: 18px;">Valid upto: <b>{{date("h:i A",strtotime($print_data->check_out))}}</b></span>
			
		</div>
		<table style="width:100%;margin: -1;" border="1" cellpadding="4" cellspacing="0" >
			<tr>
				<td class="w-46">Description</td>
				<td class="w-20">Fee Type</td>
				<td class="w-16">Quantity</td>
				<td class="w-16">Amount</td>
			</tr>
			<tr>
				<td class="w-46">For 1st hours or part there of</td>
				<td class="w-20">Adult {{$rate_list->adult_rate}}/- Perpersal</td>
				<td class="w-16" rowspan="<?php if($print_data->is_sec_rate){ echo '2'; }  ?>"> {{$print_data->no_of_adults}}</td>
				<td class="w-16">{{$print_data->adult_f_amount}}</td>
			</tr>
			@if($print_data->is_sec_rate)
			<tr>
				<td class="w-46">Per Exterded hours or part there of</td>
				<td class="w-20">Adult {{$rate_list->adult_rate_sec}}/- Perpersal</td>
				<td class="w-16">{{$print_data->adult_s_amount}}</td>
			</tr>
			@endif
			<tr>
				<td class="w-46">1st hours of part there of</td>
				<td class="w-20">Age 5 to 12, {{$rate_list->child_rate}}/ Perchilores</td>
				<td class="w-16" rowspan="<?php if($print_data->is_sec_rate){ echo '2'; }  ?>">{{$print_data->no_of_children}}</td>
				<td class="w-16">{{$print_data->children_f_amount}}</td>
			</tr>
			@if($print_data->is_sec_rate)
			<tr>
				<td class="w-46">Per Exterded hours or part there of</td>
				<td class="w-20">Age 5 to 12, {{$rate_list->child_rate_sec}}/ Perchilores</td>
				<td class="w-16">{{$print_data->children_s_amount}}</td>
			</tr>
			@endif
			<tr>
				<td class="w-46">Age Below 5 Years</td>
				<td class="w-20">Free</td>
				<td class="w-16">{{$print_data->no_of_baby_staff}}</td>
				<td class="w-16">--</td>
			</tr>
			<tr>
				<td colspan="1">Hour: <b>{{$print_data->hours_occ}}</b></td>

				<td colspan="1"><b>Total</b></td>
				<td class="w-20">{{$print_data->total_member}}</td>
				<td class="w-16">{{$total_amount}}</td>
			</tr>
		</table>
		<div style="margin-top: 20px;text-align: center;">
			<span style="text-align:center;font-weight: bold;">** Non Refundable **</span>
		</div>
		<div style="margin-top:10px;text-align: right;">
			Authorised Signatory : {{Auth::user()->name}}
		</div>
		<div style="margin-top:10px;text-align:center;">
			<p>
				<b>Please submit your slip at the counter at the time of checkout.</b>
			</p>
			<p>
				<b>*Note : Passengers must protect their own Mobile and luggage.</b>
			</p>

			<p style="margin-top:10px;font-size: 16px;">
				<strong>Thanks Visit Again</strong>
			</p>
			<span style="font-size:10px;line-height:1.2;display: inline-block;margin-top:10px;">
		        2024 &copy; Shri Web Technology, shri.webtechnology@gmail.com
		    </span>
			
		</div>	
	</div>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
	<script type="text/javascript">
		var bill_no = "{{$print_data->unique_id}}";
		// console.log(bill_no);
		JsBarcode("#barcode", bill_no, {
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
		}
	</script>
</body>
</html>
