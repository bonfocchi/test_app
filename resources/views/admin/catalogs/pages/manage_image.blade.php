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

                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Upload Image</a></li>
                    <li><a data-toggle="tab" href="#menu1">Select from library</a></li>
                  </ul>

                  <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                      <h3>Upload Image</h3>
                      <p>In progress..</p>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                      <h3>Image Library</h3>
                      <p>In progress..</p>
                    </div>
                  </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
