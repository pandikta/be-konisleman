<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Landing::get();
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
            'gambar_landing' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048',
            'judul_agenda' => 'required',
            'tgl_agenda' => 'required|date',
            'gambar_agenda' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        // $file->gambar_landing = json_encode($arr_gambar);
        // $file->save();
        // $data = Landing::create([
        //     'id_landing' => Uuid::uuid4()->getHex(),
        //     'gambar_landing' => $file,
        //     'judul_agenda' => $request->judul_agenda,
        //     'tgl_agenda' => $request->tgl_agenda,
        //     'gambar_agenda' => $request->file('gambar_agenda')->store('gambar-agenda')
        // ]);


        // $uploadFolder = 'tentang-kami-img';
        // $gambar_agenda = $request->file('gambar_agenda');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');

        // $respone = [
        //     'success' => true,
        //     'message' => 'Data successful added',
        //     'data' => $data,
        // ];

        // return response()->json($respone, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Landing  $landing
     * @return \Illuminate\Http\Response
     */
    public function show(Landing $landing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Landing  $landing
     * @return \Illuminate\Http\Response
     */
    public function edit(Landing $landing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Landing  $landing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Landing $landing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Landing  $landing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Landing $landing)
    {
        //
    }
}
