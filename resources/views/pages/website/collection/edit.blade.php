@extends('layouts.main')

@section('title', 'Edit Collection')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item">Collection</li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('collection.update', $collection->id) }}" method="POST" class="card" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Collection {{ $collection->name }}</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-icon-square-sm" data-bs-toggle="modal" data-bs-target="#modal-delete" data-route="{{ route('collection.destroy', $collection->id) }}"><i class="ti ti-trash"></i></button>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Collection Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $collection->name }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="type" class="form-label">Collection Type <span class="text-danger">*</span></label>
                                <select type="text" class="form-select" id="type" name="type" disabled>
                                    <option value="" selected disabled>Select Collection Type</option>
                                    <option value="regular" {{ $collection->type == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="bridal" {{ $collection->type == 'bridal' ? 'selected' : '' }}>Bridal</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="year" class="form-label">Collection Year <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="year" name="year" value="{{ $collection->collection_year }}" required>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Collection Description</label>
                                <textarea name="description" id="description" rows="8" class="form-control">{{ $collection->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 image-preview d-flex flex-column">
                                <label for="image" class="form-label">Main Image <span class="text-danger">*</span></label>
                                <img src="{{ asset('storage/website/collections/' . $collection->main_image) }}" alt="" height="300" onclick="triggerFile('image')" class="object-fit-contain img-border mb-2">
                                <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" hidden>
                                <button type="button" class="btn btn-primary w-100" onclick="triggerFile('image')"><i class="ti ti-upload me-1"></i> Upload Image</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('collection.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@if ($collection->type == 'regular')
    @include('pages.website.collection.type.regular')
@elseif ($collection->type == 'bridal')
    @include('pages.website.collection.type.bridal')
@endif

@include('components.modal-delete', [
  'route' => '',
  'message' => 'Are you sure you want to delete?',
])
@endsection

@push('scripts')
<script>
    // Function to trigger file input
    function triggerFile(id) {
        $(`#${id}`).trigger('click');
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
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var route = button.data('route')
        var modal = $(this)
        
        modal.find('form').attr('action', route)
      })
    });
</script>
@endpush