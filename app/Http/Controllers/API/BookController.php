<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // all books
    public function index(Request $request)
    {
        $params= $request->all();
        $perPage =  10;
        $page = (int) $request->get('page');
        $page = empty($page) ? 1 : $page;
        $skip = $perPage * ($page-1);
        $booksCount = Book::booksSearch($request, $perPage, $skip, $isCount=true);
        $pagination = $this->createPaginationArray($page, $perPage, $booksCount, $params, 'books');
        $books = Book::booksSearch($request, $perPage, $skip)->toArray();
       $response = [
                'books' => $books,
                'pagination' => $pagination
        ];
        return response()->json($response);
    }

    // add book
    public function add(Request $request)
    {
        if($request->photo){
            $ImageName = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $image = public_path().'/images/books-image/'.$ImageName;
            $this->base64_to_jpeg($request->photo,$image);
           
        }else{
            $ImageName = '';
        }
        $book = new Book([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'image' => $ImageName,
            'publisher' => $request->publisher,
            'published' =>  now()
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
        if($request->photo){
            $ImageName = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            $image = public_path().'/images/books-image/'.$ImageName;
            $this->base64_to_jpeg($request->photo,$image);
           
        }else{
            $ImageName = $request->image;
        }
            $book->title = $request->title;
            $book->author = $request->author;
            $book->genre = $request->genre;
            $book->description = $request->description;
            $book->isbn = $request->isbn;
            $book->publisher = $request->publisher;
            $book->image = $ImageName;
            $book->published = now();
        
        $book->save();
        return response()->json('The book successfully updated');
    }

    // delete book
    public function delete($id)
    {
        $book = Book::find($id);
        $book->delete();

        return response()->json('The book successfully deleted');
    }

    private function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen( $output_file, 'wb' ); 
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp ); 
        return $output_file; 
    }

    private function createPaginationArray($page, $perPage, $booksCount, $params, $route){

        $totalPages = (int) ceil($booksCount/$perPage);
        $previousPage = ($page == 1) ? 1 : ($page-1);
        $nextPage = ($page == $totalPages) ? $totalPages : ($page+1);
        $totalButtonShow = 9;
        $firstCase = (int) ceil($totalButtonShow/2);
        $secondCase = (int) floor($totalButtonShow/2);
        $thirdCase = (int) floor(($totalButtonShow-4)/2);
        $pageButtonArray = [];
        if($totalPages > $totalButtonShow){
            if($page <= $firstCase){
                for ($i = 1; $i <= $totalButtonShow-2; $i++){
                    $params['page'] = $i;
                    $pageLink = route($route, $params);
                    $pageButtonArray[] = ['page_number'=>$i, 'pageLink'=>$pageLink];
                }
                $pageButtonArray[] = ['page_number'=>'...', 'pageLink'=>'javascript:void(0)'];
                $params['page'] = $totalPages;
                $pageButtonArray[] = ['page_number'=>$totalPages, 'pageLink'=>route($route, $params)];
            }elseif($page >= $totalPages-$secondCase && $page <= $totalPages){
                $params['page'] = 1;
                $pageButtonArray[] = ['page_number'=>1, 'pageLink'=>route($route, $params)];
                $pageButtonArray[] = ['page_number'=>'...', 'pageLink'=>'javascript:void(0)'];
                for ($i = $totalPages-($totalButtonShow-3); $i <= $totalPages; $i++){
                    $params['page'] = $i;
                    $pageLink = route($route, $params);
                    $pageButtonArray[] = ['page_number'=>$i, 'pageLink'=>$pageLink];
                }
            }else{
                $params['page'] = 1;
                $pageButtonArray[] = ['page_number'=>1, 'pageLink'=>route($route, $params)];
                $pageButtonArray[] = ['page_number'=>'...', 'pageLink'=>'javascript:void(0)'];
                for ($i = $page-$thirdCase; $i <= $page+$thirdCase; $i++){
                    $params['page'] = $i;
                    $pageLink = route($route, $params);
                    $pageButtonArray[] = ['page_number'=>$i, 'pageLink'=>$pageLink];
                }
                $pageButtonArray[] = ['page_number'=>'...', 'pageLink'=>'javascript:void(0)'];
                $params['page'] = $totalPages;
                $pageButtonArray[] = ['page_number'=>$totalPages, 'pageLink'=>route($route, $params)];
            }
        }else{

            for ($i = 1; $i <= $totalPages; $i++){

                $params['page'] = $i;
                $pageLink = route($route, $params);
                $pageButtonArray[] = ['page_number'=>$i, 'pageLink'=>$pageLink];
            }
        }

        $pagination = [
            'previousPage'=>$previousPage,
            'nextPage'=>$nextPage,
            'page' => $page,
            'totalPages' => $totalPages,
            'pageButtonArray' => $pageButtonArray
        ];

        return $pagination;
    }
    public function insertBulkBookdata(Request $request){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fakerapi.it/api/v1/books?_quantity=100',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $responseArray = json_decode($response,true);
        Book::insert($responseArray['data']);
    }
}