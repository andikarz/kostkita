<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pengguna - Kost Kita</title>
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
  </head>
  <body>
    <div class="container-register">
      
      <!-- Panel kiri -->
      <div class="left-panel">
        <h1>Bergabung dengan Kost Kita!</h1>
        <p>Buat akun sekarang untuk mulai mencari atau mengelola kost.</p>
        <a href="https://wa.me/628123456789" target="_blank" class="btn-help">Hubungi Admin</a>
      </div>

      <!-- Panel kanan -->
      <div class="right-panel">
        <h2>Daftar di Kost Kita</h2>

        <!-- Pesan error global -->
        @if ($errors->any())
          <p id="errorMsg" style="display:block; color:#ff4d4f; text-align:center;">
            Ada data yang belum benar, silakan cek kembali.
          </p>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}">
          @csrf

          <label for="username"></label>
          <input type="text" id="username" name="username" 
          value="{{ old('username') }}" placeholder="Username" required />
          @error('username')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <label for="name"></label>
          <input type="text" id="name" name="name" 
          value="{{ old('name') }}" placeholder="Nama Lengkap" required />
          @error('name')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <label for="role"></label>
          <select id="role" name="role"required>
            <option value="">Daftar sebagai:</option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pencari Kost</option>
            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Pemilik Kost</option>
          </select>
          @error('role')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <label for="email"></label>
          <input type="email" id="email" name="email" 
          value="{{ old('email') }}" placeholder="Email" required />
          @error('email')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <label for="password"></label>
          <input type="password" id="password" name="password" 
          placeholder="Password" required />
          @error('password')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <label for="password_confirmation"></label>
          <input type="password" id="password_confirmation" name="password_confirmation" 
          placeholder="Ulangi Password" required />
          <button type="submit" class="btn-daftar">Daftar</button>

          <p class="login">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
          </p>
        </form>

      </div>
    </div>
  </body>
</html>
