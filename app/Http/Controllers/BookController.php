<?php
namespace App\Http\Controllers;

use App\Http\Services\ImageService;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller{

    public function index(): JsonResponse
    {
        $books = Book::all();
        return response()->json($books,200);
    }

    public function store(Request $request, ImageService $imageService): JsonResponse
    {
        try {
            $data = $this->validate($request,[
                'title' => 'required',
                'image' => 'required|image'
            ]);

            if($request->hasFile('image')){
                $data['image'] = $imageService->saveImage($request->file('image'));
            }

            $book = Book::create($data);

            return response()->json([
                'book' => $book,
                'message' => 'Creado exitosamente'
            ], 201);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Ocurrio un error'
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'book' => $book,
            'message' => 'Exito'
        ],200);
    }

    public function update(Request $request, $id, ImageService $imageService): JsonResponse
    {

        $data = $this->validate($request,[
            'title' => 'required',
            'image' => 'required|image'
        ]);

        $book = Book::findOrFail($id);
        $path = base_path('public').$book->image;

        if(file_exists($path)){
            unlink($path);
            $data['image'] = $imageService->saveImage($request->file('image'));
        } else {
            $data['image'] = $imageService->saveImage($request->file('image'));
        }

        $book->update($data);

        return response()->json([
            'book' => $book,
            'message' => 'Actualizado exitosamente'
        ],200);
    }

    public function delete($id): JsonResponse
    {
        $book = Book::findOrFail($id);

        $path = base_path('public').$book->image;
        if(file_exists($path)){
            unlink($path);
        }

        $book->delete();
        return response()->json([
            'book' => $book,
            'message' => 'Eliminado exitosamente'
        ],200);

    }
}

