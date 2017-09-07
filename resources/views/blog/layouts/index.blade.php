@extends('blog.layouts.master')

@section('page-header')
    <header class="intro-header"
            style="background-image: url('{{ page_image($page_image) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>{{ $title }}</h1>
                        <hr class="small">
                        <h2 class="subheading">{{ $subtitle }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                {{-- 文章列表 --}}
                @foreach($posts as $post)
                    <div class="post-preview">
                        <a href="{{ $post->url($tag) }}">
                            <h2 class="post-title">{{ $post->title }}</h2>
                            @if ($post->subtitle)
                                <h3 class="post-subtitle">{{ $post->subtitle }}</h3>
                            @endif
                        </a>
                        <p class="post-meta">
                            发布于 {{ $post->published_at->format('Y-m-d') }}
                            @if ($post->tags->count())
                                标签:
                                {!! join(', ', $post->tagLinks()) !!}
                            @endif
                        </p>
                    </div>
                    <hr>
                @endforeach

                {{-- 分页 --}}
                <ul class="pager">

                    {{-- Reverse direction --}}
                    @if ($reverse_direction)
                        @if ($posts->currentPage() > 1)
                            <li class="previous">
                                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                                    <i class="fa fa-long-arrow-left fa-lg"></i>
                                    Previous {{ $tag->tag }} Posts
                                </a>
                            </li>
                        @endif
                        @if ($posts->hasMorePages())
                            <li class="next">
                                <a href="{!! $posts->nextPageUrl() !!}">
                                    Next {{ $tag->tag }} Posts
                                    <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        @endif
                    @else
                        @if ($posts->currentPage() > 1)
                            <li class="previous">
                                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                                    <i class="fa fa-long-arrow-left fa-lg"></i>
                                    更新的 {{ $tag ? $tag->tag : '' }} 文章
                                </a>
                            </li>
                        @endif
                        @if ($posts->hasMorePages())
                            <li class="next">
                                <a href="{!! $posts->nextPageUrl() !!}">
                                    更老的 {{ $tag ? $tag->tag : '' }} 文章
                                    <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>

        </div>
    </div>
@stop