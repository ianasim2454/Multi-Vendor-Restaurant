<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function AllCategory(){
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category',compact('category'));
    }

    public function AddCategory(){
        return view('admin.backend.category.add_category');
    }

    public function CategoryStore(Request $request){
         //store image using image intervention package
         if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(150,150)->save(public_path('upload/category/'.$name_gen)); //store image in public folder
            $save_url = 'upload/category/'.$name_gen; //store image in database

            Category::create([
                'category_name' => $request->category_name,
                'image' => $save_url,
            ]);
        }
        $notification = array(
            'message' => 'New Category Uploaded Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }//end

    public function EditCategory($id){
        $category = Category::find($id);
        return view('admin.backend.category.edit_category', compact('category'));
    }

    //Update Category
    public function UpdateCategory(Request $request,$id){
        //store image using image intervention package
        if($request->file('image')){
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(200,200)->save(public_path('upload/category/'.$name_gen)); //store image in public folder
            $save_url = 'upload/category/'.$name_gen; //store image in database

            Category::find($id)->update([
                'category_name' => $request->category_name,
                'image' => $save_url,
            ]);
            $notification = array(
                'message' => 'Category Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
        }else{
            Category::find($id)->update([
                'category_name' => $request->category_name,
            ]);
            $notification = array(
                'message' => 'Category Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
        }
       
    }

    public function DeleteCategory($id){
        $item = Category::find($id);
        $img = $item->image;
        unlink($img);

        Category::find($id)->delete();

        $notification = array(
            'message' => 'Category Deleted',
            'alert-type' => 'warning'
        );
        return redirect()->back()->with($notification);
    }
}
