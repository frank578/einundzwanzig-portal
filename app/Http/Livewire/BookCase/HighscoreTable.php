<?php

namespace App\Http\Livewire\BookCase;

use App\Models\Country;
use App\Models\User;
use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HighscoreTable extends Component
{
    public Country $country;
    public bool $viewingModal = false;
    public ?User $modal = null;

    public function render()
    {
        return view('livewire.book-case.highscore-table', [
            'plebs' => User::query()
                           ->with([
                               'reputations',
                           ])
                           ->withCount([
                               'orangePills',
                           ])
                           ->orderByDesc('reputation')
                           ->get(),
        ])->layout('layouts.app', [
            'SEOData' => new SEOData(
                title: __('Highscore Table'),
                description: __('Hall of fame of our honorable plebs'),
                image: asset('img/highscore_table_screenshot.png'),
            )
        ]);
    }

    public function openModal($id)
    {
        $this->modal = User::query()
                           ->with([
                               'orangePills',
                               'reputations',
                           ])
                           ->where('id', $id)
                           ->first();

        $this->viewingModal = true;
    }

    public function resetModal()
    {
        $this->modal = null;
        $this->viewingModal = false;
    }
}
