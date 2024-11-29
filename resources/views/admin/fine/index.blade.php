@extends('layouts.admin')

@section('content')
    <style>
        body {
            overflow-y: auto;
        }

        .fine-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .fine-header {
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 12px 15px;
        }

        @media (max-width: 576px) {
            .table td,
            .table th {
                font-size: 12px;
                padding: 8px;
            }

            .fine-header h5 {
                font-size: 16px;
            }
        }
    </style>

    <div class="container-fluid mt-4">
        <h5 class="mb-4 text-white text-center text-md-start">Manajemen Denda</h5>

        <div class="row">
            <div class="col-12">
                <!-- Card untuk Denda Keterlambatan -->
                <div class="card fine-card">
                    <div class="card-header fine-header text-white bg-black" style="border: 2px solid white">
                        <h5 class="mb-0">Denda Keterlambatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Judul Buku</th>
                                        <th>Peminjam</th>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th>Keterlambatan</th>
                                        <th>Jumlah Denda</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lateFines as $index => $fine)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $fine->borrow->book->judul }}</td>
                                            <td>{{ $fine->user->name }}</td>
                                            <td>{{ $fine->borrow->due_date->format('d/m/Y') }}</td>
                                            <td>{{ $fine->borrow->due_date->diffInDays(now()) }} hari</td>
                                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($fine->is_paid)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$fine->is_paid)
                                                    <button type="button" onclick="markAsPaid({{ $fine->id }})"
                                                        class="btn btn-sm" style="background-color: #00328E; color: white;">
                                                        <i class="fas fa-check"></i> Tandai Lunas
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada denda keterlambatan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end me-2">
                            <li class="page-item {{ $lateFines->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $lateFines->previousPageUrl() }}" style="color: #00328E;">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $lateFines->lastPage(); $i++)
                                <li class="page-item {{ $i == $lateFines->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $lateFines->url($i) }}"
                                        style="{{ $i == $lateFines->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$lateFines->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $lateFines->nextPageUrl() }}" style="color: #00328E;">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="col-12">
                <!-- Card untuk Denda Kerusakan -->
                <div class="card fine-card">
                    <div class="card-header fine-header text-white" style="background-color: #00328E; border: 2px solid white;">
                        <h5 class="mb-0">Denda Kerusakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Judul Buku</th>
                                        <th>Peminjam</th>
                                        <th>Tanggal Rusak</th>
                                        <th>Jumlah Denda</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($brokenFines as $index => $fine)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $fine->borrow->book->judul }}</td>
                                            <td>{{ $fine->user->name }}</td>
                                            <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($fine->is_paid)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$fine->is_paid)
                                                    <button onclick="markAsPaid({{ $fine->id }})" class="btn btn-sm"
                                                        style="background-color: #00328E; color: white;">
                                                        <i class="fas fa-check"></i> Tandai Lunas
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada denda kerusakan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end me-2">
                            <li class="page-item {{ $brokenFines->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $brokenFines->previousPageUrl() }}">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $brokenFines->lastPage(); $i++)
                                <li class="page-item {{ $i == $brokenFines->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $brokenFines->url($i) }}" style="{{ $i == $brokenFines->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$brokenFines->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $brokenFines->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="col-12">
                <!-- Card untuk Denda Kehilangan -->
                <div class="card fine-card">
                    <div class="card-header fine-header bg-danger text-white" style="border: 2px solid white">
                        <h5 class="mb-0">Denda Kehilangan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Judul Buku</th>
                                        <th>Peminjam</th>
                                        <th>Tanggal Hilang</th>
                                        <th>Jumlah Denda</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lostFines as $index => $fine)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $fine->borrow->book->judul }}</td>
                                            <td>{{ $fine->user->name }}</td>
                                            <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($fine->is_paid)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$fine->is_paid)
                                                    <button onclick="markAsPaid({{ $fine->id }})" class="btn btn-sm"
                                                        style="background-color: #00328E; color: white;">
                                                        <i class="fas fa-check"></i> Tandai Lunas
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada denda kehilangan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end me-2">
                            <li class="page-item {{ $lostFines->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $lostFines->previousPageUrl() }}">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $lostFines->lastPage(); $i++)
                                <li class="page-item {{ $i == $lostFines->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $lostFines->url($i) }}" style="{{ $i == $lostFines->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ !$lostFines->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $lostFines->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let metaTag = document.createElement('meta');
        metaTag.setAttribute('name', 'csrf-token');
        metaTag.setAttribute('content', '{{ csrf_token() }}');
        document.head.appendChild(metaTag);

        function markAsPaid(fineId) {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin menandai denda ini sebagai lunas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tandai Lunas',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/fines/mark-as-paid/${fineId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                fine_id: fineId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Denda telah ditandai sebagai lunas',
                                    icon: 'success',
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Gagal!', 'Terjadi kesalahan pada server', 'error');
                        });
                }
            });
        }
    </script>
@endsection