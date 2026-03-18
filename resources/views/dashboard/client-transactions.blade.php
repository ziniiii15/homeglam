<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Transactions - HomeGlam</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #D63384;
        --primary-light: #F8D7DA;
        --bg: #FFF5F7;
        --card-bg: #ffffff;
        --text-dark: #212529;
        --text-muted: #6c757d;
        --border-color: rgba(214, 51, 132, 0.2);
        --card-radius: 16px;
        --shadow-md: 0 6px 16px rgba(214, 51, 132, 0.15);
    }
    body {
        background: var(--bg);
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
        color: var(--text-dark);
    }
    .page-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 1rem 3rem;
    }
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .page-header h3 {
        font-weight: 700;
        color: var(--primary);
    }
    .card-transactions {
        background: var(--card-bg);
        border-radius: var(--card-radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }
    .table thead th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
    }
    .table tbody td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
</style>
</head>
<body>
<div class="page-wrapper">
    <div class="page-header">
        <div>
            <h3>My Transactions</h3>
            <p class="text-muted mb-0">All payments you've made for bookings.</p>
        </div>
        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card card-transactions">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width:36px;height:36px;">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <h6 class="mb-0">Payment History</h6>
                    <small class="text-muted">Including online payments and uploaded receipts.</small>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>Service</th>
                            <th>Beautician</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Proof</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->service->service_name ?? '-' }}</td>
                                <td>{{ $booking->beautician->name ?? '-' }}</td>
                                <td class="fw-semibold">₱{{ number_format($booking->down_payment_amount > 0 ? $booking->down_payment_amount : $booking->total_cost, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill 
                                        @if($booking->payment_status === 'paid') bg-success-subtle text-success
                                        @elseif($booking->payment_status === 'pending' || $booking->payment_status === 'pending_verification') bg-warning-subtle text-warning
                                        @elseif($booking->payment_status === 'refunded') bg-info-subtle text-info
                                        @else bg-secondary-subtle text-secondary @endif">
                                        {{ ucfirst($booking->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    @if($booking->payment_receipt_path)
                                        <a href="{{ asset($booking->payment_receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                            View Proof
                                        </a>
                                    @else
                                        <span class="text-muted small">Online / none</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at ? $booking->created_at->format('M d, Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No payment transactions found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


