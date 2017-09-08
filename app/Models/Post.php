<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\Markdowner;

class Post extends Model
{
    protected $dates = ['published_at'];
    protected $fillable = [
        'title', 'subtitle', 'content_raw', 'page_image', 'meta_description', 'layout', 'is_draft', 'published_at',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (!$this->exists) {
            $this->attributes['slug'] = str_slug($value);
        }
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'post_tag_pivot')->withTimestamps();
    }

    protected function setUniqueSlug($title, $extra)
    {
        $slug = str_slug($title . '-' . $extra);
        if (static::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }
        $this->attributes['slug'] = $slug;
    }

    public function setContentRawAttribute($value)
    {
        $markdown = new Markdowner();
        $this->attributes['content_raw'] = $value;
        $this->attributes['content_html'] = $markdown->toHtml($value);
    }

    public function syncTags(array $tags)
    {
        Tag::addNeededTags($tags);
        if (count($tags)) {
            $this->tags()->sync(Tag::whereIn('tag', $tags)->lists('id')->all());
            return;
        }
        $this->tags()->detach();
    }

    /**
     * Return the date portion of published_at
     */
    public function getPublishDateAttribute($value)
    {
        return $this->published_at->format('Y-m-d');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value)
    {
        return $this->published_at->format('H:i:s');
    }

    /**
     * Alias for content_raw
     */
    public function getContentAttribute($value)
    {
        return $this->content_raw;
    }

    public function url(Tag $tag = null)
    {
        $url = url('blog/' . $this->id);
        if ($tag) {
            $url .= '?tag=' . urlencode($tag->tag);
        }
        return $url;
    }

    public function tagLinks($base = '/blog?tag=%TAG%')
    {
        $tags = $this->tags()->lists('tag');
        $return = [];
        foreach ($tags as $tag) {
            $url = str_replace('%TAG%', urlencode($tag), $base);
            $return[] = '<a href="' . $url . '">' . e($tag) . '</a>';
        }
        return $return;
    }
    public function newerPost(Tag $tag=null)
    {
        $query=static::where('published_at','<=',Carbon::now())
            ->where('published_at','>',$this->published_at)
            ->where('is_draft',0)
            ->orderBy('published_at','asc');
        if ($tag)
        {
            $query=$query->whereHas('tags',function ($q) use ($tag){
                $q->where('tag','=',$tag);
            });
        }
        return $query->first();
    }
    public function olderPost(Tag $tag=null)
    {
        $query=static::where('published_at','<',$this->published_at)
            ->where('is_draft',0)
            ->orderBy('published_at','asc');
        if ($tag)
        {
            $query=$query->whereHas('tags',function ($q) use ($tag){
                $q->where('tag','=',$tag);
            });
        }
        return $query->first();
    }
}
