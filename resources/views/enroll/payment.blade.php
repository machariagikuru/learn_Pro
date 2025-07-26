@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Course Details Column -->
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <!-- Course Image -->
                <img src="{{ asset('courses/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 300px; object-fit: cover;">
                
                <div class="card-body">
                    <h1 class="card-title display-5">{{ $course->title }}</h1>
                    <p class="card-text lead">{{ $course->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-success">${{ number_format($course->price, 2) }}</h4>
                        <!-- Changed from a link to a button with an ID -->
                        <button id="pay-now-button" class="btn btn-lg btn-primary">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Column -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">What You'll Get</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Lifetime Access</li>
                        <li class="list-group-item">Certificate of Completion</li>
                        <li class="list-group-item">24/7 Support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form to submit Stripe token -->
<form action="{{ route('stripe.post', $course->id) }}" method="POST" id="payment-form" style="display: none;">

    @csrf
    <input type="hidden" name="stripeToken" id="stripe-token">
</form>

<!-- Include Stripe Checkout script -->
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    // Configure Stripe Checkout
    var handler = StripeCheckout.configure({
        key: '{{ env("STRIPE_KEY") }}', // Your publishable key
        locale: 'auto',
        token: function(token) {
            // Insert the token ID into the hidden form and submit it
            document.getElementById('stripe-token').value = token.id;
            document.getElementById('payment-form').submit();
        }
    });

    // Open the Stripe modal when clicking the button
    document.getElementById('pay-now-button').addEventListener('click', function(e) {
        handler.open({
            name: 'Learn Pro',
            description: 'Course Payment',
            amount: {{ $course->price * 100 }}, // Amount in cents
            currency: 'usd',
            image: '{{ asset("Photos/logomark.svg") }}'

        });
        e.preventDefault();
    });

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function() {
        handler.close();
    });
</script>
@endsection
