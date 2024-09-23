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
			<th>Branch</th>
			<th>From</th>
			<th>To</th>
		</tr>
		<tr>
			<th>{{$data["branch"]->client_name}}</th>
			<th>{{date("d M Y", strtotime($data["from_date"]))}}</th>
			<th>{{date("d M Y", strtotime($data["to_date"]))}}</th>
		</tr>
	</table>
	<h3 class="page-title">Incomes</h3>
	<table style="width:100%;" cellpadding="4" cellspacing="0">
		<thead>
            <tr>
                <th>Sn</th>
                <th>Date</th>
                <th>Back Balance</th>
                <th>Collected Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
	        @if(sizeof($data['incomes']) > 0)
	        	@foreach($data["incomes"] as $index => $income){
		            <tr>
		                <td>{{$index+1}}</td>
		                <td>{{date("d M Y", strtotime($income->date))}}</td>
		                <td>{{$income->back_balance}}</td>
		                <td>{{$income->total_amount}}</td>
		                <td>
		                    <span>{{$income->all_total}}</span>
		                </td>
		            </tr>
		            @if(sizeof($income->multiple_income) > 0)
		            	<tr>
		            		<td colspan="5">
								@include("admin/incomes/multi_income_table")
							</td>
						</tr>
					@endif
		        @endforeach
		    @endif
        </tbody>
    </table>
    <table style="width:100%;" cellpadding="4" cellspacing="0">
    	<tr>
			<td>Total Cash</td>
			<td>{{$data['total_cash']}}</td>
		</tr>
		<tr>
			<td>Total UPI</td>
			<td>{{$data['total_upi']}}</td>
		</tr>
    </table>
    <table style="width:100%;" cellpadding="4" cellspacing="0">
    	<tbody>
		   	<tr>
	        	<th colspan="5"><h3 class="page-title">Expenses</h3></th>
	        </tr>
            <tr>
                <th>Sn</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th colspan="2">Remarks</th>
            </tr>
        	@if(sizeof($data['expenses']) > 0)
	        	@foreach($data["expenses"] as $index => $expense)
			        <tr>
			            <td>{{$index+1}}</td>
			            <td>{{date("d M Y", strtotime($expense->date))}}</td>
			            <td>{{$expense->total_amount}}</td>
			            <td colspan="2" style="font-size: 11px">{{$expense->remarks}}</td>
			        </tr>
	            @endforeach
	        @endif
	        <tr>
	        	<th colspan="5"><h3 class="page-title">Summary</h3></th>
	        </tr>
			<tr>
				<th>Total Income</th>
				<th>Total Expenses</th>
				<th colspan="3">Balance</th>
			</tr>
			<tr>
				<th>{{$data["total_incomes"]}}</th>
				<th>{{$data["total_expenses"]}}</th>
				<th colspan="3">{{$data["total_incomes"] - $data["total_expenses"] }}</th>
			</tr>
		</tbody>
	</table>

	<h4 style="text-align:right;">
		{{Auth::user()->name}}
		<br>
		<span style="font-size:14px;">{{date("d M Y")}}</span>
	</h4> 
</body>
</html>