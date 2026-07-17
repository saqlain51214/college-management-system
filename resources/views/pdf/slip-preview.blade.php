<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preview: {{ $template->name }} — Fee Slip Template</title>
<style>
/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    font-size: 13px;
    background: #f1f5f9;
    color: #1e293b;
    min-height: 100vh;
}

/* ── Top Bar ── */
.topbar {
    background: #1e293b;
    color: #f8fafc;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.topbar-left { display: flex; align-items: center; gap: 12px; }
.topbar-title { font-size: 14px; font-weight: 600; }
.topbar-badge {
    background: {{ $template->is_active ? '#22c55e' : '#64748b' }};
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.topbar-actions { display: flex; align-items: center; gap: 10px; }
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: background 0.15s;
}
.btn-back   { background: #334155; color: #e2e8f0; }
.btn-back:hover { background: #475569; }
.btn-pdf    { background: #e11d48; color: #fff; }
.btn-pdf:hover { background: #be123c; }
.btn-print  { background: #0891b2; color: #fff; }
.btn-print:hover { background: #0e7490; }

/* ── Meta strip ── */
.meta-strip {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 20px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
}
.meta-item { display: flex; align-items: center; gap: 5px; }
.meta-item strong { color: #334155; }
.color-swatch {
    display: inline-block;
    width: 14px;
    height: 14px;
    border-radius: 3px;
    border: 1px solid rgba(0,0,0,0.15);
    vertical-align: middle;
}

/* ── Slip Stage ── */
.stage {
    padding: 30px 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* ── Copies Row ── */
.copies-row {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    flex-wrap: nowrap;
    overflow-x: auto;
}

/* ── Single Slip Card ── */
.slip-card {
    flex: 1;
    min-width: 280px;
    max-width: 400px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
}

/* ── Slip Header ── */
.slip-header {
    padding: 10px 12px 8px;
    border-bottom: 2px solid {{ $template->primary_color ?? '#009999' }};
}
.slip-copy-label {
    background: {{ $template->primary_color ?? '#009999' }};
    color: #fff;
    text-align: center;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 4px 0;
}
.slip-college-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px 4px;
}
.slip-crest {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
}
.slip-college-name {
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    color: {{ $template->primary_color ?? '#009999' }};
}
.slip-college-sub { font-size: 9px; color: #888; }

/* ── Divider ── */
.slip-divider { height: 1px; background: {{ $template->primary_color ?? '#009999' }}; margin: 0 12px; opacity: 0.3; }
.slip-divider-dark { height: 1px; background: #333; margin: 4px 12px; }

/* ── Info Section ── */
.slip-section { padding: 6px 12px; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px 8px; }
.info-item { font-size: 9px; }
.info-lbl { font-weight: 700; color: #555; font-size: 8.5px; }
.info-val { color: {{ $template->text_color ?? '#111' }}; }

/* ── SN Row ── */
.sn-row {
    display: flex;
    justify-content: space-between;
    font-size: 9.5px;
    font-weight: 600;
    padding: 5px 12px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

/* ── Fee Table ── */
.fee-table { width: 100%; border-collapse: collapse; font-size: 9px; }
.fee-table th {
    background: {{ $template->primary_color ?? '#009999' }};
    color: #fff;
    padding: 4px 6px;
    text-align: left;
    font-size: 8.5px;
}
.fee-table th.r { text-align: right; }
.fee-table td { padding: 3px 6px; border-bottom: 1px solid #f0f0f0; }
.fee-table td.r { text-align: right; }
.fee-table td.n { text-align: center; color: {{ $template->primary_color ?? '#009999' }}; font-weight: 700; }
.fee-table tr.total td { font-weight: 700; background: #f8f8f8; border-top: 1px solid #333; font-size: 10px; }
.fee-table .fee-col-sn { width: 20px; }
.fee-table .fee-col-rs { width: 60px; }

/* ── InWords ── */
.inwords {
    padding: 4px 12px;
    font-size: 9px;
    background: #f0fdf4;
    border-top: 1px dashed #bbf7d0;
    border-bottom: 1px dashed #bbf7d0;
}
.inwords strong { color: {{ $template->accent_color ?? '#1a56db' }}; }

/* ── Depositor Fields ── */
.dep-section { padding: 4px 12px; }
.dep-field { margin-bottom: 6px; }
.dep-label { font-size: 8px; font-weight: 600; color: #555; display: block; margin-bottom: 2px; }
.dep-underline { border-bottom: 1px solid #777; height: 12px; }

/* ── Ref Row ── */
.ref-row { padding: 3px 12px; font-size: 8.5px; color: #444; border-top: 1px dashed #ddd; }

/* ── Stamp / Sig Box ── */
.stamp-section { padding: 5px 12px 8px; }
.stamp-box {
    border: 1px solid #ccc;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    color: #ccc;
    letter-spacing: 1.5px;
    border-radius: 3px;
}
.stamp-paid { color: #15803d; border-color: #15803d; }

/* ── Instructions ── */
.instructions {
    padding: 4px 12px;
    border-top: 1px dashed #ddd;
    font-size: 8px;
    color: #555;
    line-height: 1.6;
    white-space: pre-wrap;
}

/* ── Footer ── */
.slip-footer {
    padding: 3px 12px;
    border-top: 1px solid #e2e8f0;
    font-size: 7.5px;
    color: #aaa;
    text-align: center;
}

/* ── Portrait Layout for classic/minimal ── */
.portrait-stage .copies-row {
    flex-direction: column;
    align-items: stretch;
    flex-wrap: nowrap;
    overflow-x: visible;
}
.portrait-stage .slip-card {
    max-width: 100%;
    min-width: unset;
}

/* ── Print Styles ── */
@media print {
    body { background: #fff; }
    .topbar, .meta-strip { display: none; }
    .stage { padding: 0; max-width: 100%; }
    .copies-row { gap: 8mm; }
    .slip-card {
        border-radius: 0;
        box-shadow: none;
        border: 1px solid #333;
        break-inside: avoid;
    }
}
</style>
</head>
<body>

@php
$primaryColor = $template->primary_color ?? '#009999';
$accentColor  = $template->accent_color  ?? '#1a56db';
$textColor    = $template->text_color    ?? '#111111';

$d            = $data ?? [];
$sName        = $d['student_name'] ?? 'Jaffar Ali';
$sFather      = $d['father_name'] ?? 'Muhammad Ali';
$sRoll        = $d['roll_number'] ?? '24609';
$sProgram     = $d['program'] ?? 'BS Information Technology';
$sSem         = $d['semester_no'] ?? 4;
$sSession     = $d['session'] ?? 'Spring 2026';
$due          = (float)($d['amount_due'] ?? 48800);
$fine         = (float)($d['fine_amount'] ?? 0);
$disc         = (float)($d['discount_amount'] ?? 0);
$net          = $due + $fine - $disc;
$isPaid       = false;
$dueDate      = $d['due_date'] ?? '30-06-2026';
$sn           = $d['challan_number'] ?? 'JDCA-2026-0042';
$feeLabel     = $d['fee_label'] ?? 'Semester Fee';

$college      = $template->college_name ?? 'Jinnah School & Degree College Astore';
$collegeSub   = $template->college_subtitle ?? '';
$collegeShort = $template->college_short_name ?? 'JDCA';
$bankName     = $template->bank_name ?? 'KCBL';
$bankAcct     = $template->bank_account ?? '—';
$bankTitle    = $template->bank_account_title ?? $college;
$bankBranch   = $template->bank_branch ?? '';
$refPfx       = $template->ref_prefix ?? 'JDCA';
$billPfx      = $template->bill_prefix ?? '';
$copies       = $template->copies ?? ['Bank Copy', 'Accounts Copy', 'Student Copy'];
$instructions = $template->instructions ?? '';
$footerText   = $template->footer_text ?? '';
$showBarcode  = $template->show_barcode ?? true;
$showInWords  = $template->show_in_words ?? true;
$showDep      = $template->show_depositor_fields ?? true;
$showRef      = $template->show_ref_no ?? true;
$showConsumer = $template->show_consumer_no ?? true;
$showSig      = $template->show_accountant_sig ?? false;
$feeMode      = $template->fee_display_mode ?? 'dynamic';
$feeItems     = $template->fee_items ?? [];
$isPortrait   = in_array($template->orientation, ['portrait']);

$refNo     = $refPfx . '-' . $sn;
$billNo    = $billPfx ? $billPfx . $sn : '';
$consumerNo = $billNo ?: $sn;

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

{{-- ── Top Bar ── --}}
<div class="topbar">
    <div class="topbar-left">
        <a href="{{ url()->previous() }}" class="btn btn-back">
            &#8592; Back
        </a>
        <div>
            <div class="topbar-title">{{ $template->name }}</div>
        </div>
        <span class="topbar-badge">{{ $template->is_active ? 'Active' : $template->variant }}</span>
    </div>
    <div class="topbar-actions">
        <button class="btn btn-print" onclick="window.print()">&#128438; Print</button>
        <a href="{{ route('admin.fee-slip.preview.pdf', $template) }}" class="btn btn-pdf" target="_blank">
            &#128462; Download PDF
        </a>
    </div>
</div>

{{-- ── Meta Strip ── --}}
<div class="meta-strip">
    <div class="meta-item"><strong>Variant:</strong> {{ ucfirst($template->variant) }}</div>
    <div class="meta-item"><strong>Orientation:</strong> {{ ucfirst($template->orientation) }}</div>
    <div class="meta-item"><strong>Bank:</strong> {{ $bankName }} &bull; A/C {{ $bankAcct }}</div>
    <div class="meta-item">
        <strong>Colors:</strong>
        <span class="color-swatch" style="background:{{ $primaryColor }};"></span>
        <span class="color-swatch" style="background:{{ $accentColor }};"></span>
        <span class="color-swatch" style="background:{{ $textColor }};"></span>
    </div>
    <div class="meta-item"><strong>Copies:</strong> {{ count($copies) }} ({{ implode(', ', $copies) }})</div>
    <div class="meta-item"><strong>Fee Mode:</strong> {{ ucfirst($feeMode) }}</div>
</div>

{{-- ── Slip Stage ── --}}
<div class="stage {{ $isPortrait ? 'portrait-stage' : '' }}">
    <div class="copies-row">
    @foreach($copies as $copyLabel)
        <div class="slip-card">
            {{-- Copy Label --}}
            <div class="slip-copy-label" style="background:{{ $primaryColor }};">{{ $copyLabel }}</div>

            {{-- College Header --}}
            <div class="slip-college-row">
                <svg class="slip-crest" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="17" fill="#f0fafb" stroke="{{ $primaryColor }}" stroke-width="1.5"/>
                    <text x="18" y="17" text-anchor="middle" font-size="6" font-weight="bold" fill="{{ $primaryColor }}" font-family="sans-serif">{{ $collegeShort }}</text>
                    <text x="18" y="25" text-anchor="middle" font-size="3.8" fill="{{ $primaryColor }}" font-family="sans-serif">EST. 2010</text>
                </svg>
                <div>
                    <div class="slip-college-name">{{ $college }}</div>
                    @if($collegeSub)<div class="slip-college-sub">{{ $collegeSub }}</div>@endif
                    <div class="slip-college-sub">{{ $bankName }} &bull; A/C: {{ $bankAcct }}</div>
                </div>
            </div>

            {{-- SN Row --}}
            <div class="sn-row">
                <span><strong>SN#:</strong> {{ $sn }}</span>
                <span><strong>Date:</strong> {{ date('d-m-Y') }}</span>
            </div>

            {{-- Student Info --}}
            <div class="slip-section">
                <div class="info-grid">
                    <div class="info-item"><div class="info-lbl">Student Name</div><div class="info-val">{{ $sName }}</div></div>
                    <div class="info-item"><div class="info-lbl">Father Name</div><div class="info-val">{{ $sFather }}</div></div>
                    <div class="info-item"><div class="info-lbl">Reg No</div><div class="info-val">{{ $sRoll }}</div></div>
                    <div class="info-item"><div class="info-lbl">Program</div><div class="info-val">{{ $sProgram }}</div></div>
                    <div class="info-item"><div class="info-lbl">Semester No</div><div class="info-val">{{ $sSem }}</div></div>
                    <div class="info-item"><div class="info-lbl">Session</div><div class="info-val">{{ $sSession }}</div></div>
                </div>
            </div>

            <div class="slip-divider-dark" style="height:1px;background:#333;margin:4px 12px;"></div>

            {{-- Fee Table --}}
            <div class="slip-section" style="padding-top:4px;padding-bottom:4px;">
                <table class="fee-table">
                    <thead>
                        <tr>
                            <th class="fee-col-sn" style="background:{{ $primaryColor }};">S#.</th>
                            <th style="background:{{ $primaryColor }};">Particular</th>
                            <th class="r fee-col-rs" style="background:{{ $primaryColor }};">Rs.</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($feeMode === 'static' && count($feeItems) > 0)
                        @foreach($feeItems as $idx => $item)
                        <tr>
                            <td class="n" style="color:{{ $primaryColor }};">{{ $idx + 1 }}</td>
                            <td>{{ $item['label'] ?? '' }}</td>
                            <td class="r" style="color:#aaa;">—</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="n" style="color:{{ $primaryColor }};">1</td>
                            <td>{{ $feeLabel }}</td>
                            <td class="r">{{ number_format($due, 0) }}</td>
                        </tr>
                        @if($fine > 0)
                        <tr>
                            <td class="n" style="color:{{ $primaryColor }};">2</td>
                            <td>Late Surcharge</td>
                            <td class="r">{{ number_format($fine, 2) }}</td>
                        </tr>
                        @endif
                        @if($disc > 0)
                        <tr>
                            <td class="n" style="color:{{ $primaryColor }};">{{ $fine > 0 ? 3 : 2 }}</td>
                            <td>Discount / Concession</td>
                            <td class="r">- {{ number_format($disc, 0) }}</td>
                        </tr>
                        @endif
                    @endif
                        <tr class="total">
                            <td colspan="2" class="r">Total Payable</td>
                            <td class="r">{{ number_format($net, 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($showInWords)
            <div class="inwords">
                <strong style="color:{{ $accentColor }};">InWords:</strong> {{ $inWords }}
            </div>
            @endif

            @if($showDep)
            <div class="dep-section">
                <div class="dep-field">
                    <span class="dep-label">Depositor Mobile No:</span>
                    <div class="dep-underline"></div>
                </div>
                <div class="dep-field">
                    <span class="dep-label">CNIC #:</span>
                    <div class="dep-underline"></div>
                </div>
            </div>
            @endif

            @if($showRef)
            <div class="ref-row">
                <strong>Ref No:</strong> {{ $refNo }}
                @if($showConsumer && $billNo) &nbsp;&nbsp;<strong>Consumer No:</strong> {{ $consumerNo }} @endif
            </div>
            @endif

            @if($instructions)
            <div class="instructions">{{ $instructions }}</div>
            @endif

            <div class="stamp-section">
                @if($showSig)
                <div style="font-size:8px;color:#555;text-align:right;margin-bottom:4px;">Accountant Signature:</div>
                <div class="stamp-box" style="border-color:#ccc;">&nbsp;</div>
                @else
                <div class="stamp-box">For Bank Use / Official Stamp</div>
                @endif
            </div>

            @if($footerText)
            <div class="slip-footer">{{ $footerText }}</div>
            @endif
        </div>
    @endforeach
    </div>

    <p style="text-align:center;font-size:11px;color:#94a3b8;margin-top:20px;">
        This is a browser preview using sample data. Actual PDF output uses the selected template's PDF engine.
    </p>
</div>
</body>
</html>
