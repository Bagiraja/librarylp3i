<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\Borrow;
use Illuminate\Http\Request;

class FineController extends Controller
{
    //
    public function index()
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

        return view('admin.fine.index', compact('lateFines', 'brokenFines', 'lostFines'));
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
