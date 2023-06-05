<div class="modal fade" id="add-rangkuman-modal" tabindex="-1" role="dialog" aria-labelledby="add-module-modal-title"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-module-modal-title">Tambah Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store.rangkuman') }}" method="post">
                    @csrf
                    <h5 class="card-title">Video Rangkuman</h5>
                    <div class="form-group">
                        <label class="text-dom-a5" for="video">Video</label>
                        <input id="video" type="file" class="form-control" name="video_rangkuman" onchange="previewVideo('video')">
                    </div>
                    <div class="card preview mt-3">
                        <video id="video_preview" controls>
                            <source id="video_source" src="#" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
            </div>
            <div class="mb-3 px-5">
                <label for="course_description" class="form-label">Materi Rangkuman</label>
                <textarea class="form-control" id="module_materi" name="isi_rangkuman" rows="3"></textarea>
            </div>
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
