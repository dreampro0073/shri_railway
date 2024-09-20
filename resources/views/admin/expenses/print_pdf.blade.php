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
	</style>
</head>
<body>
	<h3 style="text-align:center;">Expense - {{$expense->date}}</h3>
	<table style="width:100%;" cellpadding="4" cellspacing="0">
		<tr>
			<td>Branch</td>
			<td>{{$expense->client_name}}</td>

		</tr>
		<tr>
			<td>Date</td>
			<td>{{$expense->date}}</td>
			
		</tr>
		<tr>
			<td>Total Amount</td>
			<td>{{$expense->total_amount}}</td>
			
		</tr>
		
	</table>
	<hr>
	@if(sizeof($expense->multiple_expense) > 0)
		<table style="width:100%;" cellpadding="4" cellspacing="0">
			<thead>
				<tr>
					<th>Give To</th>
					<th>Remarks</th>
					<th>Type</th>
					<th>Amount</th>
				</tr>
			</thead>

			<tbody>
				@foreach($expense->multiple_expense as $item)
				<tr>
					<td>{{$item->expense_type}}</td>
					<td>{{$item->remarks}}</td>
					<td>
						@if($item->expense_account == 1)
							Cash
						@elseif($item->expense_account == 2)
							UPI
						@else
							
						@endif
					</td>
					<td>{{$item->total_amount}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<h4 style="text-align:right;">
		{{Auth::user()->name}}
		<br>
		<span style="font-size:14px;">{{date("d M Y")}}</span>
	</h4>

</body>
</html>