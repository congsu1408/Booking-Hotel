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
                <h5 class="card-title mb-4 d-inline">Rooms</h5>
                <a  href="{{route('rooms.create')}}" class="btn btn-primary mb-4 text-center float-right">Create Room</a>
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
                            <td><img src="{{asset('assets/images/'.$room->image.'')}}" alt="" style="width: 80px; height: 80px;"></td>
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
                            <td><a href="{{route('rooms.delete',$room->id)}}" class="btn btn-danger  text-center ">Delete </a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
