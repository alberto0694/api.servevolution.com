<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Cliente;
use Validator;

class UsuarioController extends Controller
{
    public function login(Request $request){

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }
    /**
     * Register a Usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:usuario',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $Usuario = Usuario::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'Usuario successfully registered',
            'Usuario' => $Usuario
        ], 201);
    }

    /**
     * Log the Usuario out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Usuario successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
    /**
     * Get the authenticated Usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function UsuarioProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){

        $menu = $this->getUsuarioMenu(auth()->user());
        $cliente = Cliente::where('pessoa_id', auth()->user()->pessoa_id)->get();
        $count = count($cliente);
        $type = $count > 0 ? "cliente" : "colaborador";

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 2592000,
            'usuario' => auth()->user(),
            'tipo' => $type,
            'cliente' => $cliente,
            'menu' => $menu
        ]);
    }

    protected function getUsuarioMenu($usuario)
    {
        try {

            $menu = collect(\DB::select("SELECT
                                            *
                                        FROM
                                            menu
                                        WHERE
                                            papel_id IN ( SELECT papel_id FROM papel_usuario WHERE usuario_id = {$usuario->id} )
                                        ORDER BY
                                            id ASC"));
            return $menu;

        } catch (\Throwable $th) {

            throw $th;

        }
    }
}
