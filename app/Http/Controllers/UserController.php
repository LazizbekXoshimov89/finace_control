<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\IncomeExpanse;
use App\Models\Type;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Transaksiya qilish kerak
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            "full_name" => $request->full_name,
            "username" => $request->username,
            "password" => Hash::make($request->password),
            "phone" => $request->phone,
        ]);


        UserBalance::create(
            [
                "total_value" => 0,
                "credit_value" => 0,
                "user_id" => $user->id
            ]
        );

        $user->createToken("auth-token")->plainTextToken;
        return response()->json(["message" => "foydalanuvchi yaratildi"], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(UserLoginRequest $request)
    {
        if (strlen($request->username) == 0 || strlen($request->password) == 0)
            return 'error';

        $user = User::where('username', $request->get('username'))->first();
        if (!$user)
            return response()->json(['message' => 'Login yoki Parol noto\'g\'ri'], 400);
        if (!Hash::check($request->get('password'), $user->password))
            return response()->json(['message' => 'Login yoki Parol noto\'g\'ri'], 400);

        $token = $user->createToken('menimcha')->plainTextToken;
        return response()->json(["token" => "$token"], 201);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UserUpdateRequest $request, string $id)
    {
        $authUser = Auth::user();
        if ($authUser->id == $id) {

            User::where('id', $id)
                ->update([
                    'password' => $request->password,
                    'full_name' => $request->full_name,
                    'phone' => $request->phone
                ]);
            return response()->json(['message' => 'Ma\'lumot muvofaqqiyatli yangilandi!'], 200);
        } else {
            // Error message to'g'rilansin
            return response()->json(['message' => 'Boshqa foydalanuvchining ma\'lumotlarini o\'zgartira olmaysiz!'], 403);
        }
    }




    public function authUser()
    {
        // groupBy user_id , sum(value) as total_val => first()->total_val
        $authUser = Auth::user();
        $dataUser = UserBalance::where('user_balances.user_id', Auth::user()->id)->first();
        $UserIncomes = IncomeExpanse::select(
            "income_expanses.id",
            "income_expanses.value",
            "income_expanses.currency",
            "income_expanses.type_id",
            "income_expanses.comment",
            "income_expanses.user_id",
            "income_expanses.created_at",
            "income_expanses.updated_at",
            "types.title",
            "types.is_input",
        )
        ->where('income_expanses.user_id', Auth::user()->id)
        ->whereDate('income_expanses.created_at', '>=',  date('Y-m' . '-01'))
        ->whereDate('income_expanses.created_at', '<=', date( 'Y-m-d'))
        ->join('types', 'types.id', '=', 'income_expanses.type_id')
        ->get();

        $incomeInput = 0;
        $incomeOutput = 0;
        foreach ($UserIncomes as $UserIncome) {
            if ($UserIncome->is_input) {
                $incomeInput += $UserIncome->value;
            } else {
                $incomeOutput += $UserIncome->value;
            }
        }

        return response()->json([
            'id' => $authUser->id,
            'username' => $authUser->username,
            'full_name' => $authUser->full_name,
            'phone' => $authUser->phone,
            'active' => $authUser->active,
            'balans' => $dataUser->total_value,
            'kirim' => $incomeInput,
             // where between dates qaysiki shu oy boshi dan hozirgacha =>
            //where('created_at', >=, date('Y-m) . '-01') davom and where user_id =Auth::user->id
            'chiqim' => $incomeOutput

        ]);
    }

    // $orders = Order::whereBetween('created_at', ['2024-01-01', '2024-12-31'])->get();

    // public function user_info()
    // {
    //     $balans = UserBalance::where('user_id', Auth::user()->id)->first();

    //     $type = Type::get();

    //     $incomes = IncomeExpanse::where('income_expanses.user_id', Auth::user()->id)
    //         ->whereDate('income_expanses.created_at', '>=', value: date('Y-m' . '-01'))
    //         ->whereDate('income_expanses.created_at', '<=', date(format: 'Y-m-d'))
    //         ->join('types', 'types.id', 'income_expanses.type_id')
    //         ->select(
    //             "income_expanses.id",
    //             "income_expanses.value",
    //             "income_expanses.currency",
    //             "income_expanses.type_id",
    //             "income_expanses.comment",
    //             "income_expanses.user_id",
    //             "income_expanses.active",
    //             'income_expanses.created_at',
    //             'income_expanses.updated_at',
    //             "types.title",
    //             "types.is_input",
    //         )
    //         ->get();

    //     $incomeInput = 0;
    //     $incomeOutPut = 0;
    //     foreach ($incomes as $income) {
    //         if ($income->is_input) {
    //             $incomeInput += $income->value;
    //         } else {
    //             $incomeOutPut += $income->value;
    //         }
    //     }

    //     return response()->json([
    //         'data' => [
    //             'user' => Auth::user(),
    //             'Balans' => $balans->total_value,
    //             'kirim_qiymat' => $incomeInput,
    //             'chiqim_qiymat' => $incomeOutPut
    //         ]
    //     ], 200);
    // }

}





