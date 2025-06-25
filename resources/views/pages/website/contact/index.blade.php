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
                        <!-- Banner Image -->
                        <div class="mb-3 image-preview d-flex flex-column">
                            <label for="banner-image" class="form-label">Banner Image <span class="text-danger">*</span></label>
                            <img src="{{ isset($contact->banner_image) ? asset('storage/website/contact/' . $contact->banner_image) : '' }}" 
                                 alt="Banner Image" 
                                 height="300" 
                                 onclick="triggerFile('banner')" 
                                 class="object-fit-contain img-border mb-2 {{ isset($contact->banner_image) ? '' : 'd-none' }}" 
                                 id="banner-preview">
                            <input type="file" name="banner_image" id="banner-image" accept="image/png, image/jpeg, image/jpg" hidden>
                            <button type="button" class="btn btn-primary w-100" onclick="triggerFile('banner')">
                                <i class="ti ti-upload me-1"></i> Upload Banner Image
                            </button>
                            @error('banner_image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section Image -->
                        <div class="mb-3 image-preview d-flex flex-column">
                            <label for="section-image" class="form-label">Section Image</label>
                            <img src="{{ isset($contact->section_image) ? asset('storage/website/contact/' . $contact->section_image) : '' }}" 
                                 alt="Section Image" 
                                 height="300" 
                                 onclick="triggerFile('section')" 
                                 class="object-fit-contain img-border mb-2 {{ isset($contact->section_image) ? '' : 'd-none' }}" 
                                 id="section-preview">
                            <input type="file" name="section_image" id="section-image" accept="image/png, image/jpeg, image/jpg" hidden>
                            <button type="button" class="btn btn-primary w-100" onclick="triggerFile('section')">
                                <i class="ti ti-upload me-1"></i> Upload Section Image
                            </button>
                            @error('section_image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="hidden" name="description" id="description">
                            <div id="quill-description" style="height: auto">
                                {!! old('description') ? old('description') : (isset($contact->description) ? $contact->description : '') !!}
                            </div>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="hidden" name="address" id="address">
                            <div id="quill-address" style="height: auto">
                                {!! old('address') ? old('address') : (isset($contact->address) ? $contact->address : '') !!}
                            </div>
                            @error('address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone-number" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="phone-number" 
                                   name="phone_number" 
                                   value="{{ @old('phone_number') ? @old('phone_number') : (isset($contact->phone_number) ? $contact->phone_number : '') }}">
                            @error('phone_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ @old('email') ? @old('email') : (isset($contact->email) ? $contact->email : '') }}">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
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
    // File validation function
    function validateFile(file) {
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPEG, JPG)');
            return false;
        }

        if (file.size > maxSize) {
            alert('File size must be less than 10MB');
            return false;
        }

        return true;
    }

    // Function to trigger file input
    function triggerFile(type) {
        const inputId = type === 'banner' ? '#banner-image' : '#section-image';
        $(inputId).trigger('click');
    }

    // Handle image preview for both images
    $(document).ready(function() {
        // Handle banner image preview
        $('#banner-image').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!validateFile(file)) {
                $(this).val('');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#banner-preview').attr('src', e.target.result);
                $('#banner-preview').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        });

        // Handle section image preview
        $('#section-image').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!validateFile(file)) {
                $(this).val('');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#section-preview').attr('src', e.target.result);
                $('#section-preview').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush