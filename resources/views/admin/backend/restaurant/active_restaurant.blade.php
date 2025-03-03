@extends('admin.dashboard')
@section('admin')
<!-- Notification Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Active Inactive css -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Active Restaurant</h4>


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
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
        
        
                                            <tbody>
                                            @foreach($active as $key=> $data)    
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td><img src="{{ (!empty($data->photo)) ? url('upload/client_images/'.$data->photo) : url('upload/no_image.jpg') }}" alt="category image" style="width:70px; height:40px;"></td>
                                                
                                                <td>{{$data->name}}</td>
                                                <!-- display menu name from menus table by relationship -->
                                                <td>{{ $data->email}}</td>
                                                <!-- end -->
                                                <td>{{$data->phone}}</td>   
                                                <td>{{$data->address}}</td>                                            
                                                <td>
                                                    @if($data->status == 1)
                                                    <span class="text-success"><b>Active</b></span>
                                                    @else
                                                    <span class="text-danger"><b>Inactive</b></span>
                                                    @endif
                                                </td>

                <td>
                    <!-- Status Active Inactive Button -->
                    <input data-id="{{$data->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{$data->status ? 'checked' : ''}}>

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


<!-- Status Active or Inactive Javascript Code -->
<script type="text/javascript">
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var client_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/clientchangeStatus',
            data: {'status': status, 'client_id': client_id},
            success: function(data){
              // console.log(data.success)

                // Start Message 

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  icon: 'success', 
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error',
                    title: data.error, 
                    })
                }

              // End Message   


            }
        });
    })
  })
</script>


@endsection