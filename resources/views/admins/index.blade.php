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
        document.addEventListener('DOMContentLoaded', function() {
            var bookingsByMonthChart, bookingsByHotelChart, revenueByMonthChart;

            // Initialize or update charts
            function updateCharts(data) {
                // Bookings by Month Chart
                if (!bookingsByMonthChart) {
                    const ctxBookingsByMonth = document.getElementById('bookingsByMonthChart').getContext('2d');
                    bookingsByMonthChart = new Chart(ctxBookingsByMonth, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Bookings',
                                data: [],
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
                }
                bookingsByMonthChart.data.labels = Object.keys(data.bookingsByMonth).map(function(month) {
                    return new Date(0, month - 1).toLocaleString('en', { month: 'long' });
                });
                bookingsByMonthChart.data.datasets[0].data = Object.values(data.bookingsByMonth);
                bookingsByMonthChart.update();

                // Bookings by Hotel Chart
                if (!bookingsByHotelChart) {
                    const ctxBookingsByHotel = document.getElementById('bookingsByHotelChart').getContext('2d');
                    bookingsByHotelChart = new Chart(ctxBookingsByHotel, {
                        type: 'doughnut',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Bookings',
                                data: [],
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
                }
                bookingsByHotelChart.data.labels = Object.keys(data.bookingsByHotel);
                bookingsByHotelChart.data.datasets[0].data = Object.values(data.bookingsByHotel);
                bookingsByHotelChart.update();

                // Revenue by Month Chart
                if (!revenueByMonthChart) {
                    const ctxRevenueByMonth = document.getElementById('revenueByMonthChart').getContext('2d');
                    revenueByMonthChart = new Chart(ctxRevenueByMonth, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Revenue',
                                data: [],
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
                }
                revenueByMonthChart.data.labels = Object.keys(data.revenueByMonth).map(function(month) {
                    return new Date(0, month - 1).toLocaleString('en', { month: 'long' });
                });
                revenueByMonthChart.data.datasets[0].data = Object.values(data.revenueByMonth);
                revenueByMonthChart.update();
            }

            // Fetch data from server
            function fetchData() {
                fetch('{{ route('get.data') }}')
                    .then(response => response.json())
                    .then(data => {
                        updateCharts(data);
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Initial fetch
            fetchData();

            // Fetch new data every 60 seconds
            setInterval(fetchData, 5000);
        });

    </script>


@endsection
