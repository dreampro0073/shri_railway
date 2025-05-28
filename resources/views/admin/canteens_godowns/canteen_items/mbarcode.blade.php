
<table class="table table-bordered" cellspacing="40" style="width:100%;">
	<thead>
		<tr>
			<th style="width:50;">Sn</th>
			<th>Item Name</th>
			<th>Price</th>
			<th style="width:300">Barcode</th>
		</tr>
	</thead>
	<tbody>
		<?php $count =1; ?>
		@foreach($items as $key => $item)
		<tr>

			<td>{{$count}}</td>
			<td>{{$item->item_name}}</td>
			<td>{{$item->price}}</td>
			<td>{{$item->barcodevalue}} </td>
		</tr>
		<?php $count++; ?>
		@endforeach
	</tbody>
</table>
	
	