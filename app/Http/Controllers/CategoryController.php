<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();

        return view('admin.categorys.index', compact('categories'));
    }

    public function create(){
        return view('admin.categorys.create');
    }

    public function store(Request $request){
        $request->validate([
             'nama_category' => 'required',
        ]);

        Category::create([
            'nama_category' => $request->nama_category,
        ]);

        return redirect()->route('admin.category')->with('success', 'Category succesfully added');
    }

    public function update(Request $request, $id){
       $request->validate([
        'nama_category' => 'required',
       ]);

       $category = Category::findOrFail($id);
       $category->update([
        'nama_category' => $request->nama_category,
       ]);

       return redirect()->route('admin.category')->with('success', 'Category succesfully updated');
    }


    public function edit($id){
        $category = Category::findOrFail($id);
        return view('admin.categorys.edit', compact('category'));
    }

    public function delete($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.category')->with('success', 'Category succesfully deleted');
    }
}
