@extends('layouts.admin.app')
@section('title', 'Order Details')
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
                            <li class="breadcrumb-item active">Order Details</li>
                        </ul>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary mb-3 btn-sm"><i
                    class="fa-solid fa-arrow-left"></i> Back to Order List</a>


            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order Details - <span
                                    class="text-primary">{{ $order->order_code }}</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6" style="border-right: 1px solid #000">
                                    <h4>Customer Details</h4>
                                    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                                    <p><strong>Customer Email:</strong> {{ $order->customer->email }}</p>
                                    <p><strong>Total:</strong> ${{ number_format($order->total_price, 0) }}</p>
                                    <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><strong>Order Status:</strong>
                                        <span
                                            class="badge
                                            @if ($order->status == 'ordered') bg-info
                                            @elseif($order->status == 'processing') bg-warning
                                            @elseif($order->status == 'completed') bg-success
                                            @elseif($order->status == 'cancelled') bg-danger @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Order Note:</strong> {{ $order->order_note }}</p>

                                    <!-- Order Status Update Form -->
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST"
                                        class="mt-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="status" class="form-label"><strong>Update Order
                                                    Status:</strong></label>
                                            <select name="status" id="status" class="form-select">
                                                <option value="ordered"
                                                    {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                                <option value="processing"
                                                    {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                                </option>
                                                <option value="completed"
                                                    {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <h4>Shipping Address</h4>
                                    <p><strong>Name Receiver:</strong> {{ $order->address->name }}</p>
                                    <p><strong>Phone Receiver:</strong> {{ $order->address->phone }}</p>
                                    <p><strong>Address Receiver:</strong> {{ $order->address->address }},
                                        {{ $order->address->ward_name }}, {{ $order->address->district_name }},
                                        {{ $order->address->city_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Items</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover rounded">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderItems as $item)
                                            <tr class="">
                                                <td>{{ $item->product->name }}
                                                    <br>
                                                    <small>{{ $item->productVariant->name }}</small>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-center">${{ number_format($item->price, 0) }}</td>
                                                <td class="text-center">
                                                    ${{ number_format($item->price * $item->quantity, 0) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order Transactions </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover rounded">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Payment Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->transaction_code }}</td>
                                                <td>${{ number_format($transaction->total_price, 0) }}</td>
                                                <td>{{ ucfirst($transaction->status) }}</td>
                                                <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $transaction->payment_method }}</td>
                                            </tr>
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
