<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\City;
use App\Models\Client;
use App\Mail\Clientmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ClientController extends Controller
{
    public function ClientLogin(){
        return view('client.client_login');
    }

    public function ClientRegister(){
        return view('client.client_register');
    }

    public function ClientRegisterSubmit(Request $request){
        $request->validate([
            'name' => ['required','string','max:200'],
            'email' => ['required','string','unique:clients'],
        ]);

        Client::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'status' => '0',

        ]);

        $notification = array(
            'message' => 'Client Register Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('client.dashboard')->with($notification);
    }


    public function ClientLoginSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];
        if(Auth::guard('client')->attempt($data)){
            $notification = array(
                'message' => 'Successfully Log In',
                'alert-type' => 'success'
            );
            return redirect()->route('client.dashboard')->with($notification);
        }else{
            return redirect()->route('client.login')->with('error','Invalid Credential');
        }
    }


    //Forget Password Start
    public function ClientForgetPassword(){
        return view('client.forget_password');
    }

    public function ClientPasswordSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $client_data = Client::where('email',$request->email)->first();
        if(!$client_data){
            return redirect()->back()->with('error','Email Not Found');
        }
        //generate custom token:
        $token = hash('sha256',time());
        $client_data->token = $token;
        $client_data->update();

        //custom reset password link:
        $reset_link = url('/client/reset-password/'.$token.'/'.$request->email);
        $subject = "Reset Password";
        $message = "Please Click on below the link to reset password<br>";
        $message .= "<a href=' ".$reset_link." '>Click Here</a>";

        Mail::to($request->email)->send(new Clientmail($subject,$message));
        return redirect()->back()->with('success','Reset Password Link Send On Your Email');
    }


    public function ClientResetPassword($token,$email){
        $client_data = Client::where('email',$email)->where('token',$token)->first();
        if(!$client_data){
            return redirect()->route('client.login')->with('error','Invalid Token or Email');
        }
        return view('client.client_reset_password',compact('token','email'));
    }


    public function ClientResetPasswordSubmit(Request $request){
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        $client_data = Client::where('email',$request->email)->where('token',$request->token)->first();
        $client_data->password = Hash::make($request->password);
        $client_data->token = "";
        $client_data->update();

        $notification = array(
            'message' => 'Password Reset Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('client.login')->with($notification);
    }

    //Forget Password End


    public function ClientDashboard(){
        return view('client.index');
    }

    public function ClientLogout(){
        Auth::guard('client')->logout();
        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('client.login')->with($notification);
    }

     //Client Profile Start
     public function ClientProfile(){
        $city = City::latest()->get();
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_profile',compact('profileData','city'));
    }


    public function ClientProfileStore(Request $request){
        $id = Auth::guard('client')->id();
        $data = Client::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city_id = $request->city_id;
        $data->shop_info = $request->shop_info;

        $oldPhotoPath = $data->photo;

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/client_images'),$filename);
            $data->photo = $filename;

            //delete old image
            if($oldPhotoPath && $oldPhotoPath !== $filename){
                $this->deleteOldImage($oldPhotoPath);
            }

        }


        if($request->hasFile('cover_photo')){
            $c_file = $request->file('cover_photo');
            $c_filename = time().'.'.$c_file->getClientOriginalExtension();
            $c_file->move(public_path('upload/cover'),$c_filename);
            $data->cover_photo = $c_filename;

            //delete old image
            if($oldPhotoPath && $oldPhotoPath !== $c_filename){
                $this->deleteOldImage($oldPhotoPath);
            }
        }
        $data->save();
        $notification = array(
            'message' => 'Client Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    //delete old image
    private function deleteOldImage(string $oldPhotoPath): void{
        $fullpath = public_path('upload/client_images/'.$oldPhotoPath);
        if(file_exists($fullpath)){
            unlink($fullpath);
        }
    }
    //Client Profile and Image Update Controller end


       //Admin Change Password
       public function ClientChangePassword(){
        $id = Auth::guard('client')->id();
        $profileData = Client::find($id);
        return view('client.client_change_password',compact('profileData'));
    }

    public function ClientPasswordUpdate(Request $request){
        $client = Auth::guard('client')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if(!Hash::check($request->old_password,$client->password)){
            $notification = array(
                'message' => 'Old Password Does not Match',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        // Update the new Password
        Client::whereId($client->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $notification = array(
            'message' => 'Password Changes Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

}
