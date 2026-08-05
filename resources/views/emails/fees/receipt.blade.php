@extends('emails.layout')
@section('subject', 'Payment Receipt')

@section('content')
<h2 style="margin:0 0 16px; font-size:20px; color:#1c1917;">Payment Received</h2>

<p style="margin:0 0 16px;">Dear {{ $payment->student->name }},</p>
<p style="margin:0 0 20px;">Thank you — your payment has been confirmed. The receipt is attached to this email as a PDF.</p>

<div style="background:#faf9f6; border:1px solid #eee; border-radius:10px; padding:18px 20px; margin-bottom:0;">
    <p class="field-label">Challan Number</p>
    <p class="field-value">{{ $payment->challan_number }}</p>
    <p class="field-label">Amount Paid</p>
    <p class="field-value">Rs. {{ number_format((float) $payment->amount_paid) }}</p>
    <p class="field-label" style="margin-bottom:2px;">Payment Date</p>
    <p class="field-value" style="margin-bottom:0;">{{ optional($payment->payment_date)->format('d M Y') }}</p>
</div>
@endsection
