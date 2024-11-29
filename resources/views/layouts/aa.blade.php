<h5 class="mt-4 text-white">Daftar Buku</h5>

<div class="main-card">
    <div class="main-card-title">Administrasi Perkantoran</div>
    
    <div class="row">
        @foreach ($administrasiBooks as $book)
            <div class="col-md-3 card-container">
              
                <div class="card mt-4">
                    <img src="{{ asset('uploads/' . $book->image) }}" 
                         class="card-img-top" 
                         alt="{{ $book->judul }}">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $book->judul }}</h5>
                            <p class="card-text">Pengarang: {{ $book->pengarang }}</p>
                        </div>
                        <div>
                            <p class="card-text">Jumlah: {{ $book->jumlah }}</p>
                            <a href="{{ route('mahasiswa.buku.pinjam', $book->id) }}" 
                               class="btn btn-primary btn-sm">Pinjam</a>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $book->id }}">Detail</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailModal{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $book->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel{{ $book->id }}">{{ $book->judul }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('uploads/' . $book->image) }}" 
                            class="img-fluid" 
                            alt="{{ $book->judul }}" 
                            style="width: 150px; height: 200px; object-fit: cover; margin: auto; display: block;"> 
                            <p><strong>Pengarang:</strong> {{ $book->pengarang }}</p>
                            <p><strong>Penerbit:</strong> {{ $book->penerbit }}</p>
                            <p><strong>Tahun Terbit:</strong> {{ $book->tahun_terbit }}</p>
                            <p><strong>Rak:</strong> {{ $book->rak }}</p>
                            <p><strong>Jumlah:</strong> {{ $book->jumlah }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Pagination for Administrasi -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $administrasiBooks->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $administrasiBooks->previousPageUrl() }}">Previous</a>
            </li>
            @for ($i = 1; $i <= $administrasiBooks->lastPage(); $i++)
                <li class="page-item {{ $i == $administrasiBooks->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $administrasiBooks->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ !$administrasiBooks->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $administrasiBooks->nextPageUrl() }}">Next</a>
            </li>
        </ul>
    </nav>
</div>

<div class="main-card">
    <div class="main-card-title">Teknik Informatika</div>
    
    <div class="row">
        @foreach ($teknikInformatikaBooks as $book)
            <div class="col-md-3 card-container">
                <div class="card mt-4">
                    <img src="{{ asset('uploads/' . $book->image) }}" 
                         class="card-img-top" 
                         alt="{{ $book->judul }}">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $book->judul }}</h5>
                            <p class="card-text">Pengarang: {{ $book->pengarang }}</p>
                        </div>
                        <div>
                            <p class="card-text">Jumlah: {{ $book->jumlah }}</p>
                            <a href="{{ route('mahasiswa.buku.pinjam', $book->id) }}" 
                               class="btn btn-primary btn-sm">Pinjam</a>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $book->id }}">Detail</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailModal{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $book->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel{{ $book->id }}">{{ $book->judul }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('uploads/' . $book->image) }}" 
                            class="img-fluid" 
                            alt="{{ $book->judul }}" 
                            style="width: 150px; height: 200px; object-fit: cover; margin: auto; display: block;"> 
                            <p><strong>Pengarang:</strong> {{ $book->pengarang }}</p>
                            <p><strong>Penerbit:</strong> {{ $book->penerbit }}</p>
                            <p><strong>Tahun Terbit:</strong> {{ $book->tahun_terbit }}</p>
                            <p><strong>Rak:</strong> {{ $book->rak }}</p>
                            <p><strong>Jumlah:</strong> {{ $book->jumlah }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Pagination for Teknik Informatika -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $teknikInformatikaBooks->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $teknikInformatikaBooks->previousPageUrl() }}">Previous</a>
            </li>
            @for ($i = 1; $i <= $teknikInformatikaBooks->lastPage(); $i++)
                <li class="page-item {{ $i == $teknikInformatikaBooks->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $teknikInformatikaBooks->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ !$teknikInformatikaBooks->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $teknikInformatikaBooks->nextPageUrl() }}">Next</a>
            </li>
        </ul>
    </nav>
</div>
