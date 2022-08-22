<h4>{!! __('messages.Paypal and Stripe') !!}</h4>
<div class="form-group">
    <label for="paypal_client_iden">{!! __('messages.PayPal client ID') !!}</label>
    <input type="password" name="paypal_client_iden" value="{{ old('paypal_client_iden') ? old('paypal_client_iden') : $user->paypal_client_iden }}" class="form-control" id="paypal_client_iden">
    @if ($user->paypal_client_iden)
        <span class="account-show-key text-primary">{!! __('messages.Show PayPal client ID') !!}</span>
    @endif
</div>
<div class="form-group">
    <label for="stripe_secret_key">{!! __('messages.Stripe API secret key') !!}</label>
    <input type="password" name="stripe_secret_key" value="{{ old('stripe_secret_key') ? old('stripe_secret_key') : $user->stripe_secret_key }}" class="form-control" id="stripe_secret_key">
    @if ($user->stripe_secret_key)
        <span class="account-show-key text-primary">{!! __('messages.Show Stripe API secret key') !!}</span>
    @endif
</div>
<div class="form-group">
    <label for="stripe_public_key">{!! __('messages.Stripe API public key') !!}</label>
    <input type="password" name="stripe_public_key" value="{{ old('stripe_public_key') ? old('stripe_public_key') : $user->stripe_public_key }}" class="form-control" id="stripe_public_key">
    @if ($user->stripe_public_key)
        <span class="account-show-key text-primary">{!! __('messages.Stripe API public key') !!}</span>
    @endif
</div>
