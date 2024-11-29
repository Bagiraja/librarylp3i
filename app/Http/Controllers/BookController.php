<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BookController extends Controller
{
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

        return view('admin.books.index', compact('books'));
    }

    public function create(){
        return view('admin.books.create');
    }

    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|unique:books,judul', // Added unique validation
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|string',
            'rak' => 'required',
            'jumlah' => 'required|integer|min:0',
            'category' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.unique' => 'Buku dengan judul ini sudah ada dalam database.' // Custom error message
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
            'jumlah' => (int)$request->jumlah,
            'category' => $request->category,
            'image' => $fileName,
        ]);
    
        return redirect()->route('admin.book')->with('success', 'Buku Berhasil Di Tambahkan');
    }
    
    public function update(Request $request, $id) {
        $book = Book::findOrFail($id);
    
        $request->validate([
            'judul' => 'required|unique:books,judul,'.$id, // Added unique validation excluding current book
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|string',
            'rak' => 'required',
            'jumlah' => 'required|integer|min:0',
            'category' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.unique' => 'Buku dengan judul ini sudah ada dalam database.' // Custom error message
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
            'jumlah' => (int)$request->jumlah,
            'category' => $request->category,
            'image' => $book->image,
        ]);
    
        return redirect()->route('admin.book')->with('success', 'Buku Berhasil Di Update');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        $drawings = $worksheet->getDrawingCollection();
        $imageMapping = [];
        
        foreach ($drawings as $drawing) {
            if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
                $coordinates = $drawing->getCoordinates();
                $imageContent = file_get_contents($drawing->getPath());
                $imageName = time() . '_' . uniqid() . '.' . $drawing->getExtension();
                file_put_contents(public_path('uploads/' . $imageName), $imageContent);
                $imageMapping[$coordinates] = $imageName;
            }
        }

        $rows = $worksheet->toArray();
        array_shift($rows);
        $importErrors = [];

        foreach ($rows as $index => $row) {
            if (!empty($row[0])) {
                $jumlah = filter_var($row[5], FILTER_VALIDATE_INT);
                if ($jumlah === false || $jumlah < 0) {
                    continue;
                }

                try {
                    $imageName = null;
                    $imageCell = 'H' . ($index + 2);
                    if (isset($imageMapping[$imageCell])) {
                        $imageName = $imageMapping[$imageCell];
                    }

                    // Check for duplicate title before creating
                    if (Book::where('judul', trim($row[0]))->exists()) {
                        $importErrors[] = "Baris " . ($index + 2) . ": Buku dengan judul '" . trim($row[0]) . "' sudah ada dalam database.";
                        continue;
                    }

                    Book::create([
                        'judul' => trim($row[0]),
                        'pengarang' => trim($row[1]),
                        'penerbit' => trim($row[2]),
                        'tahun_terbit' => trim($row[3]),
                        'rak' => trim($row[4]),
                        'jumlah' => $jumlah,
                        'category' => trim($row[6]),
                        'image' => $imageName,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error importing book row: ' . json_encode($row) . ' Error: ' . $e->getMessage());
                    $importErrors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }
        }

        if (!empty($importErrors)) {
            return redirect()->route('admin.book')
                ->with('warning', 'Import selesai dengan beberapa kesalahan: ' . implode('<br>', $importErrors));
        }

        return redirect()->route('admin.book')
            ->with('success', 'Data buku berhasil diimport!');
    }
    
    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $headers = ['Judul', 'Pengarang', 'Penerbit', 'Tahun Terbit', 'Rak', 'Jumlah', 'Category', 'Image'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Add example data
        $example = [
            'Laskar Pelangi',
            'Andrea Hirata',
            'Bentang Pustaka',
            '2005',
            'A1',
            10,
            'Novel',
            '' // Image cell will be empty, user can paste image here
        ];
        $sheet->fromArray([$example], NULL, 'A2');

        // Set 'Jumlah' column to Number format
        $sheet->getStyle('F2')->getNumberFormat()->setFormatCode('0');

        // Add notes about image
        $sheet->setCellValue('I1', 'Catatan:');
        $sheet->setCellValue('I2', '1. Paste gambar langsung ke kolom Image');
        $sheet->setCellValue('I3', '2. Ukuran gambar max 2MB');
        $sheet->setCellValue('I4', '3. Format: JPG, PNG');

        // Set column widths
        $sheet->getColumnDimension('H')->setWidth(30); // Make image column wider
        foreach(range('A','G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Protect cells except image column
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle('H2:H1000')->getProtection()
            ->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_buku.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
     
    public function edit($id){
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function delete($id){
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('admin.book')->with('success', 'Buku berhasil dihapus');
    }

}