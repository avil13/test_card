<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Group;
use App\User;
use App\Card;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);

        $this->command->info('groupTable');
        $this->groupTable();

        $this->command->info('userTable');
        $this->userTable();

        $this->command->info('cardsTable');
        $this->cardsTable();

        $this->command->info('transactionsTable');
        $this->transactionsTable();

        Model::reguard();
    }


    private function groupTable()
    {
        Group::truncate();

        $data = [
                    [
                        'title' => 'Администраторы',
                        'system' => true,
                    ],
                    [
                        'title' => 'Пользователи',
                        'system' => false,
                    ],
                ];

        foreach ($data as $v) {
            Group::create($v);
        }
    }

    /**
     * заполнение пользователей
     */
    private function userTable()
    {
        User::truncate();

        $data = [
                    [
                        'name' => 'user1',
                        'password' => \Hash::make('user1'),
                        'email' => 'user1@mail.ru',
                        'group_id' => 2,
                    ],
                    [
                        'name' => 'user2',
                        'password' => \Hash::make('user2'),
                        'email' => 'user2@mail.ru',
                        'group_id' => 2,
                    ],
                    [
                        'name' => 'admin',
                        'password' => \Hash::make('admin'),
                        'email' => 'admin@mail.ru',
                        'group_id' => 1,
                    ],
                ];

        foreach ($data as $v) {
            User::create($v);
        }
    }

    /**
     * заполнение таблицы кошельков
     */
    private function cardsTable()
    {
        Card::truncate();

        $data = [
                    [
                        'user_id' => 1,
                        'title' => 'Visa',
                        'balance' => '',
                    ],
                    [
                        'user_id' => 2,
                        'title' => 'Master Card',
                        'balance' => '',
                    ],
                    [
                        'user_id' => 3,
                        'title' => 'Qiwi',
                        'balance' => '',
                    ],
                ];

        foreach ($data as $v) {
            Card::create($v);
        }

        // заполнение значений главной карты
        $users = User::all();
        foreach ($users as $u) {
            $u->main_card = Card::where('user_id', $u->id)->first()->id;
            $u->save();
        }
    }


    /**
     * заполнение таблицы пранзакций
     */
    private function transactionsTable()
    {
        Transaction::truncate();

        $u = User::count();
        $c = Card::count();
        $i = 0;

        while ($i < 10) {
            ++$i;
            Transaction::create([
                'user_id' => rand(1, $u),
                'card_id' => rand(1, $c),
                'money' => rand(0, 1000),
                ]);
        }

        $crd = Card::all();

        foreach ($crd as $v) {
            $v->balance = Transaction::where('card_id', $v->id)->sum('money');
            $v->save();
        }
    }
}
