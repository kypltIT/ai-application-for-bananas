{{--
Changes made:
- Enhanced the design of the order success page for a more modern and appealing look
--}}

@extends('layouts.guest.app')
@section('title', 'Order Success')
@section('content')
    <section class="order-success-section pt-60 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-success-content text-center mb-40">
                        <div class="icon mb-30">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        <h3 class="font-weight-bold">Order Success!</h3>
                        <p class="lead">Thank you for your order! Your order has been received and is being processed.</p>
                        <div class="order-number my-4">
                            <span class="label font-weight-bold">Order Number:</span>
                            <span class="value text-primary">#{{ $order->order_code }}</span>
                        </div>
                        <div class="order-date mb-4">
                            <span class="label font-weight-bold">Order Date:</span>
                            <span class="value">{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <div class="order-details-wrapper mb-40">
                        <h4 class="title font-weight-bold">Order Details</h4>
                        <div class="order-table table-responsive mt-30">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <table class="table table-striped">
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
                                                <tr>
                                                    <td>
                                                        <div class="product-item d-flex align-items-center">
                                                            <div class="product-thumbnail">
                                                                @if ($item->productVariantImage)
                                                                    <img src="{{ $item->productVariantImage->image_path }}"
                                                                        alt="product" class="img-fluid" width="60"
                                                                        style="border-radius: 10px;">
                                                                @else
                                                                    <img src="https://images.squarespace-cdn.com/content/v1/53883795e4b016c956b8d243/1671853906962-RV08WWNIS1LTNE453MOX/Artboard%2B2.jpg"
                                                                        alt="product" class="img-fluid" width="60"
                                                                        style="border-radius: 10px;">
                                                                @endif
                                                            </div>
                                                            <div class="product-name ms-3">
                                                                <h6 class="font-weight-bold">
                                                                    {{ $item->productVariant->product->name }}</h6>
                                                                <p class="text-muted">{{ $item->productVariant->name }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-center">
                                                        {{ number_format($item->productVariant->price, 0) }}VND</td>
                                                    <td class="text-center">
                                                        {{ number_format($item->productVariant->price * $item->quantity, 0) }}VND
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="font-weight-bold">Subtotal</td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($order->total_price, 0) }}VND</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-weight-bold">Shipping Fee</td>
                                                <td class="text-center">{{ number_format($order->shipping_fee) }}VND</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="font-weight-bold"><strong>Total</strong></td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($order->total_price, 0) }}VND</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <h4 class="title font-weight-bold">Customer Information</h4>
                    <div class="card mb-40 mt-30">
                        <div class="card-body">

                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="title font-weight-bold"><strong>Full Name:</strong></span>
                                    <span class="value">{{ $order->customer->name }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="title font-weight-bold"><strong>Email:</strong></span>
                                    <span class="value">{{ $order->customer->email }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="title font-weight-bold"><strong>Phone:</strong></span>
                                    <span class="value">{{ $order->customer->phone }}</span>
                                </li>
                            </ul>
                            <p class="mb-2"><strong>Shipping Address:</strong> {{ $order->address->address }},
                                {{ $order->address->ward_name }},
                                {{ $order->address->district_name }},
                                {{ $order->address->city_name }}</p>
                            <p class="mb-2"><strong>Payment Method:</strong>
                                @if ($order->transaction->payment_method == 'COD')
                                    Cash on Delivery (COD)
                                @elseif($order->transaction->payment_method == 'Bank Transfer')
                                    Bank Transfer
                                @endif
                            </p>
                            <p class="mb-2">
                                <strong>Payment Status:</strong>
                                <span
                                    class="badge {{ $order->transaction->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $order->transaction->status == 'paid' ? 'Paid' : 'Unpaid' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <div class="continue-shopping mt-30">
                        <a href="{{ route('home') }}" class="main-btn btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
