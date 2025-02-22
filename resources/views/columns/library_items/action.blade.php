<div class="flex flex-col space-y-1">
    @if(str($row->value)->contains('http'))
        <x-button xs amber :href="$row->value" target="_blank">
            <i class="fa fa-thin fa-book-open mr-2"></i>
            {{ __('Open') }}
        </x-button>
    @endif
    @if($row->type === 'downloadable_file')
        <x-button xs amber :href="$row->getFirstMediaUrl('single_file')" target="_blank">
            <i class="fa fa-thin fa-download mr-2"></i>
            {{ __('Download') }}
        </x-button>
    @endif
    @if($row->type === 'podcast_episode')
        <x-button xs amber :href="$row->episode->data['enclosureUrl']" target="_blank">
            <i class="fa fa-thin fa-headphones mr-2"></i>
            {{ __('Listen') }}
        </x-button>
    @endif

    <x-button
        x-data="{
            textToCopy: '{{ url()->route('library.table.libraryItems', ['country' => 'de', 'table' => ['filters' => ['id' => $row->id]]]) }}',
        }"
        @click.prevent="window.navigator.clipboard.writeText(textToCopy);window.$wireui.notify({title:'{{ __('Share url copied!') }}',icon:'success'});"
        xs black>
        <i class="fa fa-thin fa-copy mr-2"></i>
        {{ __('Share link') }}
    </x-button>
</div>
