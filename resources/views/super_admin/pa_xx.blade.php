@extends('admin.layout')

@section('main')

<div class="main">
    <div class="card shadow mb-4 p-4">  
        <div class="row">
            
            <div class="col-md-3 text-right">
                <a class="btn btn-sm btn-info" href="{{url('/superAdmin/clients/dashboard')}}">Dashboard</a>
            </div>
        </div>
        <hr>
        <div class="dt-layout-row dt-layout-table">
            <div class="dt-layout-cell">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr.no</th>
                            <th>Client Name</th>

                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$user.client_name}}</td>
                                <td>{{$user.name}}</td>
                                <td>{{$user.email}}</td>
                                <td>{{$user.password_check}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
