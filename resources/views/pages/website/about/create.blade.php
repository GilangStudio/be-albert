@extends('layouts.main')

@section('title', 'Create About Section')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
<style>
    .list-group {
    width: 100%;
    max-width: 460px;
    margin-inline: 1.5rem;
}

.form-check-input:checked+.form-checked-content {
    opacity: .5;
}

.form-check-input-placeholder {
    border-style: dashed;
}

[contenteditable]:focus {
    outline: 0;
}

.list-group-checkable .list-group-item {
    cursor: pointer;
}

.list-group-item-check {
    position: absolute;
    clip: rect(0, 0, 0, 0);
}

.list-group-item-check:hover+.list-group-item {
    background-color: var(--bs-secondary-bg);
}

.list-group-item-check:checked+.list-group-item {
    color: #fff;
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.list-group-item-check[disabled]+.list-group-item,
.list-group-item-check:disabled+.list-group-item {
    pointer-events: none;
    filter: none;
    opacity: .5;
}

.list-group-radio .list-group-item {
    cursor: pointer;
    border-radius: .5rem;
}

.list-group-radio .form-check-input {
    z-index: 2;
    margin-top: -.5em;
}

.list-group-radio .list-group-item:hover,
.list-group-radio .list-group-item:focus {
    background-color: var(--bs-secondary-bg);
}

.list-group-radio .form-check-input:checked+.list-group-item {
    background-color: var(--bs-body);
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 2px var(--bs-primary);
}

.list-group-radio .form-check-input[disabled]+.list-group-item,
.list-group-radio .form-check-input:disabled+.list-group-item {
    pointer-events: none;
    filter: none;
    opacity: .5;
}
</style>
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item">About</li>
<li class="breadcrumb-item active">Create About Section</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('about.store') }}" method="POST" class="card" enctype="multipart/form-data">
            @csrf
            <div class="card-header">
                <h4 class="card-title">Create About Section</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="mb-3 image-preview d-flex flex-column">
                            <label for="image" class="form-label">image <span class="text-danger">*</span></label>
                            <img src="" alt="" height="300" onclick="triggerFile()" class="object-fit-contain img-border mb-2 d-none">
                            <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" hidden>
                            <button type="button" class="btn btn-primary w-100" onclick="triggerFile()"><i class="ti ti-upload me-1"></i> Upload Image</button>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <input type="hidden" name="content" id="content">
                            <div id="quill-content" style="height: auto">{!! old('content') !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Layout <span class="text-danger">*</span></label>
                            <div class="col-md-10 d-flex align-items-center">
                                <div class="list-group-radio w-100">
    
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <input class="form-check-input position-absolute top-50 end-0 fs-5 d-none" type="radio" name="layout" id="layout-1" value="true" checked required>
                                            <div class="card border border-1 list-group-item mb-0 h-100">
                                                <div class="card-body text-center p-0">
                                                    <label class="fs-4 cursor-pointer p-2 p-md-3 d-flex justify-content-center align-items-center" for="layout-1">
                                                        <i class="ti ti-photo display-6"></i>
                                                        <i class="ti ti-align-left display-6"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input class="form-check-input position-absolute top-50 end-0 fs-5 d-none" type="radio" name="layout" id="layout-2" value="false" required>
                                            <div class="card border border-1 list-group-item mb-0 h-100">
                                                <div class="card-body text-center p-0">
                                                    <label class="fs-4 cursor-pointer p-2 p-md-3 d-flex justify-content-center align-items-center" for="layout-2">
                                                        <i class="ti ti-align-right display-6"></i>
                                                        <i class="ti ti-photo display-6"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
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
        var quill_content = new Quill('#quill-content', options);

        $('form').submit(function() {
            var content = quill_content.root.innerHTML;
            $('#content').val(content);
        });
    });
</script>
<script>
    // Function to trigger file input
    function triggerFile() {
        $(`#image`).trigger('click');
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