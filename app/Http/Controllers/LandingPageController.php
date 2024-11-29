<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Carousel;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function landingPage()
    {
        $carousels = Carousel::all();
        $bookCount = Book::Count();
        return view('landing', compact('carousels', 'bookCount'));
    }
}
