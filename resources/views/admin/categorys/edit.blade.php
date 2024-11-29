@extends('layouts.admin')

@section('content')
    <div class="container">
        <h5 class="text-white mt-4">Edit Category</h5>
        <div class="card">
          <div class="card-body">
             <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
              @csrf
             @method('PUT')
             <div class="form-group">
                 <label for="nama_category">Nama Category</label>
                 <input type="text" name="nama_category" id="nama_category" class="form-control" value="{{ $category->nama_category }}" style="border:1px solid #00328E; color: #00328E;" required>
             </div>

             <button type="submit" class="btn mt-2 w-100" style="background-color: #00328E; color: white;">Simpan Perubahan</button>
            </form>
          </div>
        </div>
    </div>
@endsection

