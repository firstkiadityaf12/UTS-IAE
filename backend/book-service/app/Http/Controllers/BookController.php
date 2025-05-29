<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json(['data' => $books]);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->all());
        return response()->json(['data' => $book], 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json(['data' => $book]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json(['data' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(null, 204);
    }
} 