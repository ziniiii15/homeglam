@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h2>Discount Details</h2>
<div class="card"><div class="card-body">
<p><strong>ID:</strong> {{ $discount->id }}</p>
<p><strong>Code:</strong> {{ $discount->code }}</p>
<p><strong>Type:</strong> {{ $discount->discount_type }}</p>
<p><strong>Value:</strong> {{ $discount->value }}</p>
<p><strong>Valid From:</strong> {{ $discount->valid_from }}</p>
<p><strong>Valid To:</strong> {{ $discount->valid_to }}</p>
<p><strong>Applicable To:</strong> {{ $discount->applicable_to }}</p>
</div></div>
<a href="{{ route('discounts.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

