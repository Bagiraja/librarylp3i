@extends('layouts.admin')

@section('content')
    <div class="container">
        <h5 class="text-white mt-4">Tambah Carousel</h5>
        <div class="card p-4">
            <form action="{{ route('admin.carousel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label">Pilih Gambar</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
               
                <button type="submit" class="btn" style="background-color: #00328E; color: white;">Tambah Gambar</button>
            </form>
        </div>
    </div>
@endsection
