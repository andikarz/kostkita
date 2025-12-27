<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Password - Kost Kita</title>
    <link rel="stylesheet" href="{{ asset('./css/style.css') }}">
  </head>
  <body>
    <div class="container-forgot">
      {{-- KIRI: Form reset --}}
      <div class="left-form">
        <h2 class="title-forgot">Lupa Password</h2>

        {{-- status sukses (link terkirim) --}}
        @if (session('status'))
          <p class="notif-success" style="margin-bottom:12px">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
          @csrf

          <label for="email"></label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Email"
            required
            autofocus
          />
          @error('email')
            <p class="field-error">{{ $message }}</p>
          @enderror

          <p class="help-text">
            Masukkan e-mail untuk menerima tautan reset.
          </p>

          <button type="submit" class="btn-daftar btn-reset">Kirim Tautan Reset</button>
        </form>
      </div>
    </div>
  </body>
</html>
