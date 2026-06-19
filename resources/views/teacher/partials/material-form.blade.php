@php
    $editing = isset($material);
@endphp

<form action="{{ $editing ? route('teacher.materials.update', $material) : route('teacher.materials.store') }}" method="POST" class="space-y-6">
  @csrf
  @if($editing)
    @method('PUT')
  @endif

  <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
        <input type="text" name="title" value="{{ old('title', $material->title ?? '') }}" required maxlength="200"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Course</label>
        <select name="course_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          <option value="">Select course</option>
          @foreach($courses as $course)
            <option value="{{ $course->id }}" @selected(old('course_id', $material->course_id ?? null) == $course->id)>
              {{ $course->code }} - {{ $course->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Material Type</label>
        <select name="material_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
          @foreach($materialTypes as $value => $label)
            <option value="{{ $value }}" @selected(old('material_type', $material->material_type ?? 'document') === $value)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Week Number</label>
        <input type="number" name="week_number" min="1" max="18" value="{{ old('week_number', $material->week_number ?? '') }}"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">External URL</label>
        <input type="url" name="external_url" value="{{ old('external_url', $material->external_url ?? '') }}"
               placeholder="Optional video or external resource link"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
        <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1e3a5f]">{{ old('description', $material->description ?? '') }}</textarea>
      </div>

      <div class="md:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $material->is_published ?? true)) class="rounded">
        <label for="is_published" class="text-sm text-gray-700">Published</label>
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
    <a href="{{ route('teacher.materials') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-700 bg-white">
      Cancel
    </a>
    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
      {{ $editing ? 'Update Material' : 'Create Material' }}
    </button>
  </div>
</form>
