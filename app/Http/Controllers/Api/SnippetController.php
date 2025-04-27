<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Snippet;
use Illuminate\Http\Request;
use App\Enums\SnippetLanguage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\SnippetResource;
use Illuminate\Support\Facades\Cache;

class SnippetController extends Controller
{
	public function index(Request $request)
	{
		$cacheKey = 'snippets_' . md5(serialize($request->all()));
        $expiration = now()->addMinutes(30);

		return Cache::tags(['snippets'])->remember($cacheKey, $expiration, function () use ($request) {
			$query = Snippet::with(['user', 'comments.user', 'likes'])->withCount(['comments', 'likes']);

			if ($request->has('language') && $request->input('language') !== '') {
				$query->where('language', $request->input('language'));
			}

			$snippets = $query->paginate($request->get('perpage', 5));

			// sleep(3);

			return SnippetResource::collection($snippets);
		});
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'code' => 'required|string',
			'language' => 'required|string|in:' . implode(',', SnippetLanguage::all()),
		]);

		try {
			$snippet = Snippet::create([
				'title' => strip_tags($validated['title']),
				'code' => $validated['code'],
				'language' => $validated['language'],
				'user_id' => auth()->id(),
			]);
		} catch (\Exception $e) {
			Log::error('Failed to create snippet', [
				'error' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);

			return response()->json(['message' => 'Server Error'], 500);
		}

		return response()->json(SnippetResource::make($snippet), 201);
	}

	public function storeComment($id, Request $request)
	{
		$validated = $request->validate([
			'comment' => 'required|string|max:1000',
		]);

		try {
			$snippet = Snippet::findOrFail($id);

			$comment = new Comment();
			$comment->snippet_id = $snippet->id;
			$comment->user_id = auth()->id();
			$comment->comment = $validated['comment'];
			$comment->save();

			return response()->json([
				'message' => 'Comment added successfully',
				'comment' => CommentResource::make($comment),
			], 201);
		} catch (\Throwable $e) {
			Log::error('Failed to create comment', [
				'error' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);

			return response()->json(['message' => 'Server Error'], 500);
		}
	}

	public function storeLike($id)
	{
		$snippet = Snippet::findOrFail($id);

		$existingLike = $snippet->likes()->where('user_id', auth()->id())->first();

		if ($existingLike) {
			return response()->json([
				'message' => 'You have already liked this snippet.',
			], 400);
		}

		$snippet->likes()->create([
			'user_id' => auth()->id(),
		]);

		return response()->json([
			'message' => 'Snippet liked successfully',
		], 201);
	}
}
