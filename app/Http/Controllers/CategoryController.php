<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('category.index', compact('categories'));
    }
    public function create()
    {
        return view('category.create');
    }

    public function edit(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            return view('category.edit', compact('category'));
        } else {
            return redirect()->rpute('category.index')->with('danger', 'You are not authorized to edit this category!');
        }
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $category->update([
            'title' => ucfirst($request->title),
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');

        } else {
            return redirect()->route('category.index')->with('danger', 'You are not authorized to deleted this category!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Category::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('category.index')
            ->with('success', 'Category created successfully!');
    }

}
