@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Komentar</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
            </div>
        </div>
    </div>
    @if ($comment->isEmpty())
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <div class="input-group">
                        <form action="{{route('transactions.search')}}" class="d-flex justify-content-between" method="get">
                            <input type="text" class="form-control search-input" name="search" placeholder="Cari nama pengguna atau kursus. . .">
                            <div class="input-group-append">
                                <button class="btn btn-primary search-button" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
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
            .filter-group{
                display: flex;
                justify-content: center;
                align-items: center;
            }
            /* styles.css */
            .transaction-card {
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s;
            }

            .transaction-card:hover {
                transform: translateY(-5px);
            }

            .filter-input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                transition: box-shadow 0.2s;
            }

            .filter-input:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            }

        </style>
        <style>
            .card_total {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                transition: box-shadow 0.2s;
                background-color: #4CAF50;
                color: white;
                font-weight: bold;
            }
            .filter-group {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .filter_status {
                width: 100%;
                padding: 14px;
                border: 1px solid #ddd;
                border-radius: 5px;
                transition: box-shadow 0.2s;
            }
            .filter_date {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                transition: box-shadow 0.2s;
            }
        </style>

        <div class="container p-2">
            <div class="row justify-content-center mt-5">
                <div class="col-md-2">
                    <form action="{{route('comments.search')}}" method="get">
                        <div class="filter-group">
                            <input type="date" class="filter_date" name="filter_date" value="{{ request('filter_date') }}">
                        </div>
                </div>
                <div class="col-md-2">
                    <div class="filter-group">
                        <select name="filter_status" class="filter_status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('filter_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('filter_status') == 'approved' ? 'selected' : '' }}>Di setujui</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="d-flex justify-content-between">
                            <input type="text" class="form-control search-input" name="search" placeholder="Cari nama pengguna atau kursus. . ." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary search-button" type="submit">Cari</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <h4 class="mt-3">Data Komentar</h4>
            <div class="table-responsive ">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Isi Ulasan</th>
                        <th>Nilai ulasan</th>
                        <th>Pengguna</th>
                        <th>Kursus</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($comment as $index => $cmnt)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $cmnt->date }}</td>
                            <td>{{ $cmnt->comment }}</td>
{{--                            sesuaikan jumlah rate dengan bintang yang di tampilkan--}}
                            <td>
                                @for ($i = 0; $i < $cmnt->rate; $i++)
                                    <i class="fas fa-star"></i>
                                    <style>
                                        .fa-star {
                                            color: #FFD700;
                                        }
                                    </style>
                                @endfor
                            </td>
                            <td>{{ $cmnt->user->email }}</td>
                        <td>{{ $cmnt->course->title_course }}</td>
                            <td>
                            <style>
                                .success {
                                    background-color: rgba(0, 204, 0, 0.5);
                                    color: #ffffff;
                                    padding: 6px;
                                    border-radius: 4px;
                                    opacity: 0.8;
                                }
                                .pending {
                                    background-color: rgba(255, 204, 0, 0.5);
                                    color: #ffffff;
                                    padding: 6px;
                                    border-radius: 4px;
                                    opacity: 0.8;
                                }
                                .failed {
                                    background-color: rgba(255, 0, 0, 0.5);
                                    color: #ffffff;
                                    padding: 6px;
                                    border-radius: 4px;
                                    opacity: 0.8;
                                }

                            </style>
                            <div class="status-box">
                                @if ($cmnt->status == 'approved')
                                    <span class="success">{{ $cmnt->status }}</span>
                                @elseif($cmnt->status == 'pending')
                                    <span class="pending">{{ $cmnt->status }}</span>
                        @endif
                            </td>
                            <td>
{{--                                jika status == pending maka ada button setujui--}}
                                @if ($cmnt->status == 'pending')
                                    <form action="{{route('approve.comments', $cmnt->id)}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                    </form>
                                @else
                                    <form action="{{route('disapprove.comments', $cmnt->id)}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Batal setujui</button>
                                    </form>
                                @endif
                            </td>
                    @endforeach
                    </tbody>
                </table>
{{--                <div class="d-flex justify-content-between align-items-center pt-5">--}}
{{--                    <div class="btn-toolbar mb-2 mb-md-0">--}}
{{--                        <div class="btn-group me-2">--}}
{{--                            <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-sm btn-primary">Previous</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="btn-toolbar mb-2 mb-md-0">--}}
{{--                        <div class="btn-group me-2">--}}
{{--                            <p class="page-info">{{ $transactions->currentPage() }}</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="btn-toolbar mb-2 mb-md-0">--}}
{{--                        <div class="btn-group me-2">--}}
{{--                            <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-sm btn-primary">Next</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    @endif
@endsection
