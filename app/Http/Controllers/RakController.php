<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use Illuminate\Http\Request;

class RakController extends Controller
{
    public function index(){
        $raks = Rak::all();

        return view('admin.raks.index', compact('raks'));
    }

    public function create(){
        return view('admin.raks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rak' => 'required',
        ]);
    
        // Assuming you only want to store the 'name' field
        Rak::create([
            'nama_rak' => $request->nama_rak,
        ]);
    
        return redirect()->route('admin.rak')->with('success', 'Rak successfully added');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_rak' => 'required',
        ]);
    
        $rak = Rak::findOrFail($id);
        $rak->update([
            'nama_rak' => $request->nama_rak,
        ]);
    
        return redirect()->route('admin.rak')->with('success', 'Rak successfully updated');
    }
    
    public function edit($id){
        $rak = Rak::findOrFail($id);
        return view('admin.raks.edit', compact('rak'));
    }

    public function delete($id){
        $rak = Rak::findOrFail($id);
        $rak->delete();
        return redirect()->route('admin.rak')->with('success', 'Rak successfully deleted');
    }
}
