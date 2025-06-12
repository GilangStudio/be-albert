@extends('layouts.main')

@section('title', 'Product')

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item active">Product</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Product</h4>
                </div>
                <div>
                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> New Product</a>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-product">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Color</th>
                            <th>Price</th>
                            <th>Preorder</th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/shop/products/' . $product->first_image->image) }}" alt="" height="100" width="100" class="object-fit-contain img-border">
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->product_code }}</small>
                                </td>
                                <td>{{ $product->total_stock }}</td>
                                <td>{{ $product->product_category->main_category->name }} <i class="mdi mdi-chevron-right"></i> {{ $product->product_category->name }}</td>
                                <td>{{ $product->color }}</td>
                                <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($product->is_preorder == 1)
                                        <span class="badge rounded-pill bg-success">Yes</span>
                                        <small class="d-block mt-1">{{ $product->preorder_days }} Days</small>
                                    @else
                                        <span class="badge rounded-pill bg-danger">No</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-light btn-icon-circle"><i class="ti ti-pencil font-16"></i></a>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>
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

@include('components.modal-delete', [
  'route' => '',
  'message' => '',
])
@endsection


@push('scripts')

<script>
    const dataTable = new simpleDatatables.DataTable("#table-product", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [1,8], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('product.destroy', '') }}/` + id)
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