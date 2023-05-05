<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function vendorDashboard()
    {
        return view('vendor.index');
    }

    public function VendorLogin()
    {
        return view('vendor.vendor_login');
    } // End Mehtod

    public function VendorProfile()
    {

        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view', compact('vendorData'));
    }

    public function VendorProfileStore(Request $request)
    {


        // dd($request);
        $request->validate([
            'name' => 'nullable|string|max:50',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->id()),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore(auth()->id()),
            ],
            'address' => 'nullable|string|max:255',
            'vendor_join' => 'required|numeric|between:2022,2030',
            'vendor_short_info' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',

        ]);


        // dd('after valida');
        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function VendorChangePassword()
    {
        return view('vendor.vendor_change_password');
    } // End Mehtod



    public function VendorUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");
    }


    public function VendorDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }


    public function becomeVendor()
    {
        return view('auth.become_vendor');
    }

    public function VendorRegister(Request $request) {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vendor_join' => $request->vendor_join,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

          $notification = array(
            'message' => 'Vendor Registered Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.login')->with($notification);

    }// End Mehtod 


}
