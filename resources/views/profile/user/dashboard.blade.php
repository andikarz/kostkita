@extends('profile.layout')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="siko-wrapper">
    {{-- MAIN --}}
    <main class="siko-main">
        
        {{-- Info cards atas --}}
        <div class="siko-cards-row">
            <div class="siko-card bg-purple">
                <div class="siko-card-title">Kamar Terisi</div>
                <div class="siko-card-value">
                    {{ $occupiedRooms ?? 2 }}
                </div>
                <div class="siko-card-sub">
                    Jumlah Penghuni {{ $totalTenants ?? 2 }}
                </div>
                <div class="siko-card-icon">
                    <i class="fas fa-bed"></i>
                </div>
            </div>

            <div class="siko-card bg-amber">
                <div class="siko-card-title">Kamar Kosong</div>
                <div class="siko-card-value">
                    {{ $emptyRooms ?? 5 }}
                </div>
                <div class="siko-card-sub">
                    dari {{ $totalRooms ?? 7 }} Kamar
                </div>
                <div class="siko-card-icon">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>
        </div>

        {{-- Grafik perbandingan kamar terisi vs kosong --}}
        <div class="siko-panel">
            <div class="siko-panel-header">
                <div>
                    <div class="siko-panel-title">
                        Statistik Kamar {{ $year ?? 2022 }}
                    </div>
                    <div class="siko-panel-sub">
                        Perbandingan Kamar Terisi dan Kosong Tahun Ini
                        dengan Tahun Lalu
                    </div>
                </div>
                <div class="siko-legend">
                    <span class="leg-income">Kamar Terisi</span>
                    <span class="leg-expense">Kamar Kosong</span>
                </div>
            </div>

            {{-- ganti id canvas kalau perlu, sesuaikan juga di script JS --}}
            <canvas id="roomChart" height="100"></canvas>

            <div class="text-center mt-2" style="font-size: 11px; color:#6b7280;">
                Data tahunan kamar terisi vs kamar kosong dibandingkan dengan tahun lalu
            </div>

            {{-- Strip ringkasan --}}
            <div class="siko-strip mt-3">
                <div class="siko-strip-item bg-soft-blue">
                    <div class="siko-strip-label">Kamar Terisi Tahun Ini</div>
                    <div class="siko-strip-value">
                        {{ $occupiedThisYear ?? 0 }}
                    </div>
                    <div class="siko-strip-extra">
                        {{ $occupiedThisYearPercentage ?? '0%' }}
                    </div>
                </div>

                <div class="siko-strip-item bg-soft-yellow">
                    <div class="siko-strip-label">Kamar Kosong Tahun Ini</div>
                    <div class="siko-strip-value">
                        {{ $emptyThisYear ?? 0 }}
                    </div>
                    <div class="siko-strip-extra">
                        {{ $emptyThisYearPercentage ?? '0%' }}
                    </div>
                </div>

                <div class="siko-strip-item bg-soft-green">
                    <div class="siko-strip-label">Kamar Terisi Tahun Lalu</div>
                    <div class="siko-strip-value">
                        {{ $occupiedLastYear ?? 0 }}
                    </div>
                    <div class="siko-strip-extra">
                        {{ $occupiedLastYearPercentage ?? '0%' }}
                    </div>
                </div>

                <div class="siko-strip-item bg-soft-red">
                    <div class="siko-strip-label">Kamar Kosong Tahun Lalu</div>
                    <div class="siko-strip-value">
                        {{ $emptyLastYear ?? 0 }}
                    </div>
                    <div class="siko-strip-extra">
                        {{ $emptyLastYearPercentage ?? '0%' }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection
