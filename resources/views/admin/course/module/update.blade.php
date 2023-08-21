@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Modul</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    <form action="{{ route('update.modules', $module->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 px-5">
            <label for="course_name" class="form-label">Judul Modul</label>
            <input type="text" class="form-control" id="course_name" name="title_module" placeholder=""
                value="{{ $module->title_module }}">
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gambar Modul</h5>
                <img src="{{ asset('storage/images/module/' . $module->image_module) }}" alt="" width="100px">
                <div class="form-group">
                    <label class="text-dom-a5" for="image">Image</label>
                    <input id="image" type="file" class="form-control" name="image_module"
                        value="{{ $module->image_module }}">
                </div>
                <div class="preview mt-3">
                    <img id="image_module_preview" class="img-fluid" alt="Preview Image">
                </div>
            </div>
        </div>
        <div class="mb-3 px-5">
            <label for="course_description" class="form-label">Deskripsi Modul</label>
            <textarea class="form-control" id="module_description" name="description"></textarea>
        </div>
        <div class="mb-3 px-5">
            <label for="course_description" class="form-label">Materi Modul</label>
            <textarea class="form-control" id="module_materi" name="materi_module"></textarea>
        </div>
        <div class="card mt-5">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    <style>
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

        .card {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

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
    <script>
        // function previewImage(inputId) {
        //     var preview = document.querySelector('#' + inputId + '_preview');
        //     var file = document.querySelector('#' + inputId).files[0];
        //     var reader = new FileReader();

        //     reader.onloadend = function() {
        //         preview.src = reader.result;
        //     }

        //     if (file) {
        //         reader.readAsDataURL(file);
        //     } else {
        //         preview.src = "";
        //     }
        // }

        document.getElementById("module_description").value = "{{ $module->description }}";
        document.getElementById("module_materi").value = "{{ $module->materi_module }}";

        // document.getElementById("image_module").value = "{{ $module->image_module }}";
    </script>
@endsection
