<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store($id, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $author = customer()->user();

        Comment::create(array_merge($data, [
            'service_id' => $id
        ]))
            ->commentable()
            ->associate($author)
            ->save();


        return back()->withFragment('comment');
    }
}
