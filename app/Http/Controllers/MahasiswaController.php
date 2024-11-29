<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    //

   
    public function index(Request $request)
    {
        $query = Book::query();
    
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%");
            });
        }
    
        // Apply category filter
        if ($request->has('rak') && $request->rak != '') {
            $query->where('rak', $request->rak);
        }
    
        // Get unique categories for the filter buttons
        $raks = Book::distinct()->pluck('rak')->toArray();
    
        // Get the filtered books with pagination
        $books = $query->paginate(12);
    
        return view('mahasiswa.buku.index', compact('books', 'raks'));
    }

    public function borrow($id)
    {
        $book = Book::findOrFail($id);
        
        // Check if user already has a pending or active borrowing for this book
        $existingBorrowing = Borrow::where('user_id', auth()->id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->first();

        if ($existingBorrowing) {
            if ($existingBorrowing->status === 'pending') {
                return redirect()->back()->with('error', 'Anda sudah memiliki permintaan peminjaman yang menunggu persetujuan untuk buku ini.');
            } else {
                return redirect()->back()->with('error', 'Anda masih meminjam buku ini.');
            }
        }

        // Create new borrowing request
        Borrow::create([
            'user_id' => auth()->id(),
            'book_id' => $id,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Permintaan peminjaman buku telah dikirim. Silakan tunggu persetujuan dari admin.');
    }

    public function profile(){
        return view('mahasiswa.profile.index', ['user' => Auth::user()]);
    }

    public function profileupt(Request $request){
        $userId = Auth::id();
        $user = User::find($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $userId,
            'current_password' => 'nullable|required_with:new_password',
            'new_password'  => 'nullable|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok']);
            }
            $updateData['password'] = Hash::make($request->new_password);
        }

        if($request->hasFile('profile_picture')) {
            if($user->profile_picture && Storage::exists('public/profile_picture/' . $user->profile_picture)) {
                Storage::delete('public/profile_picture/' . $user->profile_picture);
        }

        $fileName =  time() . '_' . $request->file('profile_picture')->getClientOriginalName();
        $request->file('profile_picture')->storeAs('public/profile_pictures', $fileName);
        $updateData['profile_picture'] = $fileName;
    }

    User::where('id', $userId)->update($updateData);
    return back()->with('success', 'Profile berhasil diperbarui');

}
}
