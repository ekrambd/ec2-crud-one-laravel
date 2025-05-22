<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try
        {
            $posts = Post::latest()->get();
            return response()->json(['status' => count($posts) > 0, 'data'=>$posts]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:50|unique:posts',
                'category_id' => 'required|integer|exists:categories,id',
                'description' => 'nullable',
            ]);

            if($validator->fails()){
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

            $post = new Post();
            $post->title = $request->title;
            $post->category_id = $request->category_id;
            $post->description = $request->description;
            $post->save();

            return response()->json(['status'=>true, 'post_id'=>intval($post->id), 'message'=>'Successfully a post has been added']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json(['status'=>true, 'data'=>$post]);
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
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:50|unique:posts,title,' . $post->id,
                'category_id' => 'required|integer|exists:categories,id',
                'description' => 'nullable',
            ]);

            if($validator->fails()){
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

            $post->update($request->validated());

            return response()->json(['status'=>true, 'post_id'=>intval($post->id), 'message'=>'Successfully the post has been updated']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try
        {
            $post->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the post has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
