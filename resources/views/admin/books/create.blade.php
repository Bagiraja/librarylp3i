  @extends('layouts.admin')

  @section('content')
  <style>
    body{
      overflow-y: auto;
    }
      .form-group input{
          border: 1px solid #00328E;
      }
  </style>
      <div class="container">
        <h5 class="mt-4 text-white">Tambah Buku</h5>

        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.book.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" name="judul" id="judul" class="form-control" required>
          </div>

          <div class="form-group">
              <label for="pengarang">Pengarang</label>
              <input type="text" name="pengarang" id="pengarang" class="form-control" required>
          </div>
          <div class="form-group">
              <label for="penerbit">Penerbit</label>
              <input type="text" name="penerbit" id="penerbit" class="form-control" required>
          </div>
          <div class="form-group">
              <label for="tahun_terbit">Tahun Terbit</label>
              <input type="text" id="tahun_terbit" name="tahun_terbit" class="form-control" required>
          </div>
          <div class="form-group">
              <label for="rak">Rak</label>
              <input type="text" class="form-control" id="rak" name="rak" required>
          </div>
          <div class="form-group">
              <label for="jumlah">Jumlah</label>
              <input type="number" class="form-control" id="jumlah" name="jumlah" required>
          </div>

          <div class="form-group">
          <label for="category">Category</label>
          <input type="text" name="category" id="category" class="form-control" required>
          </div>
          <div class="form-group">
              <label for="image">Image</label>
              <input type="file" class="form-control" id="image" name="image" required>
          </div>
            
          <button type="submit" class="btn mt-2 w-100" style="background-color: #00328E; color: white;">Simpan Buku</button>
          </form>
          </div>
        </div>
      </div>
  @endsection