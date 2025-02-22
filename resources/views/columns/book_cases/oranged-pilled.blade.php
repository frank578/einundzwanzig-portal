<div class="flex flex-col space-y-1">
    @auth
        @if($row->orange_pills_count > 0)
            <img class="aspect-auto max-h-12" src="{{ asset('img/social_credit_plus.webp') }}" alt="">
        @endif
        @if($row->orange_pills_count < 1)
            <img class="aspect-auto max-h-12" src="{{ asset('img/social_credit_minus.webp') }}" alt="">
        @endif
        <div class="flex items-center space-x-1">
            <x-button class="whitespace-nowrap" primary class="text-21gray whitespace-nowrap"
                      wire:click="viewHistoryModal({{ $row->id }})">{{ __('💊 Orange Pill Now') }}</x-button>
            <x-button class="whitespace-nowrap"
                      :href="route('bookCases.comment.bookcase', ['bookCase' => $row->id, 'country' => $country])">{{ __('Details') }}</x-button>
        </div>
    @else
        <div>
            <x-badge class="whitespace-nowrap" amber>
                <i class="fa fa-thin fa-shelves-empty mr-2"></i>
                {{ __('no bitcoin books yet') }}
            </x-badge>
        </div>
    @endauth
</div>
