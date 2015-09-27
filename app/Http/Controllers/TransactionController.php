<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Card;
use \App\User;
use \App\Transaction;

class TransactionController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = \Auth::user();
    }
    /**
     * показываем список транзакций за указанную дату для текущего пользователя
     *
     * @return \Illuminate\Http\Response
     */
    public function getList($date = '')
    {
        $date = $date ?: date("Y-m-d");

        $start = \Carbon::createFromFormat("Y-m-d", $date)->startOfDay()->toDateTimeString();
        $end = \Carbon::createFromFormat("Y-m-d", $date)->endOfDay()->toDateTimeString();

        //
        return [
            'content' => Transaction::with('card')
                                    ->where('user_id', $this->user->id)
                                    ->where('created_at', '>=', $start)
                                    ->where('created_at', '<=', $end)
                                    ->orderBy('created_at', 'DESC')
                                    ->get()
        ];
    }
}
