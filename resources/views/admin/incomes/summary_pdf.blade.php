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
	
	<table style="width:100%;" cellpadding="4" cellspacing="0">
		<tr>
			<td>Branch</td>
			<td>{{$data["branch"]->client_name}}</td>
		</tr>
		<tr>
			<td>To</td>
			<td>{{$data["to_date"]}}</td>
		</tr>		
		<tr>
			<td>From</td>
			<td>{{$data["from_date"]}}</td>
		</tr>
	</table>	

	<table style="width:100%;" cellpadding="4" cellspacing="0">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Date</th>
                <th>Branch</th>
                <th>All Amount</th>
                <th>Total Amount</th>
                <th>Back Balance</th>
            </tr>
        </thead>
        <tbody>
	        @if(sizeof($data['incomes']) > 0)
	        	@foreach($data["incomes"] as $index => $income){
		            <tr>
		                <td>{{$index+1}}</td>
		                <td>{{date("d M Y", strtotime($income->date))}}</td>
		                <td>{{$income->client_name}}</td>
		                <td>
		                    <span>{{$income->all_total}}</span>
		                </td>
		                <td>{{$income->total_amount}}</td>
		                <td>{{$income->back_balance}}</td>
		            </tr>
		        @endforeach
		    @endif
        </tbody>
	</table>
	<table style="width:100%;" cellpadding="4" cellspacing="0">
        <thead>
            <tr>
                <th>Sn</th>
                <th>Date</th>
                <th>Branch</th>
                <th>Total Amount</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
        	@if(sizeof($data['expenses']) > 0)
	        	@foreach($data["expenses"] as $index => $expense)
			        <tr>
			            <td>{{$index+1}}</td>
			            <td>{{date("d M Y", strtotime($expense->date))}}</td>
			            <td>{{$expense->client_name}}</td>
			            <td>{{$expense->total_amount}}</td>
			            <td style="font-size: 11px">{{$expense->remarks}}</td>
			        </tr>
	            @endforeach
	        @endif
        </tbody>
    </table>
    <table style="width:100%;" cellpadding="4" cellspacing="0">
		<tr>
			<td>Total Income</td>
			<td>{{$data["total_incomes"]}}</td>
		</tr>
		<tr>
			<td>Total </td>
			<td>{{$data["total_expenses"]}}</td>
		</tr>
		<tr>
			<td>Balance</td>
			<td>{{$data["total_incomes"] - $data["total_expenses"] }}</td>
		</tr>
	</table>

	<h4 style="text-align:right;">
		{{Auth::user()->name}}
		<br>
		<span style="font-size:14px;">{{date("d M Y")}}</span>
	</h4>

	

</body>
</html>