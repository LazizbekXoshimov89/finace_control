<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeExpanseRequest;
use App\Http\Requests\IncomeExpanseUpdateRequest;
use App\Http\Resources\IncomeExpanseResource;
use App\Models\IncomeExpanse;
use App\Models\Type;
use App\Models\UserBalance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class IncomeExpanseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request("search");
        $isInput = request("is_input");
        $startDate = request("start_date");
        $endDate = request("end_date");
        $id = request("type_id");
        $dataIncome = IncomeExpanse::select(
            'income_expanses.id',
            'income_expanses.value',
            'income_expanses.currency',
            'types.title',
            'income_expanses.comment',
            'users.full_name',
            'types.is_input',
            'income_expanses.created_at',
            'income_expanses.updated_at'
        )
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('types.title', 'LIKE', "%$search%")
                        ->orWhere('income_expanses.comment', 'like', "%$search%");
                }
            })
            ->where(function($query) use ($id){
                if($id){
                    $query->where('types.id', $id);
                }
            })
            // ->where(function ($query) use ($isInput) {
            //     if ($isInput) {
            //         $query->where('types.is_input', $isInput == 'input');
            //     }
            // })

            ->when($isInput, function ($query) use ($isInput) {
                $query->where('types.is_input', $isInput == 'input');
            })

            ->where(function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereDate('income_expanses.created_at', '>=', $startDate); // 2024-11-26 >= 2024-11-26
                }
                if ($endDate) {
                    $query->whereDate('income_expanses.created_at', '<=', $endDate); // 2024-11-26 <= 2024-11-26
                }
            })
            ->where('income_expanses.user_id',  Auth::user()->id)
            ->join('types', 'types.id', '=', 'income_expanses.type_id')
            ->join('users', 'users.id', '=', 'income_expanses.user_id')
            ->orderByDesc('id')
            ->paginate(env('PG '));

        return IncomeExpanseResource::collection($dataIncome);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeExpanseRequest $request)
    {
        DB::beginTransaction();
        try {
            IncomeExpanse::create([
                "value" => $request->value,
                "currency" => $request->currency,
                "type_id" => $request->type_id,
                "comment" => $request->comment,
                "user_id" => Auth::user()->id
            ]);
            $dataUser =  UserBalance::where('user_id', Auth::user()->id)->first();
            $type = Type::where('id', $request->type_id)->first();
            if ($type->is_input) {
                $result = $dataUser->total_value + $request->value;
            } else {
                $result = $dataUser->total_value - $request->value;
            }
            // UserBalance::where('user_id', Auth::user()->id)->update(['total_value' => $result]);
            $dataUser->total_value = $result;
            $dataUser->save();
            DB::commit();
            return response()->json(["message" => "amaliyot bajarildi"], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => ['errors' => ['Dasturda xatolik']]
            ], 500);
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(IncomeExpanseUpdateRequest $request, string $id)
    {
        $dataUser = UserBalance::where("user_id", Auth::user()->id)->first();
        $incomeExpanse = IncomeExpanse::where("id", $id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if (!$incomeExpanse)
            return response()->json(['message' => ['errors' => ['Bu xarajat mavjud emas yoki sizga tegishli emas']]], 404);
        $farqi = abs($incomeExpanse->value - $request->value);
        $type = Type::where("id", $request->type_id)->first();
        if ($type->is_input == 1) {
            if ($incomeExpanse->value < $request->value) {
                $newBalance = $dataUser->total_value + $farqi;
            } else {
                $newBalance = $dataUser->total_value - $farqi;
            }
        } elseif ($type->is_input == 0) {
            if ($incomeExpanse->value < $request->value) {
                $newBalance = $dataUser->total_value - $farqi;
            } elseif ($incomeExpanse->value > $request->value) {
                $newBalance = $dataUser->total_value + $farqi;
            }
        }
        DB::beginTransaction();
        try {
            UserBalance::where("user_id", Auth::user()->id)
                ->update([
                    "total_value" => $newBalance
                ]);// optimize

            IncomeExpanse::where("id", $id)
                ->where('user_id', Auth::user()->id)
                ->update([
                    "value" => $request->value,
                    "comment" => $request->comment
                ]);
            DB::commit();
            return response()->json(["message" => "ma\'lumot muvofaqqiyatli \'ozgartirildi!"], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "message" => ['errors' => ['Dasturda xatolik']]
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
