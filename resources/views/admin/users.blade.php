@extends('layouts.admin')

@section('content')
<style>
    body{
        overflow-y: auto;
    }
    .container{
        padding-bottom: 10px;
    }
    form input::placeholder{
        color: #00328E;
    }
    
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .modal-dialog {
            max-width: 95%;
        }
        .btn {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .btn {
            width: 100%;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }
        .table tbody td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #dee2e6;
        }
        .table tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            text-align: left;
            font-weight: bold;
        }
    }
</style>

<div class="container">
    <h5 class="mt-4 text-white">Manajemen Pengguna</h5>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal" style="color: #00328E;">
            Tambah Pengguna
        </button>
        <button type="button" class="btn btn-light mt-2" data-bs-toggle="modal" data-bs-target="#importExcelModal" style="color: #00328E;">
            Import Excel
        </button>
        <a href="{{ route('admin.users.template') }}" class="btn btn-light mt-2" style="color: #00328E;">
            Download Template
        </a>
    </div>

    <form action="{{ route('admin.users.search') }}" method="GET" class="mt-3">
        @csrf
        <input type="text" name="query" placeholder="Cari pengguna..." style="width: 100%; height: 40px;" class="form-control" required>
    </form>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Nim</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->nim }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light" style="border: 1px solid #00328E;">
                                        <img src="{{ asset('images/edit.png') }}" width="20" alt="">
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn" type="submit" style="background-color: #00328E;">
                                            <img src="{{ asset('images/delete (2).png') }}" alt="" width="20">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" style="color: #00328E;">Previous</a>
                        </li>

                        @php
                            $start = max(1, $users->currentPage() - 1);
                            $end = min($start + 9, $users->lastPage());
                            $start = max(1, $end - 10);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}" 
                                   style="{{ $i == $users->currentPage() ? 'background-color:#00328E; color:white;' : 'color: #00328E;'}}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endfor

                        <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" style="color: #00328E;">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="nim">Nim</label>
                            <input type="number" class="form-control" name="nim" id="nim" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" style="color: #00328E; border: 1px solid #00328E;" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn" style="background-color: #00328E; color: white;">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importExcelModalLabel">Import Data Pengguna dari Excel</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" class="form-control" name="file" id="file" required accept=".xlsx,.xls">
                            <small class="form-text text-muted">Format yang didukung: .xlsx, .xls</small>
                        </div>
                        <div class="mt-3">
                            <h6>Format Excel yang dibutuhkan:</h6>
                            <ul>
                                <li>Kolom A: Nama</li>
                                <li>Kolom B: NIM</li>
                                <li>Kolom C: Email</li>
                                <li>Kolom D: Password</li>
                            </ul>
                            <p class="text-muted">Download template Excel untuk format yang benar.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" style="color: #00328E; border: 1px solid #00328E;" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn" style="background-color: #00328E; color: white;">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection
