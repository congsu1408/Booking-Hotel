<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use App\Models\Hotel\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use PDF;

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
        $bookingsCount = Booking::select()->count();
        $usersCount = User::select()->count();

        // Lấy số lượng đặt phòng theo tháng
        $bookingsByMonth = Booking::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Lấy doanh thu theo tháng
        $revenueByMonth = Booking::select(
            DB::raw('SUM(price) as revenue'),
            DB::raw('MONTH(created_at) as month')
        )
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Lấy tỷ lệ đặt phòng theo tên khách sạn
        $bookingsByHotel = Booking::select(
            'hotel_name',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('hotel_name')
            ->pluck('count', 'hotel_name')
            ->toArray();

        return view('admins.index', compact(
            'adminsCount', 'hotelCount', 'roomsCount', 'bookingsCount', 'usersCount',
            'bookingsByMonth', 'revenueByMonth', 'bookingsByHotel'
        ));
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

    public function deleteAdmins($id)
    {
        $admin = Admin::find($id);
        $admin->delete();
        return redirect()->route('admins.all')->with(['success' => 'Admin deleted successfully']);
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
       /* if(File::exists(public_path('assets/images/' . $hotel->image))){
            File::delete(public_path('assets/images/' . $hotel->image));
        }else{
            //dd('File does not exists.');
        }*/
        $rooms = Apartment::where('hotel_id', $id)->get();
        foreach ($rooms as $room){
            $room->delete();
        }
        $hotel->delete();

        return redirect()->route('hotels.all')->with(['success' => 'Hotel deleted successfully']);
    }

    public function allRooms()
    {
        $rooms = Apartment::select()->orderBy('id', 'desc')->get();
        $hotels = Hotel::all();

        // Dữ liệu cho biểu đồ số phòng của mỗi khách sạn
        $hotelRoomCounts = [];
        foreach ($hotels as $hotel) {
            $hotelRoomCounts[$hotel->name] = $rooms->where('hotel_id', $hotel->id)->count();
        }

        // Dữ liệu cho biểu đồ loại view
        $viewCounts = $rooms->groupBy('view')->map->count();

        // Dữ liệu cho biểu đồ giá
        $prices = $rooms->pluck('price');

        return view('admins.allrooms', compact('rooms', 'hotels', 'hotelRoomCounts', 'viewCounts', 'prices'));
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
            'price' => $request->price,
            'hotel_id' => $request->hotel_id,
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

    public function allUsers()
    {
        $users = User::select()->orderBy('id', 'desc')->get();
        return view('admins.allusers', compact('users'));
    }

    public function deleteUsers($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.all')->with(['success' => 'User deleted successfully']);
    }

    public function getData()
    {
        // Fetch the data from the database
        $bookingsByMonth = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        $revenueByMonth = Booking::selectRaw('MONTH(created_at) as month, SUM(price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $bookingsByHotel = Booking::selectRaw('hotel_name, COUNT(*) as count')
            ->groupBy('hotel_name')
            ->pluck('count', 'hotel_name');

        return response()->json([
            'bookingsByMonth' => $bookingsByMonth,
            'revenueByMonth' => $revenueByMonth,
            'bookingsByHotel' => $bookingsByHotel
        ]);
    }


    public function getRoomsData()
    {
        $hotelRoomCounts = Apartment::selectRaw('hotel_id, COUNT(*) as count')
            ->groupBy('hotel_id')
            ->pluck('count', 'hotel_id');

        $viewCounts = Apartment::selectRaw('view, COUNT(*) as count')
            ->groupBy('view')
            ->pluck('count', 'view');

        $prices = Apartment::pluck('price', 'name');

        return response()->json([
            'hotelRoomCounts' => $hotelRoomCounts,
            'viewCounts' => $viewCounts,
            'prices' => $prices,
        ]);
    }


    public function getBookingsData()
    {
        $bookings = Booking::all(); // Adjust the query as needed
        return response()->json($bookings);
    }

    public function performanceReport()
    {
        try {
            // Lấy tổng số phòng
            $totalRooms = DB::table('apartments')->count();

            // Lấy tổng số đặt phòng trong tháng hiện tại
            $currentMonthBookings = DB::table('bookings')
                ->whereMonth('created_at', date('m'))
                ->count();

            // Tính tỷ lệ lấp đầy (occupancy rate)
            $occupancyRate = $totalRooms > 0 ? ($currentMonthBookings / $totalRooms) * 100 : 0;

            // Lấy tổng doanh thu trong tháng hiện tại
            $currentMonthRevenue = DB::table('bookings')
                ->whereMonth('created_at', date('m'))
                ->sum('price');

            // Tính doanh thu trung bình mỗi phòng (RevPAR)
            $RevPAR = $totalRooms > 0 ? $currentMonthRevenue / $totalRooms : 0;

            // Define KPI
            $kpi = 1500.00; // Example KPI value
            $meetsKpi = $currentMonthRevenue >= $kpi;

            // Trả về view với dữ liệu
            return view('admins.performance-report', [
                'totalRooms' => $totalRooms,
                'currentMonthBookings' => $currentMonthBookings,
                'occupancyRate' => $occupancyRate,
                'currentMonthRevenue' => $currentMonthRevenue,
                'RevPAR' => $RevPAR,
                'meetsKpi' => $meetsKpi,
                'kpi' => $kpi
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching performance report: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


/*public function downloadPerformanceReport()
    {
        try {
            // Lấy dữ liệu báo cáo như phương thức performanceReport
            $totalRooms = DB::table('rooms')->count();
            $currentMonthBookings = DB::table('bookings')
                ->whereMonth('created_at', date('m'))
                ->count();
            $occupancyRate = $totalRooms > 0 ? ($currentMonthBookings / $totalRooms) * 100 : 0;
            $currentMonthRevenue = DB::table('bookings')
                ->whereMonth('created_at', date('m'))
                ->sum('total_price');
            $RevPAR = $totalRooms > 0 ? $currentMonthRevenue / $totalRooms : 0;

            $data = [
                'totalRooms' => $totalRooms,
                'currentMonthBookings' => $currentMonthBookings,
                'occupancyRate' => $occupancyRate,
                'currentMonthRevenue' => $currentMonthRevenue,
                'RevPAR' => $RevPAR
            ];

            // Tạo PDF từ view
            $pdf = PDF::loadView('admin.performance-report-pdf', $data);

            // Trả về file PDF để tải về
            return $pdf->download('performance-report.pdf');
        } catch (\Exception $e) {
            \Log::error('Error fetching performance report: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }*/


}
