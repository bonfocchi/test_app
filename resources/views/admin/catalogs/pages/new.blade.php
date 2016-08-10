@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                  Add Page to {{ $catalog->name }} Catalog
                </div>

                <div class="panel-body">

                <form method="POST" action="/admin/catalogs/{{ $catalog->id }}/pages/new">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="form-group">
                    <input name="title" class="form-control" placeholder="Page title" />
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add</button>
                  </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
