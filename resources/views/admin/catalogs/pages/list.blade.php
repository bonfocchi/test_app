@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('admin.alert')

            <div class="panel panel-default">
                <div class="panel-heading">
                  {{ $catalog->name }} Catalog Pages
                  <a href="/admin/catalogs/{{ $catalog->id }}/pages/new" class="btn btn-primary btn-xs pull-right">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    Add Page
                  </a>
                </div>

                <div class="panel-body">

                  <ul class="list-group">
                    @foreach ($pages as $page)
                      <li class="list-group-item">
                        {{ $page->title }}

                        <form action="/admin/catalogs/{{ $catalog->id }}" method="POST" class="pull-right">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          {{ method_field('DELETE') }}
                          <button class="btn btn-danger btn-xs m-l-10 pull-right disabled" disabled="disabled">Delete</button>
                        </form>

                        <a href="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/images" class="btn btn-warning m-l-10 btn-xs pull-right disabled" disabled="disabled">Manage images</a>
                        <a href="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}" class="btn btn-info btn-xs m-l-10 pull-right">Rename</a>

                      </li>
                    @endforeach
                  </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
