<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function AllCoupon(){
        $id = Auth::guard('client')->id();
        $coupon = Coupon::where('client_id',$id)->orderBy('id','desc')->get();
        return view('client.backend.coupon.all_coupon',compact('coupon'));
    }

    public function AddCoupon(){
        return view('client.backend.coupon.add_coupon');
    }

    public function StoreCoupon(Request $request){
        
            Coupon::create([
                'coupon_name' => strtoupper($request->coupon_name),
                'coupon_description' => $request->coupon_description,
                'discount' => $request->discount,
                'validity' => $request->validity,
                'client_id' => Auth::guard('client')->id(),
                'created_at' => Carbon::now(),
            ]);
        
        $notification = array(
            'message' => 'New Coupon Insert Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.coupon')->with($notification);
    }

    public function EditCoupon($id){
        $coupon = Coupon::find($id);
        return view('client.backend.coupon.edit_coupon',compact('coupon'));
    }

    public function UpdateCoupon(Request $request,$id){
        Coupon::find($id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_description' => $request->coupon_description,
            'discount' => $request->discount,
            'validity' => $request->validity,

            'created_at' => Carbon::now(),
        ]);
    
    $notification = array(
        'message' => 'Coupon Updated Successfully',
        'alert-type' => 'success'
    );
    return redirect()->route('all.coupon')->with($notification);
    }


    public function DeleteCoupon($id){
        Coupon::find($id)->delete();

        $notification = array(
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    
}
