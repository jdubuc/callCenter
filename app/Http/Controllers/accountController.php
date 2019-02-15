<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\Campaign;
use Validator;
use View;
use Session;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

class accountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentuser= Auth::user(); 
        if ($currentuser->isSuperSuperAdmin($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
         $accounts = Account::paginate(10);
        
        return View::make('account/list')->with('accounts', $accounts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentuser= Auth::user(); 
        if ($currentuser->isSuperSuperAdmin($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
         $account = new Account;
      return View::make('account/form')->with('account', $account);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $account = new Account;
        $currentuser= Auth::user(); 
        if ($currentuser->isSuperSuperAdmin($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //var_dump($data);
        // Revisamos si la data es válido
        if ($account->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $account->fill($data);
             $account->idStatus='1';
            // Guardamos el usuario
            $account->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('account.show', array($account->id));
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('account.create')->withInput()->withErrors($account->errors);
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
       $account = Account::find($id);
        
        if (is_null($account)) App::abort(404);
        
      return View::make('account/show', array('account' => $account));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::find($id);
        $currentuser= Auth::user(); 
        if (is_null($account)) App::abort(404);
        if ($currentuser->isSuperSuperAdmin($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        return View::make('account/form', array('account' => $account));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentuser= Auth::user(); 
        if ($currentuser->isSuperSuperAdmin($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        $account = Account::find($id);
        
        if (is_null ($account))
        {
            App::abort(404);
        }
        
        $account->delete();

        if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'account ' . $account->Name . $account->email . ' eliminado',
                'id'      => $account->id
            ));
        }
        else
        {
            return Redirect::route('home');
        }
    }
}
