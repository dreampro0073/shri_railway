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
		.table {
	        width: 100%;
	        border-collapse: collapse;
	        margin-bottom: 20px;
	    }
	</style>
</head>
<body>
	<?php $income = $data["income"]; $expenses = $data["expenses"]?>

<div class="row mt-3 mb-3">
    <div class="col-md-4">
        <h2 class="page-title">Summary (Date : {{$data["date"]}}, Branch : {{$income->client_name}})</h2>
    </div>
    <div class="col-md-8">
        <small style="color: red">*Note: Expenses and previous balance are subtracted from only "Income Cash Amount"!*</small>
    </div>
</div>

    <table class="table table-striped table-bordered">
        <thead>
        	<tr>
        		<th rowspan="2">Previous Balance</th>
        		<th rowspan="2">Total Expense</th>
        		<th colspan="3">Income</th>
        		<th colspan="3">Balance</th>
        	</tr>
            <tr>
                <td>Cash Amount</td>
                <td>UPI Amount</td>
                <td>Total Amount</td>
                <td>Cash</td>
                <td>UPI</td>
                <td>Total</td>
            </tr>
            <tr>
                <th>{{$income->back_balance }}</th>
                <th>{{$data['total_expenses'] }}</th>
                <th>{{$income->cash_amount }}</th>
                <th>{{$income->upi_amount }}</th>
                <th>{{$income->total_amount }}</th>
                <th style="color: red;">{{$income->cash_amount - $income->back_balance - $data['total_expenses'] }}</th>
                <th style="color: red;">{{$income->upi_amount }}</th>
                <th style="color: red;"><b>{{$income->upi_amount + $income->cash_amount - $income->back_balance - $data['total_expenses'] }}</b></th>
            </tr>
        </thead>
    </table>
<hr>

<div class=>
    <h2 class="page-title">Income</h2>
    <h4>Total Income: {{$income->total_amount}}</h4>          
</div>
<table class="table table-condensed table-bordered table-striped">
    <thead>
        <tr>
            <th>Sn</th>
            <th>Source</th>
            <th>Total Cash</th>
            <th>Total UPI</th>
            <th>Total Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($income->multiple_income) > 0): ?>
            <?php foreach ($income->multiple_income as $index => $item): ?>
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$item->source}}</td>
                    <td>{{$item->cash_amount}}</td>
                    <td>{{$item->upi_amount}}</td>
                    <td>{{$item->total_amount}}</td>
                    <td>{{$item->remarks}}</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<hr>

<div>
    <h2 class="page-title">Expense</h2>
    <h4>Total Expense: {{$data["total_expenses"]}}</h4>
    </div>
</div>


<?php if (count($expenses) > 0): ?>
    <?php foreach ($expenses as $index => $expense): ?>
        
        {{$index + 1}} . (Expenses Amount : {{$expense['total_amount']}})<br>
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


    <?php endforeach; ?>
<?php endif; ?>


	<h4 style="text-align:right;">
		{{Auth::user()->name}}
		<br>
		<span style="font-size:14px;">{{date("d M Y")}}</span>
	</h4> 
</body>
</html>