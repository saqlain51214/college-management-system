<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Fee Challan</title>
<style>
@page { margin: 5mm 4mm; size: A4 landscape; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "DejaVu Sans", sans-serif; font-size: 7.5pt; color: #111; line-height: 1.3; }

.main { width: 100%; border-collapse: separate; border-spacing: 3pt 0; table-layout: fixed; }
.main > tbody > tr > td { width: 33.33%; vertical-align: top; padding: 0; }

.copy { width: 100%; border-collapse: collapse; border: 1pt solid #222; page-break-inside: avoid; }
.copy > tbody > tr > td { padding: 0; }

/* ── Title ── */
.ct { text-align: center; font-weight: bold; font-size: 8pt; padding: 3pt 0 2.5pt; border-bottom: 0.5pt solid #333; }

/* ── Header ── */
.hd { padding: 4pt 5pt 3pt; }
.hd-t { width: 100%; border-collapse: collapse; }
.hd-t td { vertical-align: middle; padding: 0; }
.bank-lbl {
    background: #009999; color: #fff;
    font-size: 17pt; font-weight: bold; letter-spacing: -2pt;
    padding: 0 5pt 1pt; display: inline-block; line-height: 1;
}
.uni-n { font-size: 8pt; font-weight: bold; color: #111; line-height: 1.2; text-align: center; }
.uni-s { font-size: 4.5pt; color: #666; margin-top: 1.5pt; text-align: center; }

/* ── Barcode ── */
.bc { padding: 2pt 5pt 0; }

/* ── SN + Date ── */
.sn { padding: 2pt 5pt 2pt; border-top: 0.5pt solid #888; border-bottom: 0.8pt solid #333; }
.sn-t { width: 100%; border-collapse: collapse; }
.sn-t td { font-size: 7.5pt; padding: 0; }

/* ── Info rows ── */
.if { padding: 2pt 5pt 0; }
.if-t { width: 100%; border-collapse: collapse; }
.if-t td { font-size: 7pt; padding: 0.65pt 0; vertical-align: top; }
.lbl { font-weight: bold; white-space: nowrap; width: 34%; }

/* ── Fee table ── */
.ft { padding: 2pt 5pt; }
.ft-t { width: 100%; border-collapse: collapse; }
.ft-t thead td { background: #111; color: #fff; font-size: 6.5pt; font-weight: bold; padding: 2.5pt 3pt; border: 0.5pt solid #111; }
.ft-t thead td.r { text-align: right; }
.ft-t tbody td { font-size: 7.5pt; padding: 1.8pt 3pt; border: 0.5pt solid #ccc; vertical-align: middle; }
.ft-t tbody td.r { text-align: right; }
.ft-t tbody td.n { text-align: center; color: #009999; font-weight: bold; }
.ft-t tr.tot td { font-weight: bold; background: #f0f0f0; border-top: 0.8pt solid #333; font-size: 8pt; }

/* ── InWords ── */
.iw { padding: 2pt 5pt 1pt; font-size: 7.5pt; }
.iw-v { color: #1a56db; font-weight: bold; }

/* ── Depositor full-width lines ── */
.dep { padding: 1pt 5pt; }
.dep-lbl { font-size: 7pt; font-weight: bold; }
.dep-line { width: 100%; border-collapse: collapse; margin-bottom: 3pt; }
.dep-line td { border-bottom: 0.5pt solid #555; height: 11pt; font-size: 0; }

/* ── Ref No ── */
.ref { padding: 1pt 5pt 2pt; font-size: 7pt; }

/* ── Instructions ── */
.ins { padding: 2pt 5pt; border-top: 0.5pt dashed #bbb; }
.ins-v { font-size: 5.5pt; color: #333; line-height: 1.65; }

/* ── Fee Paid stamp area ── */
.fp { padding: 2pt 5pt 4pt; border-top: 0.5pt solid #ccc; }
.fp-lbl { font-size: 5pt; color: #888; margin-bottom: 2pt; }
.fp-box { border: 0.8pt solid #aaa; height: 20pt; text-align: center; vertical-align: middle; }
.fp-paid { font-size: 8.5pt; font-weight: bold; color: #1a7a1a; letter-spacing: 1.5pt; opacity: 0.7; }
.fp-empty { font-size: 6pt; color: #ccc; letter-spacing: 0.5pt; }
</style>
</head>
<body>
@php
use App\Models\CollegeSetting;
use Carbon\Carbon;

$s        = $payment->student;
$sName    = $s?->name ?? '—';
$sRoll    = $s?->roll_number ?? '—';
$sProgram = $s?->academicProgram?->name ?? '—';
$sSem     = $payment->semester_number ?? '—';
$sSession = $payment->academicYear?->name ?? '—';

$due  = (float)($payment->amount_due      ?? 0);
$fine = (float)($payment->fine_amount     ?? 0);
$disc = (float)($payment->discount_amount ?? 0);
$net  = $due + $fine - $disc;

$stVal    = $payment->payment_status instanceof \BackedEnum ? $payment->payment_status->value : (string)$payment->payment_status;
$isPaid   = strtolower($stVal) === 'paid';
$paidDate = $isPaid && $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d-m-Y') : '';
$dueDate  = $payment->due_date ? Carbon::parse($payment->due_date)->format('d-m-Y') : '';

$bankName   = CollegeSetting::get('fee_bank_name',           'HBL');
$bankAcct   = CollegeSetting::get('fee_bank_account',        '—');
$bankTitle  = CollegeSetting::get('fee_bank_account_title',  CollegeSetting::get('college_name', 'JDCA'));
$bankBranch = CollegeSetting::get('fee_bank_branch',         '');
$billPfx    = CollegeSetting::get('fee_challan_1bill_prefix', '');
$refPfx     = CollegeSetting::get('fee_challan_ref_prefix',   'JDCA');
$sn         = $payment->challan_number ?? '—';
$refNo      = $refPfx . '-' . $sn;
$billNo     = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;

$college      = CollegeSetting::get('college_name',        'Jinnah Degree College Astore');
$collegeShort = CollegeSetting::get('college_short_name',  'JDCA');
$city         = CollegeSetting::get('college_city',        'Astore, Gilgit-Baltistan');
$phone        = CollegeSetting::get('college_phone',       '');
$bankBrStr    = $bankBranch ? ", $bankBranch" : '';

$feeLabel = $payment->feeStructure?->title
    ?? ucwords(str_replace('_', ' ', $payment->fee_type instanceof \BackedEnum
        ? $payment->fee_type->value : ($payment->fee_type ?? 'Semester Fee')));

// Amount in words
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

// ── Code 128 B Barcode ──────────────────────────────────────────────
// Full encoding table: code values 0-102 → [b1,s1,b2,s2,b3,s3]
$c128 = [
    [2,1,2,2,2,2],[2,2,2,1,2,2],[2,2,2,2,2,1],[1,2,1,2,2,3],
    [1,2,1,3,2,2],[1,3,1,2,2,2],[1,2,2,2,1,3],[1,2,2,3,1,2],
    [1,3,2,2,1,2],[2,2,1,2,1,3],[2,2,1,3,1,2],[2,3,1,2,1,2],
    [1,1,2,2,3,2],[1,2,2,1,3,2],[1,2,2,2,3,1],[1,1,3,2,2,2],
    [1,2,3,1,2,2],[1,2,3,2,2,1],[2,2,3,2,1,1],[2,2,1,1,3,2],
    [2,2,1,2,3,1],[2,1,3,2,1,2],[2,2,3,1,1,2],[3,1,2,1,3,1],
    [3,1,1,2,2,2],[3,2,1,1,2,2],[3,2,1,2,2,1],[3,1,2,2,1,2],
    [3,2,2,1,1,2],[3,2,2,2,1,1],[2,1,2,1,2,3],[2,1,2,3,2,1],
    [2,3,2,1,2,1],[1,1,1,3,2,3],[1,3,1,1,2,3],[1,3,1,3,2,1],
    [1,1,2,3,1,3],[1,3,2,3,1,1],[2,1,3,3,1,1],[1,1,3,1,2,3],
    [1,1,3,3,2,1],[1,3,3,1,2,1],[3,1,3,1,2,1],[2,1,1,3,3,1],
    [2,3,1,1,3,1],[1,1,2,1,3,3],[1,1,2,3,3,1],[2,1,3,1,1,3],
    [2,1,3,3,1,1],[2,3,3,1,1,1],[1,1,3,1,3,2],[3,1,3,1,1,2],
    [3,1,1,1,2,3],[3,3,1,1,2,1],[3,1,2,1,1,3],[3,1,2,3,1,1],
    [3,3,2,1,1,1],[3,1,4,1,1,1],[2,2,1,4,1,1],[4,3,1,1,1,1],
    [1,1,1,2,2,4],[1,1,1,4,2,2],[1,2,1,1,2,4],[1,2,1,4,2,1],
    [1,4,1,1,2,2],[1,4,1,2,2,1],[1,1,2,2,1,4],[1,1,2,4,1,2],
    [1,2,2,1,1,4],[1,2,2,4,1,1],[1,4,2,1,1,2],[1,4,2,2,1,1],
    [2,4,1,2,1,1],[2,2,1,1,1,4],[4,1,3,1,1,1],[2,4,1,1,1,2],
    [1,3,4,1,1,1],[1,1,1,2,4,2],[1,2,1,1,4,2],[1,2,1,2,4,1],
    [1,1,4,2,1,2],[1,2,4,1,1,2],[1,2,4,2,1,1],[4,1,1,2,1,2],
    [4,2,1,1,1,2],[4,2,1,2,1,1],[2,1,2,1,4,1],[2,1,4,1,2,1],
    [4,1,2,1,2,1],[1,1,1,1,4,3],[1,1,1,3,4,1],[1,3,1,1,4,1],
    [1,1,4,1,1,3],[1,1,4,3,1,1],[4,1,1,1,1,3],[4,1,1,3,1,1],
    [1,1,3,1,4,1],[1,1,4,1,3,1],[3,1,1,1,4,1],[4,1,1,1,3,1],
    [2,1,1,4,1,2],[2,1,1,2,1,4],[2,1,1,2,3,2],
];
$bcStart = [2,1,1,4,1,2];   // START B
$bcStop  = [2,3,3,1,1,1,2]; // STOP (7 elements, ends on bar)

$mods  = $bcStart;
$cksum = 104; // START B value
$pos   = 1;
$bcTxt = preg_replace('/[^\x20-\x7E]/', ' ', $sn);
foreach (str_split(substr($bcTxt, 0, 20)) as $ch) {
    $cv    = max(0, min(95, ord($ch) - 32));
    $mods  = array_merge($mods, $c128[$cv]);
    $cksum += $pos++ * $cv;
}
$mods = array_merge($mods, $c128[$cksum % 103], $bcStop);

$sc = 1.2; $bx = 4; $bars = [];
foreach ($mods as $mi => $mw) {
    $sw = round($mw * $sc, 2);
    if ($mi % 2 === 0) $bars[] = ['x' => round($bx,2), 'w' => $sw];
    $bx += $sw;
}
$bcW = round($bx + 4, 2);
@endphp

<table class="main">
<tbody>
<tr>
@foreach(['Bank Copy','Accounts Copy','Student Copy'] as $copyLabel)
<td>
<table class="copy">
<tbody>

{{-- ── Title ── --}}
<tr><td class="ct">{{ $copyLabel }}</td></tr>

{{-- ── Header: Bank | Uni Name | Crest ── --}}
<tr><td class="hd">
  <table class="hd-t">
  <tr>
    <td style="width:20%;vertical-align:middle;">
      <span class="bank-lbl">{{ strtoupper(substr($bankName,0,3)) }}</span>
    </td>
    <td style="width:55%;vertical-align:middle;padding:0 4pt;">
      <div class="uni-n">{{ $college }}</div>
      <div class="uni-s">{{ $city }}{{ $phone ? ' &bull; '.$phone : '' }}</div>
    </td>
    <td style="width:25%;vertical-align:middle;text-align:right;">
      <svg width="36" height="36" viewBox="0 0 36 36">
        <circle cx="18" cy="18" r="17" fill="#eef2ff" stroke="#1e3a8a" stroke-width="1.5"/>
        <circle cx="18" cy="18" r="12.5" fill="none" stroke="#1e3a8a" stroke-width="0.6" stroke-dasharray="2,1.5"/>
        <text x="18" y="16" text-anchor="middle" font-size="6" font-weight="bold" fill="#1e3a8a" font-family="DejaVu Sans">{{ $collegeShort }}</text>
        <text x="18" y="24" text-anchor="middle" font-size="3.8" fill="#1e3a8a" font-family="DejaVu Sans">EST. 2010</text>
      </svg>
    </td>
  </tr>
  </table>
</td></tr>

{{-- ── Code 128 B Barcode ── --}}
<tr><td class="bc">
  <svg width="68%" height="30" viewBox="0 0 {{ $bcW }} 30" preserveAspectRatio="none">
    @foreach($bars as $b)
      <rect x="{{ $b['x'] }}" y="0" width="{{ $b['w'] }}" height="24" fill="#111"/>
    @endforeach
    <text x="{{ round($bcW/2,2) }}" y="29" text-anchor="middle" font-size="5.5" fill="#333" font-family="DejaVu Sans">{{ $sn }}</text>
  </svg>
</td></tr>

{{-- ── SN# + Date ── --}}
<tr><td class="sn">
  <table class="sn-t">
  <tr>
    <td style="width:50%;"><b>SN#:</b>{{ $sn }}</td>
    <td style="width:50%;text-align:right;"><b>Date:</b>{{ $dateLabel }}</td>
  </tr>
  </table>
</td></tr>

{{-- ── Account + Student Info ── --}}
<tr><td class="if">
  <table class="if-t">
    <tr><td class="lbl">Account:</td><td>{{ $bankAcct }}</td></tr>
    <tr><td class="lbl">Title:</td><td>{{ $bankTitle }}</td></tr>
    <tr><td class="lbl">Student Name:</td><td>{{ $sName }}</td></tr>
    <tr><td class="lbl">Reg No:</td><td>{{ $sRoll }}</td></tr>
    <tr><td class="lbl">Program:</td><td>{{ $sProgram }}</td></tr>
    <tr><td class="lbl">Semester No:</td><td>{{ $sSem }}</td></tr>
    <tr><td class="lbl">Semester Name:</td><td>{{ $sSession }}</td></tr>
    <tr><td class="lbl">Remarks:</td><td>&nbsp;</td></tr>
  </table>
</td></tr>

{{-- ── Fee Table ── --}}
<tr><td class="ft">
  <table class="ft-t">
    <thead>
      <tr>
        <td style="width:16pt;">S#.</td>
        <td>Particular</td>
        <td class="r" style="width:55pt;">Rs.</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="n">1.</td>
        <td>{{ $feeLabel }}</td>
        <td class="r">{{ number_format($due, 0) }}</td>
      </tr>
      @if($fine > 0)
      <tr>
        <td class="n">2.</td>
        <td>Late Surcharge{{ $dueDate ? ' After ('.$dueDate.')' : '' }}</td>
        <td class="r">{{ number_format($fine, 2) }}</td>
      </tr>
      @endif
      @if($disc > 0)
      <tr>
        <td class="n">{{ $fine > 0 ? '3.' : '2.' }}</td>
        <td>Discount / Concession</td>
        <td class="r">- {{ number_format($disc, 0) }}</td>
      </tr>
      @endif
      <tr class="tot">
        <td colspan="2" class="r">Total Payable</td>
        <td class="r">{{ number_format($net, 0) }}</td>
      </tr>
    </tbody>
  </table>
</td></tr>

{{-- ── InWords ── --}}
<tr><td class="iw">
  <b>InWords:</b> <span class="iw-v">{{ $inWords }}</span>
</td></tr>

{{-- ── Depositor Mobile No (full-width underline) ── --}}
<tr><td class="dep">
  <div class="dep-lbl">Depositor Mobile No:</div>
  <table class="dep-line"><tr><td>&nbsp;</td></tr></table>
  <div class="dep-lbl" style="margin-top:2pt;">CNIC #:</div>
  <table class="dep-line"><tr><td>&nbsp;</td></tr></table>
</td></tr>

{{-- ── Ref No ── --}}
<tr><td class="ref">
  <b>Ref No:</b> {{ $refNo }}
  @if($billNo) &nbsp;&nbsp;<b>Consumer No:</b> {{ $consumerNo }} @endif
</td></tr>

{{-- ── Instructions ── --}}
<tr><td class="ins">
  <div class="ins-v">
    &bull; You can submit this fee voucher at any {{ $bankName }} branch or simply pay using the <b>{{ $bankName }} Mobile App</b>. Just Tap on More &gt;&gt; Education &gt;&gt;Select <b>{{ $college }}</b> from the List.<br>
    @if($billNo)
    &bull; For Payment using 1-Bill. use the consumer number <b>{{ $consumerNo }}</b><br>
    @endif
    &bull; Keep this copy as proof of payment after bank stamp / online receipt.<br>
    &bull; Late fine applicable after due date. Expired challan will not be accepted.
  </div>
</td></tr>

{{-- ── Fee Paid Stamp Area ── --}}
<tr><td class="fp">
  <div class="fp-lbl">For Bank Use / Official Stamp:</div>
  <table style="width:100%;border-collapse:collapse;">
  <tr>
    <td class="fp-box">
      @if($isPaid)
        <div class="fp-paid">&#10003; FEE PAID</div>
      @else
        <div class="fp-empty">Fee Paid</div>
      @endif
    </td>
  </tr>
  </table>
</td></tr>

</tbody>
</table>
</td>
@endforeach
</tr>
</tbody>
</table>
</body>
</html>
