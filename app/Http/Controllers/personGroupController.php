<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
//use App\person;
use App\PersonGroup;
use App\PersonPersonGroup;
use App\PersonGroupCampaign;
use App\User;
use Session;
use Validator;
use App\validation;
use View;
use DB;
use App\Http\Requests;
use App\File;
use Auth;
use Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
//use Maatwebsite\Excel\Facades\Excel;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}
use App\Http\Requests\FileUploadRequest as FileUploadRequest;

class personGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('personGroup/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personGroup = new personGroup;
       
      return View::make('personGroup/form')->with('personGroup', $personGroup);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = Session::get('backUrl');
        //se valida el archivo
       /* $rules = array('file' => 'required|max:10000|mimes:application/vnd.ms-excel,xls,csv,xlsx,txt');
          $validator = Validator::make(Input::all(), $rules);
          if ($validator->fails()) {
            return Redirect::to($url)->withErrors($validator);
            }*/
        //se lee el archivo
        $data=input::all();
        $uploadedFile   = $request->file('file');
        if($request->file('file')==null)
        {
          return Redirect::to($url)->withErrors('hubo un error con el archivo');
        }
        else
        {
          $filename       = $uploadedFile->getClientOriginalName();
        }
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
        //se extraer y crea el grupo con el nombre del archivo
        if($data["groupName"]==null){
        $personGroup = new personGroup;
        $fn = explode(".", $filename);
        $t2=count($fn);
        $personGroup->name=$fn[$t2-2];
        }
        else
        {
            $personGroup = new personGroup;
            $personGroup->name=$data["groupName"];
        }
        //dd($uploadedFile);
        //se saca el id de campaign de la url
        $validation = new validation;
          $idCampaign=$validation->urlData(); 
        if(is_int($idCampaign)==true){
            //se chequean los permisos del user para esa campaña
            $campaign = Campaign::find($idCampaign);
            //if (is_null($campaign)) App::abort(404);
                //$currentuser = Auth::user();
            if ($campaign->isValidUser($currentuser)==FALSE)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
            }
            //se crea tabla intermedia  
        }
        
        //se guarda el grupo
        $personGroup->idPersonCreator=$user->id;
        $personGroup->idAccount = $user->idAccount; 
        $personGroup->save();
        //se crea tabla intermedia de asignar una campaña
        if(is_int($idCampaign)==true){
            $personGroupCampaign = new personGroupCampaign;
            $personGroupCampaign->idPersonGroup=$personGroup->id;
            $personGroupCampaign->idCampaign=$idCampaign;
            $personGroupCampaign->save();
        }

        //se cargan lso rows de larchivo
        $resultado=\Excel::load($uploadedFile, function($archivo) {
        })->get();
        $alert=array();

        $bandera=0;
        $email=0;
        $telefono=0;
        $celular=0;
         foreach ($resultado  as $rows) {
                    foreach ($rows as $name => $descriptions){
                            //echo $name . $descriptions;   
                                if($name=='nombre')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Nombre de un Operador');
                                    }
                                }
                                elseif($name=='apellido')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Apellido de un Operador');
                                    }
                                } 
                                elseif($name=='email')  
                                {  
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Email de un Operador');
                                    }
                                } 
                                elseif($name=='telefono')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        //return Redirect::to($url)->withErrors('falta un telefono de un destinatario');
                                      $bandera++;
                                      $telefono++;
                                    }
                                } 
                                elseif($name=='cedula')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta una Cedula de un Operador');
                                        //$alert['cedula']=$alert['cedula']++;
                                    }
                                }        
                      }                   
            }
        //se asignan a las variables y se guardan los operadores leidos y los grupos en las tablas intermedias 
        foreach ($resultado  as $rows) {
            $person = new user;
            $user = Auth::user();
            $personPersonGroup = new personPersonGroup;
            $personPersonGroup->idPersonGroup=$personGroup->id;
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
                         if($validation->ConfigurationVariable('sendEmailConfirmation')=='true')
                          {
                              $data ['confirmation_code'] = $confirmation_code;
                              Mail::send('emails.verify', $data, function($message) {
                              $message->to(Input::get('email'), Input::get('firstName'))
                                  ->subject('Verifica tu dirección de correo Call Center');
                              });
                          }
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
            if($telefono!=0)
            {
               return View::make('personGroup/show')->withResultado($resultado)->withErrors('Algunos datos quedaron vacios ');
            }
            else
            {
               return View::make('personGroup/show')->withResultado($resultado);
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
        return View::make('personGroup/show');
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
        //
    }
}
