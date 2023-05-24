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
                @if ($course->is_paid == 0)
                    <p>Gratis</p>
                @else
                    <p>Harga : Rp. {{ $course->price }}</p>
                @endif
                @if ($course->discount == 0)
                    <p>Diskon: Tidak ada</p>
                @else
                    <p>Diskon: {{ $course->discount }}%</p>
                @endif
                <hr class="my-4" />
                <h2>Kategori</h2>
                <p>{{ $course->category->name_category }}</p>
                <h2>Deskripsi Kursus</h2>
                <p>{{ $course->description }}</p>
                <h2>Modul</h2>
                <ul>
                    @if ($modules->isEmpty())
                        <h5>Belum ada modul rangkuman</h5>
                    @else
                        @foreach ($modules as $m)
                            <div id="accordion">
                                <div class="card mt-5">
                                    <div class="coll-show" id="heading{{ $m->id }}">
                                        <h5 class="mb-0">
                                            <button class="btn-coll" data-toggle="collapse"
                                                data-target="#collapse{{ $m->id }}" aria-expanded="true"
                                                aria-controls="collapse{{ $m->id }}">
                                                {{ $m->title_module }}
                                                <style>
                                                    .coll-show {
                                                        background-color: #ffffff;
                                                        justify-content: center;
                                                        border: none;
                                                        border-radius: 4;
                                                        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
                                                    }

                                                    .btn-coll {
                                                        padding: 1rem;
                                                        background-color: #ffffff;
                                                        justify-content: center;
                                                        border: none;
                                                        border-radius: 40%;
                                                        box-shadow: none;
                                                    }
                                                </style>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse{{ $m->id }}" class="collapse show"
                                        aria-labelledby="heading{{ $m->id }}" data-parent="#accordion">
                                        <div class="card">
                                            <img class="image_module"
                                                src="{{ asset('storage/images/module/' . $m->image_module) }}"
                                                alt="...">
                                            <div class="card-body">
                                                Deskripsi <br>
                                                {{ $m->description }}
                                            </div>
                                            <div class="card-body">
                                                Isi Materi <br>
                                                {{ $m->materi_module }}
                                            </div>
                                            <form action="{{ route('delete.modules', $m->id) }}" method="post">
                                                @csrf
                                                <button type="submit" id="hapus-modul"
                                                    class="btn btn-sm btn-outline-danger m-2">Hapus
                                                    Modul</button>
                                            </form>
                                            <a href="{{ route('update.modules.page', $m->id) }}"
                                                class="btn btn-sm btn-outline-warning m-2">Edit Modul</a>
                                            {{-- <a href="#" class="btn btn-sm btn-outline-danger m-2">Hapus Modul</a> --}}
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
                    </div>
                </ul>
                <h2>Modul Rangkuman</h2>
                @if ($module_rangkuman->isEmpty())
                    <h5>Belum ada modul rangkuman</h5>
                    <a href="{{ route('create.rangkuman', $course->id) }}"
                        class="btn btn-sm btn-outline-secondary mt-5">Tambah rangkuman</a>
                @else
                    @foreach ($module_rangkuman as $mr)
                        <div class="card">
                            <p>{{ $mr->isi_rangkuman }}</p>
                            <video controls>
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->video_rangkuman) }}"
                                    type="video/mp4">
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->video_rangkuman) }}"
                                    type="video/webm">
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->video_rangkuman) }}"
                                    type="video/ogg">
                                Your browser does not support the video tag. You can <a
                                    href="{{ asset('storage/video/rangkuman/' . $mr->video_rangkuman) }}">download the
                                    video</a>
                                instead.
                            </video>
                        </div>
                        <a href="{{ route('update.rangkuman.page', $mr->id) }}"
                            class="btn btn-sm btn-outline-warning m-2">Edit Rangkuman</a>
                        <form action="{{ route('delete.rangkuman', $mr->id) }}" method="post">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger m-2" id="submit-delete" type="submit">Hapus
                                Rangkuman</button>
                        </form>
                    @endforeach
                @endif
                <h2 class="mt-5">Kartu AR</h2>
                @if ($ar->isEmpty())
                    <h5>Belum ada Kartu AR</h5>
                    <button id="add-module-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                        data-target="#add-ar-modal">Tambah AR</button>
                @else
                    <div class="container">
                        <div class="row">
                            @foreach ($ar as $ar)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img class="card-img" src="{{ asset('storage/images/ar/' . $ar->image_ar) }}"
                                            alt="Card Image">
                                        <div class="card-actions">
                                            <a href="{{ route('edit.ar.page', $ar->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <button class="btn btn-danger">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <style>
                        .card {
                            position: relative;
                            overflow: hidden;
                        }

                        .card-img {
                            padding: 1rem;
                            height: 100%;
                            width: 16rem;
                            object-fit: cover;
                            border-radius: 10px;
                        }

                        .card-actions {
                            bottom: 0;
                            left: 0;
                            width: 100%;
                            padding: 10px;
                            text-align: center;
                        }

                        .card-actions button {
                            margin-right: 5px;
                        }
                    </style>
                    <button id="add-ar-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                        data-target="#add-ar-modal">Tambah AR</button>
                @endif
                <div class="modal fade" id="add-ar-modal" tabindex="-1" role="dialog"
                    aria-labelledby="add-module-modal-title" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="add-module-modal-title">Tambah AR</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('store.ar') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group mt-2">
                                        <p>Gambar AR</p>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="ar"
                                                name="image_ar" onchange="previewImage('ar')">
                                            <label class="custom-file-label" for="ar">Pilih file</label>
                                        </div>
                                    </div>
                                    <div class="preview mt-3">
                                        <img id="ar_preview" class="img-fluid" alt="Preview Image">
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
                </div>
            </div>



        </div>
    </div>
    </div>
    <script>
        const deleteButton = document.getElementById("submit-delete");
        const deleteModule = document.getElementById("hapus-modul");
        deleteModule.addEventListener("click", function(event) {
            event.preventDefault();
            const confirmation = confirm("Apakah Anda yakin ingin menghapus modul ini?");
            if (confirmation) {
                // lanjutkan dengan submit form
                this.form.submit();
            }
        });
        deleteButton.addEventListener("click", function(event) {
            event.preventDefault();
            const confirmation = confirm("Apakah Anda yakin ingin menghapus kursus ini?");
            if (confirmation) {
                // lanjutkan dengan submit form
                this.form.submit();
            }
        });
    </script>
    <style>
        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            margin-bottom: 0;
        }

        .btn-add {
            margin-top: 5rem;
            padding: 10px 10rem 10px 10rem;
            border-radius: 10px;
            background-color: orange;
            /* remove outline */
            outline: none;
            color: white;
            /* center */
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            height: 3rem;
            border: 1px solid #ced4da;
        }

        .image_module {
            width: 10rem;
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
        const accordion = document.querySelector('#accordion');
        const collapses = accordion.querySelectorAll('.collapse');

        collapses.forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', () => {});
            collapse.addEventListener('hide.bs.collapse', () => {});
        });
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
@endsection
