@php
    $editing = isset($assignment);
@endphp

<form action="{{ $editing ? route('teacher.assignments.update', $assignment) : route('teacher.assignments.store') }}" method="POST" class="space-y-6">
  @csrf
  @if($editing)
    @method('PUT')
  @endif

  <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
        <input type="text" name="title" value="{{ old('title', $assignment->title ?? '') }}" required maxlength="200"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Course</label>
        <select name="course_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          <option value="">Select course</option>
          @foreach($courses as $course)
            <option value="{{ $course->id }}" @selected(old('course_id', $assignment->course_id ?? null) == $course->id)>
              {{ $course->code }} - {{ $course->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Submission Type</label>
        <select name="submission_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          @foreach($submissionTypes as $value => $label)
            <option value="{{ $value }}" @selected(old('submission_type', $assignment->submission_type ?? 'file') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Total Marks</label>
        <input type="number" step="0.01" min="1" name="total_marks" value="{{ old('total_marks', $assignment->total_marks ?? 10) }}" required
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Due Date & Time</label>
        <input type="datetime-local" name="due_datetime" value="{{ old('due_datetime', isset($assignment) && $assignment->due_datetime ? $assignment->due_datetime->format('Y-m-d\TH:i') : '') }}"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">{{ old('description', $assignment->description ?? '') }}</textarea>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Instructions</label>
        <textarea name="instructions" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">{{ old('instructions', $assignment->instructions ?? '') }}</textarea>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" name="allow_late_submission" id="allow_late_submission" value="1" @checked(old('allow_late_submission', $assignment->allow_late_submission ?? false)) class="rounded">
        <label for="allow_late_submission" class="text-sm text-gray-700">Allow late submission</label>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" name="is_published" id="assignment_is_published" value="1" @checked(old('is_published', $assignment->is_published ?? true)) class="rounded">
        <label for="assignment_is_published" class="text-sm text-gray-700">Published</label>
      </div>
    </div>
  </div>

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <div class="flex items-center justify-between gap-3">
    <a href="{{ route('teacher.assignments') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-700 bg-white">
      Cancel
    </a>
    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
      {{ $editing ? 'Update Assignment' : 'Create Assignment' }}
    </button>
  </div>
</form>
