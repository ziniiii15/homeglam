@extends('layouts.app')
@section('content')

<div class="container py-4">
    <h4 class="mb-4">Manage Time Slots</h4>

    <form action="{{ route('beautician.time-slots.store') }}" method="POST" class="card p-3 mb-4">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" name="slot_date" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
        </div>

        <button class="btn btn-primary mt-3">Add Slot</button>
    </form>

    <div class="card">
        <div class="card-header fw-bold">Existing Slots</div>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slots as $slot)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($slot->slot_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                    <td>
                        {{ $slot->is_booked ? 'Booked' : 'Available' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No slots yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

