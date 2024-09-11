
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
		@foreach($items as $key => $item)
		<tr>

			<td>{{$key++}}</td>
			<td>{{$item->item_name}}</td>
			<td>{{$item->price}}</td>
			<td>{{$item->barcodevalue}} </td>
		</tr>
		@endforeach
	</tbody>
</table>
	
	