@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kursus</h1>
        {{-- button add course --}}
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('add.course') }}" class="btn btn-sm btn-outline-secondary">Tambah Kursus</a>
            </div>
        </div>
    </div>
    @if ($courses->isEmpty())
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <div class="input-group">
                        <form action="{{route('transactions.search')}}" class="d-flex justify-content-between" method="get">
                            <input type="text" class="form-control search-input" name="search" placeholder="Cari nama kursus. . .">
                            <div class="input-group-append">
                                <button class="btn btn-primary search-button" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
        <hr>
        <div class="alert alert-danger text-light" role="alert">
            Data Kursus Kosong
        </div>
    @else
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-md-4">
                    <div class="input-group">
                        <form action="{{route('course.search')}}" class="d-flex justify-content-between" method="get">
                            <input type="text" class="form-control search-input" name="search" placeholder="Cari nama kursus. . .">
                            <div class="input-group-append">
                                <button class="btn btn-primary search-button" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mt-5">
            <li class="nav-item">
                <a class="nav-link active" id="card-tab" data-bs-toggle="tab" href="#card-view">Card View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="list-tab" data-bs-toggle="tab" href="#list-view">List View</a>
            </li>
        </ul>

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

        {{-- <script>
            const tabLinks = document.querySelectorAll('.nav-link');
            tabLinks.forEach(function(tab) {
                tab.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabLinks.forEach(function(link) {
                        link.classList.remove('active');
                    });
                    tab.classList.add('active');
                });
            });
        </script> --}}


        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="card-view">
                <div class="row">
                        {{-- <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <!-- card content here -->
                        </div> --}}
                        <div class="row mt-5">
                            @foreach ($courses as $course)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card text-black">
                                        <img src="{{ asset('storage/images/thumbnail_course/' . $course->image_course) }}"
                                            class="card-img-top" alt="{{ $course->title_course }}">
                                        <div class="card-body">
                                            <h6 class="card-title mb-2">{{ $course->title_course }}</h6>
                                            <p class="card-subtitle mb-2">Kategori : {{ $course->category->name_category }}
                                            </p>
                                            <p class="card-text">{{ Str::limit($course->description, 70) }}</p>
                                            @if ($course->is_paid == 0)
                                                <p class="card-text price">Gratis</p>
                                            @else
                                                <p class="card-text price">Rp. {{ $course->price }}</p>
                                            @endif
                                            <a class="btn btn-outline-warning"
                                                href="{{ route('update.course.page', $course->id) }}">Edit
                                                Kursus</a>
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('course.show', $course->id) }}">Lihat
                                                selengkapnya</a>
{{--                                            <a class="btn btn-outline-danger"--}}
{{--                                                href="{{ route('delete.course', $course->id) }}" id="delete-btn" onclick="confirmation(event)">Hapus</a>--}}
                                            <form action="{{ route('delete.course', $course->id) }}" method="post" id="delete-form">
                                                @csrf
                                                <button class="btn btn-outline-danger" type="button" onclick="showConfirmation()">Hapus Kursus</button>
                                            </form>
                                            <script>
                                                function showConfirmation() {
                                                    if (confirm("Apakah Anda yakin ingin menghapus kursus ini?")) {
                                                        // Jika pengguna menyetujui, submit form
                                                        document.getElementById('delete-form').submit();
                                                    }
                                                }
                                            </script>

                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>
            <div class="tab-pane fade" id="list-view">
                <div class="container">
                    <div class="table-responsive text-center">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $index => $course)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $course->title_course }}</td>
                                        <td>{{ $course->category->name_category }}</td>
                                        <td>{{ Str::limit($course->description, 70) }}</td>
                                        <td>
                                            @if ($course->is_paid == 0)
                                                Gratis
                                            @else
                                                Rp. {{ $course->price }}
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <div class="d-flex justify-content-center">
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('update.course.page', $course->id) }}"
                                                        method="GET">
                                                        @csrf
                                                        <button class="btn btn-sm btn-warning mx-1" type="submit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('course.show', $course->id) }}" method="GET">
                                                        @csrf
                                                        <button class="btn btn-sm btn-primary mx-1" type="submit">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('delete.course', $course->id) }}" method="post"
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
            </div>
        </div>
    @endif
    <style>
        /* .nav-tabs .nav-link.active {
                                        background-color: #F08A5D !important;
                                        color: #fff;
                                    } */


        .search-filter {
            margin: 0 30rem 0 30rem
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card .card-body {
            padding: 20px;
            text-align: center;
        }

        .card .card-title {
            font-size: 20px;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 0;
        }

        .card .card-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }

        .card .card-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .card .price {
            font-size: 18px;
            font-weight: 600;
            color: #2ecc71;
        }

        @media (max-width: 768px) {
            .card {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 575px) {
            .card {
                margin-bottom: 20px;
            }

            .card .card-title {
                font-size: 18px;
            }

            .card .card-subtitle {
                font-size: 14px;
            }

            .card .card-text {
                font-size: 12px;
            }

            .card .price {
                font-size: 16px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const deleteButton = document.getElementById("submit-delete");
        deleteButton.addEventListener("click", function(event) {
            event.preventDefault();
            const confirmation = confirm("Apakah Anda yakin ingin menghapus kursus ini beserta isi di dalamnya?");
            if (confirmation) {
                // lanjutkan dengan submit form
                this.form.submit();
            }
        });
    </script>
@endsection
