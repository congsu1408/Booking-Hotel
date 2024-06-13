@extends('layouts.admin')

@section('content')
    <div class="row">
        <!-- Thông tin tổng quan -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Performance Report</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Total Rooms: {{ $totalRooms }}</p>
                            <p>Current Month Bookings: {{ $currentMonthBookings }}</p>
                            <p>Occupancy Rate: {{ number_format($occupancyRate, 2) }}%</p>
                        </div>
                        <div class="col-md-6">
                            <p>Current Month Revenue: ${{ number_format($currentMonthRevenue, 2) }}</p>
                            <p>Revenue per Available Room (RevPAR): ${{ number_format($RevPAR, 2) }}</p>
                        </div>
                    </div>
                    @if (!$meetsKpi)
                        <div class="alert alert-danger mt-3">
                            <strong>Warning:</strong> The current month revenue (${{ number_format($currentMonthRevenue, 2) }}) does not meet the KPI target of ${{ number_format($kpi, 2) }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
