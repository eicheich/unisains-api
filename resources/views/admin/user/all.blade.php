@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Akun</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button id="add-module-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                    data-target="#add-acc-modal">Tambah Akun</button>
                @include('admin.user.partials.modalAdd')

            </div>
        </div>
    </div>
    @if ($users->isEmpty())
        <div class="alert alert-danger text-light" role="alert">
            Data Pengguna Kosong
        </div>
    @else
        <style>
            .search-container {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 100px;
            }

            .search-input {
                width: 300px;
                padding: 10px;
                border: 2px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
                outline: none;
            }

            .search-button {
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-left: 10px;
                font-size: 16px;
            }
        </style>
        </head>

        <body>

            <div class="container">
                <div class="row justify-content-center mt-5">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" placeholder="Cari...">
                            <div class="input-group-append">
                                <button class="btn btn-primary search-button" type="button">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <h4 class="mt-3">Pengguna</h4>
                <div class="table-responsive text-center">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>Nama Depan</th>
                                <th>Nama Belakang</th>
                                <th>Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->first_name }}</td>
                                    <td>{{ $user->last_name }}</td>

                                    <td style="vertical-align: middle; text-align: center;">
                                        <div class="d-flex justify-content-center">
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('update.course.page', $user->id) }}" method="GET">
                                                    @csrf
                                                    <button class="btn btn-sm btn-warning mx-1" type="submit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('course.show', $user->id) }}" method="GET">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary mx-1" type="submit">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('delete.course', $user->id) }}" method="post"
                                                    id="deleteForm">
                                                    @csrf
                                                    <button class="btn btn-sm btn-danger mx-1" onclick=""
                                                        id="submit-delete" type="button">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>




                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- </div> --}}
            <style>
                .form-control {
                    width: 100%;
                    padding: 1rem;
                    height: 3rem;
                    border: 1px solid #ced4da;
                }
            </style>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#add-module-btn').click(function() {
                        $('#add-acc-modal').modal('show');
                    });
                });
            </script>
    @endif
@endsection
