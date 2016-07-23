<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Session;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    public function __construct()
    {

    }

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
        try {
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
        } catch (ModelNotFoundException $ex) {
            $statusCode = 404;
            $response = ['error' => 'Invalid User ID'];
        } finally {
            return Response::json($response, $statusCode);
        }
    }


    public function giveBook($id, $bid)
    {
        try {
            $statusCode = 200;
            $response = '';

            $user = User::findOrFail($id);
            $book = Book::findOrFail($bid);

            if (!is_null($book->user)) {
                throw new \Exception('This book was taken by another user');
            }

            $user->books()->save($book);
            $user->save();

            $response = ['status' => 'success'];
        } catch (\Exception $ex) {
            $statusCode = 404;
            $response = ['error' => $ex->getMessage()];
        } finally {
            return Response::json($response, $statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $statusCode = 200;
            $response = $user;
        } catch (ModelNotFoundException $ex) {
            $statusCode = 404;
            $response = 'Invalid User Id';
        } finally {
            return Response::json($response, $statusCode);
        }

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

    }
}
