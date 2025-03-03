@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">All Banner</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Add Banner</button>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                   
                                    <div class="card-body">
        
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Banner Image</th>
                                                <th>Banner URL</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
        
        
                                            <tbody>
                                            @foreach($banner as $key=> $data)    
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td><img src="{{ asset($data->image)}}" alt="Banner image" style="width:70px; height:40px;"></td>
                                                <td>{{$data->url}}</td>
                                                
                                                <td>
                                                   
                                                    <button type="button" class="btn btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myEdit" id="{{ $data->id }}" onclick="bannerEdit(this.id)">Edit</button>
                                                    
                                                    <a class="btn btn-danger waves-effect waves-light"  href="{{route('delete.banner',$data->id)}}" id="delete">Delete</a>
                                                </td>
                                                
                                            </tr>
                                            @endforeach
                                        
                                            </tbody>
                                        </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row --> 
         
                      
                    </div> <!-- container-fluid -->
                </div>

<!--ADD Banner Modal Start -->
<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
    <div class="modal-body">
    <form id="myForm" action="{{ route('store.banner') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Banner URL</label>
                        <input class="form-control" name="url" type="text" value="" id="example-text-input">
                    </div>

                   
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Banner Image</label>
                        <input class="form-control" name="image" type="file" id="image">
                    </div>

                    <div class="mb-3">
                        <img id="showImage1" src="{{ url('upload/no_image.jpg') }}" alt="" class="rounded-circle p-1 bg-primary" width="100">   
                    </div>

                </div>
            </div>

            
        </div>
       

                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






<!--Edit Banner Modal Start -->
<div id="myEdit" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-scroll="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Edit Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
    <div class="modal-body">
    <form id="myForm" action="{{ route('update.banner') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="banner_id" id="banner_id">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Banner URL</label>
                        <input class="form-control" name="url" type="text" value="" id="banner_url">
                    </div>

                   
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Banner Image</label>
                        <input class="form-control" name="image" type="file" id="image">
                    </div>

                    <div class="mb-3">
                        <img id="bannerImage"  src="" alt="" class="rounded-circle p-1 bg-primary" width="100">   
                    </div>

                </div>
            </div>

            
        </div>
       

                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script type="text/javascript">
        $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage1').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>


<script>
    function bannerEdit(id){
        $.ajax({
            type: 'GET',
            url: '/edit/banner/'+id,
            dataType: 'json',

            success:function(data){
                // console.log(data);
                $('#banner_url').val(data.url);
                $('#bannerImage').attr('src',data.image);
                $('#banner_id').val(data.id);
            }
        });
    }
</script>







@endsection