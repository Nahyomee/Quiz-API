<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Resources\V1\CategoryCollection;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends BaseController
{

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(new CategoryCollection(Category::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:categories,name']
        ]);
        try{
            $category = Category::create($request->all() + ['slug' => Str::slug($request->name)]);
            if($category){
                return $this->sendResponse(new CategoryResource($category), 'Category created');
            }
            else{
                return $this->sendError('Error creating category');
            }
        }catch(\Throwable $th){
            return $this->sendError('Error creating category', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->sendResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('categories')->ignore($category)]
        ]);
        try{
            $category->update($request->all());
            if($category->wasChanged('name')){
                $category->slug = Str::slug($request->name);
                $category->save();
            }
            return $this->sendResponse(new CategoryResource($category), 'Category updated');
        }catch(\Throwable $th){
            return $this->sendError('Error updating category', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
        } catch (\Throwable $th) {
            return $this->sendError('Error in deleting category.', $th->getMessage());
        }
        return $this->sendResponse(null, 'Category deleted successfully');
    }
}
