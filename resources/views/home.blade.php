@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                  <h1> My Blogs Post </h1>
                  <div class="text-right">
                    <button class="btn btn-primary btn-new-blog"> New Blog </button>
                  </div>
                  @if($blogs->count() > 0)
                    <ul class="list-group mt-4">
                      @foreach($blogs as $key => $item)
                        <li class="list-group-item" aria-current="true">
                          <img src="{{ $item->image_url }}" height="200" width="500"/>
                          <h5> {{ $item->title }} </h5>
                          <p class="mb-2"> {{ $item->description }} </p>
                          <div class="">
                            <button t={{$item->title}} d={{ $item->description }} blog_id="{{ $item->id }}" class="btn btn-outline-primary btn-edit-blog"> Edit </button>
                            <button t={{$item->title}} d={{ $item->description }} blog_id="{{ $item->id }}" class="btn btn-outline-danger btn-delete-blog"> Delete </button>
                          </div>
                        </li>
                      @endforeach
                      
                    </ul>
                  @endif
              
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-blog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-blog" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Create Blog</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label> Title </label>
            <input class="form-control" name="title" required/>
          </div>
          <div class="form-group mb-2">
            <label> Description </label>
            <textarea class="form-control" rows="5" name="description" required ></textarea>
          </div>
          <div class="form-group mb-2">
            <label> Image </label>
            <input type="file" class="form-control" rows="5" name="image" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script>
    $(function(e) {
      var blog_update_id = false
      $('.btn-new-blog').on('click', function(e) {
        blog_update_id = false
        $('.modal-blog').find('.modal-title').text('Create Blog')
        $('.modal-blog').modal('show')
      })

      $('.form-blog').on('submit', function(e) {
        e.preventDefault()
        var formData = new FormData(this)
        var url = '/blogs'
        var method = 'POST'
        if(blog_update_id)
        {
          url = '/blogs/'+ blog_update_id
          formData.append('_method', "PUT")
        }
       
        $.ajax({
          url : url,
          method : method,
          data : formData,
          processData : false,
          contentType : false,
          success : function(response)
          {
            if(response.success)
            {
              window.location.reload()
            }
          },
          error : function(error)
          {
            console.log('error')
          }
        })
      })

      $('.btn-edit-blog').on('click', function(e) { 
        var me = $(this)
        blog_update_id = me.attr('blog_id')
        $('.form-blog').find('[name="title"]').val(me.attr('t'))
        $('.form-blog').find('[name="description"]').val(me.attr('d'))
        $('.modal-blog').find('.modal-title').text('Update Blog')
        $('.modal-blog').modal('show')
      })

      $('.btn-delete-blog').on('click', function(e) {
        $.ajax({
          url : '/blogs/' + $(this).attr('blog_id'),
          method : 'DELETE',
          success : function(response)
          {
            if(response.success)
            {
              window.location.reload()
            }
          }
        })
      })
      
    })
  </script>

@endsection

