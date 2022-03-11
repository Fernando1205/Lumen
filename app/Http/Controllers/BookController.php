<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller{

    public function index(): JsonResponse
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->validate($request,[
                'title' => 'required',
                'image' => 'required'
            ]);

            $book = Book::create($data);

            return response()->json([
                'book' => $book,
                'message' => 'Creado exitosamente'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Ocurrio un error'
            ]);
        }
    }
}

