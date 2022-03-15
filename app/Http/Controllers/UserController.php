<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user()->id_user;
        $data = User::get();
        if ($data->isEmpty()) {
            $respone  = [
                'success' => true,
                'message' => 'Empty set'
            ];
            return response()->json($respone, 200);
        } else {
            $respone = [
                'user login' => $user,
                'success' => true,
                'message' => 'List data user',
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::find($id);
        if (is_null($data)) {
            $response = [
                'success' => true,
                'message' => 'User id ' . $id . ' not found'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'message' => 'Detail User ' . $id,
                'data' => $data
            ];
            return response()->json($response, 200);
        }
    }

    public function activateUser(Request $request, $id)
    {
        $data = User::find($id);
        if (is_null($data)) {
            $response = [
                'success' => true,
                'message' => 'User id ' . $id . ' not found'
            ];
            return response()->json($response, 200);
        } elseif ($data->role != 0) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Access forbidden'
            ], 403);
        } elseif ($data->is_active === 0) {
            User::where('id_user', $id)
                ->update(['is_active' => 1]);
            return Response::json([
                'success' => true,
                'message' => 'User ' . $id . ' has been successfully activated ',
            ], 200);
        } else {
            User::where('id_user', $id)
                ->update(['is_active' => 0]);
            return Response::json([
                'success' => true,
                'message' => 'User ' . $id . ' has been successfully deactivated ',
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $data = User::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => 'false',
                'messages' => 'User id ' . $id . ' not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Updated successfully',
            'updated_data' => $data
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user()->id_user;
        $data = User::find($user);

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:new_password'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } elseif ((Hash::check($request->old_password, auth()->user()->password)) == false) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Wrong password'
            ], 422);
        } elseif ((Hash::check($request->new_password, auth()->user()->password)) == true) {
            return Response::json([
                'success' => 'false',
                'messages' => 'New password cannot be the same as old'
            ], 422);
        }

        $data->update([
            'password' => bcrypt($request->new_password)
        ]);

        return Response::json([
            'success' => true,
            'message' => 'Change password successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        if (is_null($data)) {
            return Response::json([
                'success' => false,
                'messages' => 'Id ' . $id . ' not found'
            ], 404);
        } elseif ($data->role != 1) {
            return Response::json([
                'success' => 'false',
                'messages' => 'Access forbidden'
            ], 403);
        }

        $data->delete();
        $response = [
            'success' => true,
            'message' => 'Data success deleted'
        ];
        return response()->json($response, 200);
    }
}
