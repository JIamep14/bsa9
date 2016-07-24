<?php

namespace App\Http\Controllers;

use App\Book;
use App\Exceptions\MyException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use App\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function userBooks($id)
    {
        //Предоставление списка книг, которые взял определенный пользователь
        $statusCode = 200;
        $user = User::findOrFail($id);
        $books = $user->books()->get();
        $response = [];
        foreach ($books as $book) {
            $response[] = [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'genre' => $book->genre,
                'year' => $book->year
            ];
        }
        return Response::json($response, $statusCode);
    }


    public function giveBook($id, $bid)
    {
        // Присваивать книгу определенному пользователю
        $statusCode = 200;
        $response = ['status' => 'success'];

        $user = User::findOrFail($id);
        $book = Book::findOrFail($bid);
        if (!is_null($book->user)) {
            throw new MyException('This book was taken by another user');
        }

        $user->books()->save($book);
        $user->save();

        return Response::json($response, $statusCode);
    }

    public function show($id)
    {   //Возвращать данные профиля об определенном пользователе
        $user = User::findOrFail($id);
        $statusCode = 200;
        $response = $user;
        return Response::json($response, $statusCode);
    }

}
