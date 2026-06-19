@extends('layouts.teacher-portal')
@section('title', 'Materials')
@section('content')

<div class="space-y-6">
  <div class="flex items-center justify-between gap-4">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Teaching Materials</h2>
      <p class="text-sm text-gray-500 mt-1">Materials uploaded under your teacher account.</p>
    </div>
    <a href="{{ route('teacher.materials.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#17324f">
      Add Material
    </a>
  </div>

  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    @if($materials->isEmpty())
      <div class="p-8 text-sm text-gray-400 text-center">No materials found.</div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Title</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Course</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Type</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Status</th>
              <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Created</th>
              <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-400">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($materials as $material)
              <tr>
                <td class="px-5 py-4">
                  <div class="font-medium text-gray-800">{{ $material->title }}</div>
                  <div class="text-xs text-gray-500 mt-1">{{ $material->description ?: 'No description added.' }}</div>
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $material->course?->code ?? '—' }}</td>
                <td class="px-5 py-4 text-gray-600">{{ ucfirst(str_replace('_', ' ', $material->material_type ?? 'general')) }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $material->is_published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $material->is_published ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td class="px-5 py-4 text-gray-500">{{ $material->created_at?->format('d M Y') ?? '—' }}</td>
                <td class="px-5 py-4 text-right">
                  <a href="{{ route('teacher.materials.edit', $material) }}" class="text-sm font-semibold" style="color:#17324f">
                    Edit
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-5 py-4 border-t border-gray-100">
        {{ $materials->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
