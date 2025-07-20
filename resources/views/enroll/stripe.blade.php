@extends('layouts.app')

@section('content')
<style>
    /* Custom styles for the modern payment panel */
    .payment-card {
        max-width: 500px;
        margin: 0 auto;
    }
    .payment-card .card-header {
        background-color: #0d6efd; /* Bootstrap primary */
        color: #fff;
        text-align: center;
    }
    .payment-card .card-header h3 {
        margin-bottom: 0;
        font-weight: 600;
    }
    .payment-card .card-header h4 {
        font-weight: 400;
        margin-top: 10px;
        font-size: 1.2rem;
    }
    .payment-card .card-body {
        padding: 2rem;
    }
    .stripe-button-el {
        width: 100% !important;
    }
</style>

<div class="container my-5">
    <div class="card payment-card shadow-sm">
        <div class="card-header">
            <h3>Payment Details</h3>
            @if($course)
                <h4>You need to pay ${{ number_format($course->price, 2) }}</h4>
            @else
                <h4>Course not found.</h4>
            @endif
        </div>
        <div class="card-body">
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if($course)
            <form action="{{ route('stripe.post', $course->price) }}" method="POST">
                @csrf
                <script
                    src="https://checkout.stripe.com/checkout.js"
                    class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount="{{ $course->price * 100 }}"
                    data-currency="usd"
                    data-name="Learn Pro"
                    data-description="Course Payment"
                    data-image="https://your-site.com/logo.png"
                    data-locale="auto">
                </script>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection