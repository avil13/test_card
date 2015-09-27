<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Card;
use \App\User;
use \App\Transaction;


class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = \Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user;
    }

    /**
     * Поиск пользователя
     * @param  Request $request
     * @return array
     */
    public function show(Request $request)
    {
        $s = $request->get('s');
        return [
            'results' => User::select('name', 'id')
                            ->where('id', '!=', $this->user->id)
                            ->where(function ($q) use ($s) {
                                $q->where('name', 'LIKE', "%$s%")->orWhere('name', 'LIKE', "%$s%");
                            })
                            ->get()
            ];
    }
}
