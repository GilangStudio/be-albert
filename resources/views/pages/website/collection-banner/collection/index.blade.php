@extends('layouts.main')

@section('title', 'Collection Banner')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Collection Banner</li>
@endsection

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <form class="card" action="{{ route('collection-banner.collection.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Image Gallery</h4>
                @if ($banner && count($banner->images) > 1)
                <div>
                    <button type="button" class="btn btn-primary btn-icon-square-sm" data-bs-toggle="modal" data-bs-target="#modal-sort">
                        <i class="ti ti-sort-ascending-numbers"></i>
                    </button>
                </div>
                @endif
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="images" class="form-label">Collection Banner Images</label>
                        <input type="file" id="images" accept="image/png, image/jpeg, image/jpg" hidden multiple>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="triggerFile('images')">
                                <i class="ti ti-upload me-1"></i> Upload Images
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row gy-3" id="images-preview">
                    @if($banner && count($banner->images) > 0)
                        @foreach ($banner->images as $image)
                        <div class="col-6 col-md-4 col-xxl-3 position-relative" data-id="{{ $image->id }}">
                            <input type="hidden" name="image_orders[]" value="{{ $image->id }}">
                            <img src="{{ asset('storage/website/collection-banners/' . $image->image) }}" 
                                 height="150" 
                                 class="w-100 object-fit-contain img-border" 
                                 alt="Collection Banner Image">
                            <button type="button" 
                                    class="btn btn-danger btn-icon-circle btn-icon-circle-sm position-absolute top-0 end-0 me-2 delete-image" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modal-delete" 
                                    data-route="{{ route('collection-banner.collection.image.destroy', $image->id) }}">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form action="{{ route('collection-banner.collection.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Collection Banner Description</h4>
                </div><!--end card-header-->
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="description" class="form-label">Banner Description</label>
                                <div id="description-editor" style="height: 200px;">
                                    {!! $banner->description ?? '' !!}
                                </div>
                                <textarea name="description" id="description" class="d-none">{{ $banner->description ?? '' }}</textarea>
                                @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Sort -->
@if ($banner && count($banner->images) > 1)
<div class="modal fade" id="modal-sort" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('collection-banner.collection.sort') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h6 class="modal-title m-0 text-white">Sort Collection Banner Images</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div><!--end modal-header-->
                <div class="modal-body">
                    <ul id="list-images" class="list-group" style="list-style-type: none">
                        @foreach ($banner->images as $image)
                        <li class="list-group-item">
                            <div class="d-flex gap-2 align-items-center">
                                <div>
                                    <span class="badge bg-primary badge-pill">{{ $image->display_order }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="hidden" name="image_ids[]" value="{{ $image->id }}">
                                    <div class="d-flex gap-2 align-items-center">
                                        <img src="{{ asset('storage/website/collection-banners/'.$image->image) }}" 
                                             height="60" 
                                             width="60" 
                                             class="object-fit-contain">
                                        <div>
                                            <strong>Image #{{ $image->display_order }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Order</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@include('components.modal-delete', [
  'route' => '',
  'message' => '',
])
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

    // Function to trigger file input
    function triggerFile(id) {
        $(`#${id}`).trigger('click');
    }

    // Function to preview multiple images
    function previewImage(files, previewContainer) {
        // Validate and preview each file
        Array.from(files).forEach((file, index) => {
            // Validate file
            if (!validateFile(file)) {
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageUrl = e.target.result;
                
                // Create preview column with image and hidden input
                const previewColumn = `
                    <div class="col-6 col-md-4 col-xxl-3 position-relative">
                        <div class="image-container">
                            <img src="${imageUrl}" 
                                 height="150" 
                                 class="w-100 object-fit-contain img-border" 
                                 alt="Preview Image">
                            <button type="button" 
                                    class="btn btn-danger btn-icon-circle btn-icon-circle-sm position-absolute top-0 end-0 me-2 remove-image" 
                                    data-index="${index}">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        <input type="file" name="images[]" hidden>
                    </div>
                `;

                // Add preview column to container
                previewContainer.append(previewColumn);

                // Set the file to the hidden input
                const hiddenInput = previewContainer.find(`div[class*="col-"]:last-child input[type="file"]`)[0];
                const dt = new DataTransfer();
                dt.items.add(file);
                hiddenInput.files = dt.files;
            };
            
            reader.readAsDataURL(file);
        });
    }

    let descriptionEditor;

    $(document).ready(function() {
        // Initialize Quill for description
        descriptionEditor = new Quill('#description-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Handle multiple image preview
        $('#images').on('change', function(e) {
            const files = e.target.files;
            const previewContainer = $('#images-preview');
            
            previewImage(files, previewContainer);
        });
        
        // Handle remove image button click
        $(document).on('click', '.remove-image', function() {
            $(this).parent().parent().remove();
        });

        // Handle form submission - sync Quill content to hidden textarea
        $('form').on('submit', function() {
            $('#description').val(descriptionEditor.root.innerHTML);
        });
    });

    // Sortable functionality
    let listImages = document.getElementById('list-images');
    if (listImages) {
        Sortable.create(listImages, {
            animation: 150,
        });
    }

    let imagesPreview = document.getElementById('images-preview');
    if (imagesPreview) {
        Sortable.create(imagesPreview, {
            animation: 150,
        });
    }
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var route = button.data('route')
        var modal = $(this)
        
        modal.find('form').attr('action', route)
        modal.find('.modal-body').html('Are you sure you want to delete this image?')
      })
    });
</script>
@endpush