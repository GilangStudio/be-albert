@extends('layouts.main')

@section('title', 'About')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item">About</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">About</h4>
                </div>
                <div>
                    <a href="{{ route('about.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Add About Section</a>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="row">
                    @foreach ($abouts as $about)
                    <div class="col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-6 {{ $about->layout == 0 ? 'order-last' : '' }}">
                                    <img src="{{ asset('storage/website/about/' . $about->image) }}" height="300" class="object-fit-contain w-100 m-3" alt="...">
                                </div>
                                <div class="col-6">
                                    <div class="card-body">
                                        <div>{!! $about->content !!}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('about.edit', $about->id) }}" class="btn btn-primary btn-sm"><i class="ti ti-pencil me-1"></i> Edit</a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $about->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash me-1"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.modal-delete', [
  'route' => '',
  'message' => '',
])
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

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('about.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
    });
</script>
@endpush