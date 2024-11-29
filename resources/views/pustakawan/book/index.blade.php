@extends('layouts.pustakawan')

@section('content')
<style>
    body{
        overflow-y: auto;
    }
    .container{
        padding-bottom: 20px;
    }
</style>
    <div class="container">
        <h5 class="mt-4 text-white">Manejemen Buku</h5>
         

        <a href="{{ route('pustakawan.book.create') }}" class="btn" style="background-color: white; color: #00328E;">Tambah Buku</a>
        <form action="{{ route('pustakawan.book.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="excel_file" class="text-white mt-3">File Excel</label>
            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
            <small class="text-white">Download template Excel <a href="{{ route('pustakawan.book.template') }}" style="text-decoration: none; color: white; font-weight: bold;">di sini</a></small>
        </div>
        <button class="btn btn-light mt-2" style="color: #00328E;" type="submit">Import Data</button>
        </form>
        <div class="search-container mt-4">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control w-50" placeholder="Cari judul, pengarang, atau kategori..." value="{{ request('search') }}">
                <button class="btn btn-light" style="color: #00328E;" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        <div class="card mt-4">
            <div class="card-body">
               <table class="table table-bordered table-striped"> 
                    <thead>
                        <tr class="text-center">
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Rak</th>
                            <th>Jumlah</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->judul }}</td>
                                <td>{{ $book->pengarang }}</td>
                                <td>{{ $book->penerbit }}</td>
                                <td>{{ $book->tahun_terbit }}</td>
                                <td>{{ $book->rak }}</td>
                                <td>{{ $book->jumlah }}</td>
                                <td>{{ $book->category }}</td>
                                <td>
                                    @if ($book->image)
                                        <img src="{{ asset('uploads/' . $book->image) }}" alt="{{ $book->judul }}" width="60">
                                        @else
                                        Tidak ada gambar
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('pustakawan.book.edit', $book->id) }}" class="btn" style="border: 1px solid #00328E;"><img src="{{ asset('images/edit.png') }}" alt="" style="width: 20px; height: 20px;"></a>
                                    <form action="{{ route('pustakawan.book.delete', $book->id) }}" method="POST" enctype="multipart/form-data">
                                     @csrf
                                    @method('DELETE')
                                    <button class="btn mt-2" style="background-color: #00328E;" type="submit"><img src="{{ asset('images/delete (2).png') }}" alt="" style="width: 20px; height: 20px;"></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                                <a class="page-link" href="{{ $books->url($i) }}" style="{{ $i == $books->currentPage() ? 'background-color: #00328E; color:white;' : '' }}">{{ $i }}</a>
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