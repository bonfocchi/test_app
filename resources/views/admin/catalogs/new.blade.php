@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                  New Catalog
                </div>

                <div class="panel-body">

                <form method="POST" action="/admin/catalogs/new">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="form-group">
                    <input name="name" class="form-control" placeholder="Catalog name" />
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create</button>
                  </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
