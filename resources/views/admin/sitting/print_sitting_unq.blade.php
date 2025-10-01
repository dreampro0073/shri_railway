<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Print</title>
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
        .m-space{ margin: 2px 0; }
        .table-div{
            display: table;
            width: 100%;
        }
        .table-div > div{
            display: table-cell;
            vertical-align: middle;
            padding: 2px;
        }
        .w-50{ width: 50%; }
        .w-16{ width: 16.66%; }
        td,span,p{
            font-size: 12px;
            font-weight: bold;
        }
        .text-right{ text-align: right; }
        .name{ text-align: left; }
    </style>
</head>
<body>
    <div id="printableArea" class="main">
        <h4>{{ Session::get('client_name') }}</h4>
        <p style="padding:0 15px;text-align: center;">
            {!! Session::get('address') !!}
        </p>
        <h5>{{ Session::get('gst_no') }}</h5>
        <h5>SITTING</h5>

        <div style="text-align: center;">
            <b style="font-size: 18px;">Slip ID : {{ $print_data->slip_id }}</b>
        </div>

        @if($type == 1)
        <div style="text-align:center;">
            <svg id="barcode"></svg>
        </div>
        @endif

        <div class="table-div">
            <div class="w-50">
                <span class="name">Name : {{ $print_data->name }}</span>
            </div>
            <div class="w-50">
                <span class="text text-right">Date: {{ date("d-m-Y",strtotime($print_data->date)) }} </span>
            </div>
        </div>

        <div class="table-div">
            <div class="w-50">
                <span class="text">PNR/ID No.: {{ $print_data->pnr_uid }}</span>
            </div>
            <div class="w-50">
                <span class="text">Mobile: {{ $print_data->mobile_no }}</span>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <span class="text" style="font-size: 18px;">In Time: <b>{{ date("h:i A",strtotime($print_data->check_in)) }}</b></span>
            <br>
            <span class="text" style="font-size: 18px;">Valid upto: <b>{{ date("h:i A",strtotime($print_data->check_out)) }}</b></span>
        </div>

        <table style="width:100%;" border="1" cellpadding="4" cellspacing="0" >
            <tr>
                <td>Description</td>
                <td>Fee Type</td>
                <td>Quantity</td>
                <td>Amount</td>
            </tr>
            <tr>
                <td>For 1st hours or part thereof</td>
                <td>Adult {{ $rate_list->adult_rate }}/- Per person</td>
                <td rowspan="{{ $print_data->is_sec_rate ? 2 : 1 }}">{{ $print_data->no_of_adults }}</td>
                <td>{{ $print_data->adult_f_amount }}</td>
            </tr>
            @if($print_data->is_sec_rate)
            <tr>
                <td>Per Extended hours or part thereof</td>
                <td>Adult {{ $rate_list->adult_rate_sec }}/- Per person</td>
                <td>{{ $print_data->adult_s_amount }}</td>
            </tr>
            @endif

            <tr>
                <td>1st hours or part thereof</td>
                <td>Age 5 to 12, {{ $rate_list->child_rate }}/- Per child</td>
                <td rowspan="{{ $print_data->is_sec_rate ? 2 : 1 }}">{{ $print_data->no_of_children }}</td>
                <td>{{ $print_data->children_f_amount }}</td>
            </tr>
            @if($print_data->is_sec_rate)
            <tr>
                <td>Per Extended hours or part thereof</td>
                <td>Age 5 to 12, {{ $rate_list->child_rate_sec }}/- Per child</td>
                <td>{{ $print_data->children_s_amount }}</td>
            </tr>
            @endif

            <tr>
                <td>Age Below 5 Years</td>
                <td>Free</td>
                <td>{{ $print_data->no_of_baby_staff }}</td>
                <td>--</td>
            </tr>
            <tr>
                <td>Hour: <b>{{ $print_data->hours_occ }}</b></td>
                <td><b>Total</b></td>
                <td>{{ $print_data->total_member }}</td>
                <td>{{ $total_amount }}</td>
            </tr>
        </table>

        <div style="margin-top: 20px;text-align: center;">
            <span style="font-weight: bold;">** Non Refundable **</span>
        </div>
        <div style="margin-top:10px;text-align: right;">
            Authorised Signatory : {{ Auth::user()->name }}
        </div>

        <div style="margin-top:10px;text-align:center;">
            <p><b>Please submit your slip at the counter at the time of checkout.</b></p>
            <p><b>*Note : Passengers must protect their own Mobile and luggage.</b></p>
            <p style="margin-top:10px;font-size: 16px;"><strong>Thanks Visit Again</strong></p>
            <span style="font-size:12px;line-height:1.2;display:inline-block;margin-top:10px;">
                2024 &copy; Aadhyasri Web Solutions, aadhyasriwebsolutions@gmail.com
            </span>
        </div>  
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        var bill_no = "{{ $print_data->unique_id }}";
        JsBarcode("#barcode", bill_no, {
            lineColor: "#000",
            width: 1,
            height: 40,
            displayValue: false
        });
    </script>

    <script>

        var client_id = "{{Auth::user()->client_id}}";
        
        window.onload = function() {
            var slipId = "{{ $print_data->id }}";
            if(client_id == 1){
                if (sessionStorage.getItem("printed_" + slipId)) {
                    alert("This slip has already been printed. Only 1 copy is allowed.");
                    return;
                }
                var printContents = document.getElementById("printableArea").innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;

                sessionStorage.setItem("printed_" + slipId, true);
            }else{
                var printContents = document.getElementById("printableArea").innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        };
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                alert("Manual printing is disabled. Please use auto-print only.");
            }
        });
    </script>
</body>
</html>
