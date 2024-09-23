<table style="width:100%;" cellpadding="4" cellspacing="0">
	<tbody>
		<tr>
			<th>Income Type</th>
			<th>Amount</th>
		</tr>
		@foreach($income->multiple_income as $item)
		<tr>
			<td>{{$item->show_income_type}}</td>
			<td>{{$item->amount}}</td>
		</tr>
		@endforeach
		<tr>
			<td>Total Cash</td>
			<td>{{$income->total_cash}}</td>
		</tr>
		<tr>
			<td>Total UPI</td>
			<td>{{$income->total_upi}}</td>
		</tr>
	</tbody>
</table>