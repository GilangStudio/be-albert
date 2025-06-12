@extends('layouts.main')

@section('title', 'Vouchers')

@section('breadcrumb')
<li class="breadcrumb-item">Shop</li>
<li class="breadcrumb-item active">Vouchers</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Vouchers</h4>
                </div>
                <div>
                    <a href="{{ route('vouchers.special') }}" class="btn btn-primary btn-sm">Special Voucher</a>
                    <a href="{{ route('vouchers.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> New Voucher</a>
                </div>
            </div><!--end card-header-->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-vouchers">
                        <thead class="thead-light">
                          <tr>
                            <th width="5%">No</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Min. Order</th>
                            <th>Expired</th>
                            {{-- <th>Status</th> --}}
                            <th width="10%"></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <p class="mb-0">
                                        {{ $voucher->name }}
                                        @if ($voucher->is_public == 0)
                                        <i class="fas fa-eye-slash text-danger ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Private"></i>
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-muted">{{ $voucher->description }}</p>
                                </td>
                                <td>
                                    <h6 class="m-0">{{ $voucher->code }}</h6>
                                </td>
                                {{-- <td>{{ $voucher->type }}</td> --}}
                                <td>
                                    @if ($voucher->type == 'GENERAL')
                                        <span class="badge rounded-pill bg-primary">General</span>
                                    @elseif ($voucher->type == 'NEW_USER')
                                        <span class="badge rounded-pill bg-warning">New User</span>
                                    @else
                                    @endif
                                </td>
                                <td>{{ $voucher->discount_percentage }}%</td>
                                <td>Rp. {{ number_format($voucher->minimum_order, 0, ',', '.') }}</td>
                                <td>{{ $voucher->expiry_date != null ? date('d F Y', strtotime($voucher->expiry_date)) : $voucher->duration . ' Days' }}</td>
                                {{-- <td>
                                    @if ($voucher->is_active == 1)
                                        <span class="badge rounded-pill bg-success">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">Inactive</span>
                                    @endif
                                </td> --}}
                                {{-- <td>
                                    @if ($product->is_preorder == 1)
                                        <span class="badge rounded-pill bg-success">Yes</span>
                                        <small class="d-block mt-1">{{ $product->preorder_days }} Days</small>
                                    @else
                                        <span class="badge rounded-pill bg-danger">No</span>
                                    @endif
                                </td> --}}
                                <td>
                                    @if ($voucher->type == 'GENERAL')
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="button-items">
                                            <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-light btn-icon-circle"><i class="ti ti-pencil font-16"></i></a>
                                            <button type="button" class="btn btn-light text-danger btn-icon-circle btn-delete" data-id="{{ $voucher->id }}" data-bs-toggle="modal" data-bs-target="#modal-delete"><i class="ti ti-trash font-16"></i></button>
                                        </div>
                                    </div>
                                    @endif
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

@include('components.modal-delete', [
  'route' => '',
  'message' => '',
])
@endsection


@push('scripts')

<script>
    const dataTable = new simpleDatatables.DataTable("#table-vouchers", {
        searchable: true,
        fixedHeight: false,
        columns: [
            { select: 0, sort: "asc" },
            { select: [8], sortable: false },
        ]
    });
</script>

<script>
    $(function () {
      $('#modal-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var modal = $(this)
        
        modal.find('form').attr('action', `{{ route('vouchers.destroy', '') }}/` + id)
        modal.find('.modal-body').html('Are you sure you want to delete?')
      })
    });
</script>
@endpush