@extends('layouts.admin')

@section('content')
    <div class="container">

        <h5 class="text-white mt-4">Tambah Category</h5>
        <div class="card mt-5">
            <div class="card-body">
       <form action="{{ route('admin.category.store') }}" method="POST">
      @csrf

     <div class="form-group">
     <label for="nama_category">Nama Category</label>
     <input type="text" name="nama_category" id="nama_category" class="form-control mt-2" style="border: 1px solid blue;" required>
     </div>

    

     <button type="submit" class="btn text-white mt-3" style="background-color: #00328E;">Simpan Category</button>
    </form>
</div>
        </div>
    </div>
@endsection