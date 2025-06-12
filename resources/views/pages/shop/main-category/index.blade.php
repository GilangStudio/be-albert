@extends('layouts.main')

@section('title', 'Main Category')

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item active">Main Category</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Main Category</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-main-category-create"><i class="fas fa-plus me-1"></i> New Main Category</button>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-main-category">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Main Category Name</th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($main_categories as $category)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <button type="button" class="btn btn-light btn-icon-circle" data-bs-toggle="modal" data-bs-target="#modal-main-category-edit-{{ $category->id }}"><i class="ti ti-pencil font-16"></i></button>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $category->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>

                                            <div class="modal fade" id="modal-main-category-edit-{{ $category->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <form action="{{ route('main-category.update', $category->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $category->id }}">
                                                        <div class="modal-header bg-primary">
                                                            <h6 class="modal-title m-0 text-white">Edit Main Category</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div><!--end modal-header-->
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label for="category-name-{{ $category->id }}" class="form-label">Main Category Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" id="category-name-{{ $category->id }}" class="form-control" value="{{ $category->name }}" autocomplete="off" required>
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

<div class="modal fade" id="modal-main-category-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('main-category.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white">Create New Main Category</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="category-name" class="form-label">Main Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="category-name" class="form-control" autocomplete="off" required>
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
    const dataTable = new simpleDatatables.DataTable("#table-main-category", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [2], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('main-category.destroy', '') }}/` + id)
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