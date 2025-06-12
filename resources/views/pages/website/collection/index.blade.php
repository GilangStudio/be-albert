@extends('layouts.main')

@section('title', 'Collection')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Collection</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Collection</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-collection-create"><i class="fas fa-plus me-1"></i> New Collection</button>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-collection">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th width="15%">Main Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Year</th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($collections as $collection)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/website/collections/' . $collection->main_image) }}" alt="" height="100" width="100" class="object-fit-contain img-border">
                                </td>
                                <td>{{ $collection->name }}</td>
                                <td>
                                    @if ($collection->type == 'regular')
                                        <span class="badge rounded-pill bg-primary">Regular</span>
                                    @elseif ($collection->type == 'bridal')
                                        <span class="badge rounded-pill bg-warning">Bridal</span>
                                    @endif
                                </td>
                                <td>{{ $collection->collection_year }}</td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <a href="{{ route('collection.edit', $collection->id) }}" class="btn btn-light btn-icon-circle"><i class="ti ti-pencil font-16"></i></a>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $collection->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            {{-- <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <img src="https://picsum.photos/700/400" alt="" width="300" class="img-border">
                                </td>
                                <td class="text-center">1</td>
                            </tr>                                                                                       --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-collection-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('collection.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white">Create New Collection</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row gy-3">
                    <div class="col-12">
                        <label for="name" class="form-label">Collection Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="off" required autofocus>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="type" class="form-label">Collection Type <span class="text-danger">*</span></label>
                        <select type="text" class="form-select" id="type" name="type" required>
                            <option value="" selected disabled>Select Collection Type</option>
                            <option value="regular">Regular</option>
                            <option value="bridal">Bridal</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="year" class="form-label">Collection Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="year" name="year" required>
                    </div>
                    <div class="col-12 image-preview d-flex flex-column">
                        <label for="image" class="form-label">Main Image <span class="text-danger">*</span></label>
                        <img src="" alt="" height="300" onclick="triggerFile()" class="object-fit-contain img-border mb-2 d-none">
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" hidden required>
                        <button type="button" class="btn btn-primary w-100" onclick="triggerFile()"><i class="ti ti-upload me-1"></i> Upload Image</button>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Collection Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                    
                </div><!--end row-->                                                    
            </div><!--end modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div><!--end modal-footer-->
        </form><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>

@include('components.modal-delete', [
  'route' => '',
  'message' => '',
])
@endsection


@push('scripts')
<script>
    // Function to trigger file input
    function triggerFile(id = '') {
        const inputId = id ? `#image-edit-${id}` : '#image';
        $(inputId).trigger('click');
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
    const dataTable = new simpleDatatables.DataTable("#table-collection", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [2,5], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('collection.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
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
@endpush