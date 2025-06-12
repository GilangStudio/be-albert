@extends('layouts.main')

@section('title', 'Orders')

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Orders</h4>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-orders">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Order Number</th>
                            <th>Buyer</th>
                            <th>Status</th>
                            <th>Voucher</th>
                            <th width="20%">Products</th>
                            {{-- <th width="15%"></th> --}}
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $order->order_number }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $order->user->name }}</strong>
                                        <small>{{ '+' . $order->user->country_code . ' ' . $order->user->phone_number }}</small>
                                    </div>
                                </td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    @if (!is_null($order->voucher))
                                    <div class="d-flex flex-column">
                                        <strong>{{ $order->voucher->code . ' - ' . $order->voucher->discount_percentage . '%' }}</strong>
                                        <small>Rp. {{ number_format($order->discount, 0, ',', '.') }}</small>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0 cursor-pointer text-primary" data-bs-toggle="collapse" data-bs-target="#collapse-product-{{ $order->id }}">{{ $order->order_products->count() }} Product(s)</p>
                                    <div id="collapse-product-{{ $order->id }}" class="collapse">
                                       
                                        <div class="list-group">
                                        @foreach ($order->order_products as $order_product)
                                        <li class="list-group-item p-2">
                                            <div class="row g-1">
                                                <div class="col-md-4">
                                                    <img src="{{ asset('storage/shop/products/' . $order_product->product->first_image->image) }}" alt="" class="img-fluid">
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="mb-0">{{ $order_product->product->name }}</p>
                                                    <small class="text-muted">{{ $order_product->quantity }} ({{ $order_product->size->size }}) x Rp. {{ number_format($order_product->product->price, 0, ',', '.') }}</small>
                                                    <small class="text-muted"></small>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        <li class="list-group-item p-2">
                                            <div class="row g-1 align-items-center">
                                                <div class="col-md-4">
                                                    <strong>Subtotal</strong>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="mb-0">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="row g-1 align-items-center">
                                                <div class="col-md-4">
                                                    <strong>Total</strong>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="mb-0">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        </div>
                                       
                                    </div>
                                </td>
                                {{-- <td>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-light btn-icon-circle"><i class="ti ti-pencil font-16"></i></a>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-light btn-icon-circle"><i class="ti ti-eye font-16"></i></a>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                            @endforeach
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
    const dataTable = new simpleDatatables.DataTable("#table-main-category", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [6], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('users.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
    });
</script>
@endpush