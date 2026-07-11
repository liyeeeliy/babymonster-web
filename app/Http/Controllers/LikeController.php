<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ForumPost;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /** Toggle like universal untuk forum post & komentar. */
    public function toggle(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:forum_post,comment',
            'id' => 'required|integer',
        ]);

        $map = [
            'forum_post' => ForumPost::class,
            'comment' => Comment::class,
        ];
        $modelClass = $map[$data['type']];
        $target = $modelClass::findOrFail($data['id']);

        $like = Like::where('user_id', $request->user()->id)
            ->where('likeable_type', $target->getMorphClass())
            ->where('likeable_id', $target->id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $request->user()->id,
                'likeable_type' => $target->getMorphClass(),
                'likeable_id' => $target->id,
            ]);
        }

        return back();
    }
}
