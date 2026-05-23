<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} — Blog</title>
        <link>{{ route('blog.index') }}</link>
        <atom:link href="{{ route('blog.rss') }}" rel="self" type="application/rss+xml" />
        <description>Latest posts from {{ config('app.name') }}.</description>
        <language>en-us</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>

        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('blog.show', $post->slug) }}</link>
                <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
                <pubDate>{{ $post->published_at?->toRfc2822String() }}</pubDate>
                @if ($post->author)
                    <author>noreply@{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost' }} ({{ $post->author->name }})</author>
                @endif
                @if ($post->category)
                    <category>{{ $post->category->name }}</category>
                @endif
                <description>{!! '<![CDATA['.($post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 240)).']]>' !!}</description>
            </item>
        @endforeach
    </channel>
</rss>
