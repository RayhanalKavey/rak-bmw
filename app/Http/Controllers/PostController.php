<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Show all posts (Public posts)
     */
    public function allPost()
    {
        $posts = Post::with('user', 'tags')
            ->where('visibility', 'public')
            ->with('tags')
            ->latest()
            ->get();
        return Inertia::render('HomePage', [
            'posts' => $posts,
        ]);
    }
    public function createPostPage(Request $request)
    {
        try {
            $user_id = $request->header('id');
            User::where('id', $user_id)->first();
            return Inertia::render('CreatePostPage');
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createPost(Request $request)
    {
        try {
            $user_id = $request->header('id');
            User::where('id', $user_id)->select('id')->first();

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'visibility' => 'required|in:public,private', // assuming visibility is an enum with 'public' and 'private'
                'tags' => 'nullable|array', // Array of tag names
                'tags.*' => 'nullable|string|distinct', // Ensure tags are unique
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048', // optional image upload
            ]);

            $data = [
                'user_id' => $user_id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'visibility' => $request->input('visibility'),
            ];
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'postsImage/' . $fileName;
                $image->move(public_path('postsImage'), $fileName);
                $data['image'] = $filePath;
            }
            $post = Post::create($data);

            if ($request->has('tags')) {
                $tags = $request->input('tags');

                // Filter out empty or null tags
                $tags = array_filter($tags, fn($tag) => !empty($tag));

                // Loop through the tags and either create new tags or associate existing ones
                foreach ($tags as $tagName) {
                    // Create the tag if it doesn't exist, or retrieve it
                    $tag = Tag::firstOrCreate(['name' => $tagName]);

                    // Associate the tag with the post
                    $post->tags()->attach($tag);
                }
            }
            $data = ['message' => 'Post created successfully', 'status' => true, 'error' => ''];
            return redirect('/')->with($data);

        } catch (Exception $e) {
            $data = ['message' => $e->getMessage(), 'status' => false, 'error' => ''];
            return redirect('/')->with($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updatePost(Request $request, $id)
    {
        try {
            $user_id = $request->header('id');

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
                'visibility' => 'sometimes|required|in:public,private',
                'tags' => 'nullable|array',
                'tags.*' => 'nullable|string|distinct',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            ]);

            $post = Post::where('id', $id)->where('user_id', $user_id)->firstOrFail();

            // Update basic fields
            $post->fill($request->only('title', 'content', 'visibility'));

            // Image upload handling
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($post->image && file_exists(public_path($post->image))) {
                    unlink(public_path($post->image));
                }

                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $filePath = 'postsImage/' . $fileName;
                $image->move(public_path('postsImage'), $fileName);
                $post->image = $filePath;
            }

            $post->save();

            // Tag syncing
            if ($request->has('tags')) {
                $tags = array_filter($request->input('tags'), fn($tag) => !empty($tag));
                $tagIds = [];

                foreach ($tags as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }

                $post->tags()->sync($tagIds);
            }

            $data = ['message' => 'Post updated successfully', 'status' => true, 'error' => ''];
            return redirect('/')->with($data);
        } catch (Exception $e) {
            $data = ['message' => $e->getMessage(), 'status' => false, 'error' => ''];
            return redirect('/')->with($data);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
