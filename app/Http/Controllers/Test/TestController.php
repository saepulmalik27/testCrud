<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Model\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Test::with('createdBy', 'updatedBy')->select('*');

        $search = $request->input('search');
        if ($search) {
            $result->whereRaw('LOWER(name) like LOWER(?)', ["%{$search}%"])
                ->orWhereRaw('LOWER(description) like LOWER(?)', ["%{$search}%"]);
        }

        $sort = $request->input('sort');

        if ($sort && $sort != 'undefined|undefined') {
            $sortBy = explode('|', $sort)[0];
            $sortOrder = explode('|', $sort)[1];
            // if ($sortBy == 'created') {
            //     $result->whereHas('createdBy', function ($query) use ($sortOrder) {
            //         return $query->orderBy('name', $sortOrder);
            //     });
            // } elseif ($sortBy == 'updated') {
            //     $result->whereHas('updatedBy', function ($query) use ($sortOrder) {
            //         return $query->orderBy('name', $sortOrder);
            //     });
            // } else {
            $result->orderBy($sortBy, $sortOrder);

            // }
        } else {
            $result->orderBy('created_at', 'DESC');
        }

        $result = $result->paginate(10);
        return response($result->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->all();
        // dd($formData);
        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
            ]);

            $data = Test::create($formData);

            $response = [
                'message' => "Document succesfully created <br> <strong>$data->name</strong>",
                'data' => $data,
            ];

            return response($response, Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd('hallo');
        $data = Test::findOrFail($id);
        return response($data, Response::HTTP_OK);
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
        $data = Test::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $form = $request->all();

        $data->update($form);

        return response([
            'data' => $data,
            'message' => "Document succesfully updated <br>
        <strong>$data->name</strong>",
        ], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = Test::findOrFail($id);
            $result->delete();
            // TODO: validation if this softdelete or not
            /*     if ($result->trashed()) {
            $result->update();
            } */

            $response = ['data' => $result, 'message' => " Document has been deleted"];
            return response($response, Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], Response::HTTP_CONFLICT);
        }
    }
}
