@if(session()->has('error'))
    <div class="alert alert-danger">
        <p>{{ session()->get('error') }}</p>
    </div>
@endif
@if(isset($error))
    <div class="alert alert-danger">
        <p>{{ $error }}</p>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if(isset($success))
    <div class="alert alert-success">
        <p>{{ $success }}</p>
    </div>
@endif