<x-layout>
    <h1 class="large-title">Register</h1>
    <section>
        <form method="POST" action="/register">
            @csrf
            <div>
                <label for="username">Username</label>
                <input class="border rounded-sm" type="text" name="username" id="username" value="{{ old('username') }}"
                    required>
                @error('username')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email">E-mail</label>
                <input class="border rounded-sm" type="email" name="email" id="email"
                    value="{{ old('email') }}" required>
                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input class="border rounded-sm" type="password" name="password" id="password" required>
                @error('password')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            <button type="submit">Submit</button>
        </form>
    </section>
</x-layout>
