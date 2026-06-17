<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
@page { header:html_header; footer:html_footer; margin-top:32mm; margin-bottom:22mm; margin-left:15mm; margin-right:15mm; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:dejavusans,sans-serif; font-size:10pt; color:#000; }

.ph-table { width:100%; border-collapse:collapse; border-bottom:2pt solid #000; padding-bottom:6pt; }
.ph-left  { width:85%; vertical-align:middle; }
.ph-right { width:15%; text-align:right; vertical-align:top; font-size:8pt; color:#444; }
.ph-name  { font-size:15pt; font-weight:bold; }
.ph-sub   { font-size:8.5pt; color:#333; margin-top:1pt; }
.pf-table { width:100%; border-collapse:collapse; border-top:1pt solid #aaa; padding-top:4pt; font-size:8pt; color:#555; }

.title-box  { border-top:2.5pt solid #000; border-bottom:2.5pt solid #000; text-align:center; padding:7pt 0; margin-bottom:12pt; }
.title-main { font-size:14pt; font-weight:bold; letter-spacing:2pt; text-transform:uppercase; }
.title-sub  { font-size:8.5pt; color:#444; margin-top:2pt; }

.sec { font-size:9.5pt; font-weight:bold; text-transform:uppercase; letter-spacing:0.5pt; border-bottom:1.5pt solid #000; padding-bottom:2pt; margin:10pt 0 5pt; }

.igrid { width:100%; border-collapse:collapse; margin-bottom:10pt; }
.igrid td { padding:5pt 8pt; border:1pt solid #bbb; vertical-align:top; width:25%; }
.lbl { font-size:7.5pt; font-weight:bold; color:#555; text-transform:uppercase; letter-spacing:0.3pt; display:block; margin-bottom:2pt; }
.val { font-size:10.5pt; }

.ftable { width:100%; border-collapse:collapse; font-size:9.5pt; margin-bottom:8pt; }
.ftable th { background:#1a1a1a; color:#fff; padding:6pt 8pt; text-align:left; border:0.5pt solid #000; font-size:9pt; }
.ftable td { padding:6pt 8pt; border:0.5pt solid #ccc; vertical-align:middle; }
.ftable tr.even td { background:#f5f5f5; }
.ftable .r { text-align:right; }
.ftable .total td { font-weight:bold; background:#efefef !important; border-top:1.5pt solid #000; font-size:10pt; }

.status-box { border:2pt solid #000; padding:6pt 12pt; margin:8pt 0; display:inline-block; }
.status-label { font-size:9pt; font-weight:bold; text-transform:uppercase; letter-spacing:1pt; }

.notebox { border-left:3pt solid #000; border:1pt solid #aaa; padding:6pt 10pt; margin:8pt 0; font-size:8.5pt; color:#333; }

.sigtable { width:100%; border-collapse:collapse; margin-top:24pt; }
.sigtable td { width:33%; text-align:center; padding:0 8pt; }
.sigline  { border-top:1pt solid #000; margin-top:22pt; padding-top:3pt; font-size:9pt; font-weight:bold; }
.sigtitle { font-size:8pt; color:#555; }

.wm { position:fixed; top:35%; left:5%; font-size:80pt; color:rgba(0,0,0,0.04); font-weight:bold; transform:rotate(-30deg); z-index:-1; }
</style>
</head>
<body>

<htmlpageheader name="header">
<table class="ph-table"><tr>
  <td class="ph-left">
    <div class="ph-name">{{ $college['name'] }}</div>
    <div class="ph-sub">{{ $college['address'] }}, {{ $college['city'] }}</div>
    <div class="ph-sub">Tel: {{ $college['phone'] }} &nbsp;|&nbsp; {{ $college['email'] }}</div>
  </td>
  <td class="ph-right">
    Challan: {{ $payment->challan_number }}<br>
    Date: {{ now()->format('d M Y') }}
  </td>
</tr></table>
</htmlpageheader>

<htmlpagefooter name="footer">
<table class="pf-table"><tr>
  <td>{{ $college['name'] }} — Accounts Office</td>
  <td style="text-align:right">Generated: {{ now()->format('d M Y, h:i A') }}</td>
</tr></table>
</htmlpagefooter>

@php
  $statusStr = $payment->payment_status instanceof \BackedEnum ? $payment->payment_status->value : $payment->payment_status;
@endphp
<div class="wm">{{ strtoupper($statusStr) }}</div>

<div class="title-box">
  <div class="title-main">Fee Payment Challan</div>
  <div class="title-sub">{{ $college['name'] }} — Accounts Department</div>
</div>

<div class="sec">Student &amp; Challan Information</div>
<table class="igrid">
  <tr>
    <td><span class="lbl">Student Name</span><span class="val">{{ $payment->student?->name ?? '—' }}</span></td>
    <td><span class="lbl">Roll Number</span><span class="val">{{ $payment->student?->roll_number ?? '—' }}</span></td>
    <td><span class="lbl">Challan Number</span><span class="val">{{ $payment->challan_number }}</span></td>
    <td><span class="lbl">Due Date</span><span class="val">{{ $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->format('d M Y') : '—' }}</span></td>
  </tr>
  <tr>
    <td><span class="lbl">Father's Name</span><span class="val">{{ $payment->student?->father_name ?? '—' }}</span></td>
    <td><span class="lbl">Program</span><span class="val">{{ $payment->student?->academicProgram?->name ?? '—' }}</span></td>
    <td><span class="lbl">Academic Year</span><span class="val">{{ $payment->academicYear?->name ?? '—' }}</span></td>
    <td><span class="lbl">Semester</span><span class="val">Semester {{ $payment->semester_number ?? '—' }}</span></td>
  </tr>
</table>

<div class="sec">Fee Breakdown</div>
<table class="ftable">
  <thead>
    <tr>
      <th style="width:30pt">#</th>
      <th>Description</th>
      <th class="r" style="width:90pt">Amount (PKR)</th>
    </tr>
  </thead>
  <tbody>
    @php $row = 1; @endphp
    <tr>
      <td class="c">{{ $row++ }}</td>
      <td>{{ $payment->feeStructure?->title ?? ucwords(str_replace('_',' ',($payment->fee_type instanceof \BackedEnum ? $payment->fee_type->value : ($payment->fee_type ?? 'Fee')))) }}</td>
      <td class="r">{{ number_format($payment->amount_due, 2) }}</td>
    </tr>
    @if(($payment->fine_amount ?? 0) > 0)
    <tr class="even">
      <td class="c">{{ $row++ }}</td>
      <td>Late Payment Fine</td>
      <td class="r">{{ number_format($payment->fine_amount, 2) }}</td>
    </tr>
    @endif
    @if(($payment->discount_amount ?? 0) > 0)
    <tr>
      <td class="c">{{ $row++ }}</td>
      <td>Discount / Concession</td>
      <td class="r">- {{ number_format($payment->discount_amount, 2) }}</td>
    </tr>
    @endif
    <tr class="total">
      <td colspan="2" class="r">NET PAYABLE AMOUNT</td>
      <td class="r">PKR {{ number_format(($payment->amount_due ?? 0) + ($payment->fine_amount ?? 0) - ($payment->discount_amount ?? 0), 2) }}</td>
    </tr>
    @if(($payment->amount_paid ?? 0) > 0)
    <tr>
      <td colspan="2" class="r">Amount Paid</td>
      <td class="r">PKR {{ number_format($payment->amount_paid, 2) }}</td>
    </tr>
    @endif
  </tbody>
</table>

<p style="margin-bottom:8pt">
  <strong>Payment Status:</strong> &nbsp;
  <span class="status-box"><span class="status-label">{{ strtoupper($statusStr) }}</span></span>
  @if($payment->payment_date)
    &nbsp;&nbsp; <strong>Paid On:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
  @endif
  @if($payment->transaction_id)
    &nbsp;&nbsp; <strong>TXN ID:</strong> {{ $payment->transaction_id }}
  @endif
</p>

<div class="notebox">
  <strong>Bank Details:</strong>
  {{ \App\Models\CollegeSetting::get('fee_bank_name','HBL') }} &nbsp;|&nbsp;
  A/C: {{ \App\Models\CollegeSetting::get('fee_bank_account','—') }} &nbsp;|&nbsp;
  Branch: {{ \App\Models\CollegeSetting::get('fee_bank_branch','—') }}<br>
  <strong>Note:</strong> Late fine applies after the due date. Keep this challan as proof of payment.
</div>

<table class="sigtable">
  <tr>
    <td><div class="sigline">Student Signature</div><div class="sigtitle">{{ $payment->student?->name }}</div></td>
    <td><div class="sigline">Accounts Office</div><div class="sigtitle">Signature &amp; Stamp</div></td>
    <td><div class="sigline">Bank Stamp</div><div class="sigtitle">Receiving Bank</div></td>
  </tr>
</table>

</body>
</html>
