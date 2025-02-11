<?php

namespace App\Services;
use App\Models\Books;
use App\Http\Requests\RequestBook;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class BookService
{
  protected function findBookOrFail(string $id)
  {

    $book = Books::find($id);
    if (!$book) {
      abort(response()->json([
        'error' => 'Livro não encontrado.'
      ], 404));
    }
    return $book;
  }

  protected function uploadImage(UploadedFile $image, string $bookTitle)
  {
    $image_title = Str::slug($bookTitle) . '-' . now()->format('Y-m-d-H-i-s') . '.' . $image->getClientOriginalExtension();

    $image_instance = ImageManager::imagick()->read($image);
    $image_instance->resize(200, 200);

    $directory = 'uploads/booksCover';
    if (!Storage::disk('public')->exists($directory)) {
      Storage::disk('public')->makeDirectory($directory);
    }

    $path = Storage::disk('public')->path($directory . '/' . $image_title);
    $image_instance->save($path);

    return $image_title;
  }

  public function createBook(RequestBook $request)
  {
    $validated = $request->validated();

    if ($request->hasFile('cover')) {
      $image_title = $this->uploadImage($request->file('cover'), $request->title);
      $validated['cover'] = $image_title;
    }

    return Books::create($validated);
  }

  public function updateBook(RequestBook $request, string $id)
  {
    $book = $this->findBookOrFail($id);

    $validated = $request->validated();

    if ($request->hasFile('cover')) {
      $image_title = $this->uploadImage($request->file('cover'), $request->title);
      $validated['cover'] = $image_title;
    }

    $book->update($validated);

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
