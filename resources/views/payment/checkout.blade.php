@extends('layouts.main')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pembayaran Sewa Kost</h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Detail Kost</h6>
                                <p class="mb-0"><strong>{{ $booking->kost->nama ?? 'Kost' }}</strong></p>
                                <p class="text-muted small">{{ $booking->kost->alamat ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6>ID Booking</h6>
                                <p class="mb-0 text-primary fw-bold">#{{ $booking->id }}</p>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Keterangan</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Harga Sewa per Bulan</td>
                                        <td class="text-end">Rp
                                            {{ number_format($booking->harga_per_bulan ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Lama Sewa</td>
                                        <td class="text-end">{{ $booking->lama_sewa }} Bulan</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Admin</td>
                                        <td class="text-end">Rp {{ number_format($booking->pajak ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Layanan</td>
                                        <td class="text-end">Rp
                                            {{ number_format($booking->biaya_layanan ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="fw-bold table-light">
                                        <td>Total Pembayaran</td>
                                        <td class="text-end text-success">Rp
                                            {{ number_format($payment->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2">
                            <button id="pay-button" class="btn btn-primary btn-lg">
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
        <!-- Note: Change URL to production for live: https://app.midtrans.com/snap/snap.js -->

        <script type="text/javascript">
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function () {
                // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                window.snap.pay('{{ $payment->snap_token }}', {
                    onSuccess: function (result) {
                        /* You may add your own implementation here */
                        // alert("payment success!"); 
                        window.location.href = "{{ route('payment.finish') }}";
                    },
                    onPending: function (result) {
                        /* You may add your own implementation here */
                        alert("wating your payment!"); console.log(result);
                    },
                    onError: function (result) {
                        /* You may add your own implementation here */
                        alert("payment failed!"); console.log(result);
                    },
                    onClose: function () {
                        /* You may add your own implementation here */
                        alert('you closed the popup without finishing the payment');
                    }
                });
            });
        </script>
    @endpush
@endsection