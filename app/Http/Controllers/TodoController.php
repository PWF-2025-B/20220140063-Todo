<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::all();
        $todos = Todo::where('user_id', Auth::id())
            ->orderBy(
                'created_at',
                'desc'
            )
            ->get();
        // dd($todos);
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->count();

        return view('todo.index', compact('todos', 'todosCompleted'));
        
    }
    public function create()
    {
        $categories = Category::all();

        return view('todo.create', compact('categories'));
    }
    // edit todo
    public function edit(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $categories = Category::all();
            return view('todo.edit', compact('todo', 'categories'));
        }
    
        return redirect()
            ->route('todo.index')
            ->with('danger', 'You are not authorized to edit this todo!');
    }
    // update todo
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);
    
        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);
    
        return redirect()
            ->route('todo.index')
            ->with('success', 'Todo updated successfully!');
    }
    // update data complete
    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_done' => true,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    // update data incomplete
    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update([
                'is_done' => false,
            ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    // destroy todo
    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();

            return redirect()
                ->route('todo.index')
                ->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()
                ->route('todo.index')
                ->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    // destroy allcompleted
    public function destroyCompleted()
    {
        // Get all todos for current user where is_complete is true
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $data) {
            $data->delete();
        }

        return redirect()
            ->route('todo.index')
            ->with('success', 'All completed todos deleted successfully!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully');
    }
}
