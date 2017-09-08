@extends('blog.layouts.master', [
  'title' => $post->title,
  'meta_description' => $post->meta_description ?: config('blog.description'),
])

@section('page-header')
    <header class="intro-header"
            style="background-image: url('{{ page_image($post->page_image) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                        <h2 class="subheading">{{ $post->subtitle }}</h2>
                        <span class="meta">
              发布于 {{ $post->published_at->format('Y-m-d') }}
                            @if ($post->tags->count())
                                in
                                {!! join(', ', $post->tagLinks()) !!}
                            @endif
            </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')

    {{-- The Post --}}
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    {!! $post->content_raw !!}
                </div>
            </div>
        </div>
    </article>

    {{-- The Pager --}}
    <div class="container">
        <div class="row">
            <ul class="pager">
                @if ($tag && $tag->reverse_direction)
                    @if ($post->olderPost($tag))
                        <li class="previous">
                            <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                                <i class="fa fa-long-arrow-left fa-lg"></i>
                                上一篇 {{ $tag->tag }} 文章
                            </a>
                        </li>
                    @endif
                    @if ($post->newerPost($tag))
                        <li class="next">
                            <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                                下一篇 {{ $tag->tag }} 文章
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </li>
                    @endif
                @else
                    @if ($post->newerPost($tag))
                        <li class="previous">
                            <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                                <i class="fa fa-long-arrow-left fa-lg"></i>
                                下一篇更新的 {{ $tag ? $tag->tag : '' }} 文章
                            </a>
                        </li>
                    @endif
                    @if ($post->olderPost($tag))
                        <li class="next">
                            <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                                下一篇更老的 {{ $tag ? $tag->tag : '' }} 文章
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>

    </div>
@stop