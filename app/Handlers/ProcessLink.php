<?php

namespace Strimoid\Handlers;

use Strimoid\Facades\Guzzle;
use Strimoid\Models\Content;

class ProcessLink
{
    public function fire($job, $data): void
    {
        $content = Content::findOrFail($data['id']);

        $url = config('app.iframely_host') . '/oembed';
        $response = Guzzle::get($url, [
            'query' => ['url' => $content->url],
        ])->json();

        $content->type = $response['type'];
        $content->save();

        if ($data['thumbnail'] && array_key_exists('thumbnail_url', $response)) {
            $content->setThumbnail($response['thumbnail_url']);
        }

        $content->autoThumbnail();

        WS::send(json_encode([
            'topic' => 'content.' . $content->getKey() . '.thumbnail',
            'url' => $content->getThumbnailPath(100, 75),
        ], JSON_THROW_ON_ERROR));

        $content->unset('thumbnail_loading');

        $job->delete();
    }
}
