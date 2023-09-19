<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            // ユーザ情報更新時
            'name' => ['sometimes', 'required_with:email', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],

            // パスワード変更時
            'current_password' => ['sometimes', 'required_with_all:password', 'string', 'min:8', 'max:16'],
            'password' => ['sometimes', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        try {
            if ($request->filled('name')) {
                // ユーザ情報更新処理
                $user->fill($request->only('name', 'email'))->save();
            }
            return response()->json([
                'message' => 'ユーザ情報を更新しました。',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'ユーザ情報の更新に失敗しました。',
            ], 500);
        }

    }
}
