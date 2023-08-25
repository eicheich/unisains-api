@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Akun</h1>
        @include('layouts.session')
    </div>
    <div class="row justify-content-center py-5">
        <div class="col-md-7">
            <div class="card p-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card-body">
                        <h5 class="card-title">{{ $user->first_name .' '. $user->last_name }}</h5>
                        <p class="card-text">Nama Pengguna: {{ $user->username }}</p>
                        <p class="card-text">Email: {{ $user->email }}</p>
                        <p class="card-text">Role: {{ $user->role }}</p>
                    </div>
                    <style>
                        .avatar {
                            width: 150px;
                            height: 150px;
                            border-radius: 50%;
                            object-fit: cover;
                            object-position: center;
                        }
                    </style>
                    <img src="{{ asset('storage/images/avatar/' . $user->avatar) }}" class="avatar">
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    Riwayat Aktivitas Pengguna
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($activity_logs as $activity_log)
                            <li class="list-group-item">
                                <span class="font-weight-bold">{{ $activity_log->created_at }}</span> - {{ $activity_log->description }}
                            </li>
                        @empty
                            <li class="list-group-item">Tidak ada riwayat aktivitas yang tersedia.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer">
                    {{ $activity_logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
