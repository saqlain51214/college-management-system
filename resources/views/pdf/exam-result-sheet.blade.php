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

.sumbox { border:1.5pt solid #000; padding:6pt 10pt; margin:6pt 0 10pt; background:#f9f9f9; }
.sumbox table { width:100%; border-collapse:collapse; }
.sumbox td { text-align:center; padding:3pt 6pt; border-right:1pt solid #ccc; }
.sumbox td:last-child { border-right:none; }
.sum-n { font-size:13pt; font-weight:bold; display:block; }
.sum-l { font-size:7.5pt; color:#666; text-transform:uppercase; }

.dtable { width:100%; border-collapse:collapse; font-size:8.5pt; margin-bottom:6pt; }
.dtable th { background:#1a1a1a; color:#fff; padding:5pt 4pt; text-align:left; border:0.5pt solid #000; font-size:8pt; }
.dtable td { padding:4pt 4pt; border:0.5pt solid #ccc; vertical-align:middle; }
.dtable tr.even td { background:#f5f5f5; }
.dtable tr.row-abs td { color:#888; font-style:italic; }
.c { text-align:center; }
.r { text-align:right; }
.pass { font-weight:bold; }
.fail { font-weight:bold; }

.sigtable  { width:100%; border-collapse:collapse; margin-top:24pt; }
.sigtable td { width:33%; text-align:center; padding:0 8pt; }
.sigline   { border-top:1pt solid #000; margin-top:22pt; padding-top:3pt; font-size:9pt; font-weight:bold; }
.sigtitle  { font-size:8pt; color:#555; }
</style>
</head>
<body>

<htmlpageheader name="header">
<table class="ph-table"><tr>
  <td class="ph-left">
    <div class="ph-name">{{ $college['name'] }}</div>
    <div class="ph-sub">{{ $college['address'] }}, {{ $college['city'] }}</div>
    <div class="ph-sub">Affiliated with: {{ $college['affiliation'] }}</div>
  </td>
  <td class="ph-right">Doc: RS-{{ $exam->id }}<br>Date: {{ now()->format('d M Y') }}</td>
</tr></table>
</htmlpageheader>

<htmlpagefooter name="footer">
<table class="pf-table"><tr>
  <td>{{ $college['name'] }} — Examinations Office &nbsp;|&nbsp; CONFIDENTIAL</td>
  <td style="text-align:right">Page {PAGENO} of {nb} &nbsp;|&nbsp; {{ now()->format('d M Y, h:i A') }}</td>
</tr></table>
</htmlpagefooter>

<div class="title-box">
  <div class="title-main">Examination Result Sheet</div>
  <div class="title-sub">Official Record — For Office Use Only</div>
</div>

<div class="sec">Examination Details</div>
<table class="igrid">
  <tr>
    <td><span class="lbl">Exam Title</span><span class="val">{{ $exam->title }}</span></td>
    <td><span class="lbl">Course</span><span class="val">{{ $exam->course?->code }} — {{ $exam->course?->name }}</span></td>
    <td><span class="lbl">Exam Type</span><span class="val">{{ $exam->exam_type instanceof \BackedEnum ? ucfirst($exam->exam_type->value) : ucfirst($exam->exam_type ?? '—') }}</span></td>
    <td><span class="lbl">Exam Date</span><span class="val">{{ $exam->exam_date?->format('d M Y') ?? '—' }}</span></td>
  </tr>
  <tr>
    <td><span class="lbl">Program</span><span class="val">{{ $exam->academicProgram?->name ?? '—' }}</span></td>
    <td><span class="lbl">Academic Year</span><span class="val">{{ $exam->academicYear?->name ?? '—' }}</span></td>
    <td><span class="lbl">Total Marks</span><span class="val">{{ $exam->total_marks }}</span></td>
    <td><span class="lbl">Passing Marks</span><span class="val">{{ $stats['passingMarks'] }}</span></td>
  </tr>
</table>

<div class="sec">Statistics</div>
<div class="sumbox">
  <table><tr>
    <td><span class="sum-n">{{ $results->count() }}</span><span class="sum-l">Enrolled</span></td>
    <td><span class="sum-n">{{ $stats['appeared'] }}</span><span class="sum-l">Appeared</span></td>
    <td><span class="sum-n">{{ $stats['passed'] }}</span><span class="sum-l">Passed</span></td>
    <td><span class="sum-n">{{ $stats['appeared'] - $stats['passed'] }}</span><span class="sum-l">Failed</span></td>
    <td><span class="sum-n">{{ $results->where('is_absent',true)->count() }}</span><span class="sum-l">Absent</span></td>
    <td><span class="sum-n">{{ $stats['highest'] }}</span><span class="sum-l">Highest</span></td>
    <td><span class="sum-n">{{ $stats['average'] }}</span><span class="sum-l">Average</span></td>
    <td><span class="sum-n">{{ $stats['passRate'] }}%</span><span class="sum-l">Pass Rate</span></td>
  </tr></table>
</div>

<div class="sec">Student Results</div>
<table class="dtable">
  <thead>
    <tr>
      <th style="width:22pt">#</th>
      <th style="width:60pt">Roll No.</th>
      <th>Student Name</th>
      <th class="c" style="width:55pt">Marks ({{ $exam->total_marks }})</th>
      <th class="c" style="width:38pt">%</th>
      <th class="c" style="width:38pt">Grade</th>
      <th class="c" style="width:38pt">Points</th>
      <th class="c" style="width:42pt">Result</th>
    </tr>
  </thead>
  <tbody>
    @foreach($results as $i => $result)
    @php
      $isPass = !$result->is_absent && $result->marks_obtained !== null && $result->marks_obtained >= $stats['passingMarks'];
      $pctVal = (!$result->is_absent && $exam->total_marks > 0 && $result->marks_obtained !== null)
                ? round($result->marks_obtained / $exam->total_marks * 100, 1) : null;
      $rowClass = ($result->is_absent ? 'row-abs' : '') . ($i%2!==0 ? ' even' : '');
    @endphp
    <tr class="{{ $rowClass }}">
      <td class="c">{{ $i + 1 }}</td>
      <td>{{ $result->student?->roll_number ?? '—' }}</td>
      <td>{{ $result->student?->name ?? '—' }}</td>
      <td class="c {{ $result->is_absent ? '' : ($isPass ? 'pass' : 'fail') }}" style="font-weight:bold">
        {{ $result->is_absent ? 'ABSENT' : ($result->marks_obtained !== null ? number_format($result->marks_obtained,1) : '—') }}
      </td>
      <td class="c">{{ $pctVal !== null ? $pctVal.'%' : '—' }}</td>
      <td class="c">{{ $result->is_absent ? 'AB' : ($result->grade ?? '—') }}</td>
      <td class="c">{{ ($result->is_absent || $result->grade_points === null) ? '—' : number_format($result->grade_points,2) }}</td>
      <td class="c {{ $result->is_absent ? '' : ($isPass ? 'pass' : 'fail') }}">
        {{ $result->is_absent ? 'ABSENT' : ($isPass ? 'PASS' : 'FAIL') }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<p style="font-size:8pt;color:#555;margin-bottom:10pt">
  Total enrolled: {{ $results->count() }} &nbsp;|&nbsp; Appeared: {{ $stats['appeared'] }} &nbsp;|&nbsp; Absent: {{ $results->where('is_absent',true)->count() }} &nbsp;|&nbsp; Passing marks: {{ $stats['passingMarks'] }}/{{ $exam->total_marks }}
</p>

<table class="sigtable">
  <tr>
    <td><div class="sigline">Course Teacher</div><div class="sigtitle">{{ $exam->course?->name }}</div></td>
    <td><div class="sigline">Controller of Examinations</div><div class="sigtitle">Signature &amp; Stamp</div></td>
    <td><div class="sigline">{{ $college['principal'] }}</div><div class="sigtitle">Principal — {{ $college['name'] }}</div></td>
  </tr>
</table>

</body>
</html>
