<?php

namespace App\Http\Controllers;

use App\Models\Shopping;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shopping = Shopping::orderBy('createdDate', 'DESC')->get();

        return response()->json($shopping, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'createdDate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        };

        try {
            $shopping = Shopping::create($request->all());

            return response()->json($shopping, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                "message" => "Failed" . $e->errorInfo
            ]);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shopping = Shopping::findOrFail($id);

        return response()->json($shopping, Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shopping = Shopping::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'createdDate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        };

        try {
            $shopping->update($request->all());

            return response()->json($shopping, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                "message" => "Failed" . $e->errorInfo
            ]);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shopping = Shopping::findOrFail($id);

        try {
            $shopping->delete();

            return response()->json(Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                "message" => "Failed" . $e->errorInfo
            ]);
        };
    }
}
