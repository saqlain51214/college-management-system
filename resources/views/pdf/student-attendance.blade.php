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

.title-box { border-top:2.5pt solid #000; border-bottom:2.5pt solid #000; text-align:center; padding:7pt 0; margin-bottom:12pt; }
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
.sum-n { font-size:14pt; font-weight:bold; display:block; }
.sum-l { font-size:7.5pt; color:#666; text-transform:uppercase; }
.sum-ok  { color:#000; }
.sum-low { color:#000; }

.dtable { width:100%; border-collapse:collapse; font-size:8.5pt; margin-bottom:6pt; }
.dtable th { background:#1a1a1a; color:#fff; padding:5pt 4pt; text-align:left; border:0.5pt solid #000; font-size:8pt; }
.dtable td { padding:4pt 4pt; border:0.5pt solid #ccc; vertical-align:middle; }
.dtable tr.even td { background:#f5f5f5; }
.c { text-align:center; }
.ok  { font-weight:bold; }
.low { font-weight:bold; }

.notebox { border-left:3pt solid #000; border:1pt solid #aaa; padding:6pt 10pt; margin:8pt 0; font-size:8.5pt; color:#333; }

.sigtable { width:100%; border-collapse:collapse; margin-top:24pt; }
.sigtable td { width:33%; text-align:center; padding:0 8pt; }
.sigline { border-top:1pt solid #000; margin-top:22pt; padding-top:3pt; font-size:9pt; font-weight:bold; }
.sigtitle { font-size:8pt; color:#555; }
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
  <td class="ph-right">Ref: AT-{{ $student->roll_number }}<br>Date: {{ now()->format('d M Y') }}</td>
</tr></table>
</htmlpageheader>

<htmlpagefooter name="footer">
<table class="pf-table"><tr>
  <td>{{ $college['name'] }} — Attendance Records Office</td>
  <td style="text-align:right">Page {PAGENO} of {nb} &nbsp;|&nbsp; Generated: {{ now()->format('d M Y, h:i A') }}</td>
</tr></table>
</htmlpagefooter>

<div class="title-box">
  <div class="title-main">Student Attendance Report</div>
  <div class="title-sub">Official Attendance Record — Academic Session</div>
</div>

<div class="sec">Student Information</div>
<table class="igrid">
  <tr>
    <td><span class="lbl">Full Name</span><span class="val">{{ $student->name }}</span></td>
    <td><span class="lbl">Roll Number</span><span class="val">{{ $student->roll_number }}</span></td>
    <td><span class="lbl">Program</span><span class="val">{{ $student->academicProgram?->name ?? '—' }}</span></td>
    <td><span class="lbl">Semester</span><span class="val">Semester {{ $student->current_semester ?? '—' }}</span></td>
  </tr>
  <tr>
    <td><span class="lbl">Father's Name</span><span class="val">{{ $student->father_name ?? '—' }}</span></td>
    <td><span class="lbl">Department</span><span class="val">{{ $student->department?->name ?? '—' }}</span></td>
    <td><span class="lbl">Batch Year</span><span class="val">{{ $student->batch_year ?? '—' }}</span></td>
    <td><span class="lbl">Min. Required</span><span class="val">{{ $minPercent }}%</span></td>
  </tr>
</table>

<div class="sec">Overall Attendance Summary</div>
<div class="sumbox">
  <table><tr>
    <td><span class="sum-n">{{ $courseSummary->count() }}</span><span class="sum-l">Courses</span></td>
    <td><span class="sum-n">{{ $courseSummary->sum('total') }}</span><span class="sum-l">Total Classes</span></td>
    <td><span class="sum-n">{{ $courseSummary->sum('present') }}</span><span class="sum-l">Present</span></td>
    <td><span class="sum-n">{{ $courseSummary->sum('late') }}</span><span class="sum-l">Late</span></td>
    <td><span class="sum-n">{{ $courseSummary->sum('absent') }}</span><span class="sum-l">Absent</span></td>
    <td><span class="sum-n {{ $overallPct >= $minPercent ? 'sum-ok' : 'sum-low' }}">{{ $overallPct }}%</span><span class="sum-l">Overall</span></td>
    <td><span class="sum-n {{ $overallPct >= $minPercent ? 'sum-ok' : 'sum-low' }}">{{ $overallPct >= $minPercent ? 'ELIGIBLE' : 'SHORT' }}</span><span class="sum-l">Exam Status</span></td>
  </tr></table>
</div>

<div class="sec">Course-wise Breakdown</div>
<table class="dtable">
  <thead>
    <tr>
      <th>Course</th>
      <th class="c" style="width:40pt">Total</th>
      <th class="c" style="width:42pt">Present</th>
      <th class="c" style="width:32pt">Late</th>
      <th class="c" style="width:32pt">Leave</th>
      <th class="c" style="width:38pt">Absent</th>
      <th class="c" style="width:55pt">Attendance %</th>
      <th class="c" style="width:50pt">Eligible</th>
    </tr>
  </thead>
  <tbody>
    @forelse($courseSummary as $i => $row)
    <tr class="{{ $i%2!==0 ? 'even' : '' }}">
      <td>{{ $row['course'] }}</td>
      <td class="c">{{ $row['total'] }}</td>
      <td class="c">{{ $row['present'] }}</td>
      <td class="c">{{ $row['late'] }}</td>
      <td class="c">{{ $row['leave'] }}</td>
      <td class="c">{{ $row['absent'] }}</td>
      <td class="c {{ $row['pct'] >= $minPercent ? 'ok' : 'low' }}">{{ $row['pct'] }}%</td>
      <td class="c {{ $row['eligible'] ? 'ok' : 'low' }}">{{ $row['eligible'] ? 'YES' : 'NO' }}</td>
    </tr>
    @empty
    <tr><td colspan="8" class="c" style="padding:14pt;color:#777">No attendance records found.</td></tr>
    @endforelse
  </tbody>
</table>

<div class="notebox">
  <strong>Note:</strong> Minimum attendance requirement is <strong>{{ $minPercent }}%</strong> per course to be eligible for examinations.
  Late arrivals are counted as Present for eligibility. Leave and excused absences are not counted as absent.
</div>

<table class="sigtable">
  <tr>
    <td><div class="sigline">Class Teacher / Advisor</div><div class="sigtitle">Signature &amp; Stamp</div></td>
    <td><div class="sigline">Attendance Office</div><div class="sigtitle">Verified By</div></td>
    <td><div class="sigline">{{ $college['principal'] }}</div><div class="sigtitle">Principal — {{ $college['name'] }}</div></td>
  </tr>
</table>

</body>
</html>
