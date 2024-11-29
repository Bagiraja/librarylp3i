@extends('layouts.admin')

@section('content')
    <div class="container">
        <h5 class="mt-4 text-white">Tambah Rak Baru</h5>
        <div class="card gap-2 mt-5">
          <div class="card-body">
          <form action="{{ route('admin.rak.store') }}" method="POST">
              @csrf
              <div class="form-group">
                  <label for="nama_rak" class="mb-2" style="color: #00328E;">Nama Rak</label>
                  <input type="text" name="nama_rak" class="form-control" id="nama_rak" style="border:1px solid #00328E;" required>
              </div>
  
  
              <button type="submit" class="btn btn-success mt-3" style="background-color: #00328E; color: white;">Simpan Rak</button>
          </form>
          </div>
        </div>
    </div>
@endsection
