<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeCreateRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $search = request("search");
      $types = Type::
      with("User:id,full_name")
      ->when($search, function($query)use($search){
        $query->where("title","like","%$search%");
      })
      ->orderByDesc('id')
      ->paginate();

      return TypeResource::collection($types);
    }
/*

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeCreateRequest $request)
    {
        Type::create([
            "title"=> $request->title,
            "user_id"=> Auth::user()->id,
            "is_input"=> $request->is_input,
            // "active"=> $request->active
        ]);
        return response()->json(["message"=> "xarajat turi yaratildi"],201);
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
    public function update(Request $request, string $id)
    {
        //
    }




    public function changeActive($id)
    {
    $type = Type::find($id);

    if (!$type) {
        return response()->json(["message" => "bu id li xarajat turi mavjud emas"], 404); // error message
    }
    $type->active = !$type->active;
    $type->save();
    return response()->json(["message" => "amaliyot bajarildi"], 200);
    }


    public function getAll()
    {
     $types = Type::where('active',"=",true)
        ->get();
     return response()->json(["data"=> $types], 200);
    }
}
