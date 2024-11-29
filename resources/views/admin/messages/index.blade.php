@extends('layouts.admin')

@section('content')
<div class="container">
    <p class="mt-4 text-white fs-4">Message</p>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Created_at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->message }}</td>
                            <td>{{ $message->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-3">
                    <li class="page-item {{ $messages->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $messages->previousPageUrl() }}" style="color: #00328E;">Previous</a>
                    </li>
                    @for ($i = 1; $i <= $messages->lastPage(); $i++)
                        <li class="page-item {{ $i == $messages->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $messages->url($i) }}"
                                style="{{ $i == $messages->currentPage() ? 'background-color: #00328E; color: white;' : '' }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ !$messages->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $messages->nextPageUrl() }}" style="color: #00328E;">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
