<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable =[
        'title','author','genre','description','isbn','image','published','publisher'
    ];

    public static function booksSearch($request, $perPage, $skip, $isCount=false){
        $search = $request->get('search');
        $books = new Book();
        $books = $books->select('books.*');
        if(!empty($search)){
                $books = $books->where('books.title','LIKE',$search.'%')
                ->orWhere('books.author','LIKE',$search.'%')
                ->orWhere('books.genre','LIKE',$search.'%')
                ->orWhere('books.isbn', $search);
        }
        if($isCount)
            $books = $books->count();
        else
            $books = $books->skip($skip)->take($perPage)->orderBy('books.id', 'DESC')->get();

        return $books;
    }
}
