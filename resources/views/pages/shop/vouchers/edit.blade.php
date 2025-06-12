@extends('layouts.main')

@section('title', 'Edit Voucher')

@push('styles')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item">Voucher</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Edit Voucher</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            
                            <div class="col-12">
                                <label for="name" class="form-label">Voucher Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ @old('name') ?? $voucher->name }}" autocomplete="off" required>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3">{{ @old('description') ?? $voucher->description }}</textarea>
                            </div>
                            <div class="col-12">
                                <label for="code" class="form-label">Voucher Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ @old('code') ?? $voucher->code }}" autocomplete="off" required>
                            </div>
                            {{-- <div class="col-12">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select type="text" class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="GENERAL">GENERAL</option>
                                    <option value="NEW_USER">NEW USER</option>
                                </select>
                            </div> --}}
                            <div class="col-12">
                                <label for="discount" class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                                {{-- <input type="text" class="form-control" id="discount" name="discount" value="{{ @old('discount') }}"  autocomplete="off" required> --}}
                                <div class="input-group">
                                    <input type="number" class="form-control" id="discount" name="discount" value="{{ @old('discount') ?? $voucher->discount_percentage }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); this.value = this.value > 100 ? 100 : this.value; this.value = this.value < 1 ? 1 : this.value;" autocomplete="off" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="min-order" class="form-label">Min. Order <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="min-order" name="min_order" value="{{ @old('min_order') ?? 'Rp. ' . number_format($voucher->minimum_order, 0, ',', '.') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="expired-date" class="form-label">Expired Date <span class="text-danger">*</span></label>
                                {{-- <input type="date" class="form-control" id="expired-date" name="expired_date" value="{{ @old('expired_date') }}" required> --}}
                                <input type="text" class="form-control date" id="expired-date" name="expired_date" value="{{ @old('expired_date') ?? \Carbon\Carbon::createFromFormat('Y-m-d', $voucher->expiry_date)->format('d/m/Y') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="voucher-status" class="form-label">Voucher Status <span class="text-danger">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="voucher-status" name="voucher_status" {{ @old('voucher_status') == 1 || $voucher->is_public == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="voucher-status">Public</label>
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

<script>
    $('#expired-date').datetimepicker({
        format: 'DD/MM/YYYY',
        viewMode: 'days',
        viewDate: new Date(),
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: "fa fa-chevron-left",
            next: "fa fa-chevron-right",
            today: "fa fa-screenshot",
            clear: "fa fa-trash",
            close: "fa fa-remove"
        }
    });
</script>
@endpush