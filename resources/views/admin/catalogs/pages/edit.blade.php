@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            @include('admin.alert')

            <div class="panel panel-default">
                <div class="panel-heading">
                  Edit Page "{{ $page->title }}" from the {{ $catalog->name }} Catalog
                </div>

                <div class="panel-body">

                <form method="POST" action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  {{ method_field('PATCH') }}

                  <div class="form-group">
                    <input name="title" class="form-control" placeholder="Catalog name" value="{{ $page->title }}" />
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Rename</button>
                  </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
