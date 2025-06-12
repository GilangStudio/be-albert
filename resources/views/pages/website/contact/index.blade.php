@extends('layouts.main')

@section('title', 'Contact')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Contact</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('contact.update') }}" method="POST" class="card" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <h4 class="card-title">Contact</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-3 image-preview d-flex flex-column">
                            <label for="banner-image" class="form-label">Banner Image <span class="text-danger">*</span></label>
                            <img src="{{ isset($contact->banner_image) ? asset('storage/website/contact/' . $contact->banner_image) : '' }}" alt="" height="300" onclick="triggerFile()" class="object-fit-contain img-border mb-2 {{ isset($contact->banner_image) ? '' : 'd-none' }}">
                            <input type="file" name="banner_image" id="banner-image" accept="image/png, image/jpeg, image/jpg" hidden>
                            <button type="button" class="btn btn-primary w-100" onclick="triggerFile()"><i class="ti ti-upload me-1"></i> Upload Image</button>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="hidden" name="description" id="description">
                            <div id="quill-description" style="height: auto">{!! old('description') ? old('description') : (isset($contact->description) ? $contact->description : '') !!}</div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="hidden" name="address" id="address">
                            <div id="quill-address" style="height: auto">{!! old('address') ? old('address') : (isset($contact->address) ? $contact->address : '') !!}</div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control">{{ @old('description') ? @old('description') : (isset($contact->description) ? $contact->description : '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="5" class="form-control">{{ @old('address') ? @old('address') : (isset($contact->address) ? $contact->address : '') }}</textarea>
                        </div> --}}
                        <div class="mb-3">
                            <label for="phone-number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone-number" name="phone_number" value="{{ @old('phone_number') ? @old('phone_number') : (isset($contact->phone_number) ? $contact->phone_number : '') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ @old('email') ? @old('email') : (isset($contact->email) ? $contact->email : '') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/quill-options.js') }}"></script>
<script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var quill_description = new Quill('#quill-description', options);
        var quill_address = new Quill('#quill-address', options);

        $('form').submit(function() {
            var description = quill_description.root.innerHTML;
            var address = quill_address.root.innerHTML;
            $('#address').val(address);
            $('#description').val(description);
        });
    });
</script>
<script>
    // Function to trigger file input
    function triggerFile() {
        $(`#banner-image`).trigger('click');
    }

    // Handle image preview for both create and edit forms
    $(document).ready(function() {
        // Handle file input change
        $('.image-preview input[type="file"]').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file
            if (!validateFile(file)) {
                $(this).val(''); // Clear input if validation fails
                return;
            }

            // Find the closest image preview element
            const previewImg = $(this).closest('.image-preview').find('img');
            
            // Create FileReader instance
            const reader = new FileReader();
            
            // Handle file load
            reader.onload = function(e) {
                previewImg.attr('src', e.target.result);
                previewImg.removeClass('d-none');
            };
            
            // Read file as data URL
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush