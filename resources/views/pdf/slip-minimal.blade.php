<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Fee Challan - Minimal</title>
<style>
@page { margin: 6mm 8mm; size: A4 portrait; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: "DejaVu Sans", sans-serif; font-size: 7.5pt; line-height: 1.3; color: #111; }

.page-wrap { width: 100%; }
.copy-section { margin-bottom: 5mm; page-break-inside: avoid; border: 0.5pt solid #ccc; }

/* Header */
.hdr { padding: 5pt 7pt 4pt; border-bottom: 1.5pt solid #111; }
.hdr-t { width: 100%; border-collapse: collapse; }
.college-name { font-size: 10pt; font-weight: bold; }
.college-sub  { font-size: 6.5pt; color: #555; margin-top: 1pt; }

/* Title bar */
.title-bar { text-align: center; font-size: 7pt; font-weight: bold; letter-spacing: 2pt; text-transform: uppercase; padding: 2pt; border-bottom: 0.5pt solid #333; background: #f5f5f5; }

/* SN + Date */
.sn-row { padding: 2pt 7pt; border-bottom: 0.5pt solid #ddd; }
.sn-t { width: 100%; border-collapse: collapse; }
.sn-t td { font-size: 7pt; padding: 0; }

/* Student Info */
.info-t { width: 100%; border-collapse: collapse; padding: 0 7pt; }
.info-t td { font-size: 7pt; padding: 1pt 0; border-bottom: 0.3pt dotted #ddd; vertical-align: top; }
.info-lbl { font-weight: bold; width: 30%; color: #333; }
.info-block { padding: 2pt 7pt; }

/* Fee Table */
.fee-block { padding: 2pt 7pt; }
.fee-t { width: 100%; border-collapse: collapse; }
.fee-t thead td { font-size: 7pt; font-weight: bold; padding: 2pt 4pt; border: 0.5pt solid #333; background: #111; color: #fff; }
.fee-t tbody td { font-size: 7pt; padding: 1.5pt 4pt; border: 0.3pt solid #ccc; }
.fee-t tbody td.r { text-align: right; }
.fee-t tbody td.n { text-align: center; color: #444; }
.fee-t tr.tot td { font-weight: bold; border-top: 1pt solid #111; }

/* InWords */
.iw-row { padding: 2pt 7pt; font-size: 7pt; border-top: 0.3pt solid #ccc; }

/* Depositor */
.dep-block { padding: 2pt 7pt; }
.dep-t { width: 100%; border-collapse: collapse; }
.dep-t td { font-size: 6.5pt; }
.dep-line { border-bottom: 0.5pt solid #333; height: 10pt; display: block; }

/* Ref */
.ref-row { padding: 2pt 7pt; font-size: 6.5pt; border-top: 0.3pt dashed #ccc; }

/* Sig */
.sig-block { padding: 3pt 7pt; }
.sig-line-el { border-top: 0.5pt solid #333; width: 50%; margin-left: 50%; }

/* Instructions */
.ins-block { padding: 2pt 7pt; border-top: 0.4pt dashed #aaa; }
.ins-text { font-size: 5.5pt; color: #555; line-height: 1.6; }

/* Copy label */
.copy-label { text-align: right; font-size: 6pt; font-weight: bold; color: #555; letter-spacing: 0.5pt; padding: 1.5pt 7pt; border-top: 0.3pt solid #ccc; background: #f9f9f9; }

/* Footer */
.footer-row { padding: 1.5pt 7pt; border-top: 0.3pt solid #ccc; font-size: 5pt; color: #aaa; text-align: center; }
</style>
</head>
<body>
@php
use Carbon\Carbon;

$primaryColor = $template->primary_color ?? '#111111';
$accentColor  = $template->accent_color  ?? '#444444';
$textColor    = $template->text_color    ?? '#111111';

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
}

$college      = $template->college_name ?? \App\Models\CollegeSetting::get('college_name', 'Jinnah School & Degree College Astore');
$collegeSub   = $template->college_subtitle ?? 'Eidgah, Astore, Gilgit-Baltistan';
$bankName     = $template->bank_name ?? 'KCBL';
$bankAcct     = $template->bank_account ?? '—';
$bankTitle    = $template->bank_account_title ?? $college;
$bankBranch   = $template->bank_branch ?? '';
$refPfx       = $template->ref_prefix ?? 'JDCA';
$billPfx      = $template->bill_prefix ?? '';
$copies       = $template->copies ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $template->instructions ?? "• Submit at any {$bankName} branch.\n• Keep this copy as proof of payment.\n• Late fine applicable after due date.";
$footerText   = $template->footer_text ?? '';
$showInWords  = $template->show_in_words ?? true;
$showDep      = $template->show_depositor_fields ?? true;
$showRef      = $template->show_ref_no ?? true;
$showConsumer = $template->show_consumer_no ?? false;
$showSig      = $template->show_accountant_sig ?? true;
$feeMode      = $template->fee_display_mode ?? 'dynamic';
$feeItems     = $template->fee_items ?? [];

$refNo     = $refPfx . '-' . $sn;
$billNo    = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;
$dateLabel = $paidDate ?: date('d-m-Y');

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
@endphp

<div class="page-wrap">
@foreach($copies as $copyLabel)
<div class="copy-section">

  {{-- Header --}}
  <div class="hdr">
    <table class="hdr-t">
    <tr>
      <td style="width:75%;vertical-align:middle;">
        <div class="college-name">{{ $college }}</div>
        @if($collegeSub)
        <div class="college-sub">{{ $collegeSub }}</div>
        @endif
        <div class="college-sub" style="margin-top:1pt;">Bank: {{ $bankName }} &bull; A/C: {{ $bankAcct }} &bull; {{ $bankBranch }}</div>
      </td>
      <td style="width:25%;vertical-align:top;text-align:right;font-size:6pt;color:#555;">
        <b>S.No:</b> {{ $sn }}<br>
        <b>Date:</b> {{ $dateLabel }}
      </td>
    </tr>
    </table>
  </div>

  {{-- Title --}}
  <div class="title-bar">Fee Challan</div>

  {{-- Student Info --}}
  <div class="info-block">
    <table class="info-t">
      <tr>
        <td class="info-lbl">Student Name</td>
        <td>{{ $sName }}</td>
        <td class="info-lbl" style="width:22%;">Father Name</td>
        <td>{{ $sFather }}</td>
      </tr>
      <tr>
        <td class="info-lbl">Reg / Roll No</td>
        <td>{{ $sRoll }}</td>
        <td class="info-lbl">Program</td>
        <td>{{ $sProgram }}</td>
      </tr>
      <tr>
        <td class="info-lbl">Semester No</td>
        <td>{{ $sSem }}</td>
        <td class="info-lbl">Session</td>
        <td>{{ $sSession }}</td>
      </tr>
    </table>
  </div>

  {{-- Fee Table --}}
  <div class="fee-block">
    <table class="fee-t">
      <thead>
        <tr>
          <td style="width:14pt;">S#.</td>
          <td>Particular</td>
          <td class="r" style="width:55pt;">Rs.</td>
        </tr>
      </thead>
      <tbody>
      @if($feeMode === 'static' && count($feeItems) > 0)
        @foreach($feeItems as $idx => $item)
        <tr>
          <td class="n">{{ $idx + 1 }}</td>
          <td>{{ $item['label'] ?? '' }}</td>
          <td class="r">&nbsp;</td>
        </tr>
        @endforeach
      @else
        <tr><td class="n">1</td><td>{{ $feeLabel }}</td><td class="r">{{ number_format($due, 0) }}</td></tr>
        @if($fine > 0)
        <tr><td class="n">2</td><td>Late Surcharge</td><td class="r">{{ number_format($fine, 2) }}</td></tr>
        @endif
        @if($disc > 0)
        <tr><td class="n">{{ $fine > 0 ? 3 : 2 }}</td><td>Discount / Concession</td><td class="r">- {{ number_format($disc, 0) }}</td></tr>
        @endif
      @endif
        <tr class="tot"><td colspan="2" class="r">Total Payable</td><td class="r">{{ number_format($net, 0) }}</td></tr>
      </tbody>
    </table>
  </div>

  @if($showInWords)
  <div class="iw-row"><b>Amount in Words:</b> {{ $inWords }}</div>
  @endif

  @if($showDep)
  <div class="dep-block">
    <table style="width:100%;border-collapse:collapse;"><tr>
      <td style="width:48%;font-size:6.5pt;">
        <b>Depositor Mobile No:</b>
        <span class="dep-line" style="display:block;border-bottom:0.5pt solid #333;height:10pt;margin-top:1pt;">&nbsp;</span>
      </td>
      <td style="width:4%;"></td>
      <td style="width:48%;font-size:6.5pt;">
        <b>CNIC #:</b>
        <span class="dep-line" style="display:block;border-bottom:0.5pt solid #333;height:10pt;margin-top:1pt;">&nbsp;</span>
      </td>
    </tr></table>
  </div>
  @endif

  @if($showRef)
  <div class="ref-row">
    <b>Ref No:</b> {{ $refNo }}
    @if($showConsumer && $billNo) &nbsp;&nbsp;<b>Consumer No:</b> {{ $consumerNo }} @endif
  </div>
  @endif

  @if($showSig)
  <div class="sig-block">
    <div style="font-size:5.5pt;color:#666;text-align:right;">Accountant Signature:</div>
    <div class="sig-line-el"></div>
  </div>
  @endif

  <div class="ins-block">
    <div class="ins-text">{{ $instructions }}</div>
  </div>

  <div class="copy-label">{{ strtoupper($copyLabel) }}</div>

  @if($footerText)
  <div class="footer-row">{{ $footerText }}</div>
  @endif
</div>
@endforeach
</div>
</body>
</html>
