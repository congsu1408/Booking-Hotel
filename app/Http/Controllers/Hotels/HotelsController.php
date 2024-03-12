<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use App\Models\Hotel\Hotel;
use Illuminate\Http\Request;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HotelsController extends Controller
{


    public function rooms($id){
        $getRooms = Apartment::select()->orderBy('id', 'desc')->take(6)->where('hotel_id', $id)->get();
        return view('hotels.rooms', compact('getRooms'));
    }

    public function roomDetails($id){
        $getRoom = Apartment::find($id);
        return view('hotels.roomdetails', compact('getRoom'));

    }
    public function roomBooking(Request $request,$id){
        $room = Apartment::find($id);
        // Lấy hotel_id từ room
        $hotelId = $room->hotel_id;

        // Tìm đối tượng Hotel tương ứng
        $hotel = Hotel::find($hotelId);

        $checkIn = Carbon::createFromFormat('m/d/Y', $request->check_in)->format('Y-m-d');
        $checkOut = Carbon::createFromFormat('m/d/Y', $request->check_out)->format('Y-m-d');

        if (Carbon::now() < $checkIn && Carbon::now() < $checkOut){
            if ($checkIn < $checkOut) {
                $interval = Carbon::parse($checkIn)->diff(Carbon::parse($checkOut));
                $days = $interval->format('%a');

                $bookRooms = Booking::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'days' => $days,
                    'price' => $room->price * $days,
                    'user_id' => Auth::user()->id,
                    'room_name' => $room->name,
                    'hotel_name' => $hotel->name,
                ]);

                $totalPrice = $room->price * $days;
                $price = Session::put('price', $totalPrice);
                $getPrice = Session::get($price);

                return Redirect::route('hotel.pay');
                }
                else{

                    return Redirect::route('hotel.rooms.details', $room->id)-> with(['error' => 'check out date must be greater than check in date!']);
                }
            }
            else{
                return Redirect::route('hotel.rooms.details', $room->id)-> with(['error_dates' => 'choose date in the future!']);

            }

    }

    public function payWithPaypal(){
        return view('hotels.pay');
    }

    public function success(){

        Session::forget('price');
        return view('hotels.success');
    }
}
