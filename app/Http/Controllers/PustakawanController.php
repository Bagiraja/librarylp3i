<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Fine;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\FineNotification;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Notifications\BookBorrowedNotification;
use Illuminate\Notifications\DatabaseNotification;

class PustakawanController extends Controller
{
    public function dashboard()
    {
        return view('pustakawan.dashboard', [
            'userCount' => User::count(),
            'bookCount' => Book::count(),
            'borrowCount' => Borrow::count(),
            // New statistics for fines
            'paidFinesCount' => Fine::where('is_paid', true)->count(),
            'unpaidFinesCount' => Fine::where('is_paid', false)->count(),
            'totalPaidAmount' => Fine::where('is_paid', true)->sum('amount'),
            'recentPaidFines' => Fine::where('is_paid', true)
                ->with(['user', 'borrow.book'])
                ->orderBy('paid_at', 'desc')
                ->take(5)
                ->get()
        ]);
    }
  
  
      public function index(Request $request)
      {
          $query = Book::query();
  
          // Handle search
          if ($request->has('search') && $request->search != '') {
              $searchTerm = $request->search;
              $query->where(function($q) use ($searchTerm) {
                  $q->where('judul', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('pengarang', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('penerbit', 'LIKE', "%{$searchTerm}%");
              });
          }
  
          $books = $query->paginate(4);
  
          // Append search query to pagination links if search is active
          if ($request->has('search')) {
              $books->appends(['search' => $request->search]);
          }
  
          return view('pustakawan.book.index', compact('books'));
      }
  
      public function profile(){
          return view('pustakawan.profile.index', ['user' => Auth::user()]);
      }
  
      public function profileupdt(Request $request){
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
  
      public function create(){
          return view('pustakawan.book.create');
      }
  
      public function store(Request $request) {
          $request->validate([
              'judul' => 'required',
              'pengarang' => 'required',
              'penerbit' => 'required',
              'tahun_terbit' => 'required|string',
              'rak' => 'required',
              'jumlah' => 'required|integer',
              'category' => 'required',
              'image' => 'image|mimes:jpeg,png,jpg|max:2048',
          ]);
      
          if ($request->hasFile('image')) {
              $fileName = time().'.'.$request->image->extension();
              $request->image->move(public_path('uploads'), $fileName);
          } else {
              $fileName = null;
          }
      
          Book::create([
              'judul' => $request->judul,
              'pengarang' => $request->pengarang,
              'penerbit' => $request->penerbit,
              'tahun_terbit' => $request->tahun_terbit,
              'rak' => $request->rak,
              'jumlah' => $request->jumlah,
              'category' => $request->category,
              'image' => $fileName,
          ]);
      
          return redirect()->route('pustakawan.book')->with('success', 'Buku Berhasil Di Tambahkan');
      }
      
      public function update(Request $request, $id) {
          $book = Book::findOrFail($id);
      
          $request->validate([
              'judul'=> 'required',
              'pengarang' => 'required',
              'penerbit' => 'required',
              'tahun_terbit' => 'required|string',
              'rak' => 'required',
              'jumlah' => 'required|integer',
              'category' => 'required',
              'image' => 'image|mimes:jpeg,png,jpg|max:2048',
          ]);
      
          if($request->hasFile('image')) {
              $fileName = time().'.'.$request->image->extension();
              $request->image->move(public_path('uploads'), $fileName);
              $book->image = $fileName;
          }
      
          $book->update([
              'judul' => $request->judul,
              'pengarang' => $request->pengarang,
              'penerbit' => $request->penerbit,
              'tahun_terbit' => $request->tahun_terbit,
              'rak' => $request->rak,
              'jumlah' => $request->jumlah,
              'category' => $request->category,
              'image' => $book->image,
          ]);
      
          return redirect()->route('pustakawan.book')->with('success', 'Buku Berhasil Di Update');
      }
  
      public function edit($id){
          $book = Book::findOrFail($id);
          return view('pustakawan.book.edit', compact('book'));
      }
  
      public function delete($id){
          $book = Book::findOrFail($id);
          $book->delete();
          return redirect()->route('pustakawan.book')->with('success', 'Buku berhasil di hapus');
      }
  
      public function import(Request $request)
      {
          $request->validate([
              'excel_file' => 'required|mimes:xlsx,xls'
          ]);
  
          $file = $request->file('excel_file');
          $spreadsheet = IOFactory::load($file->getPathname());
          $worksheet = $spreadsheet->getActiveSheet();
          $rows = $worksheet->toArray();
  
          array_shift($rows);
  
          foreach ($rows as $row) {
              if (!empty($row[0])) {
                  Book::create([
                      'judul' => $row[0],
                      'pengarang' => $row[1],
                      'penerbit' => $row[2],
                      'tahun_terbit' => $row[3],
                      'rak' => $row[4],
                      'jumlah' => $row[5],
                      'category' => $row[6],
                  ]);
              }
          }
  
          return redirect()->route('pustakawan.book')
              ->with('success', 'Data buku berhasil diimport!');
      }
  
      public function template()
      {
          $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
  
          $headers = ['Judul', 'Pengarang', 'Penerbit', 'Tahun Terbit', 'Rak', 'Jumlah', 'Category'];
          $sheet->fromArray([$headers], NULL, 'A1');
  
          $example = [
              'Laskar Pelangi',
              'Andrea Hirata',
              'Bentang Pustaka',
              '2005',
              'A1',
              '10',
              'Novel'
          ];
          $sheet->fromArray([$example], NULL, 'A2');
  
          $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
          
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment;filename="template_import_buku.xlsx"');
          header('Cache-Control: max-age=0');
  
          $writer->save('php://output');
          exit;
      }
  
      public function indexpustakawan()
      {
          $requestApprovals = Borrow::where('status', 'pending')
              ->with(['book', 'user'])
              ->get()
              ->map(function($request) {
                  $request->created_at = Carbon::parse($request->created_at);
                  return $request;
              });
  
          $beingBorrowed = Borrow::where('status', 'borrowed')
              ->where('due_date', '>=', now())
              ->with(['book', 'user'])
              ->get()
              ->map(function($borrow) {
                  $borrow->due_date = Carbon::parse($borrow->due_date);
                  return $borrow;
              });
  
          $lateReturns = Borrow::where('status', 'returns')
              ->where('due_date', '<', now())
              ->with(['book', 'user'])
              ->get()
              ->map(function($late) {
                  $late->due_date = Carbon::parse($late->due_date);
                  return $late;
              });
  
              $requestApprovals = Borrow::where('status', 'pending')->paginate(2); // pastikan paginate di sini
              $beingBorrowed = Borrow::where('status', 'borrowed')->paginate(2);
              $lateReturns = Borrow::where('status', 'returns')->paginate(10);
              
  
          return view('pustakawan.borrowing.index', compact(
              'requestApprovals',
              'beingBorrowed',
              'lateReturns'
          ));
      }
  
      public function approve($id)
    {
        $borrowing = Borrow::findOrFail($id);

        if ($borrowing->book->jumlah <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak tersedia'
            ]);
        }

        $borrowing->update([
            'status' => 'borrowed',
            'approved_at' => now(),
            'due_date' => Carbon::now()->addDays(7)
        ]);

        $borrowing->book->decrement('jumlah');
        
        // Send notification to student
        $borrowing->user->notify(new BookBorrowedNotification($borrowing));

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil disetujui'
        ]);
    }

    public function dissapprove($id)
    {
        $borrowing = Borrow::findOrFail($id);
        $borrowing->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        // Send notification to student
        $borrowing->user->notify(new BookBorrowedNotification($borrowing));

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman ditolak'
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        // Verify the notification belongs to the authenticated user
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }

    public function lost($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->status = 'lost';
        $borrow->save();
    
        // Create fine record
        $fine = Fine::create([
            'borrow_id' => $borrow->id,
            'user_id' => $borrow->user_id,
            'fine_type' => 'lost',
            'amount' => 100000.00
        ]);
    
        // Send notification to student
        $borrow->user->notify(new FineNotification($fine));
    
        return response()->json(['success' => true]);
    }
    
    public function broken($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->status = 'broken';
        $borrow->save();
    
        // Create fine record
        $fine = Fine::create([
            'borrow_id' => $borrow->id,
            'user_id' => $borrow->user_id,
            'fine_type' => 'broken',
            'amount' => 50000.00
        ]);
    
        // Send notification to student
        $borrow->user->notify(new FineNotification($fine));
    
        return response()->json(['success' => true]);
    }

    public function returnn($id)
    {
        try {
            $borrow = Borrow::findOrFail($id);
            
            $dueDate = Carbon::parse($borrow->due_date);
            $now = Carbon::now();
            
            $borrow->status = 'returned';
            $borrow->returned_at = $now;
            $borrow->save();
            
            $borrow->book()->increment('jumlah');
            
            if ($now->gt($dueDate)) {
                $daysLate = $now->diffInDays($dueDate);
                $fineAmount = $daysLate * 1000;
                
                $fine = Fine::create([
                    'borrow_id' => $borrow->id,
                    'user_id' => $borrow->user_id,
                    'fine_type' => 'late',
                    'amount' => $fineAmount,
                ]);

                // Send notification for late return fine
                $borrow->user->notify(new FineNotification($fine));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage()
            ]);
        }
    }
      public function indexfines()
      {
          $lateFines = Fine::where('fine_type', 'late')
              ->with(['user', 'borrow.book'])
              ->latest()
              ->get();
  
          $brokenFines = Fine::where('fine_type', 'broken')
              ->with(['user', 'borrow.book'])
              ->latest()
              ->get();
  
          $lostFines = Fine::where('fine_type', 'lost')
              ->with(['user', 'borrow.book'])
              ->latest()
              ->get();
  
              
            $lateFines = Fine::where('fine_type', 'late')->paginate(10); // pastikan paginate di sini
            $brokenFines = Fine::where('fine_type', 'broken')->paginate(10);
            $lostFines = Fine::where('fine_type', 'lost')->paginate(10);
            
          return view('pustakawan.fine.index', compact('lateFines', 'brokenFines', 'lostFines'));
      }
  
      public function markAsPaid($id)
      {
          try {
              $fine = Fine::findOrFail($id);
              
              if ($fine->is_paid) {
                  return response()->json([
                      'success' => false,
                      'message' => 'Denda ini sudah dibayar'
                  ]);
              }
  
              $fine->is_paid = true;
              $fine->paid_at = now();
              $fine->save();
  
              return response()->json([
                  'success' => true,
                  'message' => 'Denda berhasil ditandai sebagai lunas'
              ]);
          } catch (\Exception $e) {
              return response()->json([
                  'success' => false,
                  'message' => 'Terjadi kesalahan: ' . $e->getMessage()
              ], 500);
          }
      }
}
