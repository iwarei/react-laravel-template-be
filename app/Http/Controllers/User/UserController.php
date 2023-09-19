<?php
namespace App\Http\Controllers\User;	namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;	use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Conductor;	use App\Models\Conductor;
use Illuminate\Http\Request;	use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;	use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;	use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;	use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;	use Illuminate\Validation\Rule;
class UserController extends Controller	class UserController extends Controller
{	{
    /**	    /**
     * Store a newly created resource in storage.	     * Store a newly created resource in storage.
     */	     */
    public function store(Request $request)	    public function store(Request $request)
    {	    {
        $user = Auth::user();	        $user = Auth::user();
        $request->validate([	        $request->validate([
            // ユーザ情報更新時	            // ユーザ情報更新時
            'name' => ['sometimes', 'required_with:email', 'string', 'max:255'],	            'name' => ['sometimes', 'required_with:email', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],	            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            // パスワード変更時	            // パスワード変更時
            'current_password' => ['sometimes', 'required_with_all:password', 'string', 'min:8', 'max:16'],	            'current_password' => ['sometimes', 'required_with_all:password', 'string', 'min:8', 'max:16'],
            'password' => ['sometimes', 'string', 'confirmed', Rules\Password::defaults()],	            'password' => ['sometimes', 'string', 'confirmed', Rules\Password::defaults()],
        ]);	        ]);
        $user = Auth::user();	        $user = Auth::user();
        if ($request->filled('name')) {	        if ($request->filled('name')) {
            // ユーザ情報更新処理	            // ユーザ情報更新処理
            $user->fill($request->only('name', 'email'))->save();	            $user->fill($request->only('name', 'email'))->save();
        }	        }
        else if ($request->filled('password') && password_verify($request->input('current_password'), $user->password)) {	        else if ($request->filled('password') && password_verify($request->input('current_password'), $user->password)) {
            // パスワード変更処理	            // パスワード変更処理
            $user->forceFill([	            $user->forceFill([
                'password' => Hash::make($request->input('password')),	                'password' => Hash::make($request->input('password')),
            ])->save();	            ])->save();
        }	        }
        return response()->json([	        return response()->json([
            'message' => 'ユーザ情報を更新しました。',	            'message' => 'ユーザ情報を更新しました。',
        ]);	        ]);
    }	    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // ToDo: パスワード一致時のみ削除処理を実行する
        $user->delete();

        return response()->json([
            'message' => 'ユーザ情報を削除しました。',
        ]);
    }
}	}
