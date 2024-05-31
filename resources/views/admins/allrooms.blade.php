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
                    <div class="charts-container" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="chart-item">
                            <h5 class="card-title mb-4 mt-4 text-center">Hotel Room Count</h5>
                            <canvas id="hotelRoomChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h5 class="card-title mb-4 mt-4 text-center">View Types</h5>
                            <canvas id="viewChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h5 class="card-title mb-4 mt-4 text-center">Room Prices</h5>
                            <canvas id="priceChart"></canvas>
                        </div>
                    </div>

                    <h5 class="card-title mb-4 d-inline">Rooms</h5>
                    <a href="{{route('rooms.create')}}" class="btn btn-primary mb-4 text-center float-right">Create Room</a>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Price</th>
                            <th scope="col">Max_persons</th>
                            <th scope="col">Size</th>
                            <th scope="col">View</th>
                            <th scope="col">Num of beds</th>
                            <th scope="col">Hotel name</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rooms as $room)
                            <tr>
                                <td>{{$room->name}}</td>
                                <td><img src="{{asset('assets/images/'.$room->image)}}" alt="" style="width: 80px; height: 80px;"></td>
                                <td>{{$room->price}}</td>
                                <td>{{$room->max_persons}}</td>
                                <td>{{$room->size}}</td>
                                <td>{{$room->view}}</td>
                                <td>{{$room->num_beds}}</td>
                                @foreach($hotels as $hotel)
                                    @if($hotel->id == $room->hotel_id)
                                        <td>{{$hotel->name}}</td>
                                    @endif
                                @endforeach
                                <td><a href="{{route('rooms.delete',$room->id)}}" class="btn btn-danger text-center">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>




                </div>
            </div>
        </div>
    </div>
    <style>
        .charts-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Điều chỉnh nếu cần thiết để các biểu đồ hiển thị tốt trên các màn hình nhỏ */
        }

        .chart-item {
            flex: 1;
            margin: 10px;
        }

        .chart-item canvas {
            width: 100% !important; /* Đảm bảo canvas chiếm toàn bộ chiều rộng của cha */
            height: 300px; /* Chiều cao cố định hoặc bạn có thể điều chỉnh cho phù hợp */
        }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hotelRoomChart, viewChart, priceChart;

            // Initialize or update charts
            function updateCharts(data) {
                // Update hotel room chart
                if (!hotelRoomChart) {
                    const ctxHotelRoom = document.getElementById('hotelRoomChart').getContext('2d');
                    hotelRoomChart = new Chart(ctxHotelRoom, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data.hotelRoomCounts),
                            datasets: [{
                                label: 'Number of Rooms',
                                data: Object.values(data.hotelRoomCounts),
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
                } else {
                    hotelRoomChart.data.labels = Object.keys(data.hotelRoomCounts);
                    hotelRoomChart.data.datasets[0].data = Object.values(data.hotelRoomCounts);
                    hotelRoomChart.update();
                }

                // Update view chart
                if (!viewChart) {
                    const ctxView = document.getElementById('viewChart').getContext('2d');
                    viewChart = new Chart(ctxView, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(data.viewCounts),
                            datasets: [{
                                label: 'Number of Rooms',
                                data: Object.values(data.viewCounts),
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
                        }
                    });
                } else {
                    viewChart.data.labels = Object.keys(data.viewCounts);
                    viewChart.data.datasets[0].data = Object.values(data.viewCounts);
                    viewChart.update();
                }

                // Update price chart
                if (!priceChart) {
                    const ctxPrice = document.getElementById('priceChart').getContext('2d');
                    priceChart = new Chart(ctxPrice, {
                        type: 'line',
                        data: {
                            labels: Object.keys(data.prices),
                            datasets: [{
                                label: 'Room Prices',
                                data: Object.values(data.prices),
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
                } else {
                    priceChart.data.labels = Object.keys(data.prices);
                    priceChart.data.datasets[0].data = Object.values(data.prices);
                    priceChart.update();
                }
            }

            // Fetch data from server
            function fetchData() {
                fetch('{{ route('get.rooms.data') }}')
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
