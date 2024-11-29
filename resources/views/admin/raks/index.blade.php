    @extends('layouts.admin')

    @section('content')
        <div class="container">
            <h5 class="mt-4 text-white">Daftar Rak</h5>
            <a href="{{ route('admin.rak.create') }}" class="btn mt-3" style="color: #00328E; background-color: white;">Tambah Rak</a>

            @if(session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif
            
            <!-- Responsive card layout -->
            <div class="mt-5">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="color: #00328E;">Nama Rak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($raks as $rak)
                                        <tr>
                                            <td style="color: #00328E;">{{ $rak->nama_rak }}</td>
                                            <td class="d-flex gap-2">
                                                <a href="{{ route('admin.rak.edit', $rak->id) }}" class="btn" style="border: 1px solid #00328E;"><img src="{{ asset('images/edit.png') }}" alt="" width="20"></a>
                                                <form action="{{ route('admin.rak.delete', $rak->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn" type="submit"  style="background-color: #00328E;"><img src="{{ asset('images/delete (2).png') }}" alt="" width="20" class=""></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    @endsection
