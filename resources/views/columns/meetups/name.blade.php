<div class="flex items-center space-x-2">
    <img class="h-24" src="{{ $row->getFirstMediaUrl('logo', 'thumb') }}" alt="{{ $row->name }}">
    <div>
        {{ $row->name }}
    </div>
</div>
