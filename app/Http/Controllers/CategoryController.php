<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{

    public function __construct() {
        $this->authorizeResource(Category::class, 'category');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category;

        $category->name = $request->name;
        $category->parent_id = $request->parent_category ? $request->parent_category : 0;

        if ($category->save()) {
            return redirect()->back()->with(['success' => 'Category created successfully']);
        }

        return redirect()->back()->with(['fail' => 'Unable to create category']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->name = $request->name;
        $category->parent_id = $request->parent_category ? $request->parent_category : 0;

        if ($category->save()) {
            return redirect()->back()->with(['success' => 'Category updated successfully']);
        }

        return redirect()->back()->with(['fail' => 'Unable to update category']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        if ($category->delete()) {
            return redirect()->back()->with(['success' => 'Category deleted successfully']);
        }

        return redirect()->back()->with(['fail' => 'Unable to delete category']);
    }
}
