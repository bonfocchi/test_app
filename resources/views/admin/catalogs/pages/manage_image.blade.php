@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            @include('admin.alert')

            <div class="panel panel-default">
                <div class="panel-heading">
                  Manage images on Page "{{ $page->title }}" from the {{ $catalog->name }} Catalog
                </div>

                <div class="panel-body">

                  <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#home">Select from library</a></li>
                    <li><a data-toggle="tab" href="#menu1">Upload Image</a></li>
                  </ul>

                  <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                      <h3>Image Library</h3>
                      <img src="..." alt="..." class="img-thumbnail img-responsive">


                      <p>In progress..</p>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                      <h3>Upload Image</h3>

                      <form method="POST" action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/images">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                      <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Image title">
                      </div>

                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Image description" rows="3"></textarea>
                      </div>

                      <div class="form-group">
                        <label>File input</label>
                        <input type="file" class="form-control-file" name="file" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                      </div>

                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Upload</button>
                      </div>

                    </form>
                    </div>
                  </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
