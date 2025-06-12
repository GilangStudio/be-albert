@extends('layouts.main')

@section('title', 'Press')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Press</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Press</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-press-create"><i class="fas fa-plus me-1"></i> New Press</button>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-press">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Link</th>
                            <th>Publish Date</th>
                            {{-- <th width="15%">Display Order</th> --}}
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($presses as $press)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/website/presses/' . $press->image) }}" alt="" height="100" width="100" class="object-fit-contain img-border">
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $press->title }}</h6>
                                </td>
                                <td>
                                    <a href="{{ $press->link }}" class="text-primary" target="_blank"><i class="ti ti-external-link me-1"></i>{{ $press->published_on }}</a>
                                </td>
                                <td>{{ date('d F Y', strtotime($press->published_date)) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <button type="button" class="btn btn-light btn-icon-circle" data-bs-toggle="modal" data-bs-target="#modal-press-edit-{{ $press->id }}"><i class="ti ti-pencil font-16"></i></button>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $press->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>

                                            <div class="modal fade" id="modal-press-edit-{{ $press->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="{{ route('press.update', $press->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $press->id }}">
                                                        <div class="modal-header bg-primary">
                                                            <h6 class="modal-title m-0 text-white">Edit Press</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div><!--end modal-header-->
                                                        <div class="modal-body">
                                                            <div class="row gy-3">
                                                                <div class="col-12">
                                                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{ old('title') ?? $press->title }}" required autofocus>
                                                                </div>
                                                                <div class="col-12 col-md-6">
                                                                    <label for="published-on" class="form-label">Published On <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="published-on" name="published_on" value="{{ old('published_on') ?? $press->published_on }}" required>
                                                                </div>
                                                                <div class="col-12 col-md-6">
                                                                    <label for="published-date" class="form-label">Published Date <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control published-date" id="published-date" name="published_date" value="{{ old('published_date') ?? date('d/m/Y', strtotime($press->published_date)) }}" required>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                                                                    <input type="url" class="form-control" id="link" name="link" value="{{ old('link') ?? $press->link }}" autocomplete="off" required>
                                                                </div>
                                                                <div class="col-12 image-preview d-flex flex-column">
                                                                    <label for="image-edit-{{ $press->id }}" class="form-label">Image <span class="text-danger">*</span></label>
                                                                    <img src="{{ asset('storage/website/presses/' . $press->image) }}" alt="" height="300" onclick="triggerFile({{ $press->id }})" class="object-fit-contain img-border mb-2">
                                                                    <input type="file" name="image" id="image-edit-{{ $press->id }}" accept="image/png, image/jpeg, image/jpg" hidden>
                                                                    <button type="button" class="btn btn-primary w-100" onclick="triggerFile({{ $press->id }})"><i class="ti ti-upload me-1"></i> Upload Image</button>
                                                                </div>
                                                            </div><!--end row-->
                                                        </div><!--end modal-body-->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div><!--end modal-footer-->
                                                    </form><!--end modal-content-->
                                                </div><!--end modal-dialog-->
                                            </div>
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

<div class="modal fade" id="modal-press-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('press.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white">Create New Press</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row gy-3">
                    <div class="col-12">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{ old('title') }}" required autofocus>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="published-on" class="form-label">Published On <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="published-on" name="published_on" value="{{ old('published_on') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="published-date" class="form-label">Published Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control published-date" id="published-date" name="published_date" value="{{ old('published_date') }}" required>
                    </div>
                    <div class="col-12">
                        <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="link" name="link" value="{{ old('link') }}" autocomplete="off" required>
                    </div>
                    <div class="col-12 image-preview d-flex flex-column">
                        <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                        <img src="" alt="" height="300" onclick="triggerFile()" class="object-fit-contain img-border mb-2 d-none">
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" hidden required>
                        <button type="button" class="btn btn-primary w-100" onclick="triggerFile()"><i class="ti ti-upload me-1"></i> Upload Image</button>
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
    const dataTable = new simpleDatatables.DataTable("#table-press", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [1,3,5], sortable: false },
        ]
    });
</script>

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
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('press.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
    });
</script>

<script>
    $('.published-date').datetimepicker({
        format: 'DD/MM/YYYY',
        viewMode: 'days',
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