<?php

$builder = Content::where('created_at', '>', new MongoDate(time() - 3 * 86400));

if (isset($group))
{
    $builder->where('group_id', $group->_id);
}

$popularContents = $builder->remember(60)->orderBy('uv', 'desc')->take(5)->get();

?>

<div class="well popular_contents_widget">
    <h4>Popularne treści</h4>

    <ul class="media-list popular_contents_list">
        @foreach ($popularContents as $content)
        <li class="media">
            @if ($content->thumbnail)
            <a class="pull-left" href="{{ route('content_comments_slug', array($content->_id, Str::slug($content->title))) }}" rel="nofollow" target="_blank">
                <img src="{{ $content->getThumbnailPath(40, 40) }}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
            @endif
            <div class="media-body">
                <h6 class="media-heading"><a href="{{ route('content_comments_slug', array($content->_id, Str::slug($content->title))) }}">{{{ Str::limit($content->title, 50) }}}</a></h6>
                <small>
                    <span class="glyphicon glyphicon-thumbs-up"></span> {{ $content->uv }}
                    <span class="glyphicon glyphicon-thumbs-down"></span> {{ $content->dv }}
                </small>
            </div>
        </li>
        @endforeach
    </ul>
</div>
