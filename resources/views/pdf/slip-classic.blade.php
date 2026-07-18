<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Fee Challan - Classic</title>
<style>
@page { margin: 5mm 4mm; size: A4 landscape; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "DejaVu Sans", sans-serif; font-size: 6.5pt; line-height: 1.3; color: #111; }

/* ── Outer 3-column table ── */
.outer { width: 100%; border-collapse: separate; border-spacing: 4pt 0; table-layout: fixed; }
.outer > tbody > tr > td { width: 33.33%; vertical-align: top; padding: 0; }

/* ── Each copy ── */
.cp { width: 100%; border-collapse: collapse; border: 1.5pt solid #1a1a1a; }
.cp > tbody > tr > td { padding: 0; }

/* ── Copy label bar (at top) ── */
.cbar { text-align: center; font-weight: bold; font-size: 7pt; padding: 2.5pt 4pt; letter-spacing: 0.5pt; border-bottom: 1pt solid #1a1a1a; }

/* ── Header: logo | name | SN ── */
.hdr { padding: 3pt 5pt 2.5pt; border-bottom: 1pt solid; }
.hdr-t { width: 100%; border-collapse: collapse; }
.hdr-t td { vertical-align: middle; padding: 0; }

/* ── Bank row ── */
.bnk { padding: 1.5pt 5pt; border-bottom: 0.5pt solid #aaa; font-size: 6pt; text-align: center; }

/* ── Fee Receipt badge ── */
.badge-row { text-align: center; padding: 1.5pt 5pt; border-bottom: 0.5pt solid #ccc; }
.badge { font-size: 7pt; font-weight: bold; letter-spacing: 0.8pt; text-transform: uppercase; }
.badge-pill { display: inline-block; padding: 1pt 8pt; border-radius: 2pt; }

/* ── SN + Date row ── */
.sn { padding: 1.5pt 5pt; border-bottom: 0.5pt solid #ddd; }
.sn-t { width: 100%; border-collapse: collapse; }
.sn-t td { font-size: 6.5pt; font-weight: bold; }

/* ── Student info ── */
.inf { padding: 2pt 5pt 0; }
.inf-t { width: 100%; border-collapse: collapse; }
.inf-t td { font-size: 6pt; padding: 1pt 2pt; border-bottom: 0.4pt dotted #ccc; vertical-align: top; }
.lb { font-weight: bold; white-space: nowrap; width: 32%; }

/* ── Fee table ── */
.ft { padding: 1.5pt 5pt; }
.ft-t { width: 100%; border-collapse: collapse; }
.ft-t thead td { font-size: 6pt; font-weight: bold; padding: 2pt 3pt; border: 0.5pt solid; color: #fff; }
.ft-t tbody td { font-size: 6pt; padding: 1.2pt 3pt; border: 0.4pt dotted #bbb; vertical-align: middle; }
.ft-t tbody td.r { text-align: right; }
.ft-t tbody td.n { text-align: center; }
.ft-t tr.tot td { font-weight: bold; border-top: 1pt solid #333; border-color: #333; border-style: solid; font-size: 6.5pt; }
.ft-t tr.tot td.r { text-align: right; }

/* ── InWords ── */
.iw { padding: 1.5pt 5pt; font-size: 6.5pt; border-top: 0.5pt solid #ccc; }

/* ── Depositor ── */
.dep { padding: 1.5pt 5pt; }
.dep-ul { width: 100%; border-collapse: collapse; margin-top: 0.5pt; margin-bottom: 2.5pt; }
.dep-ul td { border-bottom: 0.5pt solid #555; height: 9pt; font-size: 0; }

/* ── Ref ── */
.rf { padding: 0.5pt 5pt 1pt; font-size: 6pt; border-top: 0.5pt dashed #ccc; }

/* ── Instructions ── */
.ins { padding: 1.5pt 5pt; border-top: 0.5pt dashed #ccc; }
.ins-v { font-size: 5pt; color: #444; line-height: 1.65; }

/* ── Accountant signature ── */
.sig { padding: 2pt 5pt 3pt; border-top: 0.5pt solid #ccc; }
.sig-line { border-top: 0.5pt solid #333; width: 55%; margin-left: 45%; margin-top: 10pt; }

/* ── Footer ── */
.foot { padding: 1pt 5pt; border-top: 0.5pt solid #ddd; font-size: 5pt; color: #999; text-align: center; }
</style>
</head>
<body>
@php
use Carbon\Carbon;

$primaryColor = $template->primary_color ?? '#2e7d32';
$accentColor  = $template->accent_color  ?? '#1b5e20';
$textColor    = $template->text_color    ?? '#111111';

// ── Data resolution ───────────────────────────────────────────────────
if ($payment !== null) {
    $s        = $payment->student;
    $sName    = $s?->name ?? '—';
    $sFather  = $s?->father_name ?? '—';
    $sRoll    = $s?->roll_number ?? '—';
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
    $fine     = (float)($d['fine_amount']     ?? 0);
    $disc     = (float)($d['discount_amount'] ?? 0);
    $net      = $due + $fine - $disc;
    $isPaid   = false;
    $paidDate = '';
    $dueDate  = $d['due_date']        ?? '30-06-2026';
    $sn       = $d['challan_number']  ?? 'JDCA-2026-0042';
    $feeLabel = $d['fee_label']       ?? 'Semester Fee';
}

// ── Template settings ─────────────────────────────────────────────────
$college      = $template->college_name       ?? \App\Models\CollegeSetting::get('college_name', 'Jinnah Degree College Astore');
$collegeSub   = $template->college_subtitle   ?? \App\Models\CollegeSetting::get('college_subtitle', '(EIDGAH ASTORE)');
$collegeShort = $template->college_short_name ?? \App\Models\CollegeSetting::get('college_short_name', 'JDCA');
$bankName     = $template->bank_name          ?? \App\Models\CollegeSetting::get('fee_bank_name', 'KCBL');
$bankAcct     = $template->bank_account       ?? \App\Models\CollegeSetting::get('fee_bank_account', '0368421');
$bankBranch   = $template->bank_branch        ?? \App\Models\CollegeSetting::get('fee_bank_branch', 'Eidgah');
$bankTitle    = $template->bank_account_title ?? $college;
$refPfx       = $template->ref_prefix         ?? 'JDCA';
$billPfx      = $template->bill_prefix        ?? '';
$copies       = $template->copies             ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $template->instructions       ?? "Submit this fee receipt at any {$bankName} branch.\nKeep this copy as proof of payment after bank stamp.";
$footerText   = $template->footer_text        ?? '';
$showInWords  = $template->show_in_words      ?? true;
$showDep      = $template->show_depositor_fields ?? true;
$showRef      = $template->show_ref_no        ?? true;
$showConsumer = $template->show_consumer_no   ?? false;
$showSig      = $template->show_accountant_sig ?? true;
$showBarcode  = $template->show_barcode       ?? false;
$feeMode      = $template->fee_display_mode   ?? 'static';
$feeItems     = $template->fee_items          ?? [];

$refNo      = $refPfx . '-' . $sn;
$billNo     = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;
$dateLabel  = $paidDate ?: '____________';
$bankStr    = $bankName . ($bankBranch ? ' ' . $bankBranch : '') . ($bankAcct ? ' | Acct# ' . $bankAcct : '');

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

// ── Code 128 B Barcode (optional) ────────────────────────────────────
$bars = []; $bcW = 100;
if ($showBarcode) {
    $c128 = [
        [2,1,2,2,2,2],[2,2,2,1,2,2],[2,2,2,2,2,1],[1,2,1,2,2,3],[1,2,1,3,2,2],[1,3,1,2,2,2],[1,2,2,2,1,3],[1,2,2,3,1,2],
        [1,3,2,2,1,2],[2,2,1,2,1,3],[2,2,1,3,1,2],[2,3,1,2,1,2],[1,1,2,2,3,2],[1,2,2,1,3,2],[1,2,2,2,3,1],[1,1,3,2,2,2],
        [1,2,3,1,2,2],[1,2,3,2,2,1],[2,2,3,2,1,1],[2,2,1,1,3,2],[2,2,1,2,3,1],[2,1,3,2,1,2],[2,2,3,1,1,2],[3,1,2,1,3,1],
        [3,1,1,2,2,2],[3,2,1,1,2,2],[3,2,1,2,2,1],[3,1,2,2,1,2],[3,2,2,1,1,2],[3,2,2,2,1,1],[2,1,2,1,2,3],[2,1,2,3,2,1],
        [2,3,2,1,2,1],[1,1,1,3,2,3],[1,3,1,1,2,3],[1,3,1,3,2,1],[1,1,2,3,1,3],[1,3,2,3,1,1],[2,1,3,3,1,1],[1,1,3,1,2,3],
        [1,1,3,3,2,1],[1,3,3,1,2,1],[3,1,3,1,2,1],[2,1,1,3,3,1],[2,3,1,1,3,1],[1,1,2,1,3,3],[1,1,2,3,3,1],[2,1,3,1,1,3],
        [2,1,3,3,1,1],[2,3,3,1,1,1],[1,1,3,1,3,2],[3,1,3,1,1,2],[3,1,1,1,2,3],[3,3,1,1,2,1],[3,1,2,1,1,3],[3,1,2,3,1,1],
        [3,3,2,1,1,1],[3,1,4,1,1,1],[2,2,1,4,1,1],[4,3,1,1,1,1],[1,1,1,2,2,4],[1,1,1,4,2,2],[1,2,1,1,2,4],[1,2,1,4,2,1],
        [1,4,1,1,2,2],[1,4,1,2,2,1],[1,1,2,2,1,4],[1,1,2,4,1,2],[1,2,2,1,1,4],[1,2,2,4,1,1],[1,4,2,1,1,2],[1,4,2,2,1,1],
        [2,4,1,2,1,1],[2,2,1,1,1,4],[4,1,3,1,1,1],[2,4,1,1,1,2],[1,3,4,1,1,1],[1,1,1,2,4,2],[1,2,1,1,4,2],[1,2,1,2,4,1],
        [1,1,4,2,1,2],[1,2,4,1,1,2],[1,2,4,2,1,1],[4,1,1,2,1,2],[4,2,1,1,1,2],[4,2,1,2,1,1],[2,1,2,1,4,1],[2,1,4,1,2,1],
        [4,1,2,1,2,1],[1,1,1,1,4,3],[1,1,1,3,4,1],[1,3,1,1,4,1],[1,1,4,1,1,3],[1,1,4,3,1,1],[4,1,1,1,1,3],[4,1,1,3,1,1],
        [1,1,3,1,4,1],[1,1,4,1,3,1],[3,1,1,1,4,1],[4,1,1,1,3,1],[2,1,1,4,1,2],[2,1,1,2,1,4],[2,1,1,2,3,2],
    ];
    $mods = [2,1,1,4,1,2]; $cksum = 104; $pos = 1;
    foreach (str_split(substr(preg_replace('/[^\x20-\x7E]/', ' ', $sn), 0, 18)) as $ch) {
        $cv = max(0, min(95, ord($ch) - 32));
        $mods = array_merge($mods, $c128[$cv]);
        $cksum += $pos++ * $cv;
    }
    $mods = array_merge($mods, $c128[$cksum % 103], [2,3,3,1,1,1,2]);
    $sc = 1.1; $bx = 3;
    foreach ($mods as $mi => $mw) {
        $sw = round($mw * $sc, 2);
        if ($mi % 2 === 0) $bars[] = ['x' => round($bx,2), 'w' => $sw];
        $bx += $sw;
    }
    $bcW = round($bx + 3, 2);
}
@endphp

<table class="outer"><tbody><tr>
@foreach($copies as $copyLabel)
<td>
<table class="cp" style="color:{{ $textColor }};">
<tbody>

{{-- ── Copy label top bar ── --}}
<tr>
  <td class="cbar" style="background:{{ $primaryColor }};color:#fff;">
    {{ $copyLabel }}
  </td>
</tr>

{{-- ── Header: Crest | College name | SN ── --}}
<tr>
  <td class="hdr" style="border-color:{{ $primaryColor }};">
    <table class="hdr-t">
    <tr>
      {{-- LEFT: School crest or logo ────────────────── --}}
      <td style="width:18%;vertical-align:middle;">
        @if(!empty($template->logo_path) && is_file(storage_path('app/public/' . $template->logo_path)))
          <img src="{{ storage_path('app/public/' . $template->logo_path) }}" style="max-width:36pt;max-height:36pt;display:block;"/>
        @else
          <svg width="36" height="36" viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="17" fill="#e8f5e9" stroke="{{ $primaryColor }}" stroke-width="1.5"/>
            <circle cx="18" cy="18" r="12" fill="none" stroke="{{ $primaryColor }}" stroke-width="0.6" stroke-dasharray="2,1.5"/>
            <polygon points="18,6 25,10.5 18,15 11,10.5" fill="{{ $primaryColor }}" opacity="0.8"/>
            <text x="18" y="24" text-anchor="middle" font-size="5.5" font-weight="bold" fill="{{ $primaryColor }}" font-family="DejaVu Sans">{{ $collegeShort }}</text>
            <text x="18" y="31" text-anchor="middle" font-size="3.5" fill="{{ $primaryColor }}" font-family="DejaVu Sans">EST. 2010</text>
          </svg>
        @endif
      </td>

      {{-- CENTER: College name ─────────────────────── --}}
      <td style="width:65%;vertical-align:middle;text-align:center;padding:0 3pt;">
        <div style="font-size:8.5pt;font-weight:bold;color:{{ $primaryColor }};line-height:1.2;">{{ $college }}</div>
        @if($collegeSub)
        <div style="font-size:6pt;color:#555;margin-top:1pt;">{{ $collegeSub }}</div>
        @endif
      </td>

      {{-- RIGHT: SN + Date ────────────────────────── --}}
      <td style="width:17%;vertical-align:top;text-align:right;">
        <div style="font-size:6pt;font-weight:bold;color:{{ $textColor }};">S.No: {{ $sn }}</div>
        <div style="font-size:5.5pt;color:#666;margin-top:1.5pt;">Date: {{ $dateLabel }}</div>
      </td>
    </tr>
    </table>
  </td>
</tr>

{{-- ── Bank name / logo row ── --}}
<tr>
  <td class="bnk">
    @if(!empty($template->bank_logo_path) && is_file(storage_path('app/public/' . $template->bank_logo_path)))
      <img src="{{ storage_path('app/public/' . $template->bank_logo_path) }}" style="max-height:20pt;max-width:70pt;display:inline-block;vertical-align:middle;"/> &nbsp;
      <span style="color:{{ $primaryColor }};font-weight:bold;font-size:6pt;">{{ $bankStr }}</span>
    @else
      <span style="color:{{ $primaryColor }};font-weight:bold;">Bank Name: {{ $bankStr }}</span>
    @endif
  </td>
</tr>

@if($showBarcode)
{{-- ── Barcode ── --}}
<tr>
  <td style="padding:1.5pt 5pt 0;">
    <svg width="72%" height="26" viewBox="0 0 {{ $bcW }} 26" preserveAspectRatio="none" style="display:block;">
      @foreach($bars as $b)
        <rect x="{{ $b['x'] }}" y="0" width="{{ $b['w'] }}" height="21" fill="#0d0d0d"/>
      @endforeach
      <text x="{{ round($bcW/2,2) }}" y="25.5" text-anchor="middle" font-size="5" fill="#444" font-family="DejaVu Sans">{{ $sn }}</text>
    </svg>
  </td>
</tr>
@endif

{{-- ── Fee Receipt badge ── --}}
<tr>
  <td class="badge-row">
    <span class="badge-pill" style="background:{{ $primaryColor }};color:#fff;">
      <span class="badge">Fee Receipt</span>
    </span>
  </td>
</tr>

{{-- ── Student Info ── --}}
<tr>
  <td class="inf">
    <table class="inf-t">
      <tr>
        <td class="lb">Student's Name</td>
        <td>{{ $sName }}</td>
      </tr>
      <tr>
        <td class="lb">Father's Name</td>
        <td>{{ $sFather }}</td>
      </tr>
      <tr>
        <td class="lb">Registration No.</td>
        <td>{{ $sRoll }}</td>
      </tr>
      <tr>
        <td class="lb" style="width:32%;">Dept.</td>
        <td style="width:38%;">{{ $sProgram }}</td>
        <td style="font-weight:bold;width:15%;white-space:nowrap;">Sem:</td>
        <td style="width:15%;">{{ $sSem }}</td>
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
          <td style="width:14pt;background:{{ $primaryColor }};border-color:{{ $primaryColor }};">S#</td>
          <td style="background:{{ $primaryColor }};border-color:{{ $primaryColor }};">Particulars</td>
          <td class="r" style="width:42pt;background:{{ $primaryColor }};border-color:{{ $primaryColor }};">Rs.</td>
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
          <td>Late Surcharge</td>
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
          <td colspan="2" class="r">TOTAL</td>
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
    <b>Rupees (In words):</b>
    <span style="color:{{ $accentColor }};font-weight:bold;">{{ $inWords }}</span>
  </td>
</tr>
@endif

@if($showDep)
{{-- ── Depositor fields (side by side) ── --}}
<tr>
  <td class="dep">
    <table style="width:100%;border-collapse:collapse;">
    <tr>
      <td style="width:49%;font-size:6pt;font-weight:bold;padding-right:4pt;">
        Depositor Mobile No:
        <table class="dep-ul"><tr><td>&nbsp;</td></tr></table>
      </td>
      <td style="width:2%;"></td>
      <td style="width:49%;font-size:6pt;font-weight:bold;">
        CNIC #:
        <table class="dep-ul"><tr><td>&nbsp;</td></tr></table>
      </td>
    </tr>
    </table>
  </td>
</tr>
@endif

@if($showRef)
{{-- ── Ref No ── --}}
<tr>
  <td class="rf">
    <b>Ref No:</b> {{ $refNo }}
    @if($showConsumer && $billNo) &nbsp;&nbsp;<b>Consumer No:</b> {{ $consumerNo }} @endif
  </td>
</tr>
@endif

{{-- ── Instructions ── --}}
<tr>
  <td class="ins">
    <div class="ins-v">
      @foreach(array_filter(array_map('trim', explode("\n", $instructions))) as $line)
        &bull; {{ $line }}<br>
      @endforeach
    </div>
  </td>
</tr>

@if($showSig)
{{-- ── Accountant Signature ── --}}
<tr>
  <td class="sig">
    <table style="width:100%;border-collapse:collapse;"><tr>
      <td style="font-size:5.5pt;color:#666;width:50%;">&nbsp;</td>
      <td style="font-size:5.5pt;color:#666;text-align:right;">Accountant Signature</td>
    </tr></table>
    <div class="sig-line"></div>
  </td>
</tr>
@endif

@if($footerText)
<tr>
  <td class="foot">{{ $footerText }}</td>
</tr>
@endif

</tbody>
</table>
</td>
@endforeach
</tr></tbody></table>
</body>
</html>
