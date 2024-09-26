<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		table{
			width: 100%;
			border: 1px solid #000;
		}
		table tr th,td{
			border: 1px solid #000;
		}
		table.ts tr th td{
			text-align: center;
		}
	</style>
</head>
<body>
	<h3 style="text-align:center;">Income - {{date("d-m-Y",strtotime($check->date))}}</h3>
	<table style="width:100%;" cellpadding="4" cellspacing="0">
		<tr>
			<td>Branch</td>
			<td>{{$check->client_name}}</td>

		</tr>
		<tr>
			<td>Date</td>
			<td>{{date("d-m-Y",strtotime($check->date))}}</td>
		</tr>
		<tr>
			<td>Total Amount</td>
			<td>{{$check->total_amount}}</td>	
		</tr>
		<tr>
			<td>Back Balance</td>
			<td>{{$check->back_balance}}</td>	
		</tr>
		<tr>
			<td>Day Total Amount</td>
			<td>{{$check->day_total}}</td>
			
		</tr>
	</table>
	<hr>
	@if(sizeof($income->c_services) > 0)
		@include('admin.incomes.multi_income_table')
	@endif

    <h4 style="text-align:right;">
		{{Auth::user()->name}}
		<br>
		<span style="font-size:14px;">{{date("d M Y")}}</span>
	</h4>

</body>
</html>