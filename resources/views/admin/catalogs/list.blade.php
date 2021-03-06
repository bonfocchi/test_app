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
                      <li class="list-group-item">
                        {{ $catalog->name }}

                        <form action="/admin/catalogs/{{ $catalog->id }}" method="POST" class="pull-right">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          {{ method_field('DELETE') }}
                          <button class="btn btn-danger btn-xs m-l-10 pull-right">Delete</button>
                        </form>

                        <a href="/admin/catalogs/{{ $catalog->id }}/pages" class="btn btn-warning m-l-10 btn-xs pull-right">Manage pages</a>
                        <a href="/admin/catalogs/{{ $catalog->id }}" class="btn btn-info btn-xs m-l-10 pull-right">Rename</a>


                      </li>
                    @endforeach
                  </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
