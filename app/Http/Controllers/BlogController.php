<?php

namespace App\Http\Controllers;

use App\Jobs\BlogIndexData;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {

        $tag = $request->get('tag');
        $data = $this->dispatch(new BlogIndexData($tag));
        $layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';
        return view($layout,$data);
    }

    public function showPost($id,Request $request)
    {

        $post = Post::with('tags')->findOrFail($id);
        $tag=$request->get('tag');
        if ($tag)
        {
            $tag=Tag::whereTag($tag)->firstOrFail();
        }
        return view($post->layout,compact('post','tag'));
    }
}
