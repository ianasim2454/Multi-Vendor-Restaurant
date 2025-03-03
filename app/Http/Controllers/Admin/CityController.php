<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function AllCity(){
        $city = City::latest()->get();
        return view('admin.backend.city.all_city',compact('city'));
    }

    public function CityStore(Request $request){
           City::create([
               'city_name' => $request->city_name,
               'city_slug' => strtolower(str_replace(' ','-',$request->city_name)),
           ]);
       
       $notification = array(
           'message' => 'New City Inserted Successfully',
           'alert-type' => 'success'
       );
       return redirect()->route('all.city')->with($notification);
   }//end

   public function EditCity($id){
        $city = City::find($id);
        return response()->json($city);
    }//end

    public function UpdateCity(Request $request){
        $cat_id = $request->cat_id;

        City::find($cat_id)->update([
            'city_name' => $request->city_name,
            'city_slug' => strtolower(str_replace(' ','-', $request->city_name)),
        ]);
    
        $notification = array(
            'message' => 'City Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.city')->with($notification);
    }//end

    public function DeleteCity($id){
        City::find($id)->delete();
        $notification = array(
            'message' => 'City Deleted Successfully',
            'alert-type' => 'wearning'
        );
        return redirect()->route('all.city')->with($notification);
    }
}
