<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Mail\Websitemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminLogin(){
        return view('admin.login');
    }

    public function AdminDashboard(){
        return view('admin.index');
    }

    public function AdminLoginSubmit(Request $request){
        $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        ]);

        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if(Auth::guard('admin')->attempt($data)){
            return redirect()->route('admin.dashboard')->with('success','Login Success');
        }else{
            return redirect()->route('admin.login')->with('error','Login Failed');
        }
       
    }

    public function AdminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','Logout Success');
    }

    public function AdminForgetPassword(){
        return view('admin.forget_password');
    }

    public function AdminPasswordSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $admin_data = Admin::where('email',$request->email)->first();
        if(!$admin_data){
            return redirect()->back()->with('error','Email Not Found');
        }
        //generate custom token:
        $token = hash('sha256',time());
        $admin_data->token = $token;
        $admin_data->update();

        //custom reset password link:
        $reset_link = url('/admin/reset-password/'.$token.'/'.$request->email);
        $subject = "Reset Password";
        $message = "Please Click on below the link to reset password<br>";
        $message .= "<a href=' ".$reset_link." '>Click Here</a>";

        \Mail::to($request->email)->send(new Websitemail($subject,$message));
        return redirect()->back()->with('success','Reset Password Link Send On Your Email');
    }

    public function AdminResetPassword($token,$email){
        $admin_data = Admin::where('email',$email)->where('token',$token)->first();
        if(!$admin_data){
            return redirect()->route('admin.login')->with('error','Invalid Token or Email');
        }
        return view('admin.reset_password',compact('token','email'));
    }

    public function AdminResetPasswordSubmit(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        $admin_data = Admin::where('email',$request->email)->where('token',$request->token)->first();
        $admin_data->password = Hash::make($request->password);
        $admin_data->token = "";
        $admin_data->update();

        return redirect()->route('admin.login')->with('success','Password Reset Successfully.');
    }


    //Admin Profile Start
    public function AdminProfile(){
        $id = Auth::guard('admin')->id();
        $profileData = Admin::find($id);
        return view('admin.admin_profile',compact('profileData'));
    }


    public function AdminProfileStore(Request $request){
        $id = Auth::guard('admin')->id();
        $data = Admin::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        $oldPhotoPath = $data->photo;

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_images'),$filename);
            $data->photo = $filename;

            //delete old image
            if($oldPhotoPath && $oldPhotoPath !== $filename){
                $this->deleteOldImage($oldPhotoPath);
            }
        }
        $data->save();
        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    //delete old image
    private function deleteOldImage(string $oldPhotoPath): void{
        $fullpath = public_path('upload/admin_images/'.$oldPhotoPath);
        if(file_exists($fullpath)){
            unlink($fullpath);
        }
    }
    //Admin Profile and Image Update Controller end


      //Admin Change Password
      public function AdminChangePassword(){
        $id = Auth::guard('admin')->id();
        $profileData = Admin::find($id);
        return view('admin.admin_change_password',compact('profileData'));
    }

    public function AdminPasswordUpdate(Request $request){
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if(!Hash::check($request->old_password,$admin->password)){
            $notification = array(
                'message' => 'Old Password Does not Match',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        // Update the new Password
        Admin::whereId($admin->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $notification = array(
            'message' => 'Password Changes Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

}