@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Akun</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                {{-- <button id="add-module-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                    data-target="#add-acc-modal">Tambah Akun</button>
                @include('admin.transaction.partials.modalAdd') --}}

            </div>
        </div>
    </div>
    @if ($transactions->isEmpty())
        <div class="alert alert-danger text-light" role="alert">
            Data Transaksi Kosong
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

            .status-box {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
                font-weight: bold;
            }

            .status-box.failed {
                background-color: rgba(255, 0, 0, 0.5);
                color: #ffffff;
            }

            .status-box.pending {
                background-color: rgba(255, 204, 0, 0.5);
                color: #ffffff;
            }

            .status-box.success {
                background-color: rgba(0, 204, 0, 0.5);
                color: #ffffff;
            }
        </style>
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
                            <th>Kode Transaksi</th>
                            <th>Kursus</th>
                            <th>Pengguna</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transaction->code_transaction }}</td>
                                <td>{{ $transaction->course->title_course }}</td>
                                <td>{{ $transaction->user->email }}</td>
                                <td>
                                    @if ($transaction->course->is_paid == 1)
                                        Rp. {{ number_format($transaction->course->price, 0, ',', '.') }}
                                    @else
                                        Gratis
                                    @endif
                                </td>
                                <td>
                                    <div class="status-box {{ strtolower($transaction->status) }}">
                                        {{ $transaction->status }}
                                    </div>
                                </td>

                                <td style="vertical-align: middle; text-align: center;">
                                    <div class="d-flex justify-content-center">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('course.show', $transaction->id) }}" method="GET">
                                                @csrf
                                                <button class="btn btn-sm btn-primary mx-1" type="submit">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center pt-5">
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-sm btn-primary">Previous</a>
                        </div>
                    </div>

                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <p class="page-info">{{ $transactions->currentPage() }}</p>
                        </div>
                    </div>

                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-sm btn-primary">Next</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
