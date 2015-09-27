<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Card;
use \App\User;
use \App\Transaction;


class CardController extends Controller
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
     * GET /
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Card::where('user_id', $this->user->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     * создаем кошелек
     *
     * POST /
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->get('title');
        $input['user_id'] = $this->user->id;

        $names = [
            'title'   => '"название кошелька"',
            'user_id' => '"пользователь"',
        ];

        $validator = \Validator::make($input, [
                'title'   => 'required',
                'user_id' => 'required',
            ])->setAttributeNames($names);

        if ($validator->fails())
        {
            return [
                'success' => false,
                'content' => implode("\n", $validator->messages()->all())
            ];
        }

        if(Card::create($input)){
            return [
                'success' => true,
                'content' => 'Кошелек успешно создан'
            ];
        }

        return [
            'success' => false,
            'content' => 'Не удалось создать кошелек'
        ];
    }

    /**
     * Display the specified resource.
     *
     * GET /{id}
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Card::where('user_id', $this->user->id)
                    ->where('id', $id)
                    ->first();
    }

    /**
     * Update the specified resource in storage.
     * перевод с одной карты на другую
     *
     * PUT /{id}
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id     id получателя
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->only('money', 'card_id', 'from_card_id', 'comment');
        $input['from_user_id'] = $from_user_id = $this->user->id;
        $input['user_id'] = $id;

        $c = User::find($id);

        $input['card_id'] = $c
                            ? $c->main_card
                            : $c = Card::where('user_id', $id)->first() ? $c->id : 0
                            ;


        $names = [
            'from_user_id' => '"отправитель"',
            'from_card_id' => '"карта отправителя"',
            'user_id'      => '"получатель"',
            'card_id'      => '"карта получателя"',
            'money'        => '"сумма"',
            'comment'      => '"комментарий"',
        ];

        // проверка того что карта пренадлежит отправителю
        \Validator::extend('card_owner', function($attribute, $value, $parameters) use ($from_user_id)
        {
            return Card::where('user_id', $from_user_id)
                        ->where('id', $value)
                        ->count() > 0;
        });

        // проверка того что у отправителя достаточно средств на карте
        \Validator::extend('card_max', function($attribute, $value, $parameters) use ($from_user_id)
        {
            $cnt = Card::where('user_id', $from_user_id)
                        ->where('id', $value)
                        ->max('balance');
            return !empty($cnt) && $cnt > $parameters[0];
        });

        $validator = \Validator::make($input, [
                'user_id'      => 'required|exists:users,id',
                'card_id'      => 'required|exists:cards,id',
                'money'        => 'required|numeric|min:1',
                'from_card_id' => 'required|different:card_id|exists:cards,id|card_owner|card_max:'.$input['money'] ,
            ])->setAttributeNames($names);

        if ($validator->fails())
        {
            return [
                'success' => false,
                'content' => implode("\n", $validator->messages()->all())
            ];
        }


        // проверка пройдена

        // баланс для получателя в случае успеха
        $to_balance   = Card::where('id', $input['card_id'])->first()->balance + $input['money'];

        // баланс отправителя в случае успеха
        $from_balance = Card::where('id', $input['from_card_id'])->first()->balance - $input['money'];

        try
        {
            // проводим транзакцию
            \DB::transaction(function () use ($input, $to_balance, $from_balance) {

                \DB::table('cards')->where('id', $input['card_id'])->update(['balance' => $to_balance]);
                \DB::table('cards')->where('id', $input['from_card_id'])->update(['balance' => $from_balance]);

                $toUser = \DB::table('users')->where('id', $input['user_id'])->first();

                // инфа для отправителя
                \DB::table('transactions')->insert([
                    'user_id'    => $input['from_user_id'],
                    'card_id'    => $input['from_card_id'],
                    'to_id'      => $input['user_id'],
                    'money'      => $from_balance,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'comment'    => 'Переведено ' . $input['money'] . 'р. пользователю ' .
                                    $toUser->name . ' [' . $toUser->id . ']'
                    ]);

                // инфа для получателя
                \DB::table('transactions')->insert([
                    'user_id' => $input['user_id'],
                    'card_id' => $input['card_id'],
                    'from_id' => $input['from_user_id'],
                    'money'   => $to_balance,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'comment' => 'Переведено ' . $input['money'] . 'р. от пользователя ' .
                                    $this->user->name . ' [' . $this->user->id . ']'
                    ]);
            });

            return [
                'success' => true,
                'content' => 'Операция выполнена успешно'
            ];
        }
        catch (Exception $e)
        {
            return [
                'success' => false,
                'content' => 'Не удалось провести транзакцию ' . $e->getMessage()
            ];
        }
    }


    /**
     * Пополнение баланса
     * @param  Request $request
     */
    public function up(Request $request)
    {
        $input = $request->only('id', 'money');

        $names = [
            'id' => '"карта"',
            'money' => '"сумма"'
        ];

        $validator = \Validator::make($input, [
                'id'      => 'required|exists:cards,id',
                'money'        => 'required|numeric|min:1',
            ])->setAttributeNames($names);

        if ($validator->fails())
        {
            return [
                'success' => false,
                'content' => implode("\n", $validator->messages()->all())
            ];
        }

        $id =  $this->user->id;

        try
        {
            \DB::transaction(function () use ($id, $input) {
                $card = \DB::table('cards')
                            ->where('user_id', $id)
                            ->where('id', $input['id'])
                            ->first();

                $balance = $card->balance + $input['money'];

                \DB::table('cards')
                    ->where('user_id', $id)
                    ->where('id', $input['id'])
                    ->update(['balance' => $balance]);

                \DB::table('transactions')->insert([
                            'user_id' => $id,
                            'from_id' => $id,
                            'card_id' => $input['id'],
                            'money'   => $balance,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                            'comment' => 'Баланс пополнен на ' . $input['money'] . 'р.'
                            ]);
            });
            return [
                'success' => true,
                'content' => 'Баланс пополнен'
            ];
        }
        catch (Exception $e)
        {
            return [
                'success' => false,
                'content' => 'Не удалось пополнить баланс'
            ];
        }
    }
}

