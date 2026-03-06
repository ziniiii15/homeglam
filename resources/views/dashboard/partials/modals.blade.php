<!-- Beauticians Modal -->
<div class="modal fade" id="beauticiansModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="beauticiansModalTitle">Choose a Specialist</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light">
        <div class="d-flex flex-column gap-3" id="beauticiansCards">
            <!-- JS will populate this -->
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 bg-light">
        <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="bookingServiceName">Book Service</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('client.bookings.store') }}">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="service_id" id="modalServiceId">
            <input type="hidden" name="beautician_id" id="modalBeauticianId">
            <input type="hidden" name="service_name" id="modalServiceName">

            <div class="mb-3">
                <label for="modalBookingDate" class="form-label fw-semibold">Date</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                    <input type="date" name="booking_date" id="modalBookingDate" class="form-control border-start-0 ps-0" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="modalBookingTime" class="form-label fw-semibold">Time</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock"></i></span>
                    <select name="booking_time" id="modalBookingTime" class="form-select border-start-0 ps-0" required>
                        <option value="">Select Date First</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Location</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="location" class="form-control border-start-0 ps-0" placeholder="Enter your address" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Special Instructions</label>
                <textarea name="client_notes" class="form-control" rows="2" placeholder="Any specific requests?"></textarea>
            </div>
        </div>
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4">Confirm Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
   <form id="editProfileForm" class="modal-content" method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                @php
                    $client = auth()->guard('client')->user();
                    $photo = $client && $client->photo_url ? asset($client->photo_url) : 'https://ui-avatars.com/api/?name=' . ($client->name ?? 'User');
                @endphp
                <img src="{{ $photo }}" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                <label for="photoUpload" class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer border">
                    <i class="bi bi-camera-fill text-primary"></i>
                    <input type="file" name="photo" id="photoUpload" class="d-none">
                </label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $client->name ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $client->email ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
        </div>
    </div>
    <div class="modal-footer border-0">
        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
    </div>
   </form>
  </div>
</div>

<!-- Existing Booking Warning Modal -->
<div class="modal fade" id="existingBookingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title fw-bold">Booking Exists</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <i class="bi bi-exclamation-circle display-4 text-danger mb-3 d-block"></i>
        <p class="mb-0 fw-medium">
          You already have a booking for this service with this specialist on this date.
        </p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Okay</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
@if(session('booking_exists'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('existingBookingModal'));
    modal.show();
});
</script>
@endif
@endpush
