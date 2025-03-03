@extends('frontend.dashboard.dashboard')
@section('dash')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
 
<section class="section pt-4 pb-4 osahan-account-page">
         <div class="container">
            <div class="row">
            @include('frontend.dashboard.sidebar')
               <div class="col-md-9">
                  <div class="osahan-account-page-right rounded shadow-sm bg-white p-4 h-100">
                    
                  <div class="tab-pane" id="favourites" role="tabpanel" aria-labelledby="favourites-tab">
                           <h4 class="font-weight-bold mt-0 mb-4">Favourites</h4>
                           <div class="row">

                            @foreach($wishlits as $wishlist)
                        @php
                            $coupons = App\Models\Coupon::where('client_id',$wishlist->client_id)->where('status','1')->first();
                         @endphp


                              <div class="col-md-4 col-sm-6 mb-4 pb-2">
                                 <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                    <div class="list-card-image">
                                    
                                       <a href="{{ route('restaurant.details',$wishlist->client_id) }}">
                                       <img src="{{asset('upload/client_images/' . $wishlist['client'] ['photo'])}}" class="img-fluid item-img" style="width: 300px; height:200px;">
                                       </a>
                                    </div>
                                    <div class="p-3 position-relative">
                                       <div class="list-card-body">
                                          <h6 class="mb-1"><a href="{{ route('restaurant.details',$wishlist->client_id) }}" class="text-black">
                                             </a>
                                          </h6>
                                          <p class="text-gray mb-3">{{$wishlist['client'] ['name']}}</p>
                                          

                                          <div style="float:right; margin-bottom:5px">
                                                <a href="{{route('remove.wishlist',$wishlist->id)}}" class="badge badge-danger">
                                                    <i class="icofont-ui-delete"></i>
                                                </a>
                                          </div>
                                       </div>
                                       <div class="list-card-badge">
                                       @if ($coupons)
                     <span class="badge badge-success">OFFER</span> <small>{{ $coupons->discount  }}% off | Use Coupon <span class="text-warning text-danger">{{ $coupons->coupon_name  }}</span></small>
                  @else 
                     <span class="badge badge-success">OFFER</span> <small>Right Now There Have No Coupon</small>
                  @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                            
                              @endforeach
                           </div>
                           
                        </div>
                       
                  </div>
               </div>
            </div>
          
         </div>
      </section>

     


    

@endsection