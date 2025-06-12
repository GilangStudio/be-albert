@extends('layouts.main')

@section('title', 'Create Product')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
@endpush

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item">Product</li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('product.store') }}" method="POST" class="card" enctype="multipart/form-data">
            @csrf
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Create Product</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <h5 class="text-muted">Product Information</h5>
                            </div>
                            <div class="col-12">
                                <label for="images" class="form-label">Product Images <span class="text-danger">*</span></label>
                                <input type="file" id="images" accept="image/png, image/jpeg, image/jpg" hidden multiple>

                                <div class="row" id="images-preview">
                                    
                                </div>
                                
                                <div>
                                    <button type="button" class="btn btn-primary w-100" onclick="triggerFile('images')"><i class="ti ti-upload me-1"></i> Upload Image</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="product-name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="product-name" name="product_name" value="{{ @old('product_name') }}" required>
                            </div>
                            <div class="col-md-12">
                                <label for="product-code" class="form-label">Product Code</label>
                                <input type="text" class="form-control" id="product-code" name="product_code" value="{{ @old('product_code') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="color" name="color" value="{{ @old('color') }}" autocomplete="off" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="price" name="price" value="{{ @old('price') }}" autocomplete="off" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="main-category" class="form-label">Main Category <span class="text-danger">*</span></label>
                                <select type="text" class="form-select" id="main-category" name="main_category" onchange="getProductCategory(this.value)" required>
                                    <option value="" selected disabled>Select Main Category</option>
                                    @foreach ($main_categories as $main_category)
                                        <option value="{{ $main_category->id }}">{{ $main_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="product-category" class="form-label">Product Category <span class="text-danger">*</span></label>
                                <select type="text" class="form-select" id="product-category" name="product_category" required>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Product Description</label>
                                <input type="hidden" name="description" id="description">
                                <div id="quill-description" style="height: auto">{!! old('description') !!}</div>
                            </div>
                            <div class="col-12 image-preview d-flex flex-column">
                                <label for="size-guide" class="form-label">Size Guide <span class="text-danger">*</span></label>
                                <img src="" alt="" height="300" onclick="triggerFile('size-guide')" class="object-fit-contain img-border mb-2 d-none">
                                <input type="file" name="size_guide" id="size-guide" accept="image/png, image/jpeg, image/jpg" hidden>
                                <button type="button" class="btn btn-primary w-100" onclick="triggerFile('size-guide')"><i class="ti ti-upload me-1"></i> Upload Image</button>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="preorder" name="preorder" onchange="let pd = this.checked ? document.getElementById('preorder-days').value : ''; document.getElementById('preorder-days').value = pd;" data-bs-toggle="collapse" data-bs-target="#preorder-collapse">
                                    <label for="preorder" class="form-label">Preorder</label>
                                </div>
                                <div id="preorder-collapse" class="collapse input-group">
                                    <input type="text" class="form-control" id="preorder-days" name="preorder_days" value="{{ @old('preorder_days') }}" placeholder="Preorder Days" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" autocomplete="off">
                                    <span class="input-group-text" id="preorder-days">Days</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row gy-3">
                            <div class="col-12">
                                <h5 class="text-muted">Product Stock By Size</h5>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-primary mb-3" id="add-size" onclick="addSize()"><i class="ti ti-plus"></i> Add Size</button>
                                <div id="size-container">
                                    @if (@old('size'))
                                        @for ($i = 1; $i < count(@old('size')); $i++)
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Size</span>
                                            <input type="text" class="form-control" id="size-{{ $i }}" name="size[]" value="{{ @old('size.' . $i) }}" required autocomplete="off">
                                            <span class="input-group-text">Stock</span>
                                            <input type="text" class="form-control" id="stock-{{ $i }}" name="stock[]" value="{{ @old('stock.' . $i) }}" required autocomplete="off">
                                            <button class="btn btn-outline-danger" type="button" onclick="removeSize({{ $i }})"><i class="ti ti-trash"></i></button>
                                        </div>
                                        @endfor
                                    @else
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Size</span>
                                        <input type="text" class="form-control" id="size-1" name="size[]" required autocomplete="off">
                                        <span class="input-group-text">Stock</span>
                                        <input type="text" class="form-control" id="stock-1" name="stock[]" required autocomplete="off">
                                        <button class="btn btn-outline-danger" type="button" onclick="removeSize(1)"><i class="ti ti-trash"></i></button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('product.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Create</button>
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
        
        $('form').submit(function() {
            if (document.getElementById('images').files.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Image',
                    text: 'Please upload at least one image for the product.',
                    confirmButtonText: 'Close'
                });

                return false;
            }

            if (document.getElementById('size-guide').files.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Size Guide',
                    text: 'Please upload a size guide for the product.',
                    confirmButtonText: 'Close'
                });

                return false;
            }

            var quill_description_html = quill_description.root.innerHTML;
            $('#description').val(quill_description_html);

            $('#price').val($('#price').val().replace(/[^0-9]/g, ''));
        });
    });
</script>

<script>
    $('#year').datetimepicker({
        format: 'YYYY',
        viewMode: 'years',
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

<script>
    // Function to trigger file input for images
    function triggerFile(id) {
        $(`#${id}`).trigger('click');
    }

    // Function to preview multiple images
    function previewImage(files, previewContainer) {
        // Create FormData object
        const formData = new FormData();
        
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
                    <div class="col-6 col-md-4 col-xxl-3 mb-3 position-relative">
                        <div class="image-container">
                            <img src="${imageUrl}" height="150" class="w-100 object-fit-contain img-border" alt="Preview Image">
                            <button type="button" class="btn btn-danger btn-icon-circle btn-icon-circle-sm position-absolute top-0 end-0 me-2 remove-image" data-index="${index}">
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

    // Handle multiple image preview
    $(document).ready(function() {
        $('#images').on('change', function(e) {
            const files = e.target.files;
            const previewContainer = $('#images-preview');
            
            // Clear previous previews and hidden inputs
            // previewContainer.empty();
            
            previewImage(files, previewContainer);
        });
        
        // Handle remove image button click
        $(document).on('click', '.remove-image', function() {
            $(this).parent().parent().remove();
        });
    });

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

<script>
    function getProductCategory(main_category_id) {
        $('#product-category').empty();
        $.ajax({
            url: "{{ route('product-category.get') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: main_category_id
            },
            success: function (response) {
                if (response.success && response.data) {
                    $('#product-category').append('<option value="" selected disabled>Select Product Category</option>');
                    $.each(response.data, function (key, value) {
                        $('#product-category').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('input[name="stock[]"]').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    function addSize() {
    const newIndex = $('#size-container div').length + 1;
    const newGroup = $(`
        <div class="input-group mb-3">
        <span class="input-group-text">Size</span>
        <input type="text" class="form-control" id="size-${newIndex}" name="size[]" required autocomplete="off">
        <span class="input-group-text">Stock</span>
        <input type="text" class="form-control" id="stock-${newIndex}" name="stock[]" required autocomplete="off">
        <button class="btn btn-outline-danger" type="button" onclick="removeSize(${newIndex})">
            <i class="ti ti-trash"></i>
        </button>
        </div>
    `);
    
    // Tambahkan event listener langsung ke elemen baru
    newGroup.find('input[name="stock[]"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    $('#size-container').append(newGroup);
    }

    function removeSize(index) {
        console.log(index);
        if ($('#size-container div').length > 1) {
            // $('#size-container div').eq(index).remove();
            $(`#size-${index}`).closest('.input-group').remove();
        }
        else {
            Swal.fire({
                icon: 'error',
                title: 'Size Required',
                text: 'Please add at least one size for the product.',
                confirmButtonText: 'Close'
            });
        }
    }
</script>
@endpush