@php
    use App\Models\CollegeSetting;
    use App\Models\LeadershipMessage;
    use Illuminate\Support\Facades\Schema;

    $leaders = collect();
    try {
        if (Schema::hasTable('leadership_messages')) {
            $leaders = LeadershipMessage::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        }
    } catch (\Throwable) {
        $leaders = collect();
    }

    // Legacy aliases so a setting saved before the 6-template picker existed
    // still resolves to the right design instead of silently falling back.
    $legacyMap = ['card' => 'cards', 'side_by_side' => 'spotlight'];
    $rawLayout = CollegeSetting::get('message_desk_layout', 'cards');
    $layout = $legacyMap[$rawLayout] ?? $rawLayout;

    $validLayouts = ['cards', 'spotlight', 'diploma', 'ribbon', 'glass', 'minimal'];
    if (! in_array($layout, $validLayouts, true)) {
        $layout = 'cards';
    }

    $initialsFor = fn (string $name) => collect(explode(' ', trim($name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
@endphp

@if($leaders->isNotEmpty())
    {{-- @include automatically shares $leaders and $initialsFor with whichever
         template partial is selected below. --}}
    @include('components.home.message-desk.' . $layout, ['leaders' => $leaders])
@endif
