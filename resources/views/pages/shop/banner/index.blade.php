@extends('layouts.main')

@section('title', 'Shop Banner')

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item active">Shop Banner</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Shop Banner</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-banner-create"><i class="fas fa-plus me-1"></i> New Banner</button>
                    
                    @if (count($banners) > 1)
                    <button type="button" class="btn btn-primary btn-icon-square-sm" data-bs-toggle="modal" data-bs-target="#modal-sort"><i class="ti ti-sort-ascending-numbers"></i></button>

                    <div class="modal fade" id="modal-sort" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('shop.banner.sort') }}" method="post">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h6 class="modal-title m-0 text-white">Sort Banner</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div><!--end modal-header-->
                                    <div class="modal-body">
                                            <ul id="list-images" class="list-group" style="list-style-type: none">
                                                @foreach ($banners as $item)
                                                    {{-- @if ($item->is_active == 0) --}}
                                                    <li class="list-group-item">
                                                        <div class="d-flex gap-2">
                                                            <div>
                                                                <span class="badge bg-primary badge-pill">{{ $item->display_order }}</span>
                                                            </div>
                                                            <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                            <img src="{{ asset('storage/shop/banners/'.$item->image) }}" height="200" class="object-fit-contain w-100">
                                                        </div>
                                                    </li>
                                                    {{-- @endif --}}
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
                    <table class="table" id="table-banner">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Image</th>
                            {{-- <th width="15%">Display Order</th> --}}
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $banner)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/shop/banners/' . $banner->image) }}" alt="" height="100" width="100" class="object-fit-contain img-border">
                                </td>
                                {{-- <td class="text-center">{{ $banner->display_order }}</td> --}}
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <button type="button" class="btn btn-light btn-icon-circle" data-bs-toggle="modal" data-bs-target="#modal-banner-edit-{{ $banner->id }}"><i class="ti ti-pencil font-16"></i></button>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $banner->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>

                                            <div class="modal fade" id="modal-banner-edit-{{ $banner->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="{{ route('shop.banner.update', $banner->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $banner->id }}">
                                                        <div class="modal-header bg-primary">
                                                            <h6 class="modal-title m-0 text-white">Edit Banner</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div><!--end modal-header-->
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 image-preview d-flex flex-column">
                                                                    <label for="image-edit-{{ $banner->id }}" class="form-label">Image <span class="text-danger">*</span></label>
                                                                    <img src="{{ asset('storage/shop/banners/' . $banner->image) }}" alt="" height="300" onclick="triggerFile({{ $banner->id }})" class="object-fit-contain img-border mb-2">
                                                                    <input type="file" name="image" id="image-edit-{{ $banner->id }}" accept="image/png, image/jpeg, image/jpg" hidden required>
                                                                    <button type="button" class="btn btn-primary w-100" onclick="triggerFile({{ $banner->id }})"><i class="ti ti-upload me-1"></i> Upload Image</button>
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

<div class="modal fade" id="modal-banner-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('shop.banner.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white">Create New Banner</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row">
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
    const dataTable = new simpleDatatables.DataTable("#table-banner", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [1,2], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('shop.banner.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
    });
</script>

<script>
    let listImages = document.getElementById('list-images');
    Sortable.create(listImages, {
        animation: 150,
    });
</script>
@endpush