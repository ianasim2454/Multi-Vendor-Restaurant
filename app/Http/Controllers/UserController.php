<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Index(){
        return view('frontend.index');
    }

    public function UserProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        $oldPhotoPath = $data->photo;

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'),$filename);
            $data->photo = $filename;

            //delete old image
            if($oldPhotoPath && $oldPhotoPath !== $filename){
                $this->deleteOldImage($oldPhotoPath);
            }
        }
        $data->save();
        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
     //delete old image
     private function deleteOldImage(string $oldPhotoPath): void{
        $fullpath = public_path('upload/user_images/'.$oldPhotoPath);
        if(file_exists($fullpath)){
            unlink($fullpath);
        }
    }

    public function UserLogout(){
        Auth::guard('web')->logout();
        return redirect()->route('login')->with('success','Logout Successfull');
    }

    public function UserChangePassword(){
        return view('frontend.dashboard.user_change_password');
    }

    public function UserUpdatePassword(Request $request){
        $user = Auth::guard('web')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if(!Hash::check($request->old_password,$user->password)){
            $notification = array(
                'message' => 'Old Password Does not Match',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        // Update the new Password
        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $notification = array(
            'message' => 'Password Changes Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }
}
