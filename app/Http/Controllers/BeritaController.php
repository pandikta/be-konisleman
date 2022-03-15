<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Berita::get();
        $user = auth()->user()->id_user;
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $berita = DB::table('berita')
                ->join('users', 'users.id_user', 'berita.id_user')
                ->where('berita.id_user', $user)
                ->select('berita.id_berita', 'berita.judul_berita', 'berita.isi_berita', 'berita.id_cabor', 'berita.gambar_berita', 'users.name as nama_penulis')
                ->get();
            $respone = [
                'success' => true,
                'message' => 'List data berita by ' . $user,
                'data' => $berita,
            ];
            return response()->json($respone, 200);
        }
    }

    public function getAllBerita()
    {
        $data = DB::table('berita')
            ->join('users', 'users.id_user', 'berita.id_user')
            ->select('berita.id_berita', 'berita.judul_berita', 'berita.isi_berita', 'berita.id_cabor', 'berita.gambar_berita', 'users.name as nama_penulis')
            ->get();
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $respone = [
                'success' => true,
                'message' => 'List data berita',
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
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'gambar_berita' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048',
            'id_cabor' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user()->id_user;
        $data = Berita::create([
            'id_berita' => Uuid::uuid4()->getHex(),
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'id_cabor' => $request->id_cabor,
            'gambar_berita' => $request->file('gambar_berita')->store('gambar-berita'),
            'id_user' => $user
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
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Berita::get();
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $berita = DB::table('berita')
                ->join('users', 'users.id_user', 'berita.id_user')
                // ->join('cabang_olahraga', 'cabang_olahraga.id_cabor', 'berita.id_cabor')
                ->select('berita.id_berita', 'berita.judul_berita', 'berita.isi_berita', 'berita.gambar_berita', 'users.name as nama_penulis')
                ->get();
            $respone = [
                'success' => true,
                'message' => 'Detail berita ' . $id,
                'data' => $berita,
            ];
            return response()->json($respone, 200);
        }
    }

    public function searchBerita(Request $request)
    {
        $search = $request->query('judul');
        $berita =
            DB::table('berita')
            ->join('users', 'users.id_user', 'berita.id_user')
            // ->join('cabang_olahraga', 'cabang_olahraga.id_cabor', 'berita.id_cabor')
            ->where('judul_berita', 'like', '%' . $search . '%')
            ->select('berita.id_berita', 'berita.judul_berita', 'berita.isi_berita', 'berita.gambar_berita', 'users.name as nama_penulis')
            ->get();
        $response = [
            'success' => true,
            'message' => 'Search berita ',
            'data' => $berita
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function edit(Berita $berita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Berita::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'gambar_berita' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048',
            'id_cabor' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('gambar') && $request->file('file')) {
            $old_file = $data->file;
            $old_img =  $data->gambar;
            Storage::delete($old_img);
            $request->file('gambar')->store('gambar-berita');
        }

        $data->update([
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'id_cabor' => $request->id_cabor,
            'gambar_berita' => $request->file('gambar_berita')->store('gambar-berita'),
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
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Berita::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }
        Storage::delete($data->gambar_berita);
        $data->delete();
        $response = [
            'success' => true,
            'message' => 'Data success deleted'
        ];
        return response()->json($response, 200);
    }
}
