<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kost Kita</title>

  {{-- Bootstrap --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <style>
    .hero-slide {
      height: 420px;
      object-fit: cover;
    }

    .kost-card img {
      height: 180px;
      object-fit: cover;
    }

    .footer-main {
      background: #145391;
      color: #bfbfbf;
      padding: 60px 0 40px;
      font-size: 14px;
    }

    .footer-main h5 {
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .footer-main p,
    .footer-main li {
      color: #ffffff !important;
    }

    .footer-contact-item {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }

    .footer-contact-icon {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #ff7a29;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      margin-right: 10px;
      color: #fff;
    }

    .footer-subscribe .form-control {
      background: #252525;
      border: none;
      border-radius: 30px 0 0 30px;
      color: #bfbfbf;
      font-size: 13px;
    }

    .footer-subscribe .form-control:focus {
      box-shadow: none;
      background: #2b2b2b;
      color: #fff;
    }

    .footer-subscribe .btn-send {
      border-radius: 0 30px 30px 0;
      border: none;
      padding: 0 25px;
      font-weight: 600;
      background: #ff7a29;
      color: #fff;
    }

    .footer-subscribe .btn-send:hover {
      background: #ff8b45;
    }

    .footer-instagram img {
      width: 100%;
      height: 80px;
      object-fit: cover;
      display: block;
      margin-bottom: 10px;
    }

    .footer-bottom {
      background: #111111;
      color: #bfbfbf;
      border-top: 1px solid rgba(189, 21, 21, 0.04);
      font-size: 13px;
      padding: 12px 0;
    }

    .footer-bottom a {
      color: #bfbfbf;
      text-decoration: none;
      margin-right: 18px;
    }

    .footer-bottom a:hover {
      color: #ffffff;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background-color: #145391;">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('img/logo kostkita.png') }}" alt="Kost Kita" height="30" class="me-2">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto">
          @auth
            <li class="nav-item me-2 mt-1 text-white">
              Halo, {{ auth()->user()->name }}
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link p-0 d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="{{ asset('img/default.png') }}" alt="User Profile" class="rounded-circle border" width="35"
                  height="35">
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit">Keluar</button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item">
              <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2" style="color: white;">Masuk</a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>


  <main class="py-4">
    @if(session('success'))
      <div class="container mb-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif
    @if(session('error'))
      <div class="container mb-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    @yield('content')
  </main>

  <footer>

    <!-- FOOTER ATAS -->
    <div class="footer-main">
      <div class="container">
        <div class="row gy-4">

          <!-- Kolom 1 -->
          <div class="col-md-5 d-flex flex-column">
            <h5>Tentang KostKita</h5>
            <p>
              KostKita adalah platform penyedia informasi kost di sekitar Purwokerto.
              Kami menyajikan data harga, fasilitas, dan foto kost yang sudah terverifikasi
              sehingga memudahkan Anda menemukan kost yang tepat dan aman.
            </p>
          </div>

          <!-- Kolom 2 -->
          <div class="col-md-4">
            <h5>Info Terbaru</h5>
            <p>
              Kami terus menambahkan data kost baru yang sudah diverifikasi pemiliknya.
            </p>
            <p>
              Gunakan filter lokasi untuk menemukan kost terdekat dari kampus atau tempat kerja Anda.
            </p>
          </div>

          <!-- Kolom 3 -->
          <div class="col-md-3 text-center">
            <h5>Hubungi Kami</h5>

            <div class="d-flex justify-content-center gap-3 mt-3">
              <a href="https://wa.me/6281234567890" target="_blank">
                <img src="{{ asset('img/logowa.png') }}" height="30" alt="WhatsApp">
              </a>

              <a href="mailto:info@kostkita.com">
                <img src="{{ asset('img/logoemail.png') }}" height="30" alt="Email">
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-2 mb-md-0">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('about') }}">Tentang</a>
          <a href="{{ route('terms') }}">Kebijakan Privasi</a>
        </div>
        <div>
          &copy; {{ date('Y') }} Kost Kita
        </div>
      </div>
    </div>


  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @stack('scripts')
</body>

</html>