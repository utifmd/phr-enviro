<?php

namespace App\Mapper;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PostMapper implements IPostMapper
{

    function fromAndRawArray(array $post): Post
    {
        return new Post($post);
    }

    function fromCollection(Collection $post): Post
    {
        $model = new Post();
        $model->id = $post->get('id');
        $model->type = $post->get('type');
        $model->title = $post->get('title');
        $model->desc = $post->get('desc');
        $model->user_id = $post->get('user_id');
        $model->created_at = $post->get('created_at');
        $model->updated_at = $post->get('updated_at');
        $model->user = $post->get('user');
        return $model;
    }

    function fromBuilderOrModel(Model|Builder $model): Post
    {
        $post = new Post();
        $post->id = $model['id'];
        $post->type = $model['type'];
        $post->title = $model['title'];
        $post->desc = $model['desc'];
        $post->user_id = $model['user_id'];
        $post->created_at = $model['created_at'];
        $post->updated_at = $model['updated_at'];
        $post->user = $model['user'];
        return $post;
    }

}