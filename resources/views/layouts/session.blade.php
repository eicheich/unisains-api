@if (session('success'))
    <div class="alert alert-success text-light" role="alert">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="alert alert-danger text-light" role="alert">
        {{ session('error') }}
    </div>
@elseif (session('warning'))
    <div class="alert alert-warning text-light" role="alert">
        {{ session('warning') }}
    </div>
@endif
