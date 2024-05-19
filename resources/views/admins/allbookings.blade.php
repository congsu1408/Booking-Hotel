@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                        @endif
                    </div>

                    <!-- Chart Containers -->
                    <div class="charts-container" style="display: flex; justify-content: space-between;">
                        <div >
                            <h5>Bookings by Room Name</h5>
                            <canvas id="bookingsByRoomChart" width="200" height="200"></canvas>
                        </div>
                        <div>
                            <h5>Bookings by Hotel Name</h5>
                            <canvas id="bookingsByHotelChart" width="200" height="200"></canvas>
                        </div>
                        <div>
                            <h5>Bookings by Days</h5>
                            <canvas id="bookingsByDaysChart" width="200" height="200"></canvas>
                        </div>
                        <div>
                            <h5>Total Booking Amount by Month</h5>
                            <canvas id="totalBookingAmountChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title mb-4 d-inline">Bookings</h5>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">name</th>
                            <th scope="col">email</th>
                            <th scope="col">phone number</th>
                            <th scope="col">check in</th>
                            <th scope="col">check out</th>
                            <th scope="col">days</th>
                            <th scope="col">user id</th>
                            <th scope="col">room name</th>
                            <th scope="col">hotel name</th>
                            <th scope="col">status</th>
                            <th scope="col">payment</th>
                            <th scope="col">change status</th>
                            <th scope="col">delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{$booking->name}}</td>
                                <td>{{$booking->email}}</td>
                                <td>{{$booking->phone_number}}</td>
                                <td>{{$booking->check_in}}</td>
                                <td>{{$booking->check_out}}</td>
                                <td>{{$booking->days}}</td>
                                <td>{{$booking->user_id}}</td>
                                <td>{{$booking->room_name}}</td>
                                <td>{{$booking->hotel_name}}</td>
                                <td>{{$booking->status}}</td>
                                <td>{{$booking->price}}</td>
                                <td><a href="{{route('bookings.edit.status', $booking->id)}}" class="btn btn-warning text-center">change status</a></td>
                                <td><a href="{{route('bookings.delete', $booking->id)}}" class="btn btn-danger text-center">delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>

    <!-- Prepare the data for Chart.js -->
    <script>



        document.addEventListener('DOMContentLoaded', function () {
            // Prepare the data
            var bookings = @json($bookings);

            // Aggregate data by room name
            var roomCounts = {};
            var hotelCounts = {};
            var daysCounts = {};
            var monthlyAmounts = {};



            bookings.forEach(function(booking) {
                // Aggregate by room name
                if (roomCounts[booking.room_name]) {
                    roomCounts[booking.room_name]++;
                } else {
                    roomCounts[booking.room_name] = 1;
                }

                // Aggregate by hotel name
                if (hotelCounts[booking.hotel_name]) {
                    hotelCounts[booking.hotel_name]++;
                } else {
                    hotelCounts[booking.hotel_name] = 1;
                }

                // Aggregate by days
                if (daysCounts[booking.days]) {
                    daysCounts[booking.days]++;
                } else {
                    daysCounts[booking.days] = 1;
                }

                // Aggregate by month for total amount
                var checkInDate = new Date(booking.check_in);
                var month = checkInDate.getMonth() + 1; // Months are zero-based
                var year = checkInDate.getFullYear();
                var monthYear = year + '-' + month;

                if (monthlyAmounts[monthYear]) {
                    monthlyAmounts[monthYear] += parseFloat(booking.price);
                } else {
                    monthlyAmounts[monthYear] = parseFloat(booking.price);
                }
            });

            // Prepare data for Chart.js
            var roomLabels = Object.keys(roomCounts);
            var roomData = Object.values(roomCounts);

            var hotelLabels = Object.keys(hotelCounts);
            var hotelData = Object.values(hotelCounts);

            var daysLabels = Object.keys(daysCounts);
            var daysData = Object.values(daysCounts);

            var monthlyLabels = Object.keys(monthlyAmounts);
            var monthlyData = Object.values(monthlyAmounts);

            // đặt kích thước cho biểu đồ
            var canvas = document.getElementById('bookingsByRoomChart');
            canvas.width = 200;
            canvas.height = 200;
            // Bookings by Room Name
            var ctxRoom = document.getElementById('bookingsByRoomChart').getContext('2d');
            var bookingsByRoomChart = new Chart(ctxRoom, {
                type: 'doughnut',
                data: {
                    labels: roomLabels,
                    datasets: [{
                        data: roomData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Percentage of Bookings by Room Name'
                        }
                    }
                }
            });

            // đặt kích thước cho biểu đồ
            var canvas = document.getElementById('bookingsByHotelChart');
            canvas.width = 200;
            canvas.height = 200;
            // Bookings by Hotel Name
            var ctxHotel = document.getElementById('bookingsByHotelChart').getContext('2d');
            var bookingsByHotelChart = new Chart(ctxHotel, {
                type: 'doughnut',
                data: {
                    labels: hotelLabels,
                    datasets: [{
                        data: hotelData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Percentage of Bookings by Hotel Name'
                        }
                    }
                }
            });

            // đặt kích thước cho biểu đồ
            var canvas = document.getElementById('bookingsByDaysChart');
            canvas.width = 200;
            canvas.height = 200;
            // Bookings by Days
            var ctxDays = document.getElementById('bookingsByDaysChart').getContext('2d');
            var bookingsByDaysChart = new Chart(ctxDays, {
                type: 'doughnut',
                data: {
                    labels: daysLabels,
                    datasets: [{
                        data: daysData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Percentage of Bookings by Days'
                        }
                    }
                }
            });

            // đặt kích thước cho biểu đồ
            var canvas = document.getElementById('totalBookingAmountChart');
            canvas.width = 200;
            canvas.height = 200;
            // Total Booking Amount by Month
            var ctxMonthly = document.getElementById('totalBookingAmountChart').getContext('2d');
            var totalBookingAmountChart = new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Total Amount',
                        data: monthlyData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Total Booking Amount by Month'
                        }
                    }
                }
            });
        });


    </script>
@endsection
