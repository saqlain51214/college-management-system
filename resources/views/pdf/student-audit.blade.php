<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Audit — {{ $student->name }}</title>
<style>
    @page { margin: 18mm 14mm; }
    * { box-sizing: border-box; }
    body { font-family: "DejaVu Sans", sans-serif; font-size: 10pt; color: #1c1917; }
    .hdr { text-align: center; margin-bottom: 14pt; border-bottom: 1.5pt solid #6b2d39; padding-bottom: 10pt; }
    .hdr h1 { font-size: 15pt; margin: 0 0 2pt; color: #6b2d39; }
    .hdr p { margin: 0; font-size: 8.5pt; color: #57534e; }
    .profile { width: 100%; border-collapse: collapse; margin-bottom: 14pt; }
    .profile td { padding: 3pt 6pt; font-size: 9.5pt; }
    .profile .lbl { font-weight: bold; width: 110pt; color: #57534e; }
    table.logs { width: 100%; border-collapse: collapse; }
    table.logs th { background: #6b2d39; color: #fff; font-size: 8.5pt; text-align: left; padding: 5pt 6pt; }
    table.logs td { font-size: 8.5pt; padding: 5pt 6pt; border-bottom: 0.5pt solid #e7e5e4; vertical-align: top; }
    table.logs tr:nth-child(even) td { background: #faf9f6; }
    .footer-note { margin-top: 16pt; font-size: 8pt; color: #a8a29e; text-align: center; }
</style>
</head>
<body>
    <div class="hdr">
        <h1>{{ $collegeName }}</h1>
        <p>Student Audit History — Generated {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <table class="profile">
        <tr><td class="lbl">Name</td><td>{{ $student->name }}</td><td class="lbl">Roll Number</td><td>{{ $student->roll_number ?: '—' }}</td></tr>
        <tr><td class="lbl">Father's Name</td><td>{{ $student->father_name ?: '—' }}</td><td class="lbl">Registration No.</td><td>{{ $student->registration_number ?: '—' }}</td></tr>
        <tr><td class="lbl">Programme</td><td>{{ $student->academicProgram?->name ?: '—' }}</td><td class="lbl">Department</td><td>{{ $student->department?->name ?: '—' }}</td></tr>
    </table>

    <table class="logs">
        <thead>
            <tr><th style="width:80pt;">Date</th><th style="width:100pt;">Event</th><th>Details</th><th style="width:80pt;">By</th></tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log['date'] }}</td>
                    <td>{{ $log['event'] }}</td>
                    <td>{{ $log['message'] ?: '—' }}</td>
                    <td>{{ $log['actor'] ?: 'System' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center; color:#a8a29e;">No recorded activity for this student yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer-note">This is a system-generated audit record from {{ $collegeName }}'s student management system.</p>
</body>
</html>
