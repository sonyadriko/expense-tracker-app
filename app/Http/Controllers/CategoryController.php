<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::forUser(Auth::id())
            ->where('is_archived', false)
            ->withCount('transactions')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Auth::user()->categories()->create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $transactions = $category->transactions()
            ->forUser(Auth::id())
            ->with(['wallet', 'attachments'])
            ->latest('occurred_at')
            ->paginate(20);

        return view('categories.show', compact('category', 'transactions'));
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->count() > 0) {
            $category->update(['is_archived' => true]);
            $message = 'Category archived successfully (has transactions).';
        } else {
            $category->delete();
            $message = 'Category deleted successfully.';
        }

        return redirect()->route('categories.index')
            ->with('success', $message);
    }
}
