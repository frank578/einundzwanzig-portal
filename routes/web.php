<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::middleware([
    'needMeetup',
])
     ->get('/', \App\Http\Livewire\Frontend\Welcome::class)
     ->name('welcome');

Route::middleware([])
     ->get('/my-meetups', \App\Http\Livewire\Profile\Meetups::class)
     ->name('profile.meetups');

Route::get('/auth/ln', \App\Http\Livewire\Auth\LNUrlAuth::class)
     ->name('auth.ln')
     ->middleware('guest');

Route::get('/auth/twitter', function () {
    return Socialite::driver('twitter')
                    ->scopes([
                        'tweet.write',
                        'offline.access',
                    ])
                    ->redirect();
})
     ->name('auth.twitter.redirect');

Route::get('/auth/twitter/callback', function () {
    $twitterUser = Socialite::driver('twitter')
                            ->user();
    $twitterAccount = \App\Models\TwitterAccount::updateOrCreate([
        'twitter_id' => $twitterUser->id,
    ], [
        'twitter_id'    => $twitterUser->id,
        'refresh_token' => $twitterUser->refreshToken,
        'nickname'      => $twitterUser->nickname,
        'token'         => $twitterUser->token,
        'expires_in'    => $twitterUser->expiresIn,
        'data'          => [],
    ]);

    echo 'Twitter account updated. We can now tweet on: '.$twitterUser->name;
    die;
})
     ->name('auth.twitter');

/*
 * School
 * */
Route::middleware([
    'needMeetup',
])
     ->as('school.')
     ->prefix('/{country:code}/school')
     ->group(function () {
         Route::get('/city', \App\Http\Livewire\School\CityTable::class)
              ->name('table.city');

         Route::get('/lecturer', \App\Http\Livewire\School\LecturerTable::class)
              ->name('table.lecturer');

         Route::get('/venue', \App\Http\Livewire\School\VenueTable::class)
              ->name('table.venue');

         Route::get('/course', \App\Http\Livewire\School\CouseTable::class)
              ->name('table.course');

         Route::get('/event', \App\Http\Livewire\School\EventTable::class)
              ->name('table.event');

         Route::get('/{lecturer:slug}', \App\Http\Livewire\School\LecturerLandingPage::class)
              ->name('landingPage.lecturer');
     });

/*
 * Library
 * */
Route::middleware([
    'needMeetup',
])
     ->as('library.')
     ->prefix('/{country:code}/library')
     ->group(function () {
         Route::get('/library-item', \App\Http\Livewire\Library\LibraryTable::class)
              ->name('table.libraryItems');

         Route::get('/content-creator', \App\Http\Livewire\Library\LibraryTable::class)
              ->name('table.lecturer');
     });

/*
 * Books
 * */
Route::middleware([
    'needMeetup',
])
     ->as('bookCases.')
     ->prefix('/{country:code}/book-cases')
     ->group(function () {
         Route::get('/city', \App\Http\Livewire\BookCase\CityTable::class)
              ->name('table.city');

         Route::get('/overview', \App\Http\Livewire\BookCase\BookCaseTable::class)
              ->name('table.bookcases');

         Route::get('/book-case/{bookCase}', \App\Http\Livewire\BookCase\CommentBookCase::class)
              ->name('comment.bookcase');

         Route::get('/high-score-table', \App\Http\Livewire\BookCase\HighscoreTable::class)
              ->name('highScoreTable');
     });

/*
 * Events
 * */
Route::middleware([
    'needMeetup',
])
     ->as('bitcoinEvent.')
     ->prefix('/{country:code}/event')
     ->group(function () {
         Route::get('stream-calendar', \App\Http\Controllers\DownloadBitcoinEventCalendar::class)
              ->name('ics');
         Route::get('overview', \App\Http\Livewire\BitcoinEvent\BitcoinEventTable::class)
              ->name('table.bitcoinEvent');
     });


/*
 * Meetups
 * */
Route::middleware([
    'needMeetup',
])
     ->as('meetup.')
     ->prefix('/{country:code}/meetup')
     ->group(function () {
         Route::get('stream-calendar', \App\Http\Controllers\DownloadMeetupCalendar::class)
              ->name('ics');
         Route::get('world', \App\Http\Livewire\Meetup\WorldMap::class)
              ->name('world');
         Route::get('overview', \App\Http\Livewire\Meetup\MeetupTable::class)
              ->name('table.meetup');
         Route::get('/meetup-events', \App\Http\Livewire\Meetup\MeetupEventTable::class)
              ->name('table.meetupEvent');
         Route::get('/{meetup:slug}', \App\Http\Livewire\Meetup\LandingPage::class)
              ->name('landing');
     });

/*
 * Authenticated
 * */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'needMeetup',
])
     ->group(function () {
         Route::get('/dashboard', function () {
             return view('dashboard');
         })
              ->name('dashboard');
     });
