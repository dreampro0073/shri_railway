<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		@page { margin: 0; }
		body { margin: 0; }
		.main{
			width: 300px;
		}
		h4{
			
			font-size: 14px;
		}
		h4,h5,p{
			text-align: center;
			margin: 0;
		}
		.m-space{
			margin: 2px 0;
		}
		.table-div{
			display: table;
			width: 100%;
		}
		.table-div > div{
			display: table-cell;
			vertical-align: middle;
			padding: 2px;
		}
		.w-50{
			width: 50%;
		}
		.w-16{
			width: 16.66%;
		}
		td,span,p{
			font-size: 12px;
		}
		.text-right{
			text-align: right;
		}
		.name{
			text-align: left;
		}
	</style>
</head>
<body>
	<div id="printableArea" class="main">
		<h4>
			{{Session::get('client_name')}}
		</h4>
		
		<table style="width:100%;margin: -1;" border="1" cellpadding="4" cellspacing="0" >
            <thead>
               
                <tr>
                	<th>#</th>
                    <th>UPI</th>
                    <th>Cash</th>
                    <th>Total</th>
                   
                </tr>
            </thead>

            <tbody>
			@if(in_array(1, $service_ids))
			    <tr>
                   	<td>
                       <b>Sitting</b>
                   	</td> 
                   
                    <td>
                        {{$sitting_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$sitting_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$sitting_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
			@if(in_array(2, $service_ids))
			    <tr>
                   	<td>
                       <b>Cloakroom</b>
                   	</td> 
                   
                    <td>
                        {{$cloak_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$cloak_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$cloak_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
			@if(in_array(3, $service_ids))
			    <tr>
                   	<td>
                       <b>Canteen</b>
                   	</td> 
                   
                    <td>
                        {{$canteen_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$canteen_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$canteen_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
			@if(in_array(4, $service_ids))
			    <tr>
                   	<td>
                       <b>Massage</b>
                   	</td> 
                   
                    <td>
                        {{$massage_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$massage_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$massage_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
			@if(in_array(5, $service_ids))
				<tr>
                   	<td>
                       <b>Locker</b>
                   	</td> 
                   
                    <td>
                        {{$locker_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$locker_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$locker_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif			

			@if(in_array(7, $service_ids))
				<tr>
                   	<td>
                       <b>Recliner</b>
                   	</td> 
                   
                    <td>
                        {{$recliner_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$recliner_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$recliner_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
			@if(in_array(8, $service_ids))
				<tr>
                   	<td>
                       <b>PODs</b>
                   	</td> 
                   
                    <td>
                        {{$pod_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$pod_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$pod_data['total_collection']}}
                    </td>
                   
                </tr>				
                <tr>
                   	<td>
                       <b>Singal Suit Cabin</b>
                   	</td> 
                   
                    <td>
                        {{$singal_cabin_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$singal_cabin_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$singal_cabin_data['total_collection']}}
                    </td>
                   
                </tr>				
                <tr>
                   	<td>
                       <b>Double Beds</b>
                   	</td> 
                   
                    <td>
                        {{$double_bed_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$double_bed_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$double_bed_data['total_collection']}}
                    </td>
                   
                </tr>
			@endif
            @if(in_array(9, $service_ids))
                <tr>
                    <td>
                       <b>Scanning</b>
                    </td> 
                   
                    <td>
                        {{$scanning_data['total_shift_upi']}}
                    </td>
                    <td>
                        {{$scanning_data['total_shift_cash']}}
                    </td>
                    <td>
                        {{$scanning_data['total_collection']}}
                    </td>
                   
                </tr>
            @endif
				
                <tr>
                   	<td>
                       <b>Grand Total</b>
                   	</td> 
                   
                    <td>
                        {{$total_shift_upi}}
                    </td>
                    <td>
                        {{$total_shift_cash}}
                    </td>
                    <td>
                        {{$total_collection}}
                    </td>
                   
                </tr>

            </tbody>
        </table>    

        <h5 style="margin-top:16px;">
			{{Auth::user()->name}} -  {{date("d-m-Y")}}
		</h5>
		
		
	</div>
	<script type="text/javascript">
		window.onload = function(e){ 
		    var printContents = document.getElementById("printableArea").innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents; 
		}
	</script>
</body>
</html>
