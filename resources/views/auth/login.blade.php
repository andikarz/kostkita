<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk ke Kost Kita</title>
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
  </head>
  <body>
    <div class="container-login">
      <!-- Panel kiri -->
      <div class="left-panel">
        <h1>Selamat Datang di Kost Kita!</h1>
        <p>Masuk untuk melanjutkan pencarian atau kelola kost-mu</p>
        <a href="https://wa.me/628123456789" target="_blank" class="btn-help">Hubungi Admin</a>
      </div>

      <!-- Panel kanan -->
      <div class="right-panel">
        <h2>Masuk ke kost kita</h2>

        {{-- Status pesan dari laravel (mis. "Password reset link sent") --}}
        @if (session('status'))
          <p id="errorMsg" style="display:block;color:#16a34a">{{ session('status') }}</p>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
          @csrf

          <label for="email"></label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Username atau Email"
            value="{{ old('email') }}"
            required
            autocomplete="username"
            autofocus
          />
          @error('email')
            <p class="field-error" style="color:#ff4d4f;margin-top:-8px;margin-bottom:10px">{{ $message }}</p>
          @enderror

          <label for="password"></label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Password"
            required
            autocomplete="current-password"
          />
          @error('password')
            <p class="field-error" style="color:#ff4d4f;margin-top:-8px;margin-bottom:10px">{{ $message }}</p>
          @enderror

          @if ($errors->has('email') && !$errors->has('password'))
            {{-- pesan gagal login default Laravel biasanya menempel ke 'email' --}}
            <p id="errorMsg" style="display:block">{{ $errors->first('email') }}</p>
          @endif

          <div class="options">
            <label class="ingat">
              <input type="checkbox" id="rememberMe" name="remember" {{ old('remember') ? 'checked' : '' }}/> Ingat Saya
            </label>

            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="forgot">Lupa Password?</a>
            @endif
          </div>

          <button type="submit" class="btn-masuk">Masuk</button>

          <p class="signup">
            Belum punya akun?
            @if (Route::has('register'))
              <a href="{{ route('register') }}">Daftar disini</a>
            @else
              <a href="#">Daftar disini</a>
            @endif
          </p>
        </form>
      </div>
    </div>

    {{-- kalau butuh JS custom, taruh di public/js & panggil asset() --}}
    {{-- <script src="{{ asset('js/pengguna/loginpengguna.js') }}"></script> --}}
  </body>
</html>
