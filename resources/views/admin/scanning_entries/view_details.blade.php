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
			{{$print_data->client_name}}
		</h4>
		<p style="padding:0 15px;text-align: center;">
			{{$print_data->client_address}}
		</p>
		<h5>
			{{$print_data->gst}}
		</h5>
		<h5>
			Scanning Details
		</h5>
		<div style="text-align: center;">
			<span class="text">Slip ID : {{ $print_data->slip_id }}</span>
		</div>
		<div style="text-align: center;">
			<span class="text">Name : {{$print_data->name}}</span>		

		</div>
		<div style="text-align: center;">
			<span class="text">Date: <?php echo date("d-m-Y",strtotime($print_data->date)); ?></span>	
		</div>
		<div style="text-align: center;">
			<span class="text">Item Type: {{$print_data->item_type_name}}</span>	
		</div>
		<div style="text-align: center;">
			<span class="text">Type: {{$print_data->item_type_name}}</span>	
		</div>
		<div style="text-align: center;">
			<span class="text">No of Item: {{$print_data->no_of_item}}</span>	
		</div>
	
	</div>
</body>
</html>
