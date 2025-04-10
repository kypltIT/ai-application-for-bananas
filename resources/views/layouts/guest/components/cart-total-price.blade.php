@if (getCartItems()->count() > 0)
    <div class="cart-mini-total">
        <div class="cart-total">
            <span><strong>Subtotal:</strong></span> <span class="amount"> <span><span
                        class="currency">$</span>{{ number_format(getCartTotal(), 0) }}</span></span>
        </div>
    </div>

    <div class="cart-button-box">
        <a href="{{ route('orders.index') }}" class="theme-btn style-one">Proceed to
            checkout</a>
    </div>
@else
    <div class="cart-mini-total">
        <div class="cart-total">
            <div class="text-center">
                <p>Your cart is empty</p>
            </div>
        </div>
    </div>
@endif
