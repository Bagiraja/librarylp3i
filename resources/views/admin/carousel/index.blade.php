@extends('layouts.admin')

@section('content')
    <div class="container">
        <h5 class="text-white mt-4">Daftar Carousel</h5>
        <div class="card mt-4">
            <div class="card-body">     
        <a href="{{ route('admin.carousel.create') }}" class="btn mb-3" style="background-color: #00328E; color: white">Tambah Gambar Carousel</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($carousels->isEmpty())
            <p>Tidak ada gambar carousel.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carousels as $index => $carousel)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $carousel->image) }}" alt="Carousel Image" width="100">
                            </td>
                            <td>
                                <form action="{{ route('admin.carousel.delete', $carousel->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background-color: #00328E;"><img src="{{ asset('images/delete (2).png') }}"  alt=""></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        </div>
    </div>
    </div>
@endsection
