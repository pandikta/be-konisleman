<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pengumuman::get();
        $user = auth()->user()->id_user;
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $pengumuman = DB::table('pengumuman')
                ->join('users', 'users.id_user', 'pengumuman.id_user')
                ->where('pengumuman.id_user', $user)
                ->select('pengumuman.id_pengumuman', 'pengumuman.judul_pengumuman', 'pengumuman.isi_pengumuman', 'pengumuman.gambar', 'pengumuman.file', 'users.name as nama_penulis')
                ->get();
            $respone = [
                'success' => true,
                'message' => 'List data pengumuman by ' . $user,
                'data' => $pengumuman,
            ];
            return response()->json($respone, 200);
        }
    }

    public function getAllPengumuman()
    {
        $data = DB::table('pengumuman')
            ->join('users', 'users.id_user', 'pengumuman.id_user')
            ->select('pengumuman.id_pengumuman', 'pengumuman.judul_pengumuman', 'pengumuman.isi_pengumuman', 'pengumuman.gambar', 'pengumuman.file', 'users.name as nama_penulis')
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
                'message' => 'List data pengumuman',
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
            'judul_pengumuman' => 'required',
            'isi_pengumuman' => 'required',
            'gambar' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $user = auth()->user()->id_user;
        $data = Pengumuman::create([
            'id_pengumuman' => Uuid::uuid4()->getHex(),
            'judul_pengumuman' => $request->judul_pengumuman,
            'isi_pengumuman' => $request->isi_pengumuman,
            'gambar' => $request->file('gambar')->store('gambar-pengumuman'),
            'file' => $request->file('file')->store('file-pengumuman'),
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
     * @param  \App\Models\Pengumuman  $pengumuman
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pengumuman::find($id);

        if (is_null($data)) {
            $response = [
                'success' => true,
                'message' => 'Id ' . $id . ' not found'
            ];
            return response()->json($response, 200);
        } else {
            $pengumuman = DB::table('pengumuman')
                ->join('users', 'users.id_user', 'pengumuman.id_user')
                ->where('id_pengumuman', $id)
                ->select('pengumuman.id_pengumuman', 'pengumuman.judul_pengumuman', 'pengumuman.isi_pengumuman', 'pengumuman.gambar', 'pengumuman.file', 'users.name as nama_penulis')
                ->get();
            $response = [
                'success' => true,
                'message' => 'Detail pengumuman ' . $id,
                'data' => $pengumuman
            ];
            return response()->json($response, 200);
        }
    }

    public function searchPengumuman(Request $request)
    {
        $search = $request->query('judul');
        $pengumuman =
            DB::table('pengumuman')
            ->where('judul_pengumuman', 'like', '%' . $search . '%')
            ->get();
        $response = [
            'success' => true,
            'message' => 'Search pengumuman ',
            'data' => $pengumuman
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengumuman  $pengumuman
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengumuman $pengumuman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengumuman  $pengumuman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Pengumuman::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul_pengumuman' => 'required',
            'isi_pengumuman' => 'required',
            'gambar' => 'required|file|image:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('gambar') && $request->file('file')) {
            $old_file = $data->file;
            $old_img =  $data->gambar;
            Storage::delete($old_img);
            Storage::delete($old_file);
            $request->file('file')->store('file-pengumuman');
            $request->file('gambar')->store('gambar-pengumuman');
        }

        $data->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'gambar' => $request->file('gambar')->store('gambar-pengumuman'),
            'file' => $request->file('file')->store('file-pengumuman'),
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
     * @param  \App\Models\Pengumuman  $pengumuman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Pengumuman::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        }
        Storage::delete($data->gambar);
        Storage::delete($data->file);
        $data->delete();
        $response = [
            'success' => true,
            'message' => 'Data success deleted'
        ];
        return response()->json($response, 200);
    }
}
