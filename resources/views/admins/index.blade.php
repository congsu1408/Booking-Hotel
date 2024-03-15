@extends('layouts.admin')

@section('content')


<div class="row">
    <div class="col-md-4">
        <div class="card">
            <a href="{{route('hotels.all')}} " style="text-decoration: none; color: black ">
            <div class="card-body">
                <h5 class="card-title">Hotels</h5>
                <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
                <p class="card-text">number of hotels: {{$hotelCount}}</p>

            </div>
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <a href="{{route('rooms.all')}} " style="text-decoration: none; color: black ">
            <div class="card-body">
                <h5 class="card-title">Rooms</h5>

                <p class="card-text">number of rooms: {{$roomsCount}}</p>

            </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <a href="{{route('admins.all')}} " style="text-decoration: none; color: black ">
            <div class="card-body">
                <h5 class="card-title">Admins</h5>

                <p class="card-text">number of admins: {{$adminsCount}}</p>

            </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <a href="{{route('bookings.all')}} " style="text-decoration: none; color: black ">
            <div class="card-body">
                <h5 class="card-title">Bookings</h5>

                <p class="card-text">number of bookings: {{$bookingsCount}}</p>

            </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <a href="{{route('users.all')}} " style="text-decoration: none; color: black ">
            <div class="card-body">
                <h5 class="card-title">Users</h5>

                <p class="card-text">number of users: {{$usersCount}}</p>

            </div>
            </a>
        </div>
    </div>
</div>
@endsection
