<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use Validator;
use View;
use App\Http\Requests;
use Session;
use Auth;
use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

if (Session::has('backUrl')) {
   Session::keep('backUrl');}
   
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $currentuser= Auth::user(); 
        if($currentuser->isValidAdmin($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }

        //if($currentuser->pOperator=='9000' )
        if($currentuser->isSuperSuperAdmin($currentuser)==true )
        {
            $users = User::paginate(10);
        }
        //if($currentuser->pOperator=='4500' )
        if($currentuser->isSuperAdmin($currentuser)==true )
        {
            $users = User::where('idAccount', '=', $currentuser->idAccount)->Paginate(15);
        }
         
        return View::make('user/list')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $currentuser= Auth::user(); 
        if($currentuser->isValidAdmin($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = new User;
      return View::make('user/form')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usertype=array( 9000 => 4500,4500 => 1,1 => 2);
        $currentuser= Auth::user(); 
        
        if($currentuser->isValidAdmin($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = new User;
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //dd($data);
        // Revisamos si la data es válido
        if ($user->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            if($currentuser->isSuperSuperAdmin($currentuser)==true)
            {
                if($data['idAccount']==null)
                {
                    return Redirect::route('user.create')->withInput()->withErrors('agrega una cuenta');
                }
                $user->idAccount=$data['idAccount'];
            }
            elseif($currentuser->isValidAdmin($currentuser)==true)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            elseif($currentuser->isValidAdmin($currentuser)==false)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            $confirmation_code = str_random(30);
            $user->pOperator=$usertype[$currentuser->pOperator];
            $user->idPersonCreator=$currentuser->id;
            $user->idPersonModificator=$currentuser->id;
            $user->confirmation_code=$confirmation_code;
            // Guardamos el usuario
            $user->save();
            $data ['confirmation_code'] = $confirmation_code;
            Mail::send('emails.verify', $data, function($message) {
            $message->to(Input::get('email'), Input::get('firstName'))
                ->subject('Verifica tu dirección de correo Call Center');
            });
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('user.show', array($user->id));
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('user.create')->withInput()->withErrors($user->errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $currentuser= Auth::user(); 
        if($currentuser->isValidAdmin($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = User::find($id);
        
        if (is_null($user)) App::abort(404);
        
      return View::make('user/show', array('user' => $user));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currentuser= Auth::user(); 
            if($currentuser->isValidAdmin($currentuser)==false)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
            }
        $user = User::find($id);
            if (is_null ($user))
            {
            App::abort(404);
            }

        return View::make('user/form')->with('user', $user);
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
        $currentuser= Auth::user(); 
            if($currentuser->isValidAdmin($currentuser)==false)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
            }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::find($id);
        
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($user))
        {
            App::abort(404);
        }
        
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        
        // Revisamos si la data es válido
        if ($user->isValid($data)) {
            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            $user->idPersonModificator=$currentuser->id;
            // Guardamos el usuario
            $user->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('user.show', array($user->id));
        } else {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('user.edit', $user->id)->withInput()->withErrors($user->errors);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*$currentuser= Auth::user(); 
        if($currentuser->pOperator!='9000' && $currentuser->pOperator!='1')
        {
            return Redirect::to('/errors')->withInput()->withErrors('you cant do that');
        }
         $user = User::find($id);
        
        if (is_null ($user))
        {
            App::abort(404);
        }
        
        $user->delete();

        if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'Usuario ' . $user->firstName . $user->lastName . ' eliminado',
                'id'      => $user->id
            ));
        }
        else
        {
            return Redirect::route('home');
        }*/
        $currentuser= Auth::user(); 
            if($currentuser->isValidAdmin($currentuser)==false)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
            }
        $user = User::find($id);
        $user->delete();
        
        if (is_null ($user))
        {
            App::abort(404);
        }
        if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'Usuario ' . $user->firstName . $user->lastName . ' eliminado',
            ));
        }
        else
        {
            return Redirect::route('home');
        }
    }

    public function createOperator($id)
    {
         $currentuser= Auth::user(); 
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = new User;
      return View::make('user/formCreateOperator')->with('user', $user);
    }
    public function editOperator($id)
    {
         $currentuser= Auth::user(); 
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = User::find($id);
      return View::make('user/formCreateOperator')->with('user', $user);
    }
    public function storeOperator(Request $request)
    {
        $usertype=array( 9000 => 4500,4500 => 1,1 => 2);
        $currentuser= Auth::user(); 
        
        if($currentuser->create($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = new User;
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //dd($data);
        // Revisamos si la data es válido
        if ($user->isValid($data))
        {

            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            if($currentuser->isSuperSuperAdmin($currentuser)==true)
            {
                $user->idAccount=$data['idAccount'];
            }
            elseif($currentuser->isValidAdmin($currentuser)==true)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            elseif($currentuser->isUser($currentuser)==true)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            else
                {
                return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
                }

        $user->pOperator=$usertype[$currentuser->pOperator];
        $user->idPersonCreator=$currentuser->id;
        $user->idPersonModificator=$currentuser->id;
            // Guardamos el usuario
            $user->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('user.show', array($user->id));
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('user.create')->withInput()->withErrors($user->errors);
        }
    }
    public function updateOperator(Request $request, $id)
    {
        $currentuser= Auth::user(); 
        if($currentuser->create($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::find($id);
        
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($user))
        {
            App::abort(404);
        }
        
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        
        // Revisamos si la data es válido
        if ($user->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            $user->idPersonModificator=$currentuser->id;
            // Guardamos el usuario
            $user->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('user.show', array($user->id));
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('user.edit', $user->id)->withInput()->withErrors($user->errors);
        }
    }
}
