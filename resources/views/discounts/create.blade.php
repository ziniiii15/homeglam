@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Add Discount</h2>
<form action="{{ route('discounts.store') }}" method="POST">@csrf
<div class="mb-3"><label>Code</label><input type="text" name="code" class="form-control" required></div>
<div class="mb-3">
<label>Type</label>
<select name="discount_type" class="form-control" required>
<option value="amount">Amount</option>
<option value="percentage">Percentage</option>
</select>
</div>
<div class="mb-3"><label>Value</label><input type="number" name="value" class="form-control" step="0.01" required></div>
<div class="mb-3"><label>Valid From</label><input type="date" name="valid_from" class="form-control" required></div>
<div class="mb-3"><label>Valid To</label><input type="date" name="valid_to" class="form-control" required></div>
<div class="mb-3"><label>Applicable To (service_id or beautician_id)</label><input type="text" name="applicable_to" class="form-control"></div>
<button type="submit" class="btn btn-success">Save</button>
</form>
</div>
@endsection
