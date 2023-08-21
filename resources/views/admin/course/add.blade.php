@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Kursus</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    {{-- form add course --}}
    <form action="{{ route('store.course') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 px-5">
            <label for="course_name" class="form-label">Nama Kursus</label>
            <input type="text" class="form-control" id="course_name" name="title_course" placeholder="">
        </div>
        <div class="mb-3 px-5">
            <label for="course_description" class="form-label">Deskripsi Kursus</label>
            <input type="text" class="form-control" id="course_name" name="description" placeholder="">
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Thumbnail Kursus</h5>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image_course" name="image_course"
                            onchange="previewImage('image_course')">
                        <label class="custom-file-label" for="image_course">Pilih file</label>
                    </div>
                </div>
                <div class="preview mt-3">
                    <img id="image_course_preview" class="img-fluid" alt="Preview Image">
                </div>
            </div>
        </div>

        <div class="mb-3 px-5">
            <label for="course_category" class="form-label">Kategori Kursus</label>
            <select class="form-select p-2" aria-label="Default select example" id="course_category" name="category_id">
                <option selected>Pilih Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->name_category }}</option>
                @endforeach
            </select>
        </div>
        {{-- checkbox free dan paid, jika check paid maka akan muncul form price --}}


        <div class="mb-3 px-5">
            <label for="price" class="form-label">Harga Kursus</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="free_course" name="is_paid" value="0    "
                    onchange="togglePrice()">
                <label class="form-check-label" for="free_course">Gratis</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="paid_course" name="is_paid" value="1"
                    onchange="togglePrice()">
                <label class="form-check-label" for="paid_course">Berbayar</label>
            </div>
            <div id="price_container" style="display:none">
                <input type="number" class="form-control" id="price" name="price" placeholder="Harga Kursus">
            </div>
        </div>
        <div class="mb-3 px-5">
            <label for="course_name" class="form-label">Link Percakapan</label>
            <input type="text" class="form-control" id="course_name" name="link_chat" placeholder="">
        </div>
        <div class="card mt-5">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
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
        </style>
    </form>
    <style>
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


        function togglePrice() {
            var free_checkbox = document.querySelector('#free_course');
            var paid_checkbox = document.querySelector('#paid_course');
            var price_container = document.querySelector('#price_container');

            if (paid_checkbox.checked) {
                price_container.style.display = 'block';
            } else {
                price_container.style.display = 'none';
            }

            free_checkbox.checked = !paid_checkbox.checked;
        }

        function toggleDiscount() {
            var discount_checkbox = document.querySelector('#discount');
            var discount_container = document.querySelector('#discount_container');

            if (discount_checkbox.checked) {
                discount_container.style.display = 'block';
            } else {
                discount_container.style.display = 'none';
            }
        }
    </script>
@endsection
