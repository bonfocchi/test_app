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
                      <h3>Image Library <small>(click to add image to page)</small></h3>
                      <?php $x = 0; ?>
                      @foreach ($images as $image)
                        <?php $image_url = presigned_url('thumbnails/'.$image->storage_file_name); ?>
                        <div class="picture">
                          <a role="button" tabindex="<?php echo $x; ?>" data-toggle="popover" title="{{ $image->title }}" >
                            <img src="{{ $image_url }}" alt="{{ $image->title }}" class="img-thumbnail img-responsive thumb-50 no-padding" />
                          </a>
                          <div class="popover-content hide" >

                            <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/picture/{{ $image->id }}" method="POST" class="pull-right mt-up">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              {{ method_field('DELETE') }}
                              <button type='submit' class='btn btn-danger btn-xs pull-right' onclick="return confirm('Are you sure you want to permanently delete this picture?');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                            </form>

                            <div>
                              <img src="{{ $image_url }}" class='thumb-m-180' />
                              <p>{{ $image->description }}</p>
                              <p>
                                <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/picture/{{ $image->id }}" method="POST" >
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                  <div class="form-group">
                                    <input type="text" class="form-control" name="x" placeholder="X-axis position">
                                  </div>

                                  <div class="form-group">
                                    <input type="text" class="form-control" name="y" placeholder="Y-axis position">
                                  </div>

                                  <div class="form-group">
                                    <input type="text" class="form-control" name="w" placeholder="Width">
                                  </div>

                                  <div class="form-group">
                                    <input type="text" class="form-control" name="h" placeholder="Height">
                                  </div>

                                  <button type='submit' class='btn btn-primary btn-xs'>Add to Page</button>
                                </form>
                              </p>
                            </div>
                          </div>
                        </div>
                        <?php $x++; ?>
                      @endforeach





                    </div>
                    <div id="menu1" class="tab-pane fade">
                      <h3>Upload Image</h3>

                      <form method="POST" action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/images" enctype='multipart/form-data'>
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
                        <label>Photo</label>
                        <input type="file" class="form-control-file" name="file" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">The file must be an image of the following type: jpeg or bmp or png.</small>
                      </div>

                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Upload</button>
                      </div>

                    </form>
                    </div>
                  </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                  Pictures in Page
                </div>

                <div class="panel-body">
                  <?php $x = 0; ?>
                  @foreach ($page_images as $image)
                  <?php $image_url = presigned_url('thumbnails/'.$image->storage_file_name); ?>

                    <div class="picture">
                      <a role="button" tabindex="<?php echo $x; ?>" data-toggle="popover" title="{{ $image->title }}" >
                        <img src="{{ $image_url }}" alt="{{ $image->title }}" class="img-thumbnail img-responsive thumb-50 no-padding" />
                      </a>
                      <div class="popover-content hide" >

                        <div>
                          <img src="{{ $image_url }}" class='thumb-m-180' />
                          <p>{{ $image->description }}</p>
                          <p>
                            <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/picture/{{ $image->id }}" method="POST" class="">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              {{ method_field('PUT') }}

                              <div class="form-group">
                                <input type="text" class="form-control" name="x" value="{{ $image->pivot->x }}">
                              </div>

                              <div class="form-group">
                                <input type="text" class="form-control" name="y" value="{{ $image->pivot->y }}">
                              </div>

                              <div class="form-group">
                                <input type="text" class="form-control" name="w" value="{{ $image->pivot->w }}">
                              </div>

                              <div class="form-group">
                                <input type="text" class="form-control" name="h" value="{{ $image->pivot->h }}">
                              </div>

                              <button type='submit' class='btn btn-success btn-xs'>Update</button>
                            </form>

                            <form action="/admin/catalogs/{{ $catalog->id }}/pages/{{ $page->id }}/picture/{{ $image->id }}/unlink" method="POST" class="pull-right mt-up2">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <button type='submit' class='btn btn-warning btn-xs pull-right'>Remove from page</button>
                            </form>

                          </p>
                        </div>
                      </div>
                    </div>
                    <?php $x++; ?>
                  @endforeach


                </div>
            </div>


        </div>
    </div>
</div>
@endsection
