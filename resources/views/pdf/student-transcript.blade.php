<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
/* ── Page setup ── */
@page {
  header: html_header;
  footer: html_footer;
  margin-top:    32mm;
  margin-bottom: 22mm;
  margin-left:   15mm;
  margin-right:  15mm;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: dejavusans, sans-serif; font-size: 10pt; color: #000; }

/* ── Page header / footer ── */
.ph-table  { width:100%; border-collapse:collapse; border-bottom: 2pt solid #000; padding-bottom:6pt; }
.ph-left   { width:85%; vertical-align:middle; }
.ph-right  { width:15%; text-align:right; vertical-align:top; font-size:8pt; color:#444; }
.ph-name   { font-size:15pt; font-weight:bold; }
.ph-sub    { font-size:8.5pt; color:#333; margin-top:1pt; }
.pf-table  { width:100%; border-collapse:collapse; border-top:1pt solid #aaa; padding-top:4pt; font-size:8pt; color:#555; }

/* ── Title bar ── */
.title-box {
  border-top: 2.5pt solid #000;
  border-bottom: 2.5pt solid #000;
  text-align: center;
  padding: 7pt 0;
  margin-bottom: 12pt;
}
.title-main { font-size:14pt; font-weight:bold; letter-spacing:2pt; text-transform:uppercase; }
.title-sub  { font-size:8.5pt; color:#444; margin-top:2pt; }

/* ── Section label ── */
.sec { font-size:9.5pt; font-weight:bold; text-transform:uppercase; letter-spacing:0.5pt;
       border-bottom:1.5pt solid #000; padding-bottom:2pt; margin:10pt 0 5pt; }

/* ── Info grid ── */
.igrid { width:100%; border-collapse:collapse; margin-bottom:10pt; }
.igrid td { padding:5pt 8pt; border:1pt solid #bbb; vertical-align:top; width:25%; }
.lbl { font-size:7.5pt; font-weight:bold; color:#555; text-transform:uppercase; letter-spacing:0.3pt; display:block; margin-bottom:2pt; }
.val { font-size:10.5pt; }

/* ── Results table ── */
.dtable { width:100%; border-collapse:collapse; font-size:8.5pt; margin-bottom:6pt; }
.dtable th { background:#1a1a1a; color:#fff; padding:5pt 4pt; text-align:left; border:0.5pt solid #000; font-size:8pt; }
.dtable td { padding:4pt 4pt; border:0.5pt solid #ccc; vertical-align:middle; }
.dtable tr.even td { background:#f5f5f5; }
.dtable .r { text-align:right; }
.dtable .c { text-align:center; }
.pass  { font-weight:bold; }
.fail  { font-weight:bold; }
.tabsent { color:#888; font-style:italic; }

/* ── Summary strip ── */
.sumbox { border:1pt solid #000; padding:6pt 10pt; margin:6pt 0 10pt; background:#f9f9f9; }
.sumbox table { width:100%; border-collapse:collapse; }
.sumbox td { text-align:center; padding:2pt 8pt; border-right:1pt solid #ccc; font-size:9.5pt; }
.sumbox td:last-child { border-right:none; }
.sum-n { font-size:13pt; font-weight:bold; display:block; }
.sum-l { font-size:7.5pt; color:#666; text-transform:uppercase; }

/* ── Attendance table ── */
.attable { width:100%; border-collapse:collapse; font-size:8.5pt; margin-bottom:6pt; }
.attable th { background:#333; color:#fff; padding:5pt 4pt; border:0.5pt solid #000; font-size:8pt; }
.attable td { padding:4pt 4pt; border:0.5pt solid #ccc; vertical-align:middle; }
.attable tr.even td { background:#f5f5f5; }
.att-ok  { font-weight:bold; }
.att-low { font-weight:bold; }

/* ── Verification box ── */
.verify { border:1pt solid #888; border-left:3pt solid #000; padding:6pt 10pt; margin:10pt 0; font-size:8.5pt; color:#333; }

/* ── Signature block ── */
.sigtable { width:100%; border-collapse:collapse; margin-top:24pt; }
.sigtable td { width:33%; text-align:center; padding:0 8pt; }
.sigline { border-top:1pt solid #000; margin-top:22pt; padding-top:3pt; font-size:9pt; font-weight:bold; }
.sigtitle { font-size:8pt; color:#555; }
</style>
</head>
<body>

{{-- ── mPDF Page Header ── --}}
<htmlpageheader name="header">
<table class="ph-table">
<tr>
  <td class="ph-left">
    <div class="ph-name">{{ $college['name'] }}</div>
    <div class="ph-sub">{{ $college['address'] }}, {{ $college['city'] }}</div>
    <div class="ph-sub">Tel: {{ $college['phone'] }} &nbsp;|&nbsp; {{ $college['email'] }} &nbsp;|&nbsp; Affiliated: {{ $college['affiliation'] }}</div>
  </td>
  <td class="ph-right">
    Ref: TR-{{ $student->roll_number }}<br>
    Date: {{ now()->format('d M Y') }}
  </td>
</tr>
</table>
</htmlpageheader>

{{-- ── mPDF Page Footer ── --}}
<htmlpagefooter name="footer">
<table class="pf-table">
<tr>
  <td>{{ $college['name'] }} — Academic Records Office</td>
  <td style="text-align:right">Page {PAGENO} of {nb} &nbsp;|&nbsp; Generated: {{ now()->format('d M Y, h:i A') }}</td>
</tr>
</table>
</htmlpagefooter>

{{-- ── Title ── --}}
<div class="title-box">
  <div class="title-main">Academic Transcript</div>
  <div class="title-sub">Official Record of Academic Performance — {{ $college['short_name'] }}</div>
</div>

{{-- ── Student Info ── --}}
<div class="sec">Student Information</div>
<table class="igrid">
  <tr>
    <td><span class="lbl">Full Name</span><span class="val">{{ $student->name }}</span></td>
    <td><span class="lbl">Roll Number</span><span class="val">{{ $student->roll_number }}</span></td>
    <td><span class="lbl">Registration No.</span><span class="val">{{ $student->registration_number ?? '—' }}</span></td>
    <td><span class="lbl">CNIC / B-Form</span><span class="val">{{ $student->cnic ?? '—' }}</span></td>
  </tr>
  <tr>
    <td><span class="lbl">Father's Name</span><span class="val">{{ $student->father_name ?? '—' }}</span></td>
    <td><span class="lbl">Program</span><span class="val">{{ $student->academicProgram?->name ?? '—' }}</span></td>
    <td><span class="lbl">Batch Year</span><span class="val">{{ $student->batch_year ?? '—' }}</span></td>
    <td><span class="lbl">Current Semester</span><span class="val">Semester {{ $student->current_semester ?? '—' }}</span></td>
  </tr>
  <tr>
    <td><span class="lbl">Department</span><span class="val">{{ $student->department?->name ?? '—' }}</span></td>
    <td><span class="lbl">Admission Date</span><span class="val">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : '—' }}</span></td>
    <td><span class="lbl">Status</span><span class="val">{{ $student->status instanceof \BackedEnum ? ucfirst(str_replace('_',' ',$student->status->value)) : ucfirst(str_replace('_',' ',$student->status ?? '')) }}</span></td>
    <td><span class="lbl">Academic Year</span><span class="val">{{ $student->academicYear?->name ?? '—' }}</span></td>
  </tr>
</table>

{{-- ── Exam Results ── --}}
@if($results->count())
<div class="sec">Examination Results</div>
<table class="dtable">
  <thead>
    <tr>
      <th>Exam Title</th>
      <th>Type</th>
      <th>Date</th>
      <th class="r">Max</th>
      <th class="r">Marks</th>
      <th class="r">%</th>
      <th class="c">Grade</th>
      <th class="c">GPA</th>
      <th class="c">Result</th>
    </tr>
  </thead>
  <tbody>
    @foreach($results as $i => $r)
    @php
      $mo       = $r->marks_obtained;
      $tm       = $r->exam?->total_marks ?? 0;
      $pm       = $r->exam?->passing_marks ?? 40;
      $pct      = (!$r->is_absent && $tm > 0 && $mo !== null) ? round($mo/$tm*100,1) : null;
      $isPass   = !$r->is_absent && $mo !== null && $mo >= $pm;
      $etStr    = $r->exam?->exam_type instanceof \BackedEnum ? ucfirst($r->exam->exam_type->value) : ucfirst($r->exam?->exam_type ?? '');
      $rowClass = $i % 2 === 0 ? '' : 'even';
    @endphp
    <tr class="{{ $rowClass }}{{ $r->is_absent ? ' tabsent' : '' }}">
      <td>{{ $r->exam?->title ?? '—' }}</td>
      <td>{{ $etStr }}</td>
      <td>{{ $r->exam?->exam_date?->format('d M Y') ?? '—' }}</td>
      <td class="r">{{ $tm ?: '—' }}</td>
      <td class="r {{ $r->is_absent ? '' : ($isPass ? 'pass' : 'fail') }}">
        {{ $r->is_absent ? 'ABSENT' : ($mo !== null ? number_format($mo,1) : '—') }}
      </td>
      <td class="r">{{ $pct !== null ? $pct.'%' : '—' }}</td>
      <td class="c">{{ $r->is_absent ? 'AB' : ($r->grade ?? '—') }}</td>
      <td class="c">{{ ($r->is_absent || $r->grade_points === null) ? '—' : number_format($r->grade_points,2) }}</td>
      <td class="c {{ $r->is_absent ? '' : ($isPass ? 'pass' : 'fail') }}">
        {{ $r->is_absent ? 'ABSENT' : ($isPass ? 'PASS' : 'FAIL') }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@php
  $gpRows   = $results->where('is_absent',false)->whereNotNull('grade_points');
  $avgGpa   = $gpRows->count() ? round($gpRows->avg('grade_points'),2) : 0;
  $totObt   = $results->where('is_absent',false)->sum('marks_obtained');
  $totMax   = $results->where('is_absent',false)->sum(fn($r) => $r->exam?->total_marks ?? 0);
  $oPct     = $totMax > 0 ? round($totObt/$totMax*100,1) : 0;
@endphp
<div class="sumbox">
  <table><tr>
    <td><span class="sum-n">{{ $results->count() }}</span><span class="sum-l">Total Exams</span></td>
    <td><span class="sum-n">{{ $results->where('is_absent',false)->count() }}</span><span class="sum-l">Appeared</span></td>
    <td><span class="sum-n">{{ $results->where('is_absent',true)->count() }}</span><span class="sum-l">Absent</span></td>
    <td><span class="sum-n">{{ $oPct }}%</span><span class="sum-l">Overall %</span></td>
    <td><span class="sum-n">{{ $avgGpa }} / 4.00</span><span class="sum-l">CGPA</span></td>
  </tr></table>
</div>
@endif

{{-- ── Attendance ── --}}
@if($attendanceSummary->count())
<div class="sec">Attendance Summary</div>
<table class="attable">
  <thead>
    <tr>
      <th>Course</th>
      <th class="c">Total</th>
      <th class="c">Present</th>
      <th class="c">Late</th>
      <th class="c">Absent</th>
      <th class="c">Attendance %</th>
      <th class="c">Eligible</th>
    </tr>
  </thead>
  <tbody>
    @foreach($attendanceSummary as $i => $row)
    <tr class="{{ $i % 2 !== 0 ? 'even' : '' }}">
      <td>{{ $row['course'] }}</td>
      <td class="c">{{ $row['total'] }}</td>
      <td class="c">{{ $row['present'] }}</td>
      <td class="c">{{ $row['late'] }}</td>
      <td class="c">{{ $row['absent'] }}</td>
      <td class="c {{ $row['pct'] >= $minPct ? 'att-ok' : 'att-low' }}">{{ $row['pct'] }}%</td>
      <td class="c {{ $row['eligible'] ? 'att-ok' : 'att-low' }}">{{ $row['eligible'] ? 'YES' : 'NO' }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<p style="font-size:8pt;color:#555;margin-bottom:10pt">* Minimum required: {{ $minPct }}%. Late arrivals counted as Present.</p>
@endif

{{-- ── Verification ── --}}
<div class="verify">
  <strong>Verification:</strong> This transcript is issued by the Academic Records Office of {{ $college['name'] }}.
  Any alteration renders this document invalid. For verification contact: {{ $college['phone'] }} or {{ $college['email'] }}.
</div>

{{-- ── Signatures ── --}}
<table class="sigtable">
  <tr>
    <td><div class="sigline">Student</div><div class="sigtitle">{{ $student->name }}</div></td>
    <td><div class="sigline">Examination Controller</div><div class="sigtitle">{{ $college['name'] }}</div></td>
    <td><div class="sigline">{{ $college['principal'] }}</div><div class="sigtitle">Principal — {{ $college['short_name'] }}</div></td>
  </tr>
</table>

</body>
</html>
