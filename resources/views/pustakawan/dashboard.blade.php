@extends('layouts.pustakawan')

@section('content')
<style>
    body{
        overflow-y: auto;
    }

    .container{
        padding-bottom: 10px;
    }
</style>
<div class="container">
<h5 class="mt-4 text-white">Dashboard</h5>
<div class="row mt-4">
    <div class="col">
        <div class="card ">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title" style="color: #00328E;">Total Users</h5>
                    <h5 class="card-text fw-bold fs-4" style="color: #00328E;">{{ $userCount }}</h5>
                </div>
                <img src="{{ asset('images/user (4).png') }}" alt="" style="width: 50px; height: 50px;">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-end">
                <div class="text-start">
                    <h5 class="card-title" style="color: #00328E;">Total Books</h5>
                    <p class="card-text fw-bold fs-5" style="color: #00328E;">{{ $bookCount }}</p>
                </div>
                <img src="{{ asset('images/books-stack-of-three (1).png') }}" alt="" style="width: 50px; height: 50px;">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title" style="color: #00328E;">Total Borrowing</h5>
                    <p class="card-text fw-bold fs-5"  style="color: #00328E;">{{ $borrowCount }}</p>
                </div>
                <img src="{{ asset('images/borrow (1).png') }}" alt="" style="width: 50px; height: 50px;">
            </div>
        </div>
    </div>
</div>

<!-- New Row for Fines Statistics -->
<div class="row  mt-4">
    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title" style="color: #00328E;">Total Denda Lunas</h5>
                    <h5 class="card-text fw-bold fs-5" style="color: #00328E;">{{ $paidFinesCount }}</h5>
                </div>
                <img src="{{ asset('images/money.png') }}" alt="" style="width: 50px; height: 50px;">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-end">
                <div class="text-start">
                    <h5 class="card-title" style="color: #00328E;">Total Pembayaran</h5>
                    <p class="card-text fw-bold fs-6" style="color: #00328E;">Rp {{ number_format($totalPaidAmount, 0, ',', '.') }}</p>
                </div>
                <img src="{{ asset('images/salary (2).png') }}" alt="" width="50">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title" style="color: #00328E;">Denda Belum Lunas</h5>
                    <p class="card-text fw-bold fs-6" style="color: #00328E;">{{ $unpaidFinesCount }}</p>
                </div>
                <img src="{{ asset('images/paper-money.png') }}" alt="" style="width: 50px; height: 50px;">
            </div>
        </div>
    </div>
</div>

<!-- Recent Paid Fines Table -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card ">
            <div class="card-header" style="background-color: #00328E; color: white;">
                <h5 class="mb-0">Pembayaran Denda Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Jenis Denda</th>
                                <th>Jumlah</th>
                                <th>Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPaidFines as $index => $fine)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fine->user->name }}</td>
                                <td>{{ $fine->borrow->book->judul }}</td>
                                <td>
                                    @if($fine->type == 'late')
                                        Keterlambatan
                                    @elseif($fine->type == 'damage')
                                        Kerusakan
                                    @else
                                        Kehilangan
                                    @endif
                                </td>
                                <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                <td>{{ $fine->paid_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pembayaran denda terbaru</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection