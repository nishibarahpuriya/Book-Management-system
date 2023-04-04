<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // all books
    public function index()
    {
        $books = Book::all()->toArray();
        return array_reverse($books);
    }

    // add book
    public function add(Request $request)
    {
        if($request->photo){
            dd($request->photo );
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            
            // \Image::make($request->photo)->save(public_path('images/books/').$name);
            // $request->merge(['photo' => $name]);
           
        }

      
        $book = new Book([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'image' => ' ',
            'publisher' => $request->publisher,
            'published' => '2023-04-14 00:19:23',
        ]);
        $book->save();

        return response()->json('The book successfully added');
    }

    // edit book
    public function edit($id)
    {
        $book = Book::find($id);
        return response()->json($book);
    }

    // update book
    public function update($id, Request $request)
    {
        $book = Book::find($id);
        $book->update($request->all());

        return response()->json('The book successfully updated');
    }

    // delete book
    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();

        return response()->json('The book successfully deleted');
    }
}