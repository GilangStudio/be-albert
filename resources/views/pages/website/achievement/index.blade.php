@extends('layouts.main')

@section('title', 'Achievement')

@section('breadcrumb')
<li class="breadcrumb-item">Website</li>
<li class="breadcrumb-item active">Achievement</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Achievement</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-achievement-create"><i class="fas fa-plus me-1"></i> New Achievement</button>
                    
                    @if (count($achievements) > 1)
                    <button type="button" class="btn btn-primary btn-icon-square-sm" data-bs-toggle="modal" data-bs-target="#modal-sort"><i class="ti ti-sort-ascending-numbers"></i></button>

                    <div class="modal fade" id="modal-sort" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('achievement.sort') }}" method="post">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h6 class="modal-title m-0 text-white">Sort Achievement</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div><!--end modal-header-->
                                    <div class="modal-body">
                                        <ul id="list-images" class="list-group" style="list-style-type: none">
                                            @foreach ($achievements as $item)
                                            <li class="list-group-item">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div>
                                                        <span class="badge bg-primary badge-pill">{{ $item->display_order }}</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <img src="{{ asset('storage/website/achievements/'.$item->image) }}" height="60" width="60" class="object-fit-contain">
                                                            <span>{{ $item->name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-achievement">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($achievements as $achievement)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $achievement->name }}</td>
                                <td>
                                    <img src="{{ asset('storage/website/achievements/' . $achievement->image) }}" alt="" height="100" width="100" class="object-fit-contain img-border">
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <button type="button" class="btn btn-light btn-icon-circle" data-bs-toggle="modal" data-bs-target="#modal-achievement-edit-{{ $achievement->id }}"><i class="ti ti-pencil font-16"></i></button>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $achievement->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>

                                            <div class="modal fade" id="modal-achievement-edit-{{ $achievement->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="{{ route('achievement.update', $achievement->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $achievement->id }}">
                                                        <div class="modal-header bg-primary">
                                                            <h6 class="modal-title m-0 text-white">Edit Achievement</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div><!--end modal-header-->
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <label for="name-edit-{{ $achievement->id }}" class="form-label">Achievement Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" id="name-edit-{{ $achievement->id }}" class="form-control" value="{{ $achievement->name }}" required>
                                                                </div>
                                                                <div class="col-12 image-preview d-flex flex-column">
                                                                    <label for="image-edit-{{ $achievement->id }}" class="form-label">Image</label>
                                                                    <img src="{{ asset('storage/website/achievements/' . $achievement->image) }}" alt="" height="300" onclick="triggerFile({{ $achievement->id }})" class="object-fit-contain img-border mb-2">
                                                                    <input type="file" name="image" id="image-edit-{{ $achievement->id }}" accept="image/png, image/jpeg, image/jpg" hidden>
                                                                    <button type="button" class="btn btn-primary w-100" onclick="triggerFile({{ $achievement->id }})"><i class="ti ti-upload me-1"></i> Upload Image</button>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-achievement-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('achievement.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white">Create New Achievement</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="name" class="form-label">Achievement Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required>
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
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div><!--end modal-footer-->
        </form><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>

<div class="modal fade" id="modal-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger">
                <h6 class="modal-title m-0 text-white">Delete Achievement</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                Are you sure you want to delete this achievement?
            </div><!--end modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div><!--end modal-footer-->
        </form><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>
@endsection

@push('scripts')
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
    function triggerFile(id) {
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
    const dataTable = new simpleDatatables.DataTable("#table-achievement", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [2,3], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('achievement.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete this achievement?')
      })
    });
</script>

<script>
    let listImages = document.getElementById('list-images');
    if (listImages) {
        Sortable.create(listImages, {
            animation: 150,
        });
    }
</script>
@endpush