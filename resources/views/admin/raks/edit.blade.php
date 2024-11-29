@extends('layouts.admin')

@section('content')
<div class="container">
    <h5 class="mt-4 text-white">Edit Rak</h5>

    <div class="card">
        <div class="card-body">
           <form action="{{ route('admin.rak.update', $rak->id) }}" method="POST">
           @csrf
           @method('PUT')
          <div class="form-group">
            <label for="nama_rak" style="color: #00328E;">Nama Rak</label>
            <input type="text" name="nama_rak" id="nama_rak" class="form-control" value="{{ $rak->nama_rak }}" style="border: 1px solid #00328E; color: #00328E;" required>
          </div>

          <button class="btn mt-2 w-100" type="submit" style="background-color: #00328E; color: white;">Simpan Perubahan</button>
        </form>
        </div>
    </div>
</div>
@endsection