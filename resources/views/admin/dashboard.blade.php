@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>You are logged in as an Admin!</p>
                    <p>Use the green button to go to catalogs.</p>
                    <a href="/admin/catalogs" class="btn btn-success btn-sm pull-right">Catalogs</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
