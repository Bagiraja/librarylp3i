        @extends('layouts.admin')

        @section('content')
        <style>
            body{
                overflow-y: auto;
                overflow-x: auto;
            }

            .container-fluid{
                padding-bottom: 10px;
            }

            .badge {
        padding: 8px 12px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .bg-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
    
    .bg-danger {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    
    .bg-success {
        background-color: #28a745 !important;
        color: #fff !important;
    }
    
    .bg-info {
        background-color: #17a2b8 !important;
        color: #fff !important;
    }

            .custom-card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
            .custom-card .card-header {
                border-radius: 10px 10px 0 0;
                padding: 15px 20px;
            }
            .table thead th {
                background-color: #f8f9fa;
                border-bottom: none ;
            }
            .table td, .table th {
                vertical-align: middle;
                padding: 12px 15px;
            }
            .badge {
                padding: 8px 12px;
                font-weight: 500;
            }
            .btn-action {
                padding: 5px 10px;
                margin: 0 2px;
                font-size: 0.875rem;
            }
        </style>

        <div class="container-fluid mt-4">
            <h5 class="mb-4 text-white">Manajemen Peminjaman Buku</h5>

            <!-- Request Approval Card -->
            <div class="card custom-card">
                <div class="card-header bg-dark text-white" style="border: 2px solid white">
                    <h5 class="mb-0">Permintaan Persetujuan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Judul Buku</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal Request</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requestApprovals as $index => $request)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $request->book->judul }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <button onclick="approveRequest({{ $request->id }})" 
                                                class="btn btn-success btn-action mt-2">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button onclick="disapproveRequest({{ $request->id }})" 
                                                class="btn btn-danger btn-action mt-2">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada permintaan persetujuan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-start ms-4">
                        <!-- Tombol 'Previous' -->
                        <li class="page-item {{ $requestApprovals->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $requestApprovals->previousPageUrl() }}" style="color: #00328E;">Previous</a>
                        </li>

                        <!-- Menampilkan nomor halaman -->
                        @for ($i = 1; $i <= $requestApprovals->lastPage(); $i++)
                        <li class="page-item {{ $i == $requestApprovals->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $requestApprovals->url($i) }}" 
                               style="{{ $i == $requestApprovals->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                    

                        <!-- Tombol 'Next' -->
                        <li class="page-item {{ !$requestApprovals->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $requestApprovals->nextPageUrl() }}" style="color: #00328E;">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Being Borrowed Card -->
          <div class="card custom-card">
    <div class="card-header text-white" style="background-color: #00328E; border: 2px solid white;">
        <h5 class="mb-0">Sedang Dipinjam</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Judul Buku</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tenggat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beingBorrowed as $index => $borrow)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $borrow->book->judul }}</td>
                        <td>{{ $borrow->user->name }}</td>
                        <td>{{ $borrow->approved_at ? \Carbon\Carbon::parse($borrow->approved_at)->format('d/m/Y') : 'Tidak Tersedia' }}</td>

                        <td>{{ \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @php
                                $today = \Carbon\Carbon::now();
                                $dueDate = \Carbon\Carbon::parse($borrow->due_date);
                                $daysLeft = $today->diffInDays($dueDate, false);
                            @endphp

                            @if($borrow->status === 'pending')
                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                            @elseif($borrow->status === 'borrowed')
                                @if($today > $dueDate)
                                    <span class="badge bg-danger">
                                        Terlambat {{ abs($daysLeft) }} hari
                                    </span>
                                @elseif($daysLeft <= 2)
                                    <span class="badge bg-warning">
                                        Sisa {{ $daysLeft }} hari
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Sisa {{ $daysLeft }} hari
                                    </span>
                                @endif
                            @elseif($borrow->status === 'returned')
                                <span class="badge bg-info">Dikembalikan</span>
                            @elseif($borrow->status === 'broken')
                                <span class="badge bg-warning">Rusak</span>
                            @elseif($borrow->status === 'lost')
                                <span class="badge bg-danger">Hilang</span>
                            @endif
                        </td>
                        <td>
                            <button onclick="returnBook({{ $borrow->id }})" 
                                    class="btn btn-primary btn-action mt-2">
                                <i class="fas fa-undo "></i> Kembalikan
                            </button>
                            <button onclick="markAsBroken({{ $borrow->id }})" 
                                    class="btn btn-warning btn-action mt-2">
                                <i class="fas fa-exclamation-triangle"></i> Rusak
                            </button>
                            <button onclick="markAsLost({{ $borrow->id }})" 
                                    class="btn btn-danger btn-action mt-2">
                                <i class="fas fa-times-circle"></i> Hilang
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada buku yang sedang dipinjam</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-start ms-4">
            <!-- Tombol 'Previous' -->
            <li class="page-item {{ $beingBorrowed->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $beingBorrowed->previousPageUrl() }}" style="color: #00328E;">Previous</a>
            </li>

            <!-- Menampilkan nomor halaman -->
            @for ($i = 1; $i <= $beingBorrowed->lastPage(); $i++)
                <li class="page-item {{ $i == $beingBorrowed->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $beingBorrowed->url($i) }}" style="{{ $i == $beingBorrowed->currentPage() ? 'background-color:#00328E; color:white;' : ''  }}">{{ $i }}</a>
                </li>
            @endfor

            <!-- Tombol 'Next' -->
            <li class="page-item {{ !$beingBorrowed->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $beingBorrowed->nextPageUrl() }}" style="color: #00328E;">Next</a>
            </li>
        </ul>
    </nav>
</div>
            <!-- Late Returns Card -->
            <div class="card custom-card">
                <div class="card-header bg-danger text-white" style="border: 2px solid white">
                    <h5 class="mb-0">Terlambat Dikembalikan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Judul Buku</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tenggat</th>
                                    <th>Keterlambatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lateReturns as $index => $late)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $late->book->judul }}</td>
                                    <td>{{ $late->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($late->approved_at)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($late->due_date)->format('d/m/Y H:i:s') }}</td>
                                        <button onclick="returnBook({{ $late->id }})" 
                                                class="btn btn-primary btn-action">
                                            <i class="fas fa-undo"></i> Kembalikan
                                        </button>
                                        <button onclick="markAsBroken({{ $late->id }})" 
                                                class="btn btn-warning btn-action">
                                            <i class="fas fa-exclamation-triangle"></i> Rusak
                                        </button>
                                        <button onclick="markAsLost({{ $late->id }})" 
                                                class="btn btn-danger btn-action">
                                            <i class="fas fa-times-circle"></i> Hilang
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada keterlambatan pengembalian</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-start ms-4">
                        <!-- Tombol 'Previous' -->
                        <li class="page-item {{ $lateReturns->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $lateReturns->previousPageUrl() }}" style="color: #00328E;">Previous</a>
                        </li>
            
                        <!-- Menampilkan nomor halaman -->
                        @for ($i = 1; $i <= $lateReturns->lastPage(); $i++)
                            <li class="page-item {{ $i == $lateReturns->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $lateReturns->url($i) }}" style="{{ $i == $lateReturns->currentPage() ? 'background-color: #00328E; color:white;' : ''}}">{{ $i }}</a>
                            </li>
                        @endfor
            
                        <!-- Tombol 'Next' -->
                        <li class="page-item {{ !$lateReturns->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $lateReturns->nextPageUrl() }}" style="color: #00328E;">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Include SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Font Awesome untuk icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <script>
            function approveRequest(requestId) {
                Swal.fire({
                    title: 'Konfirmasi Persetujuan',
                    text: 'Apakah Anda yakin ingin menyetujui peminjaman ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX request to approve
                        fetch(`/admin/peminjaman/approve/${requestId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', 'Peminjaman telah disetujui', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
            
            function disapproveRequest(requestId) {
                Swal.fire({
                    title: 'Konfirmasi Penolakan',
                    text: 'Apakah Anda yakin ingin menolak peminjaman ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/peminjaman/disapprove/${requestId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', 'Peminjaman telah ditolak', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
            
            function returnBook(borrowId) {
                Swal.fire({
                    title: 'Konfirmasi Pengembalian',
                    text: 'Apakah buku dalam kondisi baik dan siap dikembalikan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kembalikan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/peminjaman/return/${borrowId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', 'Buku telah dikembalikan', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
            
            function markAsBroken(borrowId) {
                Swal.fire({
                    title: 'Konfirmasi Buku Rusak',
                    text: 'Apakah Anda yakin ingin menandai buku ini sebagai rusak?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tandai Rusak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/peminjaman/broken/${borrowId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', 'Buku telah ditandai sebagai rusak', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
            
            function markAsLost(borrowId) {
                Swal.fire({
                    title: 'Konfirmasi Buku Hilang',
                    text: 'Apakah Anda yakin ingin menandai buku ini sebagai hilang?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tandai Hilang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/peminjaman/lost/${borrowId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', 'Buku telah ditandai sebagai hilang', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
            </script>
        @endsection



