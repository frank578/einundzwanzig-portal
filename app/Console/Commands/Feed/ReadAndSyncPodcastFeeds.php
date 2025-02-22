<?php

namespace App\Console\Commands\Feed;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ReadAndSyncPodcastFeeds extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'feed:sync';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $client = new \PodcastIndex\Client([
            'app'    => 'Einundzwanzig School',
            'key'    => config('feeds.services.podcastindex-org.key'),
            'secret' => config('feeds.services.podcastindex-org.secret'),
        ]);
        $feedIds = [
            185230, // Einundzwanzig, der Bitcoin Podcast
            4627128, // Nodesignal - Deine Bitcoin-Frequenz
            4426306, // Pleb's Taverne
            4409506, // Sound Money Bitcoin Podcast
        ];

        foreach ($feedIds as $feedId) {
            $podcast = $client->podcasts->byFeedId($feedId)
                                        ->json();
            $importPodcast = Podcast::query()
                                           ->updateOrCreate(['guid' => $podcast->feed->podcastGuid], [
                                               'title'         => $podcast->feed->title,
                                               'link'          => $podcast->feed->link,
                                               'language_code' => $podcast->feed->language,
                                               'data'          => $podcast->feed,
                                               'created_by'    => 1,
                                           ]);
            $episodes = $client->episodes->withParameters(['max' => 10000])
                                         ->byFeedId($feedId)
                                         ->json();
            foreach ($episodes->items as $item) {
                Episode::query()
                       ->updateOrCreate(['guid' => $item->guid], [
                           'podcast_id' => $importPodcast->id,
                           'data'       => $item,
                           'created_by' => 1,
                           'created_at' => Carbon::parse($item->datePublished),
                       ]);
            }
        }

        return Command::SUCCESS;
    }
}
