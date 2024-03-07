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
            /*$room = Apartment::find($id);
            $hotel = Hotel::find($id);
            if(date("Y/m/d") < $request->check_in AND date("Y/m/d") < $request->check_out){


                if($request->check_in < $request->check_out){

                    $datetime1 = new DateTime($request->check_in);
                    $datetime2 = new DateTime($request->check_out);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');


                    $bookRooms = Booking::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'check_in' => $request->check_in,
                        'check_out' => $request->check_out,
                        'days' => $days,
                        'price' => $room->price * $days,
                        'user_id' => Auth::user()->id,
                        'room_name' => $room->name,
                        'hotel_name' => $hotel->name,
                    ]);*/
        $room = Apartment::find($id);
        $hotel = Hotel::find($id);

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
                    echo "booking successful!";
                }
                else{
                    echo "check out date must be greater than check in date!";
                }
            }
            else{
                echo "choose a valid date!";
            }

    }


}
