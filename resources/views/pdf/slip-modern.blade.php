<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Fee Challan - Modern</title>
<style>
@page { margin: 5mm 7mm; size: A4 portrait; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "DejaVu Sans", sans-serif; font-size: 7.5pt; line-height: 1.3; }

.copy { width: 100%; border-collapse: collapse; border: 1.5pt solid #1e40af; margin-bottom: 3mm; page-break-inside: avoid; }
.copy > tbody > tr > td { padding: 0; }

/* Header gradient done with layered bg */
.ct-hdr { padding: 0; }
.ct-bar { text-align: center; font-weight: bold; font-size: 8pt; padding: 3pt 0; color: #fff; letter-spacing: 0.5pt; }

.hd { padding: 3pt 5pt 2pt; border-bottom: 0.5pt solid #93c5fd; }
.hd-t { width: 100%; border-collapse: collapse; }
.hd-t td { vertical-align: middle; padding: 0; }

.uni-n { font-size: 8.5pt; font-weight: bold; line-height: 1.2; text-align: center; }
.uni-s { font-size: 5pt; color: #555; margin-top: 1.5pt; text-align: center; }

.bc { padding: 2pt 5pt 0; }
.sn { padding: 2pt 5pt 2pt; border-bottom: 1pt solid #333; }
.sn-t { width: 100%; border-collapse: collapse; }
.sn-t td { font-size: 7.5pt; padding: 0; }

.if { padding: 2pt 5pt 0; }
.if-t { width: 100%; border-collapse: collapse; }
.if-t td { font-size: 7pt; padding: 0.7pt 0; vertical-align: top; }
.lbl { font-weight: bold; white-space: nowrap; width: 34%; }

.ft { padding: 2pt 5pt; }
.ft-t { width: 100%; border-collapse: collapse; }
.ft-t thead td { font-size: 6.5pt; font-weight: bold; padding: 2.5pt 3pt; border: 0.5pt solid #111; color: #fff; }
.ft-t tbody td { font-size: 7.5pt; padding: 1.8pt 3pt; border: 0.5pt solid #dbeafe; vertical-align: middle; }
.ft-t tbody td.r { text-align: right; }
.ft-t tbody td.n { text-align: center; font-weight: bold; }
.ft-t tr.tot td { font-weight: bold; background: #eff6ff; border-top: 0.8pt solid #1e40af; font-size: 8pt; }

.iw { padding: 2pt 5pt 1pt; font-size: 7.5pt; background: #f0f9ff; border-top: 0.5pt solid #bfdbfe; border-bottom: 0.5pt solid #bfdbfe; }
.dep { padding: 1pt 5pt; }
.dep-lbl { font-size: 7pt; font-weight: bold; }
.dep-line { width: 100%; border-collapse: collapse; margin-bottom: 3pt; }
.dep-line td { border-bottom: 0.5pt solid #555; height: 11pt; font-size: 0; }
.ref { padding: 1pt 5pt 2pt; font-size: 7pt; }
.ins { padding: 2pt 5pt; border-top: 0.5pt dashed #93c5fd; }
.ins-v { font-size: 5.5pt; color: #333; line-height: 1.65; }
.fp { padding: 2pt 5pt 4pt; border-top: 0.5pt solid #bfdbfe; }
.fp-lbl { font-size: 5pt; color: #888; margin-bottom: 2pt; }
.fp-box { border: 0.8pt solid #93c5fd; height: 20pt; text-align: center; vertical-align: middle; }
.fp-paid { font-size: 8.5pt; font-weight: bold; letter-spacing: 1.5pt; opacity: 0.75; }
.fp-empty { font-size: 6pt; color: #ccc; letter-spacing: 0.5pt; }
</style>
</head>
<body>
@php
use Carbon\Carbon;

$primaryColor = $template->primary_color ?? '#1e40af';
$accentColor  = $template->accent_color  ?? '#3b82f6';
$textColor    = $template->text_color    ?? '#111827';

if ($payment !== null) {
    $s        = $payment->student;
    $sName    = $s?->name ?? 'â€”';
    $sFather  = $s?->father_name ?? 'â€”';
    $sRoll    = $s?->registration_number ?: ($s?->roll_number ?? 'â€”');
    $sProgram = $s?->academicProgram?->name ?? 'â€”';
    $sSem     = $payment->semester_number ?? 'â€”';
    $sSession = $payment->academicYear?->name ?? 'â€”';
    $installmentLabel = $payment->installment_no ? "Installment #{$payment->installment_no}" : '';
    $due      = (float)($payment->amount_due ?? 0);
    $fine     = (float)($payment->fine_amount ?? 0);
    $disc     = (float)($payment->discount_amount ?? 0);
    $net      = $due + $fine - $disc;
    $stVal    = $payment->payment_status instanceof \BackedEnum ? $payment->payment_status->value : (string)$payment->payment_status;
    $isPaid   = strtolower($stVal) === 'paid';
    $paidDate = $isPaid && $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d-m-Y') : '';
    $dueDate  = $payment->due_date ? Carbon::parse($payment->due_date)->format('d-m-Y') : '';
    $sn       = $payment->challan_number ?? 'â€”';
    $feeLabel = $payment->feeStructure?->title
        ?? ucwords(str_replace('_', ' ', $payment->fee_type instanceof \BackedEnum
            ? $payment->fee_type->value : ($payment->fee_type ?? 'Semester Fee')));
} else {
    $d        = $data ?? [];
    $sName    = $d['student_name'] ?? 'Jaffar Ali';
    $sFather  = $d['father_name'] ?? 'Muhammad Ali';
    $sRoll    = $d['roll_number'] ?? '24609';
    $sProgram = $d['program'] ?? 'BS Information Technology';
    $sSem     = $d['semester_no'] ?? 4;
    $sSession = $d['session'] ?? 'Spring 2026';
    $due      = (float)($d['amount_due'] ?? 48800);
    $fine     = (float)($d['fine_amount'] ?? 0);
    $disc     = (float)($d['discount_amount'] ?? 0);
    $net      = $due + $fine - $disc;
    $isPaid   = false;
    $paidDate = '';
    $dueDate  = $d['due_date'] ?? '30-06-2026';
    $sn       = $d['challan_number'] ?? 'JDCA-2026-0042';
    $feeLabel = $d['fee_label'] ?? 'Semester Fee';
    $installmentLabel = $d['installment_label'] ?? '';
}

$college      = $template->college_name ?? \App\Models\CollegeSetting::get('college_name', 'Jinnah Degree College Astore');
$collegeSub   = $template->college_subtitle ?? '';
$collegeShort = $template->college_short_name ?? 'JDCA';
$bankName     = $template->bank_name ?? 'KCBL';
$bankAcct     = $template->bank_account ?? 'â€”';
$bankTitle    = $template->bank_account_title ?? $college;
$bankBranch   = $template->bank_branch ?? '';
$refPfx       = $template->ref_prefix ?? 'JDCA';
$billPfx      = $template->bill_prefix ?? '';
$copies       = $template->copies ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $template->instructions ?? "â€¢ Submit at any {$bankName} branch.\nâ€¢ Keep this copy as proof of payment.\nâ€¢ Late fine applicable after due date.";
$footerText   = $template->footer_text ?? '';
$showBarcode  = $template->show_barcode ?? true;
$showInWords  = $template->show_in_words ?? true;
$showDep      = $template->show_depositor_fields ?? true;
$showRef      = $template->show_ref_no ?? true;
$showConsumer = $template->show_consumer_no ?? true;
$showSig      = $template->show_accountant_sig ?? false;
$feeMode      = $template->fee_display_mode ?? 'dynamic';
$feeItems     = $template->fee_items ?? [];

$refNo      = $refPfx . '-' . $sn;
$billNo     = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;
$bankBrStr  = $bankBranch ? ", $bankBranch" : '';

$nw = (int)round($net);
$o  = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten',
       'Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
$tn = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
$wp = []; $tmp = $nw;
if ($v=intdiv($tmp,10000000)){$wp[]=$o[$v].' Crore';$tmp%=10000000;}
if ($v=intdiv($tmp,100000)){$wp[]=($v<20?$o[$v]:$tn[intdiv($v,10)].($v%10?' '.$o[$v%10]:'')).' Lakh';$tmp%=100000;}
if ($v=intdiv($tmp,1000)){$wp[]=($v<20?$o[$v]:$tn[intdiv($v,10)].($v%10?' '.$o[$v%10]:'')).' Thousand';$tmp%=1000;}
if ($v=intdiv($tmp,100)){$wp[]=$o[$v].' Hundred';$tmp%=100;}
if ($tmp>=20){$wp[]=$tn[intdiv($tmp,10)].($tmp%10?' '.$o[$tmp%10]:'');}elseif($tmp>0){$wp[]=$o[$tmp];}
$inWords = (empty($wp)?'Zero':implode(' ',$wp)).' Only';

$dateLabel = $paidDate ?: '____________________';

// Code 128 barcode
$barcodeSrc = \App\Support\Barcode::code128Png($sn, $primaryColor);
$paymentQrSrc = \App\Support\PaymentQr::forSlip($college, $net, $sn, $bankAcct);
@endphp

@foreach($copies as $copyLabel)
<table class="copy" style="color:{{ $textColor }};">
<tbody>

{{-- Header gradient bar --}}
<tr><td class="ct-hdr">
  <div class="ct-bar" style="background:{{ $primaryColor }};">
    {{ $copyLabel }} &nbsp;&mdash;&nbsp; {{ $college }}
  </div>
  <div class="hd">
    <table class="hd-t">
    <tr>
      <td style="width:22%;vertical-align:middle;">
        <span style="background:{{ $primaryColor }};color:#fff;font-size:14pt;font-weight:bold;padding:1pt 5pt;display:inline-block;border-radius:2pt;line-height:1.3;">{{ strtoupper(substr($bankName,0,4)) }}</span>
        <div style="font-size:5pt;color:#555;margin-top:1pt;text-align:center;">{{ $bankName }}</div>
      </td>
      <td style="width:55%;vertical-align:middle;padding:0 4pt;">
        <div style="font-size:6pt;color:#444;text-align:center;">Account: <b>{{ $bankAcct }}</b> &bull; {{ $bankBranch }}</div>
        <div style="font-size:5.5pt;color:#666;text-align:center;">Title: {{ $bankTitle }}</div>
      </td>
      <td style="width:23%;vertical-align:middle;text-align:right;">
        <svg width="34" height="34" viewBox="0 0 34 34">
          <circle cx="17" cy="17" r="16" fill="#eff6ff" stroke="{{ $primaryColor }}" stroke-width="1.5"/>
          <text x="17" y="15" text-anchor="middle" font-size="6" font-weight="bold" fill="{{ $primaryColor }}" font-family="DejaVu Sans">{{ $collegeShort }}</text>
          <text x="17" y="23" text-anchor="middle" font-size="3.5" fill="{{ $primaryColor }}" font-family="DejaVu Sans">KIU AFFILIATED</text>
        </svg>
      </td>
    </tr>
    </table>
  </div>
</td></tr>

@if($showBarcode && $barcodeSrc)
<tr><td class="bc" style="text-align:center;padding:3px 0;">
  <div style="background:#fff;padding:4px 14px;display:inline-block;">
    <img src="{{ $barcodeSrc }}" alt="{{ $sn }}" style="width:190px;height:42px;display:block;margin:0 auto;"/>
    <div style="font-size:6pt;color:#222;font-family:'DejaVu Sans',sans-serif;letter-spacing:2px;">{{ $sn }}</div>
  </div>
</td></tr>
@endif

<tr><td class="sn">
  <table class="sn-t">
  <tr>
    <td style="width:50%;"><b>SN#:</b> {{ $sn }}</td>
    <td style="width:50%;text-align:right;"><b>Date:</b> {{ $dateLabel }}</td>
  </tr>
  </table>
</td></tr>

<tr><td class="if">
  <table class="if-t">
    <tr><td class="lbl">Student Name:</td><td>{{ $sName }}</td></tr>
    <tr><td class="lbl">Father Name:</td><td>{{ $sFather }}</td></tr>
    <tr><td class="lbl">Reg No:</td><td>{{ $sRoll }}</td></tr>
    <tr><td class="lbl">Program:</td><td>{{ $sProgram }}</td></tr>
    <tr><td class="lbl">Semester No:</td><td>{{ $sSem }}</td></tr>
    <tr><td class="lbl">Semester Name:</td><td>{{ $sSession }}</td></tr>
    <tr><td class="lbl">Remarks:</td><td>{{ $installmentLabel ?: '' }}</td></tr>
  </table>
</td></tr>

<tr><td class="ft">
  <table class="ft-t">
    <thead>
      <tr>
        <td style="width:16pt;background:{{ $primaryColor }};">S#.</td>
        <td style="background:{{ $primaryColor }};">Particular</td>
        <td class="r" style="width:55pt;background:{{ $primaryColor }};">Rs.</td>
      </tr>
    </thead>
    <tbody>
    @if($feeMode === 'static' && count($feeItems) > 0)
      @foreach($feeItems as $idx => $item)
      <tr>
        <td class="n" style="color:{{ $accentColor }};">{{ $idx + 1 }}.</td>
        <td>{{ $item['label'] ?? '' }}</td>
        <td class="r">&nbsp;</td>
      </tr>
      @endforeach
    @else
      <tr>
        <td class="n" style="color:{{ $accentColor }};">1.</td>
        <td>{{ $feeLabel }}</td>
        <td class="r">{{ number_format($due, 0) }}</td>
      </tr>
      @if($fine > 0)
      <tr><td class="n" style="color:{{ $accentColor }};">2.</td><td>Late Surcharge{{ $dueDate ? ' ('.$dueDate.')' : '' }}</td><td class="r">{{ number_format($fine, 2) }}</td></tr>
      @endif
      @if($disc > 0)
      <tr><td class="n" style="color:{{ $accentColor }};">{{ $fine > 0 ? '3.' : '2.' }}</td><td>Discount / Concession</td><td class="r">- {{ number_format($disc, 0) }}</td></tr>
      @endif
    @endif
      <tr class="tot">
        <td colspan="2" class="r">Total Payable</td>
        <td class="r">{{ number_format($net, 0) }}</td>
      </tr>
    </tbody>
  </table>
</td></tr>

@if($showInWords)
<tr><td class="iw">
  <b>InWords:</b> <span style="color:{{ $accentColor }};font-weight:bold;">{{ $inWords }}</span>
</td></tr>
@endif

@if($showDep)
<tr><td class="dep">
  <div class="dep-lbl">Depositor Mobile No:</div>
  <table class="dep-line"><tr><td>&nbsp;</td></tr></table>
  <div class="dep-lbl" style="margin-top:2pt;">CNIC #:</div>
  <table class="dep-line"><tr><td>&nbsp;</td></tr></table>
</td></tr>
@endif

@if($showRef)
<tr><td class="ref">
  <b>Ref No:</b> {{ $refNo }}
  @if($showConsumer && $billNo) &nbsp;&nbsp;<b>Consumer No:</b> {{ $consumerNo }} @endif
</td></tr>
@endif

<tr><td class="ins">
  <table style="width:100%;border-collapse:collapse;"><tr>
    <td style="vertical-align:top;"><div class="ins-v">{{ $instructions }}</div></td>
    @if($paymentQrSrc)
    <td style="width:64pt;vertical-align:top;text-align:center;padding-left:4pt;">
      <img src="{{ $paymentQrSrc }}" alt="Scan to Pay" style="width:58pt;height:58pt;display:block;margin:0 auto;"/>
      <div style="font-size:5pt;font-weight:bold;color:{{ $primaryColor }};margin-top:1pt;">SCAN TO PAY</div>
      <div style="font-size:4.5pt;color:#666;">Rs. {{ number_format($net) }}</div>
    </td>
    @endif
  </tr></table>
</td></tr>

<tr><td class="fp">
  @if($showSig)
  <div class="fp-lbl">Accountant Signature:</div>
  <table style="width:100%;border-collapse:collapse;"><tr><td class="fp-box">&nbsp;</td></tr></table>
  @else
  <div class="fp-lbl">For Bank Use / Official Stamp:</div>
  <table style="width:100%;border-collapse:collapse;">
  <tr>
    <td class="fp-box">
      @if($isPaid)
        <div class="fp-paid" style="color:#1a7a1a;">&#10003; FEE PAID</div>
      @else
        <div class="fp-empty">Fee Paid</div>
      @endif
    </td>
  </tr>
  </table>
  @endif
  @if($footerText)
  <div style="font-size:5pt;color:#999;text-align:center;margin-top:2pt;">{{ $footerText }}</div>
  @endif
</td></tr>

</tbody>
</table>
@if(!$loop->last)
<div style="text-align:center;font-size:6pt;color:#ccc;padding:0.5mm 0;border-top:0.5pt dashed #bbb;">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>
@endif
@endforeach
</body>
</html>


