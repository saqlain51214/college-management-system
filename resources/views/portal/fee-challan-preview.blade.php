<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fee Challan — {{ $payment->challan_number }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 11px;
    background: #e5e7eb;
    color: #111;
    min-height: 100vh;
}

/* ── Top action bar ── */
.topbar {
    background: #1a3a4a;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    flex-wrap: wrap;
    gap: 8px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,.25);
}
.topbar-title { font-size: 14px; font-weight: 700; }
.topbar-sub   { font-size: 10px; opacity: .7; margin-top: 2px; }
.topbar-actions { display: flex; gap: 8px; }
.btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 6px; font-size: 11px;
    font-weight: 600; cursor: pointer; border: none; text-decoration: none;
}
.btn-white   { background: #fff; color: #1a3a4a; }
.btn-outline { background: transparent; color: #fff; border: 1.5px solid rgba(255,255,255,.5); }

/* ── Challan page wrapper ── */
.page-wrap {
    width: 100%;
    max-width: 1100px;
    margin: 20px auto 40px;
    padding: 0 12px;
}

/* ── 3-column row ── */
.copies-row {
    display: flex;
    gap: 6px;
    align-items: flex-start;
}

/* ── Individual copy ── */
.copy {
    flex: 1;
    border: 1.5px solid #222;
    background: #fff;
    font-size: 10.5px;
    line-height: 1.35;
    min-width: 0;
}

/* ── Copy title ── */
.ct {
    text-align: center;
    font-weight: 700;
    font-size: 11px;
    padding: 5px 0 4px;
    border-bottom: 0.5px solid #333;
    letter-spacing: 0.3px;
    color: #fff;
}

/* ── Header ── */
.hd {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 8px 5px;
    border-bottom: 0.5px solid #ddd;
}
.bank-logo-wrap { flex-shrink: 0; }
.bank-lbl {
    color: #fff;
    font-size: 20px;
    font-weight: 900;
    letter-spacing: -2.5px;
    padding: 0 6px 1px;
    line-height: 1;
    flex-shrink: 0;
}
.uni-block {
    flex: 1;
    text-align: center;
    min-width: 0;
}
.uni-n {
    font-size: 9.5px;
    font-weight: 700;
    color: #111;
    line-height: 1.2;
}
.uni-s {
    font-size: 7px;
    color: #666;
    margin-top: 2px;
}
.crest-wrap { flex-shrink: 0; }

/* ── Barcode ── */
.bc { padding: 3px 8px 1px; }

/* ── SN + Date ── */
.sn-row {
    padding: 3px 8px;
    border-top: 0.5px solid #888;
    border-bottom: 0.8px solid #333;
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    font-weight: 700;
}

/* ── Info rows ── */
.if { padding: 3px 8px 0; }
.if table { width: 100%; border-collapse: collapse; }
.if table td { font-size: 10px; padding: 1px 0; vertical-align: top; }
.if table td.lb { font-weight: 700; width: 36%; white-space: nowrap; }

/* ── Fee table ── */
.ft { padding: 3px 8px; }
.ft table { width: 100%; border-collapse: collapse; }
.ft table thead td { color: #fff; font-size: 9px; font-weight: 700; padding: 3px 4px; border: 0.5px solid #333; }
.ft table thead td.r { text-align: right; }
.ft table tbody td { font-size: 10.5px; padding: 2.5px 4px; border: 0.5px solid #ccc; }
.ft table tbody td.r { text-align: right; }
.ft table tbody td.n { text-align: center; font-weight: 700; }
.ft table tr.tot td { font-weight: 700; background: #f0f0f0; border-top: 1px solid #333; font-size: 11px; }

/* ── InWords ── */
.iw { padding: 3px 8px 2px; font-size: 10.5px; border-top: 0.5px solid #eee; }

/* ── Depositor lines ── */
.dep { padding: 2px 8px; }
.dep-row { display: flex; gap: 8px; }
.dep-col { flex: 1; }
.dep-lbl { font-size: 10px; font-weight: 700; margin-bottom: 1px; }
.dep-line { border-bottom: 0.8px solid #555; height: 14px; margin-bottom: 4px; }

/* ── Ref No ── */
.ref { padding: 1px 8px 3px; font-size: 10px; border-top: 0.5px dashed #ccc; }

/* ── Instructions ── */
.ins {
    padding: 3px 8px 3px;
    border-top: 0.5px dashed #bbb;
}
.ins-v { font-size: 8px; color: #444; line-height: 1.65; }

/* ── Accountant sig ── */
.sig { padding: 2px 8px 4px; border-top: 0.5px solid #ccc; text-align: right; }
.sig-line { display: inline-block; border-top: 0.8px solid #333; width: 55%; margin-top: 12px; font-size: 8px; color: #666; }

/* ── Fee Paid stamp ── */
.fp { padding: 3px 8px 6px; border-top: 0.5px solid #ccc; }
.fp-lbl { font-size: 7.5px; color: #999; margin-bottom: 3px; }
.fp-box {
    border: 1px solid #aaa;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.fp-paid { font-size: 11px; font-weight: 700; color: #1a7a1a; letter-spacing: 2px; opacity: 0.7; }
.fp-empty { font-size: 8px; color: #ccc; }

/* ── Footer ── */
.ft-txt { font-size: 7px; color: #bbb; text-align: center; padding: 2px 8px 4px; border-top: 0.5px solid #eee; }

/* ── Print styles ── */
@media print {
    body { background: #fff; }
    .topbar { display: none; }
    .page-wrap { max-width: 100%; margin: 0; padding: 0; }
    .copies-row { gap: 4px; }
    .copy { border-color: #333; }
}
</style>
</head>
<body>

@php
use App\Models\CollegeSetting;
use Carbon\Carbon;

// ── Active template (passed from controller) ──────────────────────────
$t = $template; // may be null

$primaryColor = $t?->primary_color ?? '#009999';
$accentColor  = $t?->accent_color  ?? '#1a56db';

// ── Payment data ──────────────────────────────────────────────────────
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

// ── Template-aware settings (fall back to CollegeSetting) ─────────────
$college      = $t?->college_name       ?? CollegeSetting::get('college_name',       'Jinnah School & Degree College Astore');
$collegeSub   = $t?->college_subtitle   ?? CollegeSetting::get('college_subtitle',   '');
$collegeShort = $t?->college_short_name ?? CollegeSetting::get('college_short_name', 'JDCA');
$city         = CollegeSetting::get('college_city', 'Astore, Gilgit-Baltistan');
$phone        = CollegeSetting::get('college_phone', '');

$bankName   = $t?->bank_name          ?? CollegeSetting::get('fee_bank_name',            'HBL');
$bankAcct   = $t?->bank_account       ?? CollegeSetting::get('fee_bank_account',          '—');
$bankTitle  = $t?->bank_account_title ?? CollegeSetting::get('fee_bank_account_title',    $college);
$bankBranch = $t?->bank_branch        ?? CollegeSetting::get('fee_bank_branch',           '');
$bankLogo   = $t?->bank_logo_path     ?? null;
$collegeLogo= $t?->logo_path          ?? null;
$refPfx     = $t?->ref_prefix         ?? CollegeSetting::get('fee_challan_ref_prefix',   'JDCA');
$billPfx    = $t?->bill_prefix        ?? CollegeSetting::get('fee_challan_1bill_prefix', '');
$copies     = $t?->copies             ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $t?->instructions     ?? "You can submit this fee voucher at any {$bankName} branch or simply pay using the {$bankName} Mobile App.\nKeep this copy as proof of payment after bank stamp / online receipt.\nLate fine applicable after due date. Expired challan will not be accepted.";
$footerText   = $t?->footer_text      ?? '';
$showBarcode  = $t?->show_barcode       ?? true;
$showInWords  = $t?->show_in_words      ?? true;
$showDep      = $t?->show_depositor_fields ?? true;
$showRef      = $t?->show_ref_no        ?? true;
$showConsumer = $t?->show_consumer_no   ?? true;
$showSig      = $t?->show_accountant_sig ?? false;
$feeMode      = $t?->fee_display_mode   ?? 'dynamic';
$feeItems     = $t?->fee_items          ?? [];

$sn         = $payment->challan_number ?? '—';
$refNo      = $refPfx . '-' . $sn;
$billNo     = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;
$dateLabel  = $paidDate ?: '____________________';

$feeLabel = $payment->feeStructure?->title
    ?? ucwords(str_replace('_', ' ', $payment->fee_type instanceof \BackedEnum
        ? $payment->fee_type->value : ($payment->fee_type ?? 'Semester Fee')));

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

// ── Code 128 B barcode ────────────────────────────────────────────────
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
$mods  = [2,1,1,4,1,2]; $cksum = 104; $pos = 1;
foreach (str_split(substr(preg_replace('/[^\x20-\x7E]/',' ',$sn), 0, 20)) as $ch) {
    $cv = max(0, min(95, ord($ch) - 32));
    $mods = array_merge($mods, $c128[$cv]);
    $cksum += $pos++ * $cv;
}
$mods = array_merge($mods, $c128[$cksum % 103], [2,3,3,1,1,1,2]);
$sc = 1.5; $bx = 4; $bars = [];
foreach ($mods as $mi => $mw) {
    $sw = round($mw * $sc, 2);
    if ($mi % 2 === 0) $bars[] = ['x' => round($bx,2), 'w' => $sw];
    $bx += $sw;
}
$bcW = round($bx + 4, 2);
@endphp

{{-- ── Top action bar ── --}}
<div class="topbar" style="background:{{ $primaryColor }};">
  <div>
    <div class="topbar-title">Fee Payment Challan</div>
    <div class="topbar-sub">SN# {{ $sn }} &nbsp;·&nbsp; {{ $sName }} &nbsp;·&nbsp; {{ $sProgram }}</div>
  </div>
  <div class="topbar-actions">
    <a href="{{ route('portal.fees') }}" class="btn btn-outline">
      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
    <button onclick="window.print()" class="btn btn-white" style="color:{{ $primaryColor }};">
      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
      Print
    </button>
    <a href="{{ route('portal.fees.challan', $payment) }}" target="_blank" class="btn btn-white" style="color:{{ $primaryColor }};">
      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Download PDF
    </a>
  </div>
</div>

{{-- ── 3-column challan ── --}}
<div class="page-wrap">
  <div class="copies-row">
    @foreach($copies as $copyLabel)
    <div class="copy">

      {{-- Title bar ── --}}
      <div class="ct" style="background:{{ $primaryColor }};">{{ $copyLabel }}</div>

      {{-- Header: Bank logo | College name | College logo/crest ── --}}
      <div class="hd">

        {{-- LEFT: bank logo or text pill ── --}}
        <div class="bank-logo-wrap">
          @if($bankLogo)
            <img src="{{ asset('storage/' . $bankLogo) }}" style="max-height:36px;max-width:60px;display:block;object-fit:contain;"/>
          @else
            <span class="bank-lbl" style="background:{{ $primaryColor }};">{{ strtoupper(substr($bankName,0,3)) }}</span>
          @endif
          @if($bankBranch)
            <div style="font-size:7px;color:#666;text-align:center;margin-top:2px;">{{ $bankBranch }}</div>
          @endif
        </div>

        {{-- CENTER: college name ── --}}
        <div class="uni-block">
          <div class="uni-n" style="color:{{ $primaryColor }};">{{ $college }}</div>
          @if($collegeSub)
            <div class="uni-s">{{ $collegeSub }}</div>
          @else
            <div class="uni-s">{{ $city }}{{ $phone ? ' · '.$phone : '' }}</div>
          @endif
        </div>

        {{-- RIGHT: college logo or SVG crest ── --}}
        <div class="crest-wrap">
          @if($collegeLogo)
            <img src="{{ asset('storage/' . $collegeLogo) }}" style="max-height:40px;max-width:40px;display:block;object-fit:contain;"/>
          @else
            <svg width="38" height="38" viewBox="0 0 38 38" style="opacity:0.6;">
              <circle cx="19" cy="19" r="18" fill="none" stroke="{{ $primaryColor }}" stroke-width="1.5"/>
              <circle cx="19" cy="19" r="13" fill="none" stroke="{{ $primaryColor }}" stroke-width="0.7" stroke-dasharray="2.5,2"/>
              <polygon points="19,6 27,11 19,16 11,11" fill="{{ $primaryColor }}" opacity="0.75"/>
              <text x="19" y="25" text-anchor="middle" font-size="6.5" font-weight="bold" fill="{{ $primaryColor }}" font-family="Arial">{{ $collegeShort }}</text>
              <text x="19" y="33" text-anchor="middle" font-size="4" fill="{{ $primaryColor }}" font-family="Arial" opacity="0.8">EST. 2010</text>
            </svg>
          @endif
        </div>
      </div>

      @if($showBarcode)
      {{-- Code 128 B barcode ── --}}
      <div class="bc">
        <svg width="70%" height="34" viewBox="0 0 {{ $bcW }} 34" preserveAspectRatio="none" style="display:block;">
          @foreach($bars as $b)
            <rect x="{{ $b['x'] }}" y="0" width="{{ $b['w'] }}" height="26" fill="#111"/>
          @endforeach
          <text x="{{ round($bcW/2,2) }}" y="33" text-anchor="middle" font-size="6" fill="#333" font-family="Arial">{{ $sn }}</text>
        </svg>
      </div>
      @endif

      {{-- SN# + Date ── --}}
      <div class="sn-row">
        <span><strong>SN#:</strong>{{ $sn }}</span>
        <span><strong>Date:</strong>{{ $dateLabel }}</span>
      </div>

      {{-- Account + Student info ── --}}
      <div class="if">
        <table>
          <tr><td class="lb">Account:</td><td>{{ $bankAcct }}</td></tr>
          <tr><td class="lb">Title:</td><td>{{ $bankTitle }}</td></tr>
          <tr><td class="lb">Student Name:</td><td>{{ $sName }}</td></tr>
          <tr><td class="lb">Reg No:</td><td>{{ $sRoll }}</td></tr>
          <tr><td class="lb">Program:</td><td>{{ $sProgram }}</td></tr>
          <tr><td class="lb">Semester No:</td><td>{{ $sSem }}</td></tr>
          <tr><td class="lb">Session:</td><td>{{ $sSession }}</td></tr>
          <tr><td class="lb">Remarks:</td><td>&nbsp;</td></tr>
        </table>
      </div>

      {{-- Fee table ── --}}
      <div class="ft">
        <table>
          <thead>
            <tr>
              <td style="width:18px;background:{{ $primaryColor }};">S#.</td>
              <td style="background:{{ $primaryColor }};">Particular</td>
              <td class="r" style="width:60px;background:{{ $primaryColor }};">Rs.</td>
            </tr>
          </thead>
          <tbody>
            @if($feeMode === 'static' && count($feeItems) > 0)
              @foreach($feeItems as $idx => $item)
              <tr>
                <td style="text-align:center;color:{{ $primaryColor }};font-weight:700;">{{ $idx + 1 }}.</td>
                <td>{{ $item['label'] ?? '' }}</td>
                <td class="r">&nbsp;</td>
              </tr>
              @endforeach
            @else
              <tr>
                <td style="text-align:center;color:{{ $primaryColor }};font-weight:700;">1.</td>
                <td>{{ $feeLabel }}</td>
                <td class="r">{{ number_format($due, 0) }}</td>
              </tr>
              @if($fine > 0)
              <tr>
                <td style="text-align:center;color:{{ $primaryColor }};font-weight:700;">2.</td>
                <td>Late Surcharge{{ $dueDate ? ' After ('.$dueDate.')' : '' }}</td>
                <td class="r">{{ number_format($fine, 2) }}</td>
              </tr>
              @endif
              @if($disc > 0)
              <tr>
                <td style="text-align:center;color:{{ $primaryColor }};font-weight:700;">{{ $fine > 0 ? '3.' : '2.' }}</td>
                <td>Discount / Concession</td>
                <td class="r">- {{ number_format($disc, 0) }}</td>
              </tr>
              @endif
            @endif
            <tr class="tot">
              <td colspan="2" style="text-align:right;">Total Payable</td>
              <td class="r">{{ number_format($net, 0) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      @if($showInWords)
      {{-- InWords ── --}}
      <div class="iw">
        <strong>InWords:</strong> <span style="color:{{ $accentColor }};font-weight:700;">{{ $inWords }}</span>
      </div>
      @endif

      @if($showDep)
      {{-- Depositor Mobile + CNIC side by side ── --}}
      <div class="dep">
        <div class="dep-row">
          <div class="dep-col">
            <div class="dep-lbl">Depositor Mobile No:</div>
            <div class="dep-line"></div>
          </div>
          <div class="dep-col">
            <div class="dep-lbl">CNIC #:</div>
            <div class="dep-line"></div>
          </div>
        </div>
      </div>
      @endif

      @if($showRef)
      {{-- Ref No ── --}}
      <div class="ref">
        <strong>Ref No:</strong> {{ $refNo }}
        @if($showConsumer && $billNo) &nbsp;&nbsp;<strong>Consumer No:</strong> {{ $consumerNo }} @endif
      </div>
      @endif

      {{-- Instructions ── --}}
      <div class="ins">
        <div class="ins-v">
          @foreach(array_filter(array_map('trim', explode("\n", $instructions))) as $line)
            &bull; {{ $line }}<br>
          @endforeach
        </div>
      </div>

      @if($showSig)
      {{-- Accountant signature ── --}}
      <div class="sig">
        <span class="sig-line">Accountant Signature</span>
      </div>
      @endif

      {{-- Fee Paid stamp ── --}}
      <div class="fp">
        <div class="fp-lbl">For Bank Use / Official Stamp:</div>
        <div class="fp-box">
          @if($isPaid)
            <span class="fp-paid">&#10003; FEE PAID</span>
          @else
            <span class="fp-empty">Fee Paid</span>
          @endif
        </div>
      </div>

      @if($footerText)
      <div class="ft-txt">{{ $footerText }}</div>
      @endif

    </div>
    @endforeach
  </div>
</div>

</body>
</html>
