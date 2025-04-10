{{--
Changes made:
- Improved the design of the checkout page for a more modern and appealing look
- Changed the layout and styling for better user experience
--}}

@extends('layouts.guest.app')
@section('title', 'Checkout for order #' . $order->order_code)
@section('content')
    <section class="checkout-section pt-60 pb-60">
        <div class="container">
            <div class="row ">
                <div class="col-lg-8 mx-auto ">
                    <div class="checkout-confirmation mb-40 p-4 border rounded shadow bg-white">
                        <h3 class="title text-center mb-4">Payment Confirmation</h3>
                        <div class="confirmation-details mb-4">
                            <h4 class="font-weight-bold">Customer Information</h4>
                            <p class="mb-2 mt-20"><strong>Name:</strong> {{ $order->customer->name }}</p>
                            <p class="mb-2"><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                            <p class="mb-2"><strong>Email:</strong> {{ $order->customer->email }}</p>
                            <p class="mb-2"><strong>Address:</strong> {{ $order->address->address }},
                                {{ $order->address->ward_name }}, {{ $order->address->district_name }},
                                {{ $order->address->city_name }}</p>
                        </div>
                        <hr>
                        <div class="order-summary mb-4">
                            <h4 class="font-weight-bold mb-20">Order Information</h4>
                            <ul class="products-items list-group">
                                @foreach ($order->orderItems as $item)
                                    <li
                                        class="product-item list-group-item d-flex justify-content-between align-items-center">
                                        <div class="product-info">

                                            <h6 class="title mt-2">{{ $item->productVariant->product->name }}</h6>
                                            <p class="mb-2"><strong>Variant:</strong> {{ $item->productVariant->name }}
                                            </p>
                                        </div>
                                        <div class="price">x {{ $item->quantity }} =
                                            ${{ number_format($item->productVariant->price * $item->quantity, 0) }}</div>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Subtotal</span>
                                    <span class="price">${{ number_format($order->total_price, 0) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Shipping Fee</span>
                                    <span class="price">${{ number_format($order->shipping_fee, 0) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Total Amount</span>
                                    <span
                                        class="price">${{ number_format($order->total_price + $order->shipping_fee, 0) }}</span>
                                </li>
                            </ul>

                        </div>
                        <div class="qr-code text-center">
                            <h4 class="font-weight-bold">QR Code Information</h4>
                            <p class="mb-20 mt-2">Please scan the QR code below to complete the payment:</p>
                            <img src="{{ $qrCodeUrl }}" alt="QR Code" class="img-fluid mx-auto rounded"
                                style="max-width: 200px;" />
                            <p class="mt-20">After payment, please wait about 30 seconds for the payment status to be
                                updated.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    url: "{{ route('orders.getPaymentStatus', $order->id) }}",
                    type: "GET",
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = response.redirect;
                        }
                    }
                });
            }, 1000);
        });
    </script>
@endsection
