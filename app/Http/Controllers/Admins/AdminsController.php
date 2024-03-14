<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use App\Models\Hotel\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AdminsController extends Controller
{




    public function viewLogin()
    {
        return view('admins.login');
    }

    public function checkLogin(Request $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {

            return redirect() -> route('admins.dashboard');
        }
        return redirect()->back()->with(['error' => 'error logging in']);
    }

    public function index()
    {

        $adminsCount = Admin::select()->count();
        $hotelCount = Hotel::select()->count();
        $roomsCount = Apartment::select()->count();

        return view('admins.index', compact('adminsCount', 'hotelCount', 'roomsCount'));
    }

    public function allAdmins()
    {
        $admins = Admin::select()->orderBy('id', 'desc')->get();
        return view('admins.alladmins', compact('admins'));
    }

    public function createAdmins()
    {
        return view('admins.createadmins');
    }

    public function storeAdmins(Request $request)
    {
        $storeAdmins = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($storeAdmins){
            return redirect()->route('admins.all')->with(['success' => 'Admin created successfully']);
        }
    return redirect()->back()->with(['error' => 'error creating admin']);
    }

    public function allHotels()
    {
        $hotels = Hotel::select()->orderBy('id', 'desc')->get();
        return view('admins.allhotels', compact('hotels'));
    }

    public function createHotels()
    {
        return view('admins.createhotels');
    }

    public function storeHotels(Request $request)
    {

        Request()->validate([
            'name' => 'required|max:40',
            'location' => 'required|max:40',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:888',

        ]);

        $destinationPath = 'assets/images/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeHotels = Hotel::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $myimage,
        ]);

        if ($storeHotels) {
            return redirect()->route('hotels.all')->with(['success' => 'Hotel created successfully']);
        }
        return redirect()->back()->with(['error' => 'error creating hotel']);
    }

    public function editHotels($id)
    {
        $hotel = Hotel::find($id);
        return view('admins.edithotels', compact('hotel'));
    }

    public function updateHotels(Request $request, $id)
    {
        Request()->validate([
            'name' => 'required|max:40',
            'location' => 'required|max:40',
            'description' => 'required',
        ]);


        $hotel = Hotel::find($id);

        $hotel->update($request->all());

        if ($hotel) {
            return redirect()->route('hotels.all')->with(['success' => 'Hotel updated successfully']);
        }
        return redirect()->back()->with(['error' => 'error updating hotel']);
    }

    public function deleteHotels($id)
    {
        $hotel = Hotel::find($id);
        if(File::exists(public_path('assets/images/' . $hotel->image))){
            File::delete(public_path('assets/images/' . $hotel->image));
        }else{
            //dd('File does not exists.');
        }
        $hotel->delete();
        return redirect()->route('hotels.all')->with(['success' => 'Hotel deleted successfully']);
    }

    public function allRooms()
    {
        $rooms = Apartment::select()->orderBy('id', 'desc')->get();
        return view('admins.allrooms', compact('rooms'));
    }

    public function createRooms()
    {
        $hotels = Hotel::all();
        return view('admins.createrooms', compact('hotels'));
    }

    public function storeRooms(Request $request)
    {

//        Request()->validate([
//            'name' => 'required|max:40',
//            'location' => 'required|max:40',
//            'description' => 'required',
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:888',
//
//        ]);

        $destinationPath = 'assets/images/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeRooms = Apartment::create([
            'name' => $request->name,
            'image' => $myimage,
            'size' => $request->size,
            'max_persons' => $request->max_persons,
            'view' => $request->view,
            'num_beds' => $request->num_beds,
            'price' => $request->hotel_id,
        ]);

        if ($storeRooms) {
            return redirect()->route('rooms.all')->with(['success' => 'Room created successfully']);
        }
        return redirect()->back()->with(['error' => 'error creating room']);
    }

    public function deleteRooms($id)
    {
        $room = Apartment::find($id);
        /*if(File::exists(public_path('assets/images/' . $room->image))){
            File::delete(public_path('assets/images/' . $room->image));
        }else{
            //dd('File does not exists.');
        }*/
        $room->delete();
        return redirect()->route('rooms.all')->with(['success' => 'Room deleted successfully']);
    }

    public function allBookings()
    {
        $bookings = Booking::select()->orderBy('id', 'desc')->get();
        return view('admins.allbookings', compact('bookings'));
    }

    public function editStatus($id)
    {
        $booking = Booking::find($id);
        return view('admins.editstatus', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $status = Booking::find($id);
        $status->update($request->all());

        if ($status) {
            return redirect()->route('bookings.all')->with(['success' => 'Status updated successfully']);
        }
        return redirect()->back()->with(['error' => 'error updating status']);
    }

    public function deleteBooking($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return redirect()->route('bookings.all')->with(['success' => 'Booking deleted successfully']);
    }
}
