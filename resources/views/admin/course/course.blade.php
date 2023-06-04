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
        <div class="alert alert-danger text-light" role="alert">
            Data Kursus Kosong
        </div>
    @else
        <style>
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
        {{-- search bar and filter --}}
        <div class="search-filter">
            <div class="col">
                <form action="#" method="GET">
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" class="form-control" placeholder="Search by course name"
                            aria-label="Recipient's username" aria-describedby="button-addon2" name="search">
                        <select class="" name="category">
                            <option value="select">All categories</option>
                            <option value="math">Math</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                        </select>
                        <button class="btn-search" type="submit" id="button-addon2">Search</button>
                        <style>
                            .btn-search{
                                border: none;
                                background-color: #F08A5D;
                                color: #fff;
                                width: 5rem;
                            }
                        </style>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            @foreach ($courses as $course)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card text-black">
                        <img src="{{ asset('storage/images/thumbnail_course/' . $course->image_course) }}"
                            class="card-img-top" alt="{{ $course->title_course }}">
                        <div class="card-body">
                            <h6 class="card-title mb-2">{{ $course->title_course }}</h6>
                            <p class="card-subtitle mb-2">Kategori : {{ $course->category->name_category }}</p>
                            <p class="card-text">{{ Str::limit($course->description, 70) }}</p>
                            @if ($course->is_paid == 0)
                                <p class="card-text price">Gratis</p>
                            @else
                                <p class="card-text price">Rp. {{ $course->price }}</p>
                            @endif
                            <a class="btn btn-outline-warning" href="{{ route('update.course.page', $course->id) }}">Edit
                                Kursus</a>
                            <a class="btn btn-outline-primary" href="{{ route('course.show', $course->id) }}">Lihat
                                selengkapnya</a>
                            <form action="{{ route('delete.course', $course->id) }}" method="post">
                                @csrf
                                <button class="btn btn-outline-danger" onclick="" id="submit-delete" type="submit">Hapus
                                    Kursus</button>
                            </form>

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

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
