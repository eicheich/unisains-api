<div class="modal fade" id="add-quiz-modal" tabindex="-1" role="dialog" aria-labelledby="add-module-modal-title"
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
                <form action="{{ route('store.quiz') }}" method="post">
                    @csrf
                    <h5>Soal</h5>
                    <div class="form-group">
                        <label for="module-name">Pertanyaan</label>
                        <input type="text" class="form-control" id="module-name" name="question" placeholder="Pertanyaan" required>
                        <hr>
                        <div class="form-group">
                            <label for="module-name">Jawaban Benar</label>
                            <div class="option">
                                <div class="radio" >
                                    <input type="radio" name="correct_answer" value="a" id="jawaban-a">
                                    <label for="jawaban-a">A</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" name="correct_answer" value="b" id="jawaban-b">
                                    <label for="jawaban-b">B</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" name="correct_answer" value="c" id="jawaban-c">
                                    <label for="jawaban-c">C</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" name="correct_answer" value="d" id="jawaban-d">
                                    <label for="jawaban-d">D</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="quiz_id" value="{{ $quizItem->id }}">
                        <hr>
                        <style>
                            .opsi-jawaban {
                                margin-bottom: 10px;
                            }
                        </style>
                        <h5>Jawaban Pilihan Ganda</h5>
                        <div class="opsi-jawaban">
                            <label for="module-name">Opsi A</label>
                            <input type="text" class="form-control" id="module-name" name="a" placeholder="Opsi A" required>
                        </div>
                        <div class="opsi-jawaban">
                            <label for="module-name">Opsi B</label>
                            <input type="text" class="form-control" id="module-name" name="b" placeholder="Opsi B" required>
                        </div>
                        <div class="opsi-jawaban">
                            <label for="module-name">Opsi C</label>
                            <input type="text" class="form-control" id="module-name" name="c" placeholder="Opsi C" required>
                        </div>
                        <div class="opsi-jawaban">
                            <label for="module-name">Opsi D</label>
                            <input type="text" class="form-control" id="module-name" name="d" placeholder="Opsi D" required>
                        </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
