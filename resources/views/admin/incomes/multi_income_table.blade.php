<table class="table table-condensed table-bordered table-striped ts" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Sn</th>
            <th>From</th>
           
            <th>Cash Amount</th>
            <th>UPI Amount</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($income->c_services as $index => $item): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td>{{$item['source']}}</td>
                <td>{{$item['cash_amount']}}</td>
                <td>{{$item['upi_amount']}}</td>
                <td>{{$item['total_amount']}}</td>
            </tr>
        <?php endforeach; ?>
        <tr>
	        <td colspan="2"><b>Total</b></td>
	        <td><b>{{$check->cash_amount}}</b></td>
	        <td><b>{{$check->upi_amount}}</b></td>
	        <td><b>{{$check->total_amount}}</b></td>
	    </tr>
    </tbody>
</table>