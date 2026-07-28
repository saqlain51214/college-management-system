<h1>New Job Application</h1>

<p>A new job application was submitted from the public website.</p>

<p><strong>Position:</strong> {{ $jobApplication->position }}</p>
<p><strong>Name:</strong> {{ $jobApplication->name }}</p>
<p><strong>Email:</strong> {{ $jobApplication->email }}</p>
<p><strong>Phone:</strong> {{ $jobApplication->phone }}</p>
<p><strong>Education:</strong> {{ $jobApplication->education }}</p>
<p><strong>Experience:</strong> {{ $jobApplication->experience ?: 'Not specified' }}</p>
<p><strong>Cover Letter:</strong><br>{{ $jobApplication->message }}</p>

@if($jobApplication->cv_path)
<p>The applicant's CV is attached to this email.</p>
@else
<p>No CV was attached to this application.</p>
@endif
