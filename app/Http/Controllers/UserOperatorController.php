<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PersonCampaign;
use App\PersonGroup;
use App\PersonPersonGroup;
use App\PersonGroupCampaign;
use App\Campaign;
use App\Account;
use App\validation;
use Validator;
use View;
use App\Http\Requests;
use DB;
use Session;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

if (Session::has('backUrl')) {
   Session::keep('backUrl');}

class UserOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $currentuser= Auth::user(); 
         if($currentuser->createOperator($currentuser)==true )
        {
            $users = User::where('idAccount','=',$currentuser->idAccount)->where('pOperator','=','2')->paginate(10);
        }
        else
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
         
        return View::make('userOperator/list')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $currentuser= Auth::user(); 
        if($currentuser->createOperator($currentuser)==false)
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
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');

        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $url2 =  url('/personGroupShowGroup/'.$idPersonGroup);
        //dd($url2);
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //dd($data);
           // Creamos un nuevo objeto para la tabla intermedia 
        $personPersonGroup = new personPersonGroup; 
        //borramos las tablas intermedias que ya existen
        \DB::table('PersonPersonGroup')->where('idPersonGroup', '=', $idPersonGroup)->delete();        

        foreach ($data["idPerson"]  as $data["idPerson"]) {
                //echo $data["idpersonGroup"];
                $personPersonGroup = new personPersonGroup; 
                $personPersonGroup->idPersonGroup=$idPersonGroup;
                $personPersonGroup->idPerson=$data["idPerson"];
                $personPersonGroup->save();
            }
             return Redirect::to($url2);
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
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $personGroup = personGroup::find($id);
        //dd($personGroup);
        if (is_null($personGroup)) App::abort(404);
        
      return View::make('userOperator/show', array('personGroup' => $personGroup));
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

        return View::make('user/formCreateOperator')->with('user', $user);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = User::find($id);
        //dd($user);
        //dd($url); 
        if (is_null ($user))
        {
            App::abort(404);
        }
       /* if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'Operador ' . $user->firstName . $user->lastName . ' desactivado',
            ));
        }*/
        else
        {
            $personPersonGroup = personPersonGroup::where('idPerson', '=', $user->id)->delete();
            //dd($personPersonGroup);
            $user->delete();
            //return Redirect::route('home');
            return Redirect::to($url);
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
        
    }
    public function updateOperator(Request $request, $id)
    {
        
    }
    public function deactivatedOperator()
    {
        $currentuser= Auth::user(); 
         if($currentuser->createOperator($currentuser)==true )
        {
            $users = User::onlyTrashed()
                ->where('idAccount','=',$currentuser->idAccount)->where('pOperator','=','2')->paginate(10);
        }
        else
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
         
        return View::make('userOperator/deactivatedList')->with('users', $users);
    }
    public function activateOperator($id)
    {
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        //dd($id);
        $userF = User::onlyTrashed()->where('id',$id)->restore();
        $user = User::find($id);
        $personPersonGroup = personPersonGroup::onlyTrashed()->where('idPerson', '=', $user->id)->restore();
       /* if($personPersonGroup != null)
        {
            //dd($personPersonGroup);
            foreach ($personPersonGroup as $ppg ) {
                $ppg->restore();
            }
        }*/
        //$user = User::withTrashed()->where('id',$id)->restore();
        /*$user = DB::table('Person')
        ->where('id','=',$id)
        ->where('idAccount','=',$currentuser->idAccount)
        ->where('pOperator','=','2')
        ->whereNotNull('deleted_at')->first()->update(['deleted_at' => null]);*/
        //dd($user);
        //$user->restore();
        //$user->deleted_at=null;
        //$user->save;
        if (is_null ($user))
        {
            App::abort(404);
        }
        
            //return Redirect::route('home');
            return Redirect::to($url);
        
    }
}
