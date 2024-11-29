@extends('layouts.admin')

@section('content')
    <div class="container">
      <h5 class="mt-4 text-white">Category</h5>

        <a href="{{ route('admin.category.create') }}" class="btn mt-4" style="background-color: white; color: #00328E;">Tambah Category</a>

      <div class="card mt-5">
       <div class="card-body">
          <table class="table table-striped table-bordered">
             <thead>
              <tr>
                <th>Category</th>
                <th>Aksi</th>
              </tr>
             </thead>
             <tbody>

                  @foreach ($categories as $category)
                      <tr>
                        <td>{{ $category->nama_category }}</td>
                        <td class="d-flex gap-2"> 
                          <a href="{{ route('admin.category.edit', $category->id) }}" class="btn" style="border: 1px solid #00328E;"><img src="{{ asset('images/edit.png') }}" width="20"  alt=""></a>
                          <form action="{{ route('admin.category.delete', $category->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn" style="background-color: #00328E;"><img src="{{ asset('images/delete (2).png') }}" width="20" alt=""></button>
                          </form>
                        </td>
                      </tr>
                  @endforeach
             </tbody>
          </table>
       </div>
      </div>
    </div>
@endsection