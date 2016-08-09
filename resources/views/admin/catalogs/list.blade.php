@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('admin.alert')

            <div class="panel panel-default">
                <div class="panel-heading">
                  Catalogs
                  <a href="/admin/catalogs/new" class="btn btn-primary btn-xs pull-right">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    New Catalog
                  </a>
                </div>

                <div class="panel-body">

                  <ul class="list-group">
                    @foreach ($catalogs as $catalog)
                      <li class="list-group-item"><a href="/admin/catalogs/{{ $catalog->id }}">{{ $catalog->name }}</a></li>
                    @endforeach
                  </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
