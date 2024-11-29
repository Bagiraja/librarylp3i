<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Fine;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\CssSelector\Node\FunctionNode;

class AdminController extends Controller
{
    
  public function dashboard()
  {
      $recentPaidFines = Fine::where('is_paid', true)
          ->with(['user', 'borrow.book'])
          ->orderBy('paid_at', 'desc')
          ->paginate(5);
  
      return view('admin.dashboard', [
          'userCount' => User::count(),
          'bookCount' => Book::count(),
          'borrowCount' => Borrow::count(),
          'paidFinesCount' => Fine::where('is_paid', true)->count(),
          'unpaidFinesCount' => Fine::where('is_paid', false)->count(),
          'totalPaidAmount' => Fine::where('is_paid', true)->sum('amount'),
          'recentPaidFines' => $recentPaidFines
      ]);
  }

    public function manegeuser(){
      $users = User::all(); // Ambil semua data pengguna

      $users = User::paginate(6); 
      return view('admin.users', compact('users'));

    }

    public function updaterole(Request $request, $id){
        $request->validate([
                'role' =>['required', Rule::In(['admin','penggguna','pustakawan'])],
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();


        return redirect()->route('admin.users')->with('success','berhasil update role');
    }

   public function storeuser(Request $request){
       $request->validate([
        'name' => 'required|string|max:255',
        'nim'=> 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
       ]);

       User::create([
        'name' => $request->name,
        'nim' => $request->nim,
        'email' => $request->email,
        'password' => Hash::make($request->password),

       ]);
       return redirect()->route('admin.users')->with('success','berhasil menambahkan user');
   }

   public function edituser($id){
    $user = User::findOrFail($id);
    return view('admin.edituser',compact('user'));
   }

   public function updateuser(Request $request, $id){
      $request->validate([
        'name' => 'required|string|max:255',
        'nim' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
        'role' => ['required' , Rule::in(['admin','pengguna','pustakawan'])],
      ]);

      $user = User::findOrFail($id);
      $user->name = $request->name;
      $user->nim = $request->nim;
      $user->email = $request->email;
      $user->role = $request->role;

      if($request->filled('password')){
        $request->validate([
         'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->password = Hash::make($request->password);
      }

      $user->save();
      return redirect()->route('admin.users')->with('success', 'user berhasil update');
   }

   public function deleteuser($id){
     $user = User::findOrFail($id);
     $user->delete();

     return redirect()->route('admin.users')->with('success','berhasil di delete');
   }

   public function searchuser(Request $request){
      $query = $request->input('query');

      $users = User::where('name', 'LIKE' , "%{$query}%")->
      where('email', 'LIKE' , "%{$query}%")->Orwhere('nim', 'LIKE' ,"%{$query}%")->paginate(6);

      return view('admin.users', compact('users'));
   }

  //  public function indexcarousel()
  //  {
  //      $carousels = Carousel::all();
  //      return view('', compact('carousels'));
  //  }

   // Menampilkan form untuk menambahkan carousel baru
   public function createcarousel()
   {
       return view('admin.carousel.create');
   }

   // Menyimpan data carousel baru
   public function carousel(Request $request)
   {
       $request->validate([
           'image' => 'required|image|mimes:jpeg,png,jpg,gif',
       ]);

       // Menyimpan gambar ke folder public/storage/carousel_images
       $imagePath = $request->file('image')->store('carousel_images', 'public');

       // Menyimpan data ke database
       Carousel::create([
           'image' => $imagePath,
       ]);

       return redirect()->route('admin.carousel')->with('success', 'Gambar carousel berhasil ditambahkan.');
   }

   public function indexcrsl()
{
    $carousels = Carousel::all();
    return view('admin.carousel.index', compact('carousels'));
}

public function deleteCarousel($id)
{
    $carousel = Carousel::findOrFail($id);
    Storage::delete($carousel->image); // Hapus gambar dari storage
    $carousel->delete();

    return redirect()->route('admin.carousel')->with('success', 'Gambar carousel berhasil dihapus.');
}

public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('error', 'File harus berformat Excel (.xlsx atau .xls)');
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            $successCount = 0;
            $errorRows = [];

            foreach ($rows as $index => $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Validate data
                if (count($row) < 4) {
                    $errorRows[] = $index + 2;
                    continue;
                }

                [$name, $nim, $email, $password] = array_pad($row, 4, null);

                // Validate required fields
                if (!$name || !$nim || !$email || !$password) {
                    $errorRows[] = $index + 2;
                    continue;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorRows[] = $index + 2;
                    continue;
                }

                // Check for duplicate NIM or email
                if (User::where('nim', $nim)->orWhere('email', $email)->exists()) {
                    $errorRows[] = $index + 2;
                    continue;
                }

                try {
                    User::create([
                        'name' => $name,
                        'nim' => $nim,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => 'pengguna', // default role
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = $index + 2;
                }
            }

            $message = "Berhasil import $successCount pengguna.";
            if (!empty($errorRows)) {
                $message .= " Gagal import data pada baris: " . implode(', ', $errorRows);
            }

            return redirect()
                ->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memproses file Excel: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Nama', 'NIM', 'Email', 'Password'];
        $sheet->fromArray([$headers], null, 'A1');

        // Add example row
        $example = ['John Doe', '12345678', 'john@example.com', 'password123'];
        $sheet->fromArray([$example], null, 'A2');

        // Style the header row
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create the file
        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_users.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
