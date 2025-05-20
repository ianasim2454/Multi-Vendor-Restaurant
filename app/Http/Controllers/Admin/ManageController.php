<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ManageController extends Controller
{
    public function AdminAllProduct(){
        $product = Product::latest()->get();
        return view('admin.backend.product.all_product',compact('product'));
    }

    public function AdminAddProduct(){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        return view('admin.backend.product.add_product',compact('category','city','menu','client'));
    }

    public function AdminStoreProduct(Request $request){

        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => '5', 'prefix' => 'PC']);

                //store image using image intervention package
                if($request->file('image')){
                    $image = $request->file('image');
                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                    $img = $manager->read($image);
                    $img->resize(100,100)->save(public_path('upload/product/'.$name_gen)); //store image in public folder
                    $save_url = 'upload/product/'.$name_gen; //store image in database
        
                    Product::create([
                        'name' => $request->name,
                        'slug' => strtolower(str_replace(' ','-', $request->name)),
                        'image' => $save_url,
                        'category_id' => $request->category_id,
                        'menu_id' => $request->menu_id,
                        'city_id' => $request->city_id,
                        'code' => $pcode,
                        'price' => $request->price,
                        'discount_price' => $request->discount_price,
                        'size' => $request->size,
                        'qty' => $request->qty,
                        'best_seller' => $request->best_seller,
                        'most_popular' => $request->most_popular,
                        'client_id' => $request->client_id,
                        'status' => 1,
                        'created_at' => Carbon::now(),
                    ]);
                }
                $notification = array(
                    'message' => 'New Product Uploaded Successfully By Admin',
                    'alert-type' => 'success'
                );
                return redirect()->route('admin.all.product')->with($notification);
    }


    public function AdminEditProduct($id){
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::latest()->get();
        $client = Client::latest()->get();
        $product = Product::find($id);
        return view('admin.backend.product.edit_product', compact('category','city','menu','product','client'));
    }



    public function AdminUpdateProduct(Request $request,$id){

        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => '5', 'prefix' => 'PC']);

                //updated with have image
                if($request->file('image')){
                    $image = $request->file('image');
                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                    $img = $manager->read($image);
                    $img->resize(200,200)->save(public_path('upload/product/'.$name_gen)); //store image in public folder
                    $save_url = 'upload/product/'.$name_gen; //store image in database
        
                    Product::find($id)->update([
                        'name' => $request->name,
                        'slug' => strtolower(str_replace(' ','-', $request->name)),
                        'image' => $save_url,
                        'category_id' => $request->category_id,
                        'menu_id' => $request->menu_id,
                        'city_id' => $request->city_id,
                        // 'code' => $pcode, -> //not needed
                        'price' => $request->price,
                        'discount_price' => $request->discount_price,
                        'size' => $request->size,
                        'qty' => $request->qty,
                        'best_seller' => $request->best_seller,
                        'most_popular' => $request->most_popular,
                        'client_id' => $request->client_id,
                        'updated_at' => Carbon::now(),
                    ]);

                    $notification = array(
                        'message' => 'Product Updated Successfully By Admin',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('admin.all.product')->with($notification);
                }else{

                    //updated else have not image
                    Product::find($id)->update([
                        'name' => $request->name,
                        'slug' => strtolower(str_replace(' ','-', $request->name)),
                        'category_id' => $request->category_id,
                        'menu_id' => $request->menu_id,
                        'city_id' => $request->city_id,
                        // 'code' => $pcode, -> //not needed
                        'price' => $request->price,
                        'discount_price' => $request->discount_price,
                        'size' => $request->size,
                        'qty' => $request->qty,
                        'best_seller' => $request->best_seller,
                        'most_popular' => $request->most_popular,
                        'client_id' => $request->client_id,
                        'updated_at' => Carbon::now(),
                    ]);
                    $notification = array(
                        'message' => 'Product Updated Successfully By Admin',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('admin.all.product')->with($notification);
                }
    }


    public function AdminDeleteProduct($id){
        $item = Product::find($id);
        $img = $item->image;
        unlink($img);

        Product::find($id)->delete();

        $notification = array(
            'message' => 'Product Deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


     //  Pending and Approved Restaurant method Start
    
     public function PendingRestaurant(){
        $pending = Client::where('status',0)->get();
        return view('admin.backend.restaurant.pending_restaurant',compact('pending'));
    }

    //Restaurant status change by admin
    public function ClientChangeStatus(Request $request){
        $client = Client::find($request->client_id);
        $client->status = $request->status;
        $client->save();
        return response()->json(['success' => 'Status Chaged Successfully']);
    }

    public function ActiveRestaurant(){
        $active = Client::where('status',1)->get();
        return view('admin.backend.restaurant.active_restaurant',compact('active'));
    }

     //  Pending and Approved Restaurant method End

       //  Banner Method Start
       public function AllBanner(){
        $banner = Banner::latest()->get();
        return view('admin.backend.banner.all_banner',compact('banner'));
     }

     public function StoreBanner(Request $request){
        //store image using image intervention package
        if($request->file('image')){
          $image = $request->file('image');
          $manager = new ImageManager(new Driver());
          $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
          $img = $manager->read($image);
          $img->resize(400,400)->save(public_path('upload/banner/'.$name_gen)); //store image in public folder
          $save_url = 'upload/banner/'.$name_gen; //store image in database

          Banner::create([
              'url' => $request->url,
              'image' => $save_url,
          ]);
      }
        $notification = array(
          'message' => 'New Banner Insert Successfully',
          'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
   }

    public function EditBanner($id){
        $banner = Banner::find($id);
        if($banner){
            $banner->image = asset($banner->image); 
        }
        return response()->json($banner);

    }

    public function UpdateBanner(Request $request){
        $banner_id = $request->banner_id;
        //store image using image intervention package
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(400,400)->save(public_path('upload/banner/'.$name_gen)); //store image in public folder
            $save_url = 'upload/banner/'.$name_gen; //store image in database

            //remove existing picture
            $bannerimage = Banner::find($banner_id);
            if($bannerimage->image){
                $img = $bannerimage->image;
                unlink($img);
            }


            Banner::find($banner_id)->update([
                'url' => $request->url,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.banner')->with($notification);
        }else{
            Banner::find($banner_id)->update([
                'url' => $request->url,
            ]);
            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.banner')->with($notification);
        }
       
    }


    public function DeleteBanner($id){
        $item = Banner::find($id);
        $img = $item->image;
        unlink($img);

        Banner::find($id)->delete();

        $notification = array(
            'message' => 'Banner Deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    //User Order Manage Start


     public function UserOrderList(){
        $userId = Auth::user()->id;
        $allUserOrder = Order::where('user_id',$userId)->orderBy('id','desc')->get();
        return view('frontend.dashboard.order.order_list',compact('allUserOrder'));
    }
      //End Method 

       public function UserOrderDetails($id){
        $order = Order::with('user')->where('id',$id)->where('user_id',Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id',$id)->orderBy('id','desc')->get();

        $totalPrice = 0;
        foreach($orderItem as $item){
            $totalPrice += $item->price * $item->qty;
        }

        return view('frontend.dashboard.order.order_details',compact('order','orderItem','totalPrice'));
    }
     //End Method 

}
