@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-between align-items-center mb-4">
            <div class="col">
                <h1 class="h2 mb-0">Detail Transaksi</h1>
            </div>
            <div class="col-auto">
                @include('layouts.session')
            </div>
        </div>

        <div class="row justify-content-center py-5">
            <div class="col-md-7">
                <div class="card p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-container mr-3">
                            <img src="{{ asset('storage/images/avatar/' . $transaction->user->avatar) }}" class="avatar" alt="User Avatar">
                            <style>
                                .avatar {
                                    width: 150px;
                                    height: 150px;
                                    border-radius: 50%;
                                    object-fit: cover;
                                    object-position: center;
                                }
                            </style>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $transaction->user->first_name . ' ' . $transaction->user->last_name }}</h4>
                            <p class="text-muted mb-0">Nama Pengguna: {{ $transaction->user->username }}</p>
                            <p class="text-muted mb-0">Email: {{ $transaction->user->email }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center" scope="col">Tanggal Transaksi</th>
                                <th class="text-center" scope="col">Total</th>
                                <th class="text-center" scope="col">Status</th>
                                <th class="text-center" scope="col">Nama Kursus</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{ $transaction->date }}</td>
                                <td class="text-center">{{ $transaction->total_price }}</td>
                                <td class="text-center">{{ $transaction->status }}</td>
                                <td class="text-center">{{ $transaction->course->title_course }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .avatar-container {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
        }

    </style>
@endsection
