@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Laporan</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
            </div>
        </div>
    </div>
    @if ($reports->isEmpty())
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
                            <th scope="col">No.</th>
                            <th scope="col">Nama Pengguna</th>
                            <th scope="col">Email</th>
                            <th scope="col">Jumlah Transaksi</th>
                            <th scope="col">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $report->user->name }}</td>
                                <td>{{ $report->user->email }}</td>
                                <td>{{ $report->user->transactions->count() }}</td>
                                <td>Rp. {{ number_format($report->user->transactions->sum('total')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h4 class="mt-3">Transaksi</h4>x  
    @endif
@endsection
