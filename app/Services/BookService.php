<?php

namespace App\Services;
use App\Models\Books;

use App\Http\Requests\RequestBook;

class BookService
{
  protected function findBookOrFail(string $id)
  {

    $book = Books::find($id);
    if (!$book) {
      abort(response()->json([
        'error' => 'Livro não encontrado'
      ], 404));
    }
    return $book;
  }

  public function createBook(RequestBook $request)
  {
    return Books::create($request->validated());
  }

  public function updateBook(RequestBook $request, string $id)
  {
    $book = $this->findBookOrFail($id);
    $book->update($request->validated());

    return $book;
  }

  public function deleteBook(string $id)
  {
    $book = $this->findBookOrFail($id);
    $book->delete();

    return true;
  }

  public function getBookById(string $id)
  {
    return $this->findBookOrFail($id);
  }
}
