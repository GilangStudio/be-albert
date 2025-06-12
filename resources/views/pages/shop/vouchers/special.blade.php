@extends('layouts.main')

@section('title', 'Special Voucher')

@push('styles')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item">Vouchers</li>
<li class="breadcrumb-item active">Special</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('vouchers.special') }}" method="POST" class="card">
            @csrf
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Special Voucher New User</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="code" class="form-label">Voucher Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ @old('code') ?? $new_user_voucher?->code }}" autocomplete="off" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="discount" class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                                {{-- <input type="text" class="form-control" id="discount" name="discount" value="{{ @old('discount') }}"  autocomplete="off" required> --}}
                                <div class="input-group">
                                    <input type="number" class="form-control" id="discount" name="discount" value="{{ @old('discount') ?? $new_user_voucher?->discount_percentage }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); this.value = this.value > 100 ? 100 : this.value; this.value = this.value < 1 ? 1 : this.value;" autocomplete="off" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="min-order" class="form-label">Min. Order <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="min-order" name="min_order" value="{{ @old('min_order') ?? $new_user_voucher?->minimum_order }}" required>
                            </div>
                            <div class="col-12">
                                <label for="duration" class="form-label">Voucher Duration <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="duration" name="duration" value="{{ @old('duration') ?? $new_user_voucher?->duration  }}" required>
                                    <span class="input-group-text">Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('vouchers.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        
        $('form').submit(function() {
            $('#min-order').val($('#min-order').val().replace(/[^0-9]/g, ''));
        });

        $('#code').on('input', function() {
            $(this).val($(this).val().replace(/\s/g, ''));
        });
    });
</script>
@endpush