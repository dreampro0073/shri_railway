@extends('admin.layout')

@section('main')
    <div class="main"> 
      
        <div class="card shadow mb-4 p-4">    
           
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead >
                            <tr>
                                <th>S.no</th>
                                <th>Mobile</th>
                                <th>
                                    Otp
                                </th>
                                <th>Created At</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->mobile}}</td>
                                <td>{{$item->otp}}</td>
                                <td>{{date("d-m-Y h:i A",strtotime($item->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div>     
        </div>
    </div>
@endsection
    
    