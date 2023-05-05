<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');
    }

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {

        $adminData = User::find(Auth::user()->id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function AdminProfileStore(Request $request)
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
            'photo' => 'nullable|image|max:2048',
        ]);


        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword()
    {
        return view('admin.admin_change_password');
    }

    public function AdminUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        User::findOrFail(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");
    }

    public function InactiveVendor()
    {
        $inActiveVendor = User::where([
            ['status', '=', 'inactive'],
            ['role', '=', 'vendor']
        ])->latest()->get();
        return view('backend.vendor.inactive_vendor', compact('inActiveVendor'));
    }

    public function activeVendor()
    {
        $activeVendor = User::where([
            ['status', '=', 'active'],
            ['role', '=', 'vendor']
        ])->latest()->get();
        return view('backend.vendor.active_vendor', compact('activeVendor'));
    }

    public function InactiveVendorDetails($id)
    {

        $inactiveVendorDetails = User::findOrFail($id);
        return view('backend.vendor.inactive_vendor_details', compact('inactiveVendorDetails'));
    } // End Mehtod


    public function ActiveVendorApprove(Request $request)
    {

        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('active.vendor')->with($notification);
    } // End Mehtod


    public function ActiveVendorDetails($id)
    {

        $activeVendorDetails = User::findOrFail($id);
        return view('backend.vendor.active_vendor_details', compact('activeVendorDetails'));
    } // End Mehtod


    public function InActiveVendorApprove(Request $request)
    {

        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor InActive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);
    } // End Mehtod


    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
