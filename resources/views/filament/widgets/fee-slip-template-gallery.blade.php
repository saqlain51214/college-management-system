<div class="mb-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="text-base font-semibold text-gray-700 dark:text-gray-300">
      Fee Slip Templates
    </h2>
    <span class="text-xs text-gray-400">Click Preview to see a live sample · Click Activate to use a template for PDF generation</span>
  </div>

  <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
    @foreach($templates as $template)
    @php
      $variantLabels = ['kiu' => 'KIU Style', 'classic' => 'Classic', 'modern' => 'Modern Blue', 'minimal' => 'Minimal B&W'];
      $variantColors = ['kiu' => 'bg-teal-100 text-teal-800', 'classic' => 'bg-green-100 text-green-800', 'modern' => 'bg-blue-100 text-blue-800', 'minimal' => 'bg-gray-100 text-gray-700'];
      $vLabel = $variantLabels[$template->variant] ?? ucfirst($template->variant);
      $vColor = $variantColors[$template->variant] ?? 'bg-gray-100 text-gray-700';
      $orient = ucfirst($template->orientation ?? 'landscape');
      $primary = $template->primary_color ?? '#009999';
    @endphp

    <div class="rounded-xl overflow-hidden border {{ $template->is_active ? 'border-2 border-green-500 shadow-md' : 'border-gray-200 dark:border-gray-700' }} bg-white dark:bg-gray-800 flex flex-col">

      {{-- Color strip / mini slip preview --}}
      <div class="relative h-28 overflow-hidden" style="background: {{ $primary }}08;">

        {{-- Mini slip illustration --}}
        <div class="absolute inset-0 flex flex-col items-center justify-start pt-2 px-2 gap-1">

          {{-- Header bar --}}
          <div class="w-full rounded flex items-center gap-1 px-1.5 py-0.5" style="background:{{ $primary }};">
            <span class="text-white font-black text-xs tracking-tighter" style="letter-spacing:-1px;font-size:11px;">
              {{ strtoupper(substr($template->bank_name ?? 'HBL', 0, 3)) }}
            </span>
            <span class="text-white text-[6px] font-medium truncate flex-1 text-center opacity-90">
              {{ $template->college_short_name ?? 'JDCA' }}
            </span>
          </div>

          {{-- Barcode simulation --}}
          @if($template->show_barcode ?? true)
          <div class="w-3/4 flex gap-px h-4 items-stretch">
            @foreach([2,1,3,1,2,2,1,3,2,1,2,1,3,2,1,2,2,1,3,1,2,2,1,3,2,1] as $i => $w)
            <div class="{{ $i % 2 === 0 ? 'bg-gray-800' : 'bg-transparent' }} rounded-[0.5px]" style="flex:{{ $w }};"></div>
            @endforeach
          </div>
          @endif

          {{-- Info lines --}}
          <div class="w-full space-y-0.5 px-0.5">
            @foreach(['Student Name', 'Reg No', 'Program'] as $field)
            <div class="flex gap-1 items-center">
              <div class="h-1 rounded-full opacity-60" style="width:28%;background:{{ $primary }};"></div>
              <div class="h-1 rounded-full bg-gray-300 flex-1"></div>
            </div>
            @endforeach
          </div>

          {{-- Fee table mini --}}
          <div class="w-full rounded overflow-hidden border border-gray-200 text-[5px]">
            <div class="text-white px-1 py-0.5" style="background:{{ $primary }};">
              <span class="opacity-90">S#</span> &nbsp; Particular &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rs.
            </div>
            <div class="bg-white px-1 py-0.5 text-gray-500">1. Semester Fee ........... 48800</div>
            <div class="bg-gray-50 px-1 py-0.5 font-semibold text-gray-700">Total Payable ................. 48800</div>
          </div>
        </div>

        {{-- Active badge overlay --}}
        @if($template->is_active)
        <div class="absolute top-1.5 right-1.5">
          <span class="inline-flex items-center gap-0.5 text-[9px] font-bold bg-green-500 text-white px-1.5 py-0.5 rounded-full shadow">
            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            ACTIVE
          </span>
        </div>
        @endif
      </div>

      {{-- Card body --}}
      <div class="p-3 flex flex-col gap-2 flex-1">
        <div>
          <p class="font-semibold text-sm text-gray-800 dark:text-gray-100 leading-tight">{{ $template->name }}</p>
          <div class="flex items-center gap-1.5 mt-1 flex-wrap">
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full {{ $vColor }}">{{ $vLabel }}</span>
            <span class="text-[10px] text-gray-400">{{ $orient }}</span>
            @if($template->bank_name)
            <span class="text-[10px] text-gray-400">{{ $template->bank_name }}</span>
            @endif
          </div>
        </div>

        {{-- Color dot row --}}
        <div class="flex items-center gap-1.5">
          <div class="w-4 h-4 rounded-full border border-white shadow-sm" style="background:{{ $template->primary_color ?? '#009999' }};" title="Primary color"></div>
          <div class="w-4 h-4 rounded-full border border-white shadow-sm" style="background:{{ $template->accent_color ?? '#1a56db' }};" title="Accent color"></div>
          <span class="text-[10px] text-gray-400 ml-0.5">
            {{ count($template->copies ?? ['Bank','Accounts','Student']) }} copies
            @if($template->show_barcode ?? true) · Barcode @endif
          </span>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2 mt-auto pt-1">
          <a href="{{ route('admin.fee-slip.preview', $template) }}"
             target="_blank"
             class="flex-1 text-center text-xs font-medium px-2 py-1.5 rounded-lg border transition-colors"
             style="border-color:{{ $primary }}33;color:{{ $primary }};"
             onmouseover="this.style.background='{{ $primary }}15'"
             onmouseout="this.style.background='transparent'">
            Preview
          </a>
          @if(!$template->is_active)
          <form method="POST" action="{{ route('filament.admin.resources.fee-slip-templates.index') }}" class="flex-1">
            @csrf
            @method('PATCH')
            <input type="hidden" name="activate_id" value="{{ $template->id }}">
            {{-- Activate via direct link to the edit page --}}
          </form>
          <a href="{{ route('filament.admin.resources.fee-slip-templates.edit', $template) }}"
             class="flex-1 text-center text-xs font-medium px-2 py-1.5 rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 transition-colors">
            Edit
          </a>
          @else
          <a href="{{ route('filament.admin.resources.fee-slip-templates.edit', $template) }}"
             class="flex-1 text-center text-xs font-medium px-2 py-1.5 rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 transition-colors">
            Edit
          </a>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
