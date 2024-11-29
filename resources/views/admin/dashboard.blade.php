@extends('layouts.admin')

@section('content')
<style>
    /* Base Styles */
    body {
        overflow-y: auto;
        overflow-x: hidden; /* Prevent horizontal scrolling */
        background-color: #00328E; /* Light background for better readability */
    }

    .container {
        padding-bottom: 20px;
        max-width: 1200px; /* Limit container width on larger screens */
    }

    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        margin-bottom: 20px;
        transition: transform 0.3s ease; /* Subtle hover effect */
    }

    .card:hover {
        transform: translateY(-5px);
    }

    /* Typography Adjustments */
    .card-title {
        font-size: 1rem;
        color: #00328E;
        margin-bottom: 0.5rem;
    }

    .card-text {
        color: #00328E;
        font-weight: bold;
    }

    /* Responsive Image Sizing */
    .card-icon {
        width: 50px;
        height: 50px;
        object-fit: contain; /* Ensure images scale correctly */
    }

    /* Enhanced Responsiveness */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .card {
            margin-bottom: 15px;
        }

        .card-body {
            flex-direction: column;
            align-items: flex-start !important;
            text-align: left;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            margin-top: 10px;
            align-self: flex-start;
        }

        .card-title {
            font-size: 0.9rem;
        }

        .card-text {
            font-size: 1rem;
        }

        /* Responsive Table */
        .table-responsive {
            font-size: 0.85rem;
        }

        .table th, .table td {
            padding: 0.5rem;
        }

        /* Pagination Responsiveness */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination li {
            margin: 2px;
        }

        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .card-title {
            font-size: 0.8rem;
        }

        .card-text {
            font-size: 0.9rem;
        }

        .card-icon {
            width: 35px;
            height: 35px;
        }
    }
</style>

<div class="container-fluid px-4">
    <h5 class="mt-4 text-white">Dashboard</h5>

    <!-- Statistics Cards -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mt-4">
        <!-- User Statistics -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text fs-5">{{ $userCount }}</p>
                    </div>
                    <img src="{{ asset('images/user (4).png') }}" alt="Total Users" class="card-icon">
                </div>
            </div>
        </div>

        <!-- Books Statistics -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Books</h5>
                        <p class="card-text fs-5">{{ $bookCount }}</p>
                    </div>
                    <img src="{{ asset('images/books-stack-of-three (1).png') }}" alt="Total Books" class="card-icon">
                </div>
            </div>
        </div>

        <!-- Borrowing Statistics -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Borrowing</h5>
                        <p class="card-text fs-5">{{ $borrowCount }}</p>
                    </div>
                    <img src="{{ asset('images/borrow (1).png') }}" alt="Total Borrowing" class="card-icon">
                </div>
            </div>
        </div>
    </div>

    <!-- Fines Statistics -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mt-4">
        <!-- Paid Fines -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Denda Lunas</h5>
                        <p class="card-text fs-5">{{ $paidFinesCount }}</p>
                    </div>
                    <img src="{{ asset('images/money.png') }}" alt="Paid Fines" class="card-icon">
                </div>
            </div>
        </div>

        <!-- Total Payment -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Pembayaran</h5>
                        <p class="card-text fs-6">Rp {{ number_format($totalPaidAmount, 0, ',', '.') }}</p>
                    </div>
                    <img src="{{ asset('images/salary (2).png') }}" alt="Total Payment" class="card-icon">
                </div>
            </div>
        </div>

        <!-- Unpaid Fines -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Denda Belum Lunas</h5>
                        <p class="card-text fs-6">{{ $unpaidFinesCount }}</p>
                    </div>
                    <img src="{{ asset('images/paper-money.png') }}" alt="Unpaid Fines" class="card-icon">
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Paid Fines Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #00328E;">
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

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="d-flex justify-content-center my-3">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $recentPaidFines->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $recentPaidFines->previousPageUrl() }}">Previous</a>
                        </li>
            
                        @php
                            $start = max(1, $recentPaidFines->currentPage() - 1);
                            $end = min($start + 3, $recentPaidFines->lastPage());
                            $start = max(1, $end - 3);
                        @endphp
            
                        {{-- Pagination Elements --}}
                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $recentPaidFines->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $recentPaidFines->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
            
                        {{-- Next Page Link --}}
                        <li class="page-item {{ !$recentPaidFines->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $recentPaidFines->nextPageUrl() }}">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection