@extends('layouts.main')

@section('title', 'Edit User')

@push('styles')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item">User</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="card">
            @method('PUT')
            @csrf
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title">Edit User</h4>
                {{-- <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('reset-password-form').submit();"><i class="fas fa-key me-1"></i> Reset Password</button>
                </div> --}}
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ @old('name') ?? $user->name }}" autocomplete="off" required>
                            </div>
                            <div class="col-12">
                                <label for="code" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" id="country_code" name="country_code" required>
                                        <option selected="" value="62" {{ $user->country_code == '62' ? 'selected' : '' }}>(+62)</option>
                                    </select>
                                    <input type="text" class="form-control w-75" id="phone" name="phone" value="{{ @old('phone') ?? $user->phone_number }}" placeholder="8123456789" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ @old('email') ?? $user->email }}" required>
                            </div>
                            <div class="col-12">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="radio-1" value="MALE" {{ $user->gender == 'MALE' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="radio-1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="radio-2" value="FEMALE" {{ $user->gender == 'FEMALE' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="radio-2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted mt-5">Change Password (Optional)</h6>
                            <div class="col-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-12">
                                <label for="confirm-password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- <form action="{{ route('users.reset-password', $user->id) }}" id="reset-password-form" method="POST">
    @csrf
</form> --}}

@endsection

@push('scripts')
<script>
    $(function () {
        $('form').submit(function() {
            if($('#password').val() != $('#confirm-password').val()) {
                alert('Password and Confirm Password doesn\'t match');
                $('#password').focus();
                return false;
            }
        });
    });
</script>
@endpush