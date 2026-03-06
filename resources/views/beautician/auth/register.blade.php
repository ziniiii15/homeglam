<form action="{{ route('beautician.register') }}" method="POST">
    @csrf
    <!-- Your input fields: name, email, phone, etc. -->
    <button type="submit">Register</button>
</form>
