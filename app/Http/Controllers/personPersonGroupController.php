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


class personPersonGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $PersonGroup = new PersonGroup;
      return View::make('personGroupShowGroup/formUploadFile')->with('PersonGroup', $PersonGroup);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {//agregar al grupo ya existente
         $url = Session::get('backUrl');
          $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        //dd($idPersonGroup);
        $url2 =  url('/personGroupShowGroup/'.$idPersonGroup);
        //se lee el archivo
        $data=input::all();
        $uploadedFile   = $request->file('file');
        $filename       = $uploadedFile->getClientOriginalName();
        $user           = Auth::user();
        $currentuser    = Auth::user();
        $validation1 = new validation;
        if ($validation1->create($currentuser)==FALSE)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
            }
        //se extrae el tipo de archivo
        $archtype=array( "xls","csv","xlsx");
        $fn = explode(".", $filename);
        $t2=count($fn);
        $tipo=$fn[$t2-1];
        if(in_array($tipo, $archtype)==false)
        {
            return Redirect::to($url)->withErrors('no se acepta el tipo de archivo');
        }
        //dd($uploadedFile);
        //se cargan los rows de larchivo
        $resultado=\Excel::load($uploadedFile, function($archivo) {
        })->get();

        foreach ($resultado  as $rows) {
                    foreach ($rows as $name => $descriptions){
                            //echo $name . $descriptions;   
                                if($name=='nombre')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Nombre de un destinatario');
                                    }
                                }
                                elseif($name=='apellido')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Apellido de un destinatario');
                                    }
                                } 
                                elseif($name=='email')  
                                {  
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Email de un destinatario');
                                    }
                                } 
                                elseif($name=='telefono')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un telefono de un destinatario');
                                    }
                                } 
                                elseif($name=='cedula')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta una Cedula de un destinatario');
                                    }
                                }        
                      }                   
            }
        //se asignan a las variables y se guardan los operadores leidos y los grupos en las tablas intermedias 
        foreach ($resultado  as $rows) {
            $person = new user;
            $user = Auth::user();
            $personPersonGroup = new personPersonGroup;
            $personPersonGroup->idPersonGroup=$idPersonGroup;
                    foreach ($rows as $name => $descriptions){
                            //echo $name . $descriptions;   
                                if($name=='nombre')  
                                { $person->firstName=$descriptions; }
                                elseif($name=='apellido')  
                                { $person->lastName=$descriptions; } 
                                elseif($name=='email')  
                                { $person->email=$descriptions; } 
                                elseif($name=='telefono')  
                                { $person->phoneNumber=$descriptions; } 
                                elseif($name=='cedula')  
                                { $person->cedula=$descriptions; } 
                                elseif($name=='twitter')  
                                {  
                                  if($descriptions==null||$descriptions=='')
                                  {
                                    $person->twitter='';
                                  }
                                  else
                                  {
                                    $person->twitter=$descriptions; 
                                  }
                                } 
                                if($name=='cedula')  
                                { $person->password=$descriptions; }  
                                $person->pOperator = 2; 
                                $person->idTelegram = 1; 
                                $person->idPersonCreator = $user->id;    
                                $person->idPersonModificator = $user->id; 
                                $person->idAccount = $user->idAccount;    
                      } 
                      //se chequea el email del operador si existe se asigna solo en la tabla intermedia sino se guarda
                      if ($person->emailIsValid()==true){
                        $person->save();
                        $personPersonGroup->idPerson=$person->id;
                        $personPersonGroup->save();
                        //echo '//no existe';
                      }
                      else
                      {
                        $op=user::where('email','=',$person->email)->first();
                        $personPersonGroup->idPerson=$op->id;
                        $personPersonGroup->save();
                        //echo '-- existe--';
                      }
            }
        //dd($resultado);
        //return View::make('personGroup/show')->withResultado($resultado);
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
        //
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
            $validation = new validation;
            $idGroup=$validation->urlData(); 
            $personPersonGroup = personPersonGroup::where('idPerson', '=', $user->id)->where('idPersonGroup', '=', $idGroup)->forcedelete();
         
            return Redirect::to($url);
        }
    }
}
