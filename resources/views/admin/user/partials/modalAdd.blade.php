<div class="modal fade" id="add-acc-modal" tabindex="-1" role="dialog" aria-labelledby="add-module-modal-title"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-module-modal-title">Tambah Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('store.users') }}" method="post">
                    @csrf
{{--                    drop down role--}}
                    <div class="form-group">
                        <label for="module-name">Role</label>
                        <select class="" name="role">
                            <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Pengajar</option>
                                <option value="user">Pengguna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="module-name">Nama Depan</label>
                        <input type="text" class="form-control" id="module-name" name="first_name" placeholder="Nama Depan">
                    </div>
                    <div class="form-group mt-3">
                        <label for="module-name">Nama Belakang</label>
                        <input type="text" class="form-control" id="module-name" name="last_name" placeholder="Nama Belakang">
                    </div>
                    <div class="form-group mt-3">
                        <label for="module-name">Nama Pengguna</label>
                        <input type="text" class="form-control" id="module-name" name="username"
                            placeholder="Nama Pengguna">
                    </div>
                    <div class="form-group mt-3">
                        <label for="module-name">Email</label>
                        <input type="email" class="form-control" id="module-name" name="email"
                            placeholder="Email">
                    </div>
                    <div class="form-group mt-3">
                        <label for="module-name">Kata Sandi</label>
                        <input type="password" class="form-control" id="module-name" name="password"
                            placeholder="Kata sandi">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
