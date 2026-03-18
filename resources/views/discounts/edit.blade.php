@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Edit Discount</h2>
<form action="{{ route('discounts.update', $discount->id) }}" method="POST">@csrf @method('PUT')
<div class="mb-3"><label>Code</label><input type="text" name="code" value="{{ $discount->code }}" class="form-control" required></div>
<div class="mb-3">
<label>Type</label>
<select name="discount_type" class="form-control" required>
<option value="amount" {{ $discount->discount_type=='amount'?'selected':'' }}>Amount</option>
<option value="percentage" {{ $discount->discount_type=='percentage'?'selected':'' }}>Percentage</option>
</select>
</div>
<div class="mb-3"><label>Value</label><input type="number" name="value" value="{{ $discount->value }}" class="form-control" step="0.01" required></div>
<div class="mb-3"><label>Valid From</label><input type="date" name="valid_from" value="{{ $discount->valid_from }}" class="form-control" required></div>
<div class="mb-3"><label>Valid To</label><input type="date" name="valid_to" value="{{ $discount->valid_to }}" class="form-control" required></div>
<div class="mb-3"><label>Applicable To</label><input type="text" name="applicable_to" value="{{ $discount->applicable_to }}" class="form-control"></div>
<button type="submit" class="btn btn-success">Update</button>
</form>
</div>
@endsection

