<?php

namespace App\Http\Controllers;

use App\Models\tentang_kami;
use App\Models\TentangKami;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class TentangKamiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TentangKami::get();
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $respone = [
                'success' => true,
                'message' => 'List data tentang kami',
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
            'visi' => 'required',
            'misi' => 'required',
            'struktur_org' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $uploadFolder = 'tentang-kami-img';
        $image = $request->file('struktur_org');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        $data = TentangKami::create([
            'id_tentang_kami' => Uuid::uuid4()->getHex(),
            'visi' => $request->visi,
            'misi' => $request->misi,
            'struktur_org' => $request->file('struktur_org')->store('tentang-kami-img')
        ]);
        $respone = [
            'success' => true,
            'message' => 'Data successful added',
            'data' => $data,
            // 'image_name' => basename($image_uploaded_path),
            // 'mime' => $image->getClientMimeType(),
            // 'original_name' => $image->getClientOriginalName(),
        ];

        return response()->json($respone, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TentangKami  $TentangKami
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TentangKami  $TentangKami
     * @return \Illuminate\Http\Response
     */
    public function edit(TentangKami $TentangKami)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TentangKami  $TentangKami
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = TentangKami::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'visi' => 'required',
            'misi' => 'required',
            'struktur_org' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('struktur_org')) {
            $old_img =  $data->struktur_org;
            Storage::delete($old_img);
            $request->file('struktur_org')->store('tentang-kami-img');
        }

        $data->update([
            'visi' => $request->visi,
            'misi' => $request->misi,
            'struktur_org' => $request->file('struktur_org')->store('tentang-kami-img')
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
     * @param  \App\Models\TentangKami  $TentangKami
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TentangKami::find($id);
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
