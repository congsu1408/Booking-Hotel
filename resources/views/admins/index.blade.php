@extends('layouts.admin')

@section('content')

    <div class="row">
        <!-- Các phần tử thống kê như trước -->

        <!-- Biểu đồ số lượng đặt phòng theo tháng -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings by Month</h5>
                    <canvas id="bookingsByMonthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ tỷ lệ đặt phòng theo tên khách sạn -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings by Hotel</h5>
                    <canvas id="bookingsByHotelChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu theo tháng -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue by Month</h5>
                    <canvas id="revenueByMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dữ liệu từ server
        var bookingsByMonth = @json($bookingsByMonth);
        var revenueByMonth = @json($revenueByMonth);
        var bookingsByHotel = @json($bookingsByHotel);

        // Xử lý dữ liệu cho biểu đồ số lượng đặt phòng theo tháng
        var bookingsByMonthLabels = Object.keys(bookingsByMonth).map(function(month) {
            return new Date(0, month-1).toLocaleString('en', { month: 'long' });
        });
        var bookingsByMonthData = Object.values(bookingsByMonth);

        // Xử lý dữ liệu cho biểu đồ doanh thu theo tháng
        var revenueByMonthLabels = Object.keys(revenueByMonth).map(function(month) {
            return new Date(0, month-1).toLocaleString('en', { month: 'long' });
        });
        var revenueByMonthData = Object.values(revenueByMonth);

        // Xử lý dữ liệu cho biểu đồ tỷ lệ đặt phòng theo tên khách sạn
        var bookingsByHotelLabels = Object.keys(bookingsByHotel);
        var bookingsByHotelData = Object.values(bookingsByHotel);

        // Khởi tạo biểu đồ số lượng đặt phòng theo tháng
        var ctxBookingsByMonth = document.getElementById('bookingsByMonthChart').getContext('2d');
        new Chart(ctxBookingsByMonth, {
            type: 'bar',
            data: {
                labels: bookingsByMonthLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsByMonthData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false
            }
        });

        // Khởi tạo biểu đồ tỷ lệ đặt phòng theo tên khách sạn
        var ctxBookingsByHotel = document.getElementById('bookingsByHotelChart').getContext('2d');
        new Chart(ctxBookingsByHotel, {
            type: 'doughnut',
            data: {
                labels: bookingsByHotelLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsByHotelData,
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
                maintainAspectRatio: false
            }
        });

        // Khởi tạo biểu đồ doanh thu theo tháng
        var ctxRevenueByMonth = document.getElementById('revenueByMonthChart').getContext('2d');
        new Chart(ctxRevenueByMonth, {
            type: 'bar',
            data: {
                labels: revenueByMonthLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueByMonthData,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
