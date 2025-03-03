<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function RestaurantDetails($id){
        $client = Client::find($id);
        $menus = Menu::where('client_id',$client->id)->get()->filter(function($menu){
            return $menu->products->isNotEmpty(); 
        }); 
        

        $gallerys = Gallery::where('client_id',$id)->get();
        return view('frontend.restaurant_details',compact('client','menus','gallerys'));
    }

    public function AddWishList(Request $request, $id){
        if (Auth::check()) {
            $exists = WishList::where('user_id',Auth::id())->where('client_id',$id)->first();
            if (!$exists ) {
                WishList::insert([
                    'user_id'=> Auth::id(),
                    'client_id' => $id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Your Wishlist Addedd Successfully']);
            } else {
                return response()->json(['error' => 'This product has already on your wishlist']);
            } 
        }else{
            return response()->json(['error' => 'First Login Your Account']);
        }

    }
    //End Method

    public function AllWishList(){ 
       
        $wishlits = WishList::where('user_id',Auth::id())->get();
        return view('frontend.dashboard.all_wishlist',compact('wishlits'));
    }

    public function RemoveWishList($id){
       
        WishList::find($id)->delete();
        $notification = array(
            'message' => 'Deleted From Favourites List',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}




