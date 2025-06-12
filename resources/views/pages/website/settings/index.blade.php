@extends('layouts.main')

@section('title', 'Settings')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('settings.update') }}" method="POST" class="card" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <h4 class="card-title">Settings</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-3 image-preview d-flex flex-column">
                            <label for="logo" class="form-label">Logo <span class="text-danger">*</span></label>
                            <img src="{{ isset($settings->logo) ? asset('storage/website/' . $settings->logo) : '' }}" alt="" height="300" onclick="triggerFile()" class="object-fit-contain img-border mb-2 {{ isset($settings->logo) ? '' : 'd-none' }}">
                            <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg" hidden>
                            <button type="button" class="btn btn-primary w-100" onclick="triggerFile()"><i class="ti ti-upload me-1"></i> Upload Image</button>
                        </div>
                        <div class="mb-3">
                            <label for="instagram-url" class="form-label">Instagram URL</label>
                            <input type="url" class="form-control" id="instagram-url" name="instagram_url" value="{{ @old('instagram_url') ? @old('instagram_url') : (isset($settings->instagram_url) ? $settings->instagram_url : '') }}">
                        </div>
                        <div class="mb-3">
                            <label for="facebook-url" class="form-label">Facebook URL</label>
                            <input type="url" class="form-control" id="facebook-url" name="facebook_url" value="{{ @old('facebook_url') ? @old('facebook_url') : (isset($settings->facebook_url) ? $settings->facebook_url : '') }}">
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp-url" class="form-label">WhatsApp URL</label>
                            <input type="url" class="form-control" id="whatsapp-url" name="whatsapp_url" value="{{ @old('whatsapp_url') ? @old('whatsapp_url') : (isset($settings->whatsapp_url) ? $settings->whatsapp_url : '') }}">
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
<script>
    // Function to trigger file input
    function triggerFile() {
        $(`#logo`).trigger('click');
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