@extends('layouts.main')

@section('title', 'Create User')

@push('styles')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item">User</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('users.store') }}" method="POST" class="card">
            @csrf
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Create User</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ @old('name') }}" autocomplete="off" required>
                            </div>
                            <div class="col-12">
                                <label for="code" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" id="country_code" name="country_code" required>
                                        <option selected="" value="62">(+62)</option>
                                    </select>
                                    <input type="text" class="form-control w-75" id="phone" name="phone" value="{{ @old('phone') }}" placeholder="8123456789" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ @old('email') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="radio-1" value="MALE" required>
                                        <label class="form-check-label" for="radio-1">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="radio-2" value="FEMALE" required>
                                        <label class="form-check-label" for="radio-2">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-12">
                                <label for="confirm-password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm-password" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        
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