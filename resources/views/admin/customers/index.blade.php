@extends('layouts.admin.app')
@section('title', 'Customer List')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customer Management
                                </a>
                            </li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Customer List</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>Customer List</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                            placeholder="Search here">
                                                        <a class="btn"><img
                                                                src="{{ asset('assets/admin/img/icons/search-normal.svg') }}"
                                                                alt=""></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-01.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-02.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-03.svg') }}"
                                                alt=""></a>
                                        <a href="javascript:;"><img
                                                src="{{ asset('assets/admin/img/icons/pdf-icon-04.svg') }}"
                                                alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Customer Name</th>
                                            <th>Customer Email</th>
                                            <th class="text-center">Customer Phone</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $index => $customer)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $customer->name }}
                                                </td>
                                                <td>
                                                    {{ $customer->email }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $customer->phone }}
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#viewCustomerModal{{ $customer->id }}">
                                                        <i class="fa-solid fa-eye"></i> View
                                                    </button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="viewCustomerModal{{ $customer->id }}"
                                                tabindex="-1" aria-labelledby="viewCustomerModalLabel{{ $customer->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="viewCustomerModalLabel{{ $customer->id }}">
                                                                Customer Details
                                                            </h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4 class="mb-3">Customer Details</h4>
                                                                    <p><strong>Customer Name:</strong>
                                                                        {{ $customer->name }}
                                                                    </p>
                                                                    <p><strong>Customer Email:</strong>
                                                                        {{ $customer->email }}</p>
                                                                    <p><strong>Customer Phone:</strong>
                                                                        {{ $customer->phone }}</p>
                                                                </div>
                                                                <hr>
                                                                <div class="col-md-12">
                                                                    <h4 class="mb-3">Customer Address</h4>
                                                                    @foreach ($customer->address as $index => $address)
                                                                        <p><strong>Address {{ $index + 1 }}:</strong>
                                                                            {{ $address->name }} - {{ $address->phone }}
                                                                            <br>
                                                                            {{ $address->address }},
                                                                            {{ $address->ward_name }},
                                                                            {{ $address->district_name }},
                                                                            {{ $address->city_name }}.
                                                                        </p>
                                                                    @endforeach
                                                                </div>
                                                                <hr>
                                                                <div class="col-md-12">
                                                                    <h4 class="mb-3">Customer Orders</h4>
                                                                    <ul>
                                                                        @foreach ($customer->orders as $order)
                                                                            <li>
                                                                                <strong>Order
                                                                                    {{ $order->order_code }}:</strong>
                                                                                {{ $order->created_at->format('d-m-Y') }} -
                                                                                <strong>Order Status:</strong>
                                                                                {{ ucfirst($order->status) }} -
                                                                                <strong>Order Total:</strong>
                                                                                ${{ $order->total_price }}
                                                                                <hr>
                                                                                @foreach ($order->orderItems as $order_item)
                                                                                    <p><strong>Product
                                                                                            Name:</strong>
                                                                                        {{ $order_item->product->name }}
                                                                                        (${{ number_format($order_item->productVariant->price, 0, ',', '.') }})
                                                                                        /
                                                                                        {{ $order_item->productVariant->name }}
                                                                                        x {{ $order_item->quantity }} =
                                                                                        {{ number_format($order_item->productVariant->price * $order_item->quantity, 0, ',', '.') }}

                                                                                    </p>
                                                                                @endforeach
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
