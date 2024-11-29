@extends('layouts.admin')

@section('content')
<style>
    /* Base Styles */
    body {
        background-color: #00328E;
        overflow-y: auto;
    }

    .container {
        max-width: 1200px;
        padding: 15px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .action-buttons .btn {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Search Container */
    .search-container form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .search-container input {
        flex-grow: 1;
        min-width: 200px;
    }

    /* Import Form */
    .import-form {
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .import-form label {
        color: #00328E;
        font-weight: bold;
    }

    /* Responsive Table */
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }

    .table th, .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        /* Responsive Table Layout */
        .table thead {
            display: none;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .table tbody td {
            display: block;
            width: 100%;
            text-align: right;
            padding: 10px 15px;
            position: relative;
            border-bottom: 1px solid #ddd;
        }

        .table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 50%;
            font-weight: bold;
            text-align: left;
            color: #00328E;
        }

        .table tbody td:last-child {
            border-bottom: none;
        }

        /* Responsive Action Buttons */
        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }

        /* Search and Import Responsiveness */
        .search-container form,
        .import-form {
            flex-direction: column;
        }

        .search-container input,
        .import-form input,
        .search-container .btn,
        .import-form .btn {
            width: 100%;
        }
    }

    /* Book Image */
    .book-image {
        max-width: 80px;
        max-height: 120px;
        object-fit: cover;
        border-radius: 4px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 20px;
    }

    .pagination .page-item {
        margin: 0 2px;
    }

    .pagination .page-link {
        color: #00328E;
        background-color: transparent;
        border: 1px solid #00328E;
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-item.active .page-link {
        background-color: #00328E;
        color: white;
        border-color: #00328E;
    }

    /* Action Column */
    .action-column {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .action-column .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border-radius: 4px;
    }

    .action-column .btn img {
        width: 20px;
        height: 20px;
    }
</style>

<div class="container">
    <h5 class="mt-4 text-white mb-4">Manajemen Buku</h5>

    <div class="action-buttons">
        <a href="{{ route('admin.book.create') }}" class="btn btn-light" style="color: #00328E;">
         Tambah Buku
        </a>
    </div>

    <!-- Import Excel Form -->
    <div class="import-form">
        <form action="{{ route('admin.book.import') }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <div class="form-group mb-3">
                <label for="excel_file" class="mb-2">Import File Excel</label>
                <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                <small class="text-muted">
                    Download template Excel 
                    <a href="{{ route('admin.book.template') }}" class="text-primary">di sini</a>
                </small>
            </div>
            <button type="submit" class="btn text-white" style="background-color: #00328E;">
                Import Data
            </button>
        </form>
    </div>

    <!-- Search Container -->
    <div class="search-container mb-4">
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari judul, pengarang, atau kategori..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn text-dark" style="background-color: white;">
                Cari
            </button>
        </form>
    </div>  

    <!-- Book Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Rak</th>
                            <th>Jumlah</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td data-label="Judul">{{ $book->judul }}</td>
                                <td data-label="Pengarang">{{ $book->pengarang }}</td>
                                <td data-label="Penerbit">{{ $book->penerbit }}</td>
                                <td data-label="Tahun Terbit">{{ $book->tahun_terbit }}</td>
                                <td data-label="Rak">{{ $book->rak }}</td>
                                <td data-label="Jumlah">{{ $book->jumlah }}</td>
                                <td data-label="Kategori">{{ $book->category }}</td>
                                <td data-label="Gambar">
                                    @if ($book->image)
                                        <img src="{{ asset('uploads/' . $book->image) }}" 
                                             alt="{{ $book->judul }}" 
                                             class="book-image">
                                    @else
                                        <small>Tidak ada gambar</small>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <a href="{{ route('admin.book.edit', $book->id) }}" class="btn" style="border: 1px solid #00328E;">
                                        <img src="{{ asset('images/edit.png') }}" alt="" style="width: 20px; height: 20px;">
                                    </a>
                                    <form action="{{ route('admin.book.delete' , $book->id) }}" method="POST" enctype="multipart/form-data" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn mt-2" style="background-color: #00328E;" type="submit">
                                            <img src="{{ asset('images/delete (2).png') }}" alt="" style="width: 20px; height: 20px;">
                                        </button>
                                    </form>
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{-- Previous Page Link --}}
                    <li class="page-item {{ $books->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $books->previousPageUrl() }}">Previous</a>
                    </li>

                    @php
                        $start = max(1, $books->currentPage() - 1);
                        $end = min($start + 9, $books->lastPage());
                        $start = max(1, $end - 10);
                    @endphp

                    {{-- Pagination Elements --}}
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i == $books->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $books->url($i) }}" style="{{ $i == $books->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Next Page Link --}}
                    <li class="page-item {{ !$books->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $books->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection