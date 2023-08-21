@extends('layouts.main')
@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kursus</h1>
        @include('layouts.session')
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <img src="{{ asset('storage/images/thumbnail_course/' . $course->image_course) }}" alt="Course Image"
                     class="img-fluid mb-3"/>
                <h3>Judul Kursus : {{ $course->title_course }}</h3>
                @if ($course->is_paid == 0)
                    <p>Gratis</p>
                @else
                    <p>Harga : Rp. {{ $course->price }}</p>
                @endif
                <p>Kategori : {{ $course->category->name_category }}</p>
                <p>Deskripsi : {{ $course->description }}</p>
                <h3>Modul</h3>
                <ul>
                    @if ($modules->isEmpty())
                        <p>Belum ada Modul</p>
                    @else
                        @foreach ($modules as $m)
                            <div id="accordion">
                                <div class="card mt-3">
                                    <div class="coll-show" id="heading{{ $m->id }}">
                                        <h5 class="mb-0">
                                            <button class="btn-coll" data-toggle="collapse"
                                                    data-target="#collapse{{ $m->id }}" aria-expanded="true"
                                                    aria-controls="collapse{{ $m->id }}">
                                                {{ $m->title_module }}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse{{ $m->id }}" class="collapse show"
                                         aria-labelledby="heading{{ $m->id }}" data-parent="#accordion">
                                        <div class="card">
                                            <img class="image_modul"
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
                                            <div class="card-actions d-flex justify-content-center mt-3">
                                                <div>
                                                    <a href="{{ route('update.modules.page', $m->id) }}"
                                                       class="btn btn-primary">Edit</a>
                                                </div>
                                                <div>
                                                    <form action="{{ route('delete.modules', $m->id) }}" method="post">
                                                        @csrf
                                                        <button type="submit" id="hapus-ar"
                                                                class="btn btn-danger mx-2">Hapus
                                                            Module
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <button id="add-module-btn" class="btn btn-sm btn-outline-secondary mt-3" data-toggle="modal"
                            data-target="#add-module-modal">Tambah Modul
                    </button>
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
                                            <input type="text" class="form-control" id="module-name" name="description"
                                                   placeholder="Deskripsi Modul">
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
                                            <input type="text" class="form-control" id="module-name" name="materi_module"
                                                   placeholder="Materi Modul">
                                        </div>
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
                <h3>Modul Rangkuman</h3>
                @if ($summary_modules->isEmpty())
                    <p>Belum ada modul rangkuman</p>
                    <button id="add-module-btn" class="btn btn-sm btn-outline-secondary " data-toggle="modal"
                            data-target="#add-rangkuman-modal">Tambah Rangkuman
                    </button>
                @else
                    @foreach ($summary_modules as $mr)
                        <div class="card">
                            <p>{{ $mr->summary }}</p>
                            <video controls>
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->summary_video) }}"
                                        type="video/mp4">
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->summary_video) }}"
                                        type="video/webm">
                                <source src="{{ asset('storage/video/rangkuman/' . $mr->summary_video) }}"
                                        type="video/ogg">
                                Your browser does not support the video tag. You can <a
                                    href="{{ asset('storage/video/rangkuman/' . $mr->summary_video) }}">download the
                                    video</a>
                                instead.
                            </video>
                        </div>
                        <div class="card-actions d-flex justify-content-center mt-3">
                            <div>
                                <a href="{{ route('update.rangkuman.page', $mr->id) }}" class="btn btn-primary">Edit</a>
                            </div>
                            <div>
                                <form action="{{ route('delete.rangkuman', $mr->id) }}" method="post">
                                    @csrf
                                    <button type="submit" id="hapus-ar" class="btn btn-danger mx-2">Hapus
                                        Module
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
                <h3 class="mt-5">Kartu AR</h3>
                @if ($ar->isEmpty())
                    <p>Belum ada Kartu AR</p>
                    <button id="add-module-btn" class="btn btn-sm btn-outline-secondary" data-toggle="modal"
                            data-target="#add-ar-modal">Tambah AR
                    </button>
                @else
                    <div class="container">
                        <div class="row">
                            @foreach ($ar as $ar)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img class="card-img" src="{{ asset('storage/images/ar/' . $ar->image_ar) }}"
                                             alt="Card Image">
                                        <div class="card-actions d-flex justify-content-center mt-3">
                                            <div>
                                                <a href="{{ route('edit.ar.page', $ar->id) }}"
                                                   class="btn btn-primary">Edit</a>
                                            </div>
                                            <div>
                                                <form action="{{ route('delete.ar', $ar->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" id="hapus-ar"
                                                            class="btn btn-danger mx-2">Hapus
                                                        AR
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button id="add-ar-btn" class="btn btn-sm btn-outline-secondary mt-4" data-toggle="modal"
                            data-target="#add-ar-modal">Tambah AR
                    </button>
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
                                                data-dismiss="modal">Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="mt-5">Soal Kursus</h3>
                @if ($quiz->isEmpty())
                    <p>Belum ada soal kursus</p>
                    <button id="add-ar-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                            data-target="#add-quiz-modal">Tambah Soal
                    </button>
                @else
                    <div class="container">
                        <table id="quizTable" class="table table-striped text-center">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Soal</th>
                                <th>Jawaban benar</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($quiz as $index => $quizItem)
                                @foreach ($quizItem->questions as $question)
                                    <tr>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->question }}</td>
                                    <td>{{ $question->correct_answer }}</td>
                                    <td>

                                        <a href="{{ route('quiz.show', $quizItem->id) }}" class="btn btn-primary">Detail</a>
                                        <form action="{{ route('delete.quiz', $quizItem->id) }}" method="post" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Hapus Soal</button>
                                        </form>
                                    </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button id="add-ar-btn" class="btn btn-sm btn-outline-secondary mt-5" data-toggle="modal"
                            data-target="#add-quiz-modal">Tambah Soal
                    </button>
                @endif
                @include('admin.course.partials.modalGroupQuiz')
            </div>
        </div>
    </div>
    </div>
    @include('admin.course.partials.modalVideo')
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
            outline: none;
            color: white;
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

        .coll-show {
            background-color: #ffffff;
            justify-content: center;
            border: none;
            border-radius: 4;
            box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
        }

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

        .btn-coll {
            padding: 1rem;
            background-color: #ffffff;
            justify-content: center;
            border: none;
            border-radius: 40%;
            box-shadow: none;
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
        $(document).ready(function () {
            $('#quizTable').DataTable();
        });
        const deleteButton = document.getElementById("submit-delete");
        const deleteModule = document.getElementById("hapus-modul");
        const deleteAr = document.getElementById("hapus-ar");

        deleteModule.addEventListener("click", function (event) {
            handleDelete.call(this, event, "Apakah Anda yakin ingin menghapus modul ini?");
        });

        deleteButton.addEventListener("click", function (event) {
            handleDelete.call(this, event, "Apakah Anda yakin ingin menghapus kursus ini?");
        });
        deleteAr.addEventListener("click", function (event) {
            handleDelete.call(this, event, "Apakah Anda yakin ingin menghapus kursus ini?");
        });

        function handleDelete(event, confirmationMessage) {
            event.preventDefault();
            const confirmation = confirm(confirmationMessage);
            if (confirmation) {
                this.form.submit();
            }
        }

        const accordion = document.querySelector('#accordion');
        const collapses = accordion.querySelectorAll('.collapse');

        collapses.forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', () => {
            });
            collapse.addEventListener('hide.bs.collapse', () => {
            });
        });
        $(document).ready(function () {
            $('#add-module-btn').click(function () {
                $('#add-module-modal').modal('show');
            });
        });

        function previewImage(inputId) {
            var preview = document.querySelector('#' + inputId + '_preview');
            var file = document.querySelector('#' + inputId).files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
                preview.src = reader.result;
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }

        function previewVideo(inputId) {
            var preview = document.querySelector('#video_preview');
            var source = document.querySelector('#video_source');
            var file = document.querySelector('#' + inputId).files[0];
            var reader = new FileReader();

            reader.onloadend = function () {
                source.src = reader.result;
                preview.load();
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                source.src = "";
            }
        }
        document.querySelector('#video').addEventListener('change', function () {
            previewVideo('video');
        });
    </script>
@endsection
