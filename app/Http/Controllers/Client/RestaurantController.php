<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Menu;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class RestaurantController extends Controller
{
    public function AllMenu(){
        $id = Auth::guard('client')->id();
        $menu = Menu::where('client_id',$id)->orderBy('id','desc')->get();
        return view('client.backend.menu.all_menu', compact('menu'));
    }//end

    public function AddMenu(){
        return view('client.backend.menu.add_menu');
    }

    public function StoreMenu(Request $request){
        //store image using image intervention package
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(200,200)->save(public_path('upload/menu/'.$name_gen)); //store image in public folder
            $save_url = 'upload/menu/'.$name_gen; //store image in database

            Menu::create([
                'menu_name' => $request->menu_name,
                'client_id' => Auth::guard('client')->id(),
                'image' => $save_url,
            ]);
        }
        $notification = array(
            'message' => 'New Menu Uploaded Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.menu')->with($notification);
    }//end

    public function EditMenu($id){
        $menu = Menu::find($id);
        return view('client.backend.menu.edit_menu', compact('menu'));
    }//end

    //update menu
    public function UpdateMenu(Request $request,$id){
        //store image using image intervention package
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(200,200)->save(public_path('upload/category/'.$name_gen)); //store image in public folder
            $save_url = 'upload/category/'.$name_gen; //store image in database

            Menu::find($id)->update([
                'menu_name' => $request->menu_name,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Menu Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.menu')->with($notification);
        }else{
            Menu::find($id)->update([
                'menu_name' => $request->menu_name,
            ]);
            $notification = array(
                'message' => 'Menu Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.menu')->with($notification);
        }
       
    }

    public function DeleteMenu($id){
        $item = Menu::find($id);
        $img = $item->image;
        unlink($img);

        Menu::find($id)->delete();

        $notification = array(
            'message' => 'Menu Deleted',
            'alert-type' => 'warning'
        );
        return redirect()->back()->with($notification);
    }


    //All Product Method Start
    public function AllProduct(){
        $id = Auth::guard('client')->id();
        $product = Product::where('client_id',$id)->orderBy('id','desc')->get();
        return view('client.backend.product.all_product',compact('product'));
    }

    public function AddProduct(){
        $id = Auth::guard('client')->id();
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::where('client_id',$id)->latest()->get();
        return view('client.backend.product.add_product',compact('category','city','menu'));
    }


    public function StoreProduct(Request $request){
        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => '5', 'prefix' => 'PC']);
        //store image using image intervention package
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(508,320)->save(public_path('upload/product/'.$name_gen)); //store image in public folder
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
                        'client_id' => Auth::guard('client')->id(),
                        'status' => 1,
                        'created_at' => Carbon::now(),
            ]);
        }
        $notification = array(
            'message' => 'New Product Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification);
    }//end


    public function EditProduct($id){
        $c_id = Auth::guard('client')->id();
        $category = Category::latest()->get();
        $city = City::latest()->get();
        $menu = Menu::where('client_id',$c_id)->latest()->get();
        $product = Product::find($id);
        return view('client.backend.product.edit_product',compact('category','city','menu','product'));
    }//end


    public function UpdateProduct(Request $request,$id){

        // $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => '5', 'prefix' => 'PC']); // not needed

                //updated with have image
                if($request->file('image')){
                    $image = $request->file('image');
                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                    $img = $manager->read($image);
                    $img->resize(508,320)->save(public_path('upload/product/'.$name_gen)); //store image in public folder
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
                        // 'client_id' => Auth::guard('client')->id(), -> //Not Needed
                        // 'status' => 1, -> //Not Needed
                        'created_at' => Carbon::now(),
                    ]);

                    $notification = array(
                        'message' => 'Product Updated Successfully',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('all.product')->with($notification);
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
                        // 'client_id' => Auth::guard('client')->id(), -> //Not Needed
                        // 'status' => 1, -> //Not Needed
                        'created_at' => Carbon::now(),
                    ]);
                    $notification = array(
                        'message' => 'Product Updated Successfully',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('all.product')->with($notification);
                }
    }//end

    public function DeleteProduct($id){
        $item = Product::find($id);
        $img = $item->image;
        unlink($img);

        Product::find($id)->delete();

        $notification = array(
            'message' => 'Product Deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }//end

    public function ChangeStatus(Request $request){
        $product = Product::find($request->product_id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status Chaged Successfully']);
    }
    //end


   /////All Gallery method start

   public function AllGallery(){
        $id = Auth::guard('client')->id();
        $gallery = Gallery::where('client_id',$id)->orderBy('id','desc')->get();
        return view('client.backend.gallery.all_gallery',compact('gallery'));
    } 

    public function AddGallery(){
        return view('client.backend.gallery.add_gallery');
    } 

    public function StoreGallery(Request $request){
        $images = $request->file('gallery_img');
        foreach($images as $gimg){
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$gimg->getClientOriginalExtension();
            $img = $manager->read($gimg);
            $img->resize(800,800)->save(public_path('upload/gallery/'.$name_gen)); //store image in public folder
            $save_url = 'upload/gallery/'.$name_gen; //store image in database

            Gallery::insert([
                'client_id' => Auth::guard('client')->id(),
                'gallery_img' => $save_url,
            ]);
        }

        
        $notification = array(
            'message' => 'Gallery Image Uploaded Successfully.',
            'alert-type' => 'success'
        );
        return redirect()->route('all.gallery')->with($notification);

    }

    public function EditGallery($id){
        $gallery = Gallery::find($id);
        return view('client.backend.gallery.edit_gallery',compact('gallery'));
    }


    public function UpdateGallery(Request $request,$id){
        //update image with remove existing image using image intervention package

        if($request->hasFile('gallery_img')){
            $image = $request->file('gallery_img');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(800,800)->save(public_path('upload/gallery/'.$name_gen)); //store image in public folder
            $save_url = 'upload/gallery/'.$name_gen; //store image in database

            //remove existing image from gallery
            $gallery = Gallery::find($id);
            if($gallery->gallery_img){
                $img = $gallery->gallery_img;
                unlink($img);
            }


            $gallery->update([
                'gallery_img' => $save_url,
            ]);

            $notification = array(
                'message' => 'Gallery Image Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.gallery')->with($notification);
        }else{
            $notification = array(
                'message' => 'No Image Selected For Update',
                'alert-type' => 'warning'
            );
            return redirect()->back()->with($notification);
        }
       
    }


    public function DeleteGallery($id){
        $item = Gallery::find($id);
        $img = $item->gallery_img;
        unlink($img);

        Gallery::find($id)->delete();

        $notification = array(
            'message' => 'Gallery Image Deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
   
}
