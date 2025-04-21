@extends('layouts.admin.app')
@section('title', 'Order List')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Order Management </a>
                            </li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Order List</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Filter Form -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search by order code, customer name, or note"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>
                                            Ordered</option>
                                        <option value="processing"
                                            {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>Order List</h3>
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
                                                <div class="add-group">
                                                    <button class="btn btn-primary add-pluss ms-2" data-bs-toggle="modal"
                                                        data-bs-target="#add_product"><img
                                                            src="{{ asset('assets/admin/img/icons/plus.svg') }}"
                                                            alt=""></button>

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
                                            <th>Order Code</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Order Note</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="profile-image">{{ $order->order_code }}</td>
                                                <td>
                                                    {{ $order->customer->name }}
                                                </td>
                                                <td>
                                                    {{ number_format($order->total_price, 0) }}VND
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge
                                                        @if ($order->status == 'ordered') bg-info
                                                        @elseif($order->status == 'processing') bg-warning
                                                        @elseif($order->status == 'completed') bg-success
                                                        @elseif($order->status == 'cancelled') bg-danger @endif
                                                    ">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    {{ $order->order_note }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="btn btn-warning add-pluss ms-2">
                                                        <i class="fa-solid fa-eye"></i> View
                                                    </a>

                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->


                                            <!-- Delete Modal -->
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            {{-- <ul class="pagination ">
                                {!! $orders->links() !!}
                            </ul> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
