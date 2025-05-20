<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $categories = Category::latest()->get();
            return response()->json(['status'=>count($categories) > 0, 'total'=>count($categories), 'data'=>$categories]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
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
                'category_name' => 'required|string|max:50|unique:categories',
                'status' => 'required|in:Active,Inactive',
            ]);

            if($validator->fails()){
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

            Category::create([
                'category_name' => $request->category_name,
                'status' => $request->status,
            ]);

            return response()->json(['status'=>true, 'message'=>'Successfully a category has been added']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(['status'=>true, 'category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string|max:50|unique:categories,category_name,' . $category->id,
                'status' => 'required|in:Active,Inactive',
            ]);

            if($validator->fails()){
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

            $category->category_name = $request->category_name;
            $category->status = $request->status;
            $category->update();

            return response()->json(['status'=>true, 'message'=>'Successfully the category has been updated']);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try
        {
            $category->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the category has been deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }

    }
}
