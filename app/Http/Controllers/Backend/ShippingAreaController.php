<?php

namespace App\Http\Controllers\Backend;

use App\Models\ShipState;
use App\Models\ShipDivision;
use Illuminate\Http\Request;
use App\Models\ShipDistricts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StateStoreRequest;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use App\Http\Requests\Backend\StateUpdateRequest;

class ShippingAreaController extends Controller
{

    public function allDivision()
    {
        $divisions = ShipDivision::latest()->get();
        return view('backend.ship.division.division_all', ['divisions' => $divisions]);
    }

    public function AddDivision()
    {
        return view('backend.ship.division.division_add');
    } // End Method

    public function StoreDivision(Request $request)
    {

        $validatedData = $request->validate([
            'division_name' => 'required|max:255',
        ]);

        ShipDivision::insert($validatedData);

        $notification = array(
            'message' => 'ShipDivision Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.division')->with($notification);
    } // End Method


    public function EditDivision($id)
    {

        $division = ShipDivision::findOrFail($id);
        return view('backend.ship.division.division_edit', compact('division'));
    } // End Method

    public function UpdateDivision(Request $request, $id)
    {


        $validatedData = $request->validate([
            'division_name' => 'required|max:255',
        ]);

        ShipDivision::findOrFail($id)->update($validatedData);

        $notification = array(
            'message' => 'ShipDivision Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.division')->with($notification);
    } // End Method



    public function DeleteDivision($id)
    {
        $division = ShipDivision::findOrFail($id);
        if ($division) {
            $division->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /////////////// District CRUD ///////////////


    public function AllDistrict()
    {
        $districts = ShipDistricts::latest()->get();
        return view('backend.ship.district.district_all', compact('districts'));
    } // End Method


    public function AddDistrict()
    {
        $divisions = ShipDivision::orderBy('division_name', 'ASC')->get();
        return view('backend.ship.district.district_add', compact('divisions'));
    } // End Method


    public function StoreDistrict(Request $request)
    {

        $validatedData = $request->validate([
            'district_name' => 'required|max:255',
            'division_id' => 'required|exists:ship_divisions,id',
        ]);

        ShipDistricts::insert($validatedData);

        $notification = array(
            'message' => 'ShipDistricts Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.district')->with($notification);
    } // End Method




    public function EditDistrict($id)
    {
        $divisions = ShipDivision::orderBy('division_name', 'ASC')->get();
        $district = ShipDistricts::findOrFail($id);
        return view('backend.ship.district.district_edit', compact('district', 'divisions'));
    } // End Method

    public function UpdateDistrict(Request $request, $id)
    {

        $validatedData = $request->validate([
            'district_name' => 'required|max:255',
            'division_id' => 'required|exists:ship_divisions,id',
        ]);
        ShipDistricts::findOrFail($id)->update($validatedData);
        $notification = array(
            'message' => 'ShipDistricts Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.district')->with($notification);
    } // End Method


    public function DeleteDistrict($id)
    {
        $district = ShipDistricts::findOrFail($id);
        if ($district) {
            $district->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    // State Crud

    public function allState()
    {
        $state = ShipState::latest()->get();
        return view('backend.ship.state.state_all', ['state' => $state]);
    }

    public function AddState()
    {
        $division = ShipDivision::orderBy('division_name', 'ASC')->get();
        $district = ShipDistricts::orderBy('district_name', 'ASC')->get();
        return view('backend.ship.state.state_add', compact('division', 'district'));
    }

    public function GetDistrict($division_id)
    {
        $dist = ShipDistricts::where('division_id', $division_id)->orderBy('district_name', 'ASC')->get();
        return json_encode($dist);
    }

    public function stoteState(StateStoreRequest $request)
    {
        ShipState::insert($request->validated());

        $notification = array(
            'message' => 'ShipDistricts Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.state')->with($notification);
    }

    public function EditState($id)
    {
        $division = ShipDivision::orderBy('division_name', 'ASC')->get();
        $district = ShipDistricts::orderBy('district_name', 'ASC')->get();
        $state = ShipState::findOrFail($id);
        return view('backend.ship.state.state_edit', compact('division', 'district', 'state'));
    } // End Method



    public function updateState(StateUpdateRequest $request, $id)
    {

        ShipState::findOrFail($id)->update($request->validated()); 
        $notification = array(
            'message' => 'Ship State Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.state')->with($notification);
    }






    public function deleteState($id)
    {
        $state = ShipState::findOrFail($id);
        if ($state) {
            $state->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
