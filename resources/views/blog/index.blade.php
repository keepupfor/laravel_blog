@extends('layouts.default')
@section('content')
    <h1>{{ config('blog.title') }}</h1>
    <h5>第 {{ $posts->currentPage() }} / {{ $posts->lastPage() }}页</h5>
    <hr>
    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                <em>({{ $post->published_at->diffForHumans() }})</em>
                <p>
                    {{ str_limit($post->content) }}
                </p>
            </li>
        @endforeach
    </ul>
    <hr>
    {!! $posts->render() !!}
@stop