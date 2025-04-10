@foreach (getCartItems() as $cartItem)
    <li class="sidebar-cart-item">
        <a style="cursor: pointer;" id="remove-cart" data-cart-id="{{ $cartItem->id }}" class="remove-cart"><i
                class="far fa-trash-alt"></i></a>
        <a href="#">
            <img src="{{ asset('storage/' . $cartItem->product->productVariants()->first()->productVariantImages()->first()->image_path) }}"
                alt="cart image">
            {{ $cartItem->product->name }}
        </a>
        <span>{{ $cartItem->productVariant->name }}</span> <br>
        <span class="quantity">{{ $cartItem->quantity }} × <span><span
                    class="currency">$</span>{{ number_format($cartItem->productVariant->price, 0) }}</span></span>
    </li>
@endforeach

<div class="text-center alert alert-danger" id="empty-cart-message" style="display: none;">
    <p>Your cart is empty.</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var removeCartButtons = document.querySelectorAll('.remove-cart');
        removeCartButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var cartId = this.getAttribute('data-cart-id');
                var listItem = this.closest('.sidebar-cart-item');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('cart.removeFromCart') }}", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                                // Xóa phần tử <li> khỏi DOM
                            listItem.remove();
                            // Kiểm tra nếu giỏ hàng trống
                            if (document.querySelectorAll('.sidebar-cart-item').length ===
                                0) {
                                document.getElementById('empty-cart-message').style
                                    .display = 'block';
                                document.getElementById('cart-total-price').style
                                    .display = 'none';
                            }
                        } else {
                            console.error('Xóa giỏ hàng thất bại');
                        }
                    }
                };
                xhr.send('cart_item_id=' + encodeURIComponent(cartId));
            });
        });
    });
</script>
