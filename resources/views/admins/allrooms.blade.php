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

        // Dữ liệu cho biểu đồ số phòng của khách sạn
        const hotelRoomData = {
            labels: {!! json_encode(array_keys($hotelRoomCounts)) !!},
            datasets: [{
                label: 'Number of Rooms',
                data: {!! json_encode(array_values($hotelRoomCounts)) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderWidth: 1
            }]
        };

        const hotelRoomConfig = {
            type: 'bar',
            data: hotelRoomData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };


        const hotelRoomChart = new Chart(
            document.getElementById('hotelRoomChart'),
            hotelRoomConfig
        );

        // Dữ liệu cho biểu đồ loại view
        const viewData = {
            labels: {!! json_encode(array_keys($viewCounts->toArray())) !!},
            datasets: [{
                label: 'Number of Rooms',
                data: {!! json_encode(array_values($viewCounts->toArray())) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderWidth: 1
            }]
        };

        const viewConfig = {
            type: 'pie',
            data: viewData
        };

        const viewChart = new Chart(
            document.getElementById('viewChart'),
            viewConfig
        );

        // Dữ liệu cho biểu đồ giá
        const priceData = {
            labels: {!! json_encode($rooms->pluck('name')) !!},
            datasets: [{
                label: 'Room Prices',
                data: {!! json_encode($prices) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderWidth: 1
            }]
        };

        const priceConfig = {
            type: 'line',
            data: priceData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const priceChart = new Chart(
            document.getElementById('priceChart'),
            priceConfig
        );
    </script>
@endsection
