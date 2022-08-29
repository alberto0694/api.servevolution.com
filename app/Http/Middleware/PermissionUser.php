<?php
 
 namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ErrorResponse;
 
class PermissionUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $programa, $acao)
    {

        $usuario = JWTAuth::toUser($request->header('authorization'));
        $permissoes = collect(\DB::select("SELECT
                            * 
                        FROM
                            permissao
                            LEFT JOIN papel ON papel.permissao_id = permissao.
                            ID LEFT JOIN papel_usuario ON papel_usuario.papel_id = papel.ID 
                        WHERE
                            permissao.programa = '$programa'
                            AND papel.acao = '$acao'
                            AND papel_usuario.usuario_id = $usuario->id"));

        if(count($permissoes) == 0){
            return response()->json(new ErrorResponse("Usuário não possui permissões para este recurso."), 200);
        }

        return $next($request);
    }
}