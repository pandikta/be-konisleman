<?php

namespace App\Http\Controllers;

use App\Models\PengurusKoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PengurusKoniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PengurusKoni::get();
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $respone = [
                'success' => true,
                'message' => 'List data pengurus koni',
                'data' => $data,
            ];
            return response()->json($respone, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $uploadFolder = 'tentang-kami-img';
        $image = $request->file('foto');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        $data = PengurusKoni::create([
            'id_pengurus_koni' => Uuid::uuid4()->getHex(),
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'foto' => $request->file('foto')->store('foto-pengurus-koni')
        ]);
        $respone = [
            'success' => true,
            'message' => 'Data successful added',
            'data' => $data,
        ];

        return response()->json($respone, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PengurusKoni  $pengurusKoni
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PengurusKoni::find($id);
        if (is_null($data)) {
            $response = [
                'success' => true,
                'message' => 'Id ' . $id . ' not found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'message' => 'Detail pengurus koni ' . $id,
                'data' => $data
            ];
            return response()->json($response, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengurusKoni  $pengurusKoni
     * @return \Illuminate\Http\Response
     */
    public function edit(PengurusKoni $pengurusKoni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PengurusKoni  $pengurusKoni
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = PengurusKoni::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jabatan' => 'required',
            'foto' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('foto')) {
            $old_img =  $data->foto;
            Storage::delete($old_img);
            $request->file('foto')->store('foto-pengurus-koni');
        }

        $data->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'foto' => $request->file('foto')->store('foto-pengurus-koni')
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Updated successfully',
            'updated_data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengurusKoni  $pengurusKoni
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = PengurusKoni::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }
        Storage::delete($data->struktur_org);
        $data->delete();
        $response = [
            'success' => true,
            'message' => 'Data success deleted'
        ];
        return response()->json($response, 200);
    }
}
