@extends('client.client_dashboard')
@section('client')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Add Menu</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                                            <li class="breadcrumb-item active">Add Menu</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-9 col-lg-8">
                               
                                <!-- end card -->


<div class="card">
                                <div class="card-body p-4">
    <form id="myForm" action="{{ route('store.menu') }}" method="post" enctype="multipart/form-data"> 
        @csrf  
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Menu Name</label>
                        <input class="form-control" name="menu_name" type="text" value="" id="example-text-input">
                    </div> 
                    
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mt-3 mt-lg-0">
                    <div class="form-group mb-3">
                        <label for="example-text-input" class="form-label">Menu Image</label>
                        <input class="form-control" name="image" type="file" value="" id="image">
                    </div> 

                    <div class="mb-3">
                    <img src="{{url('upload/no_image.jpg')}}" alt="" id="showImage" class="rounded-circle p-1 bg-primary" width="110">
                    </div>

                    <div class="mt-4">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                    </div>
                   
                </div>
            </div>
        </div>
    </form> 
</div>
</div>

                           
                                <!-- end tab content -->
                            </div>
                            <!-- end col -->

                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>


    <script type="text/javascript">
        $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>



<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                Menu_name: {
                    required : true,
                }, 
                image: {
                    required : true,
                }, 
                
            },
            messages :{
                menu_name: {
                    required : 'Please Enter Menu Name',
                }, 
                image: {
                    required : 'Please Select Image',
                }, 
                 

            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>


@endsection