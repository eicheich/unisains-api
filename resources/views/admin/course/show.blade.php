@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kursus</h1>
        {{-- button add course --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <img src="{{ asset('storage/images/thumbnail_course/' . $course->image_course) }}" alt="Course Image"
                    class="img-fluid mb-3" />
                <h1 class="mb-3">{{ $course->title_course }}</h1>
                <p class="lead">Deskripsi kursus singkat</p>
                <hr class="my-4" />
                <h2>Deskripsi Kursus</h2>
                <p>{{ $course->description }}</p>
                <h2>Apa yang akan Anda pelajari</h2>
                <ul>
                    @if ($modules->isEmpty())
                        <li>Belum ada modul</li>
                    @else
                        @foreach ($modules as $m)
                            <div id="accordion">
                                <div class="card mt-5">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn" data-toggle="collapse" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                {{ $m->title_module }}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card">
                                            <img src="{{ asset('storage/images/module/' . $m->image_module) }}"
                                                 alt="...">
                                        </div>
                                        <div class="card-body">
                                            Deskripsi <br>
                                            {{ $m->description }}}
                                        </div>
                                        <div class="card-body">
                                            Isi Materi <br>
                                            {{ $m->materi_module }}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- button tambah moduke --}}
                    @endif
                    <button id="add-module-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                        data-target="#add-module-modal">Tambah Modul</button>

                    <div class="modal fade" id="add-module-modal" tabindex="-1" role="dialog"
                        aria-labelledby="add-module-modal-title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="add-module-modal-title">Tambah Modul</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('store.modules') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="module-name">Judul Modul</label>
                                            <input type="text" class="form-control" id="module-name" name="title_module"
                                                placeholder="Nama Modul">
                                        </div>
                                        <div class="form-group">
                                            <label for="module-description">Deskripsi Modul</label>
                                            <textarea type="text" class="form-control" id="module-description" name="description" rows="5">
                                            </textarea>
                                        </div>
                                        <div class="form-group mt-2">
                                            <p>Gambar Module</p>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="certificate"
                                                    name="image_module" onchange="previewImage('certificate')">
                                                <label class="custom-file-label" for="certificate">Pilih file</label>
                                            </div>
                                        </div>
                                        <div class="preview mt-3">
                                            <img id="certificate_preview" class="img-fluid" alt="Preview Image">
                                        </div>
                                        <div class="form-group">
                                            <label for="module-description">Materi Modul</label>
                                            <textarea type="text" class="form-control" id="module-description" name="materi_module" rows="5">
                                            </textarea>
                                        </div>
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <style>
                            .custom-file {
                                position: relative;
                                display: inline-block;
                                width: 100%;
                                margin-bottom: 0;
                            }

                            .custom-file-input {
                                position: relative;
                                z-index: 2;
                                width: 100%;
                                height: calc(2.25rem + 2px);
                                margin: 0;
                                opacity: 0;
                            }

                            .custom-file-label {
                                position: absolute;
                                top: 0;
                                right: 0;
                                left: 0;
                                z-index: 1;
                                height: calc(2.25rem + 2px);
                                padding: 0.375rem 0.75rem;
                                line-height: 1.5;
                                color: #495057;
                                background-color: #fff;
                                border: 1px solid #ced4da;
                                border-radius: 0.25rem;
                                font-weight: 400;
                                cursor: pointer;
                            }

                            .preview {
                                text-align: center;
                            }

                            .preview img {
                                max-width: 100%;
                                height: auto;
                                margin: 0 auto;
                            }
                        </style>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#add-module-btn').click(function() {
                                    // tampilkan modal
                                    $('#add-module-modal').modal('show');
                                });
                            });

                            function previewImage(inputId) {
                                var preview = document.querySelector('#' + inputId + '_preview');
                                var file = document.querySelector('#' + inputId).files[0];
                                var reader = new FileReader();

                                reader.onloadend = function() {
                                    preview.src = reader.result;
                                }

                                if (file) {
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = "";
                                }
                            }
                        </script>
                        {{-- <li>Topik kedua</li>
                    <li>Topik ketiga</li> --}}
                </ul>
                <h2>Persyaratan</h2>
                <ul>
                    <li>Persyaratan pertama</li>
                    <li>Persyaratan kedua</li>
                    <li>Persyaratan ketiga</li>
                </ul>
            </div>

            <script>
                // Mengambil elemen dengan ID accordion
                const accordion = document.querySelector('#accordion');

                // Mengambil seluruh elemen card di dalam accordion
                const cards = accordion.querySelectorAll('.card');

                // Menambahkan event listener untuk setiap card
                cards.forEach(card => {
                    card.addEventListener('click', () => {
                        // Mengambil elemen collapse di dalam card
                        const collapse = card.querySelector('.collapse');

                        // Memeriksa apakah collapse sedang ditampilkan atau disembunyikan
                        const isCollapsed = collapse.classList.contains('show');

                        // Mengubah tampilan collapse sesuai dengan kondisi sebelumnya
                        if (isCollapsed) {
                            collapse.classList.remove('show');
                        } else {
                            collapse.classList.add('show');
                        }
                    });
                });
            </script>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Harga: $99</h5>
                        <p class="card-text">
                            Diskon 20% untuk pendaftar awal.
                        </p>
                        <a href="#" class="btn btn-primary btn-lg btn-block">Edit kursus</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
