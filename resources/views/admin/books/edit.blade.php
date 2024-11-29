@extends('layouts.admin')

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
       <h5 class="mt-4 text-white">Edit Buku</h5>

       <div class="card">
           <div class="card-body">
               <form action="{{ route('admin.book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
               @csrf
               @method('PUT')

               
               <div class="form-group">
                  <label for="judul">Judul</label>
                  <input type="text" name="judul" id="judul" class="form-control" value="{{ $book->judul }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                   <label for="pengarang">Pengarang</label>
                   <input type="text" id="pengarang" class="form-control" name="pengarang" value="{{ $book->pengarang }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                  <label for="penerbit">Penerbit</label>
                  <input type="text" id="penerbit" class="form-control" name="penerbit" value="{{ $book->penerbit }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="{{ $book->tahun_terbit }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                <label for="rak">Rak</label>
                <input type="text" name="rak" id="rak" class="form-control" value="{{ $book->rak }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                  <label for="jumlah">Jumlah</label>
                  <input type="number" class="form-control" name="jumlah" id="jumlah" value="{{ $book->jumlah }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>

               <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" id="category" class="form-control" value="{{ $book->category }}" style="color: #00328E; border: 1px solid #00328E;" required>
               </div>
               <div class="form-group">
                  <label for="image" >Image</label>
                  @if ($book->image)
                      <img src="{{ asset('uploads/' . $book->image) }}" alt="{{ $book->judul }}" width="70">
                  @endif
                  <input type="file" name="image" id="image" style="color: #00328E; border: 1px solid #00328E;" class="form-control">
               </div>
               <button type="submit" class="btn text-white mt-2 w-100" style="background-color: #00328E;">Simpan Perubahan</button>
            </form>
           </div>
           
       </div>
    </div>
@endsection