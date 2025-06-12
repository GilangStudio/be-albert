<div class="row">
    <div class="col-12">
        <form class="card" action="{{ route('collection.images', $collection->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Image Gallery</h4>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="images" class="form-label">Collection Images <span class="text-danger">*</span></label>
                        <input type="file" id="images" accept="image/png, image/jpeg, image/jpg" hidden multiple>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="triggerFile('images')"><i class="ti ti-upload me-1"></i> Upload Image</button>
                        </div>
                    </div>
                </div>
                <div class="row gy-3" id="images-preview">
                    @foreach ($collection->collection_genders as $collection_gender)
                        @if ($collection_gender->gender == '')
                        <div class="col-6 col-md-4 col-xxl-3 position-relative" data-id="{{ $collection_gender->id }}">
                            <input type="hidden" name="ids[]" value="{{ $collection_gender->id }}">
                            <img src="{{ asset('storage/website/collections/bridal/' . $collection_gender->image) }}" height="150" class="w-100 object-fit-contain img-border" alt="Preview Image">
                            <button type="button" class="btn btn-danger btn-icon-circle btn-icon-circle-sm position-absolute top-0 end-0 me-2 delete-image" data-bs-toggle="modal" data-bs-target="#modal-delete" data-route="{{ route('collection.image.delete', [$collection->id, $collection_gender->id]) }}">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
                    <div class="col-6 col-md-4 col-xxl-3 position-relative">
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
</script>

<script>
    let listImages = document.getElementById('images-preview');
    Sortable.create(listImages, {
        animation: 150,
    });
</script>
@endpush