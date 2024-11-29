@extends('layouts.mahasiswa')

@section('content')
<style>
    body {
        overflow-y: auto;
        overflow-x: auto;
    }

    .container {
        padding-bottom: 10px;
    }

    .main-card {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        background-color: #fff;
    }

    .main-card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .card {
        width: 100%;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        width: 100px;
        height: 150px;
        object-fit: cover;
        margin: auto;
        padding-top: 10px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 180px;
    }

    .card-title {
        font-size: 14px;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-text {
        font-size: 12px;
        margin-bottom: 5px;
    }

    .btn-sm {
        font-size: 12px;
        padding: 4px 8px;
    }

    .card-container {
        margin-bottom: 15px;
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: center;
    }

    .filter-btn {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        flex: 0 0 calc(33.333% - 10px);
        max-width: calc(33.333% - 10px);
        text-align: center;
    }

    .filter-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .filter-btn.active {
        background: white;
        color: #00328E;
        border-color: #007bff;
    }

    .search-section {
        margin-bottom: 15px;
    }

    .search-input {
        border-radius: 20px;
        padding-left: 15px;
    }

    @media (max-width: 768px) {
        .col-md-3 {
            flex: 0 0 calc(50% - 10px);
            max-width: calc(50% - 10px);
            margin-bottom: 15px;
        }

        .card {
            margin: 0 auto;
            width: 100%;
        }

        .card-img-top {
            width: 80px;
            height: 120px;
        }

        .card-body {
            height: auto;
        }

        .card-title {
            font-size: 12px;
        }

        .card-text {
            font-size: 10px;
        }

        .btn-sm {
            font-size: 10px;
            padding: 2px 4px;
        }

        .filter-buttons {
            gap: 10px;
            justify-content: center;
        }

        .filter-btn {
            flex: 0 0 calc(33.333% - 10px);
            max-width: calc(33.333% - 10px);
        }
    }

    @media (max-width: 576px) {
        .col-md-3 {
            flex: 0 0 calc(50% - 10px);
            max-width: calc(50% - 10px);
        }

        .filter-btn {
            flex: 0 0 calc(33.333% - 10px);
            max-width: calc(33.333% - 10px);
        }
    }
</style>

<div class="container mt-4">
    <h5 class="text-white mt-4">Daftar Buku</h5>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="filter-section">
        <form action="{{ route('mahasiswa.books') }}" method="GET" id="filterForm">
            <div class="search-section">
                <div class="input-group">
                    <input type="text" 
                           class="form-control search-input" 
                           name="search" 
                           placeholder="Cari judul atau pengarang..." 
                           value="{{ request('search') }}">
                    <a href="{{ route('mahasiswa.books') }}" class="btn ms-2" style="background: white;">Reset</a>
                </div>
            </div>

            <div class="filter-buttons">
                <button type="button" 
                        class="filter-btn {{ !request('rak') ? 'active' : '' }}"
                        data-rak="">
                    Semua
                </button>
                @foreach($raks as $rak)
                    <button type="button" 
                            class="filter-btn {{ request('rak') == $rak ? 'active' : '' }}"
                            data-rak="{{ $rak }}">
                        {{ $rak }}
                    </button>
                @endforeach
            </div>
            <input type="hidden" name="rak" id="selectedRak" value="{{ request('rak') }}">
        </form>
    </div>

    <div class="row">
        @forelse($books as $book)
        <div class="col-md-3 col-sm-6 col-6 mb-4">
            <div class="card">
                @if($book->image)
                <img src="{{ asset('uploads/' . $book->image) }}" class="card-img-top" alt="{{ $book->judul }}">
                @else
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="No Image">
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $book->judul }}</h5>
                    <p class="card-text"><strong>Pengarang:</strong> {{ $book->pengarang }}</p>
                    <p class="card-text"><strong>Rak:</strong> {{ $book->rak }}</p>
                    <p class="card-text"><strong>Jumlah Tersedia:</strong> {{ $book->jumlah }}</p>
                </div>
                
                <div class="card-footer text-center">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn me-2" style="background: white; border: 1px solid #00328E; color: #00328E;" data-bs-toggle="modal" data-bs-target="#bookDetailModal{{ $book->id }}">
                            Detail
                        </button>

                        @if($book->jumlah > 0)
                            <form action="{{ route('mahasiswa.borrow', $book->id) }}" method="POST">
                                @csrf
                                <button onclick="confirmBorrow({{ $book->id }}, '{{ $book->judul }}')" class="btn" style="background: #00328E; color: white;">
                                    Pinjam
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Tidak Tersedia</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p class="text-white">Tidak ada buku yang ditemukan.</p>
        </div>
        @endforelse
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $books->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $books->previousPageUrl() }}" style="color: #00328E">Previous</a>
            </li>
            @php
                $start = max(1, $books->currentPage() - 1);
                $end = min($start + 9, $books->lastPage());
                $start = max(1, $end - 10);
            @endphp
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $books->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $books->url($i) }}" style="{{ $i == $books->currentPage() ? 'background: #00328E; color: white;' : 'color: #00328E;' }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ !$books->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $books->nextPageUrl() }}" style="color: #00328E">Next</a>
            </li>
        </ul>
    </nav>
</div>


<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Add this JavaScript code at the bottom of your view -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    const filterForm = document.getElementById('filterForm');
    const selectedRakInput = document.getElementById('selectedRak');

    // Add click event to all filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Set the selected category
            selectedRakInput.value = this.dataset.rak;
            
            // Submit the form
            filterForm.submit();
        });
    });
});

function confirmBorrow(bookId, bookTitle) {
    Swal.fire({
        title: 'Konfirmasi Peminjaman',
        html: `Apakah Anda yakin ingin meminjam buku:<br><strong>${bookTitle}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Pinjam',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#00328E',
        cancelButtonColor: '#dc3545',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/mahasiswa/books/borrow/${bookId}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Handle session messages with SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#00328E',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545',
        });
    @endif
});

</script>
@endsection