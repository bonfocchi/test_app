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

                        <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}" method="POST" class="pull-right">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          {{ method_field('DELETE') }}
                          <button class="btn btn-danger btn-xs m-l-10 pull-right">Delete</button>
                        </form>

                        <a href="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/images" class="btn btn-warning m-l-10 btn-xs pull-right">Manage images</a>
                        <a href="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}" class="btn btn-info btn-xs m-l-10 pull-right">Rename</a>

                        <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/reposition" method="POST" class="pull-right">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          {{ method_field('PATCH') }}
                          <div class="form-group mu-7 mb-0">
                              <select class="form-control" id="exampleSelect1" name="position" onchange="this.form.submit();">
                                <?php for($x=1 ; $x <= $last_position; $x++){ ?>
                                <option <?php if($x == $page->position){ ?> selected="selected" disabled='disabled' <?php } ?>>
                                  <?php echo $x; ?>
                                </option>
                                <?php } ?>
                              </select>
                            </div>
                        </form>
                        <label class="pull-right">Position: &nbsp;</label>


                      </li>
                    @endforeach
                  </ul>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
