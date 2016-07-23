<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Предоставление списка книг, имеющихся в библиотеке
        $response = [];
        try {
            $statusCode = 200;
            $books = Book::all();

            foreach ($books as $book) {

                $response[] = [
                    'id' => $book->id,
                    'user_id' => $book->user_id,
                    'title' => $book->title,
                    'genre' => $book->genre,
                    'year' => $book->year,
                    'author' => $book->author,
                ];
            }

        } catch (\Exception $e) {
            $statusCode = 404;
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    public function returnBook($id, $uid)
    {
        // Возвращать книгу от определенного пользователя в билиотеку
        try {
            $response = '';
            $statusCode = 200;

            $book = Book::findOrFail($id);
            $user = User::findOrFail($uid);

            if (is_null($book->user)) throw new \Exception('This book was not taken. Not needed to return it.');

            if ($book->user->id != $user->id) throw new \Exception('Specified user does not have this book.');

            $book->user()->dissociate();
            $book->save();
            $response = Book::find($id);

        } catch (ModelNotFoundException $mex) {
            $statusCode = 404;
            $response = ['error' => 'ID\'s are invalid.'];
        } catch (\Exception $ex) {
            $statusCode = 404;
            $response = ['error' => $ex->getMessage()];
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Добавление новой книги в библиотеку
        $statusCode = 201;
        $rules = [
            'title' => ['required'],
            'author' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
            'year' => ['required', 'regex: /^[0-9]+$/'],
            'genre' => ['required', 'regex:/^[a-zA-Z\s]+$/']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $statusCode = 400;
            return Response::json($validator->messages(), $statusCode);
        } else {
            $book = new Book($request->all());
            $book->save();

            return Response::json($book, $statusCode);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Предоставление детальной инорфмации о книге
        $statusCode = 200;

        $response = Book::findOrFail($id);
        return Response::json($response, $statusCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Списание книги из библиотеки
        $responseCode = 202;
        $response = ['status' => 'success'];

        $book = Book::findOrFail($id);
        $book->delete();

        return Response::json($response, $responseCode);
    }
}
