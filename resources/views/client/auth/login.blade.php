<h1>Beautician Login</h1>
<form method="POST" action="{{ route('beautician.login') }}">
    @csrf
    <div>
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>
<a href="{{ route('beautician.register') }}">Register</a>

