@extends('layouts.main')

@section('title', 'About Company')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">About Company</li>
@endsection

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">About Company</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <form action="{{ route('about-company.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="row">
                        <!-- Banner Image -->
                        <div class="col-md-6 mb-4">
                            <div class="image-preview d-flex flex-column">
                                <label for="banner_image" class="form-label">Banner Image</label>
                                @if($aboutCompany && $aboutCompany->banner_image)
                                    <img src="{{ asset('storage/website/about-company/' . $aboutCompany->banner_image) }}" 
                                         alt="Banner Image" 
                                         height="300" 
                                         onclick="triggerFile('banner')" 
                                         class="object-fit-contain img-border mb-2" 
                                         id="banner-preview">
                                @else
                                    <img src="" 
                                         alt="Banner Image" 
                                         height="300" 
                                         onclick="triggerFile('banner')" 
                                         class="object-fit-contain img-border mb-2 d-none" 
                                         id="banner-preview">
                                @endif
                                <input type="file" 
                                       name="banner_image" 
                                       id="banner_image" 
                                       accept="image/png, image/jpeg, image/jpg" 
                                       hidden>
                                <button type="button" 
                                        class="btn btn-primary w-100" 
                                        onclick="triggerFile('banner')">
                                    <i class="ti ti-upload me-1"></i> Upload Banner Image
                                </button>
                                @error('banner_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section Image -->
                        <div class="col-md-6 mb-4">
                            <div class="image-preview d-flex flex-column">
                                <label for="section_image" class="form-label">Section Image</label>
                                @if($aboutCompany && $aboutCompany->section_image)
                                    <img src="{{ asset('storage/website/about-company/' . $aboutCompany->section_image) }}" 
                                         alt="Section Image" 
                                         height="300" 
                                         onclick="triggerFile('section')" 
                                         class="object-fit-contain img-border mb-2" 
                                         id="section-preview">
                                @else
                                    <img src="" 
                                         alt="Section Image" 
                                         height="300" 
                                         onclick="triggerFile('section')" 
                                         class="object-fit-contain img-border mb-2 d-none" 
                                         id="section-preview">
                                @endif
                                <input type="file" 
                                       name="section_image" 
                                       id="section_image" 
                                       accept="image/png, image/jpeg, image/jpg" 
                                       hidden>
                                <button type="button" 
                                        class="btn btn-primary w-100" 
                                        onclick="triggerFile('section')">
                                    <i class="ti ti-upload me-1"></i> Upload Section Image
                                </button>
                                @error('section_image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Main Description -->
                        <div class="col-12 mb-4">
                            <label for="main_description" class="form-label">Main Description</label>
                            <div id="main-description-editor" style="height: 200px;">
                                {!! $aboutCompany->main_description ?? '' !!}
                            </div>
                            <textarea name="main_description" id="main_description" class="d-none">{{ $aboutCompany->main_description ?? '' }}</textarea>
                            @error('main_description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section Description -->
                        <div class="col-12 mb-4">
                            <label for="section_description" class="form-label">Section Description</label>
                            <div id="section-description-editor" style="height: 200px;">
                                {!! $aboutCompany->section_description ?? '' !!}
                            </div>
                            <textarea name="section_description" id="section_description" class="d-none">{{ $aboutCompany->section_description ?? '' }}</textarea>
                            @error('section_description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Update About Company
                            </button>
                        </div>
                    </div><!--end row-->
                </form>
            </div><!--end card-body-->
        </div><!--end card-->
    </div><!--end col-->
</div><!--end row-->
@endsection

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

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

    // Trigger file input
    function triggerFile(type) {
        const inputId = type === 'banner' ? '#banner_image' : '#section_image';
        $(inputId).trigger('click');
    }

    // Initialize Quill editors
    let mainDescriptionEditor, sectionDescriptionEditor;

    $(document).ready(function() {
        // Initialize Quill for main description
        mainDescriptionEditor = new Quill('#main-description-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Initialize Quill for section description
        sectionDescriptionEditor = new Quill('#section-description-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Handle image preview for banner
        $('#banner_image').on('change', function(e) {
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

        // Handle image preview for section
        $('#section_image').on('change', function(e) {
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

        // Handle form submission - sync Quill content to hidden textareas
        $('form').on('submit', function() {
            $('#main_description').val(mainDescriptionEditor.root.innerHTML);
            $('#section_description').val(sectionDescriptionEditor.root.innerHTML);
        });
    });
</script>
@endpush