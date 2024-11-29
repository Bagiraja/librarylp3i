<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fine;
use App\Models\Borrow;
use Illuminate\Http\Request;
use App\Notifications\FineNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookBorrowedNotification;


class PeminjamanController extends Controller
{
    public function index()
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
            

        return view('admin.borrowing.index', compact(
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

    public function disapprove($id)
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

    public function markAsLost($id)
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
    
    public function markAsBroken($id)
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

    public function return($id)
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
}