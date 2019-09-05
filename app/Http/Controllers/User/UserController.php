<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        //dump("esta es una prueba");
        //return response()->json(['data' => $usuarios],200);
        return $this->showAll($usuarios);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request,$rules);
        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarTerificationToken();
        $campos['verified'] = User::USUARIO_REGULAR;
        
        $usuario = User::create($campos);
        return response()->json(['data' => $usuario],201);
        
        //'name', 'email', 'password','verified','verification_token','admin'
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show($id)
    public function show(User $user )  //inyeccion implicita
    {
        //$usuario  = User::findOrFail($id);
        //return response()->json(['data' => $usuario],200);
        return  $this->showOne($user);
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
        
        $user = User::findOrFail($id);
        $rules = [
            'email'    => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin'    => 'in:' . User::USUARIO_ADMINISTRADOR .','. User::USUARIO_REGULAR,
        ];

        $this->validate($request,$rules);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_REGULAR;
            $user->verification_token = User::generarTerificationToken();
            $user->email = $request->email; 
        }
         
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if($user->esVerificado()){
                //return response()->json(['error'=>'Unicamente los usuarios verificados pueden cambiar su valor de Admin','code'=>409],409); 

                return errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de Admin',409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            //return response()->json(['error'=>'No se ha cambiado ningun dato','code'=>422],422); 
            return errorResponse('No se ha cambiado ningun dato',422);
        }
        $user->save();

        return response()->json(['data' => $user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data' => $user],200);
    }
    
    public function verify($token){
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;
        $user->save();
        return $this->showMessage("El usuario ha sido verificado");
    }
}    
