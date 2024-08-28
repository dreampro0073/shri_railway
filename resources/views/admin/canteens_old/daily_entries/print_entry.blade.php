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
		<div class="table-div">
			<div class="w-50">
				<span class="text">Bill No: {{ $print_data->unique_id }}</span>
			</div>
			<div class="w-50 text-right">
				<span class="text">Date: <?php echo date("d-m-Y"); ?></span>
			</div>
		</div>
		<span class="name">Name : {{$print_data->name}}</span>
		<div class="table-div">
			<div class="w-50">
				<span class="text text-right">Mobile: {{$print_data->mobile}}</span>
			</div>
		</div>
		
		<table style="width:100%;margin: -1;" border="1" cellpadding="4" cellspacing="0" >
			<tr>
				<td class="w-45">Item</td>
				<td class="w-16">Price</td>
				<td class="w-16">Quantity</td>
				<td class="w-16">Amount</td>
			</tr>
			@if(sizeof($print_data->products) > 0)
				@foreach($print_data->products as $product)
				<tr>
					<td class="w-45">{{$product->item_name}}</td>
					<td class="w-16">{{$product->price}}</td>
					<td class="w-16">{{$product->quantity}}</td>
					<td class="w-16">{{$product->paid_amount}}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="2" class="w-61">Total</td>
					<td class="w-16">{{$print_data->total_quantity}}</td>
					<td class="w-16">{{$print_data->total_amount}}</td>
				</tr>
			@endif
		</table>
		<div style="margin-top: 20px;text-align: center;">
			<span style="font-weight: bold;">** Non Refundable **</span>
		</div>
		<div style="margin-top:10px;text-align:center;">
			<p style="text-align:right;">Authorised Signatory : {{Auth::user()->name}}</p>
			<p style="margin-top:10px;font-size: 16px;">
				<strong>Thanks Visit Again</strong>
			</p>
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
