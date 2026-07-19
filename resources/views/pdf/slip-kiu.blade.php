<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Fee Challan</title>
<style>
@page { margin: 5mm 4mm; size: A4 landscape; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "DejaVu Sans", sans-serif; font-size: 7pt; line-height: 1.3; color: #111; }

/* ── Outer 3-column table ── */
.outer { width: 100%; border-collapse: separate; border-spacing: 4pt 0; table-layout: fixed; }
.outer > tbody > tr > td { width: 33.33%; vertical-align: top; padding: 0; }

/* ── Each copy ── */
.cp { width: 100%; border-collapse: collapse; border: 1.5pt solid #1a1a1a; }
.cp > tbody > tr > td { padding: 0; }

/* ── Title bar ── */
.tbar { text-align: center; font-weight: bold; font-size: 7.5pt; padding: 3pt 4pt 2.5pt; letter-spacing: 0.4pt; }

/* ── Header ── */
.hdr { padding: 4pt 5pt 3pt; border-bottom: 0.5pt solid #ddd; }
.hdr-t { width: 100%; border-collapse: collapse; }
.hdr-t > tbody > tr > td { vertical-align: middle; padding: 0; }

/* ── Barcode ── */
.bc { padding: 2pt 5pt 0; }

/* ── SN row ── */
.sn { padding: 2pt 5pt 2pt; border-top: 0.5pt solid #aaa; border-bottom: 1pt solid #1a1a1a; }
.sn-t { width: 100%; border-collapse: collapse; }
.sn-t td { font-size: 7pt; font-weight: bold; }

/* ── Info fields ── */
.inf { padding: 2pt 5pt 0; }
.inf-t { width: 100%; border-collapse: collapse; }
.inf-t td { font-size: 6.5pt; padding: 0.6pt 0; vertical-align: top; }
.lb { font-weight: bold; white-space: nowrap; width: 35%; }

/* ── Fee table ── */
.ft { padding: 2pt 5pt; }
.ft-t { width: 100%; border-collapse: collapse; }
.ft-t thead td { font-size: 6.5pt; font-weight: bold; padding: 2.5pt 3pt; border: 0.5pt solid; }
.ft-t tbody td { font-size: 7pt; padding: 1.8pt 3pt; border: 0.5pt solid #ccc; vertical-align: middle; }
.ft-t tbody td.r { text-align: right; }
.ft-t tbody td.n { text-align: center; font-weight: bold; }
.ft-t tr.tot td { font-weight: bold; background: #f2f2f2; border-top: 1pt solid #333; border-color: #333; font-size: 7.5pt; }
.ft-t tr.tot td.r { text-align: right; }

/* ── InWords ── */
.iw { padding: 2pt 5pt 1.5pt; font-size: 7pt; border-top: 0.5pt solid #eee; }

/* ── Depositor ── */
.dep { padding: 1.5pt 5pt; }
.dep-lbl { font-size: 6.5pt; font-weight: bold; margin-bottom: 0.5pt; }
.dep-ln { width: 100%; border-collapse: collapse; margin-bottom: 2.5pt; }
.dep-ln td { border-bottom: 0.5pt solid #555; height: 10pt; font-size: 0; }

/* ── Ref No ── */
.rf { padding: 0.5pt 5pt 1.5pt; font-size: 6.5pt; }

/* ── Instructions ── */
.ins { padding: 2pt 5pt; border-top: 0.5pt dashed #ccc; }
.ins-v { font-size: 5.2pt; color: #333; line-height: 1.7; }

/* ── Fee Paid stamp ── */
.fp { padding: 2pt 5pt 4pt; border-top: 0.5pt solid #ddd; }
.fp-lbl { font-size: 5pt; color: #999; margin-bottom: 1.5pt; }
.fp-box { border: 0.8pt solid #bbb; height: 18pt; text-align: center; vertical-align: middle; }
.fp-paid { font-size: 9pt; font-weight: bold; letter-spacing: 2pt; }
.fp-empty { font-size: 6pt; color: #ccc; }
.ft-txt { font-size: 5pt; color: #bbb; text-align: center; margin-top: 2pt; }
</style>
</head>
<body>
@php
use Carbon\Carbon;

$primaryColor = $template->primary_color ?? '#009999';
$accentColor  = $template->accent_color  ?? '#1a56db';
$textColor    = $template->text_color    ?? '#111111';

// ── Data resolution ───────────────────────────────────────────────────
if ($payment !== null) {
    $s        = $payment->student;
    $sName    = $s?->name ?? '—';
    $sFather  = $s?->father_name ?? '—';
    $sRoll    = $s?->registration_number ?: ($s?->roll_number ?? '—');
    $sProgram = $s?->academicProgram?->name ?? '—';
    $sSem     = $payment->semester_number ?? '—';
    $sSession = $payment->academicYear?->name ?? '—';
    $due      = (float)($payment->amount_due ?? 0);
    $fine     = (float)($payment->fine_amount ?? 0);
    $disc     = (float)($payment->discount_amount ?? 0);
    $net      = $due + $fine - $disc;
    $stVal    = $payment->payment_status instanceof \BackedEnum ? $payment->payment_status->value : (string)$payment->payment_status;
    $isPaid   = strtolower($stVal) === 'paid';
    $paidDate = $isPaid && $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d-m-Y') : '';
    $dueDate  = $payment->due_date ? Carbon::parse($payment->due_date)->format('d-m-Y') : '';
    $sn       = $payment->challan_number ?? '—';
    $feeLabel = $payment->feeStructure?->title
        ?? ucwords(str_replace('_', ' ', $payment->fee_type instanceof \BackedEnum
            ? $payment->fee_type->value : ($payment->fee_type ?? 'Semester Fee')));
} else {
    $d        = $data ?? [];
    $sName    = $d['student_name']    ?? 'Jaffar Ali';
    $sFather  = $d['father_name']     ?? 'Muhammad Ali';
    $sRoll    = $d['roll_number']     ?? '24609';
    $sProgram = $d['program']         ?? 'BS Information Technology';
    $sSem     = $d['semester_no']     ?? 4;
    $sSession = $d['session']         ?? 'Spring 2026';
    $due      = (float)($d['amount_due']      ?? 48800);
    $fine     = (float)($d['fine_amount']     ?? 4200);
    $disc     = (float)($d['discount_amount'] ?? 0);
    $net      = $due + $fine - $disc;
    $isPaid   = false;
    $paidDate = '';
    $dueDate  = $d['due_date']        ?? '11-04-2026';
    $sn       = $d['challan_number']  ?? 'JDCA-2026-0042';
    $feeLabel = $d['fee_label']       ?? 'Semester Fee';
}

// ── Template settings ─────────────────────────────────────────────────
$college      = $template->college_name       ?? \App\Models\CollegeSetting::get('college_name', 'Jinnah Degree College Astore');
$collegeShort = $template->college_short_name ?? \App\Models\CollegeSetting::get('college_short_name', 'JDCA');
$collegeSub   = $template->college_subtitle   ?? '';
$bankName     = $template->bank_name          ?? \App\Models\CollegeSetting::get('fee_bank_name', 'HBL');
$bankAcct     = $template->bank_account       ?? \App\Models\CollegeSetting::get('fee_bank_account', '—');
$bankTitle    = $template->bank_account_title ?? $college;
$bankBranch   = $template->bank_branch        ?? '';
$refPfx       = $template->ref_prefix         ?? \App\Models\CollegeSetting::get('fee_challan_ref_prefix', 'JDCA');
$billPfx      = $template->bill_prefix        ?? \App\Models\CollegeSetting::get('fee_challan_1bill_prefix', '');
$copies       = $template->copies             ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $template->instructions       ?? "You can submit this fee voucher at any {$bankName} branch or simply pay using the {$bankName} Mobile App. Just Tap on More >> Education >> Select {$college} from the List.";
$footerText   = $template->footer_text        ?? '';
$showBarcode  = $template->show_barcode       ?? true;
$showInWords  = $template->show_in_words      ?? true;
$showDep      = $template->show_depositor_fields ?? true;
$showRef      = $template->show_ref_no        ?? true;
$showConsumer = $template->show_consumer_no   ?? true;
$showSig      = $template->show_accountant_sig ?? false;
$feeMode      = $template->fee_display_mode   ?? 'dynamic';
$feeItems     = $template->fee_items          ?? [];

$refNo      = $refPfx . '-' . $sn;
$billNo     = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;
$dateLabel  = $paidDate ?: '____________________';

// ── Amount in words ───────────────────────────────────────────────────
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

// -- Code 128 barcode --
$barcodeSrc = \App\Support\Barcode::code128Png($sn);

// Payment QR (EMVCo / Raast bill format). Uses the configured bank account as
// the merchant id for now — swap in a registered Raast merchant id for live pay.
$showQr       = $template->show_qr ?? true;
$paymentQrSrc = $showQr ? \App\Support\PaymentQr::png([
    'merchant_name' => $college,
    'merchant_city' => 'Astore',
    'merchant_id'   => $bankAcct,
    'amount'        => $net,
    'bill_ref'      => $sn,
], 220) : null;
@endphp

<table class="outer"><tbody><tr>
@foreach($copies as $copyLabel)
<td>
<table class="cp" style="color:{{ $textColor }};">
<tbody>

{{-- ── Title bar ── --}}
<tr>
  <td class="tbar" style="background:{{ $primaryColor }};color:#fff;border-bottom:none;">
    {{ $copyLabel }}
  </td>
</tr>

{{-- ── Header: Bank logo | College name | School crest ── --}}
<tr>
  <td class="hdr">
    <table class="hdr-t">
    <tr>
      {{-- LEFT: Bank logo ─────────────────────────────── --}}
      <td style="width:22%;vertical-align:middle;">
        @if(!empty($template->bank_logo_path) && is_file(storage_path('app/public/' . $template->bank_logo_path)))
          <img src="{{ storage_path('app/public/' . $template->bank_logo_path) }}" style="max-width:52pt;max-height:32pt;display:block;"/>
        @elseif(is_file(public_path('assets/images/default/kcbl-logo-web.png')))
          <img src="{{ public_path('assets/images/default/kcbl-logo-web.png') }}" style="max-width:52pt;max-height:32pt;display:block;"/>
        @else
          <div style="background:{{ $primaryColor }};color:#fff;display:inline-block;padding:1pt 5pt 2pt;line-height:1;">
            <span style="font-size:16pt;font-weight:900;letter-spacing:-2.5pt;">{{ strtoupper(substr($bankName,0,3)) }}</span>
          </div>
        @endif
        @if($bankBranch)
        <div style="font-size:5pt;color:#666;margin-top:1pt;text-align:center;">{{ $bankBranch }}</div>
        @endif
      </td>

      {{-- CENTER: University name ──────────────────────── --}}
      <td style="width:54%;vertical-align:middle;text-align:center;padding:0 3pt;">
        <div style="font-size:8pt;font-weight:bold;color:{{ $textColor }};line-height:1.3;">{{ $college }}</div>
        @if($collegeSub)
        <div style="font-size:6pt;color:#555;margin-top:1pt;">{{ $collegeSub }}</div>
        @endif
        @if(\App\Models\CollegeSetting::get('college_city'))
        <div style="font-size:5pt;color:#888;margin-top:0.5pt;">{{ \App\Models\CollegeSetting::get('college_city') }}</div>
        @endif
      </td>

      {{-- RIGHT: College logo or SVG crest ───────────── --}}
      <td style="width:24%;vertical-align:middle;text-align:right;">
        @if(!empty($template->logo_path) && is_file(storage_path('app/public/' . $template->logo_path)))
          <img src="{{ storage_path('app/public/' . $template->logo_path) }}" style="max-width:44pt;max-height:40pt;display:inline-block;"/>
        @elseif(is_file(public_path('assets/images/default/cologe-logo-web.png')))
          <img src="{{ public_path('assets/images/default/cologe-logo-web.png') }}" style="max-width:44pt;max-height:40pt;display:inline-block;"/>
        @else
          <svg width="40" height="40" viewBox="0 0 40 40" style="opacity:0.55;">
            <circle cx="20" cy="20" r="19" fill="none" stroke="{{ $primaryColor }}" stroke-width="2"/>
            <circle cx="20" cy="20" r="14" fill="none" stroke="{{ $primaryColor }}" stroke-width="0.8" stroke-dasharray="2.5,2"/>
            <polygon points="20,7 28,12 20,17 12,12" fill="{{ $primaryColor }}" opacity="0.7"/>
            <rect x="17" y="17" width="6" height="2" fill="{{ $primaryColor }}" opacity="0.7"/>
            <text x="20" y="27" text-anchor="middle" font-size="6.5" font-weight="bold" fill="{{ $primaryColor }}" font-family="DejaVu Sans">{{ $collegeShort }}</text>
            <text x="20" y="33" text-anchor="middle" font-size="3.8" fill="{{ $primaryColor }}" font-family="DejaVu Sans" opacity="0.8">EST. 2010</text>
          </svg>
        @endif
      </td>
    </tr>
    </table>
  </td>
</tr>

@if($showBarcode && $barcodeSrc)
{{-- ── Code 128 B Barcode ── --}}
<tr>
  <td class="bc" style="text-align:center;padding:3px 0;">
    <div style="background:#fff;padding:4px 14px;display:inline-block;">
      <img src="{{ $barcodeSrc }}" alt="{{ $sn }}" style="width:190px;height:42px;display:block;margin:0 auto;"/>
      <div style="font-size:6pt;color:#222;font-family:'DejaVu Sans',sans-serif;letter-spacing:2px;margin-top:1px;">{{ $sn }}</div>
    </div>
  </td>
</tr>
@endif

{{-- ── SN# + Date ── --}}
<tr>
  <td class="sn">
    <table class="sn-t"><tr>
      <td style="width:50%;"><b>SN#:</b> {{ $sn }}</td>
      <td style="text-align:right;"><b>Date:</b> {{ $dateLabel }}</td>
    </tr></table>
  </td>
</tr>

{{-- ── Info fields (KIU order) ── --}}
<tr>
  <td class="inf">
    <table class="inf-t">
      <tr>
        <td class="lb">Account:</td>
        <td style="color:{{ $primaryColor }};font-weight:bold;">{{ $bankAcct }}</td>
      </tr>
      <tr>
        <td class="lb">Title:</td>
        <td>{{ $bankTitle }}</td>
      </tr>
      <tr>
        <td class="lb" style="border-top:0.4pt solid #eee;padding-top:1.5pt;">Student Name:</td>
        <td style="border-top:0.4pt solid #eee;padding-top:1.5pt;font-weight:bold;">{{ $sName }}</td>
      </tr>
      <tr>
        <td class="lb">Reg No:</td>
        <td>{{ $sRoll }}</td>
      </tr>
      <tr>
        <td class="lb">Program:</td>
        <td>{{ $sProgram }}</td>
      </tr>
      <tr>
        <td class="lb">Semester No:</td>
        <td>{{ $sSem }}</td>
      </tr>
      <tr>
        <td class="lb">Semester Name:</td>
        <td>{{ $sSession }}</td>
      </tr>
      <tr>
        <td class="lb">Remarks:</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </td>
</tr>

{{-- ── Fee Table ── --}}
<tr>
  <td class="ft">
    <table class="ft-t">
      <thead>
        <tr>
          <td style="width:18pt;background:{{ $primaryColor }};color:#fff;border-color:{{ $primaryColor }};">S#.</td>
          <td style="background:{{ $primaryColor }};color:#fff;border-color:{{ $primaryColor }};">Particular</td>
          <td class="r" style="width:56pt;background:{{ $primaryColor }};color:#fff;border-color:{{ $primaryColor }};">Rs.</td>
        </tr>
      </thead>
      <tbody>
      @if($feeMode === 'static' && count($feeItems) > 0)
        @foreach($feeItems as $idx => $item)
        <tr>
          <td class="n" style="color:{{ $primaryColor }};">{{ $idx + 1 }}.</td>
          <td>{{ $item['label'] ?? '' }}</td>
          <td class="r">&nbsp;</td>
        </tr>
        @endforeach
      @else
        <tr>
          <td class="n" style="color:{{ $primaryColor }};">1.</td>
          <td>{{ $feeLabel }}</td>
          <td class="r">{{ number_format($due, 0) }}</td>
        </tr>
        @if($fine > 0)
        <tr>
          <td class="n" style="color:{{ $primaryColor }};">2.</td>
          <td>Late Surcharge{{ $dueDate ? ' After ('.$dueDate.')' : '' }}</td>
          <td class="r">{{ number_format($fine, 2) }}</td>
        </tr>
        @endif
        @if($disc > 0)
        <tr>
          <td class="n" style="color:{{ $primaryColor }};">{{ $fine > 0 ? '3.' : '2.' }}</td>
          <td>Discount / Concession</td>
          <td class="r">- {{ number_format($disc, 0) }}</td>
        </tr>
        @endif
      @endif
        <tr class="tot">
          <td colspan="2" class="r">Total Payable</td>
          <td class="r">{{ number_format($net, 0) }}</td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>

@if($showInWords)
{{-- ── InWords ── --}}
<tr>
  <td class="iw">
    <b>InWords:</b>
    <span style="color:{{ $accentColor }};font-weight:bold;">{{ $inWords }}</span>
  </td>
</tr>
@endif

@if($showDep)
{{-- ── Depositor fields ── --}}
<tr>
  <td class="dep">
    <div class="dep-lbl">Depositor Mobile No:</div>
    <table class="dep-ln"><tr><td>&nbsp;</td></tr></table>
    <div class="dep-lbl">CNIC #:</div>
    <table class="dep-ln"><tr><td>&nbsp;</td></tr></table>
  </td>
</tr>
@endif

@if($showRef || ($showConsumer && $billNo))
{{-- ── Ref No / Consumer No ── --}}
<tr>
  <td class="rf">
    @if($showRef)<b>Ref No:</b> {{ $refNo }}@endif
    @if($showConsumer && $billNo) &nbsp;&nbsp;<b>Consumer No:</b> {{ $consumerNo }}@endif
  </td>
</tr>
@endif

{{-- ── Instructions (+ Scan-to-Pay QR) ── --}}
<tr>
  <td class="ins">
    <table style="width:100%;border-collapse:collapse;"><tr>
      <td style="vertical-align:top;">
        <div class="ins-v">
          @foreach(array_filter(array_map('trim', explode("\n", $instructions))) as $line)
            {{ $line }}<br>
          @endforeach
          @if($showConsumer && $billNo)
            For Payment using 1-Bill, use the consumer number <b>{{ $consumerNo }}</b>
          @endif
        </div>
      </td>
      @if($paymentQrSrc)
      <td style="width:64pt;vertical-align:top;text-align:center;padding-left:4pt;">
        <img src="{{ $paymentQrSrc }}" alt="Scan to Pay" style="width:58pt;height:58pt;display:block;margin:0 auto;"/>
        <div style="font-size:5pt;font-weight:bold;color:#111;margin-top:1pt;">SCAN TO PAY</div>
        <div style="font-size:4.5pt;color:#666;">Rs. {{ number_format($net) }}</div>
      </td>
      @endif
    </tr></table>
  </td>
</tr>

{{-- ── Fee Paid / Bank Stamp ── --}}
<tr>
  <td class="fp">
    @if($showSig)
      <table style="width:100%;border-collapse:collapse;"><tr>
        <td style="font-size:5.5pt;color:#888;width:50%;">&nbsp;</td>
        <td style="text-align:right;font-size:5.5pt;color:#888;">Accountant Signature:</td>
      </tr><tr>
        <td>&nbsp;</td>
        <td><table style="width:100%;border-collapse:collapse;"><tr><td class="fp-box"></td></tr></table></td>
      </tr></table>
    @else
      <div class="fp-lbl">For Bank Use / Official Stamp:</div>
      <table style="width:100%;border-collapse:collapse;"><tr>
        <td class="fp-box">
          @if($isPaid)
            <span class="fp-paid" style="color:#1a7a1a;">&#10003; FEE PAID</span>
          @else
            <span class="fp-empty">Bank Stamp</span>
          @endif
        </td>
      </tr></table>
    @endif
    @if($footerText)
    <div class="ft-txt">{{ $footerText }}</div>
    @endif
  </td>
</tr>

</tbody>
</table>
</td>
@endforeach
</tr></tbody></table>
</body>
</html>
