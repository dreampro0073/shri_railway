<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		@page { margin: 0; }
		body { margin: 0; }
		.main{
			width: 300px;
			height: 100%;
		}
		h4{
			
			font-size: 14px;
		}
		h4,h5,p{
			text-align: center;
			margin: 0;
		}
		
		.w-32{
			width: 32%;
		}
		td,span,p{
			font-size: 13px;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div class="main" id="printableArea">
		<h4>
			{{date("d-m-Y",strtotime('now'))}}
		</h4>
		<table style="width:100%;margin: -1;" border="1" cellpadding="4" cellspacing="0" >
			<tbody>
				<tr>
					<td class="w-32">Sl No.</td>
					<td class="w-32">Slip Id</td>
					<td class="w-32">Begs</td>
					
				</tr>
				@if(sizeof($l_entries) > 0)
					@foreach($l_entries as $key => $item)
						<tr>
							<td class="w-32">{{$key+1}}</td>
							<td class="w-32">{{$item->slip_id}}</td>
							<td class="w-32">{{$item->no_of_bag}}</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
</body>
</html>


