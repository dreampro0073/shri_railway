@extends('admin.layout')

@section('main')

<div class="main">

    
    <div >
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr.no</th>
                    <th>Collected Cloak</th>
                    <th>Collected Pen</th>
                    <th>Total</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Today</th>
                    <td>{{$c_sum}}</td>
                    <td>{{$penalty_sum}}</td>
                    <td>{{$t_sum}}</td>
                    
                </tr>
                <tr>
                    <th>Total</th>
                    <td>{{$a_c_sum}}</td>
                    <td>{{$a_penalty_sum}}</td>
                    <td>{{$a_t_sum}}</td>
                </tr>
            </tbody>
        </table>        
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
