@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Akun</h1>
        @include('layouts.session')
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card p-2">
                <div class="d-flex align-items-center justify-content-between">
                        <div class="card-body">
                            <h5 class="card-title">{{ $user->first_name .' '. $user->last_name }}</h5>
                            <p class="card-text">Email: {{ $user->email }}</p>
                             <p class="card-text">Role: {{ $user->role }}</p>
                    <!-- Tambahkan informasi detail lainnya sesuai kebutuhan -->
                        </div>
                    <img src="{{ asset('storage/images/avatar/' . $user->avatar) }}" class="avatar">
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
            </div>
        </div>
    </div>





@endsection
