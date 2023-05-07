@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <h1 class="h2">Kursus</h1>
        {{-- button add course --}}
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{route('add.course')}}" class="btn btn-sm btn-outline-secondary">Tambah Kursus</a>
            </div>
        </div>
    </div>
    @if ($courses->isEmpty())
        <div class="alert alert-danger" role="alert">
            Data Kursus Kosong
        </div>
    @else
    @endif
@endsection
