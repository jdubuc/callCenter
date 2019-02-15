<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Champaign;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\PublicPersonGroupCampaign;
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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
//use Maatwebsite\Excel\Facades\Excel;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}
use App\Http\Requests\FileUploadRequest as FileUploadRequest;

class publicPersonGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('publicPersonGroup/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $publicPersonGroup = new publicPersonGroup;
       
      return View::make('publicPersonGroup/form')->with('publicPersonGroup', $publicPersonGroup);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=input::all();
        $uploadedFile   = $request->file('file');
        $filename       = $uploadedFile->getClientOriginalName();
        $user           = Auth::user();

        $url = Session::get('backUrl');
        //dd($url);
        /*$claves = explode("/", $url);
        $t=count($claves);
        $idCampaign = $claves[$t-1];*/
        $currentuser    = Auth::user();
          $validation1 = new validation;
        if ($validation1->create($currentuser)==FALSE)
            {
                return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
            }

        $validation = new validation;
          $idCampaign=$validation->urlData(); 
          if(is_int($idCampaign)==true){
            //se chequea campaña
            $campaign=Campaign::find($idCampaign);
                if ($campaign->isValidUser($currentuser)==FALSE)
                {
                    return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
                }
            }
        $user = Auth::user();
        $idAccountUSer=$user->idAccount;

        //nombre del grupo y se crea el grupo
        /*$publicPersonGroup = new publicPersonGroup;
        $fn = explode(".", $filename);
        $t2=count($fn);
        $publicPersonGroup->name=$fn[$t2-2];*/
        //se extrae el tipo de archivo
        $archtype=array( "xls","csv","xlsx");
        $fn = explode(".", $filename);
        $t2=count($fn);
        $tipo=$fn[$t2-1];
        
        if(in_array($tipo, $archtype)==false)
        {
            return Redirect::to($url)->withErrors('no se acepta el tipo de archivo');
        }
        $resultado=\Excel::load($uploadedFile, function($archivo) {
        /*  $results = $archivo->all();
            foreach ($results as $rows) {
                 $columna[]=$rows;
                }*/
        })->get();
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
                                        //return Redirect::to($url)->withErrors('falta un Email de un destinatario');
                                      $bandera++;
                                      $email++;
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
                                elseif($name=='celular')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        //return Redirect::to($url)->withErrors('falta un Celular de un destinatario');
                                        $bandera++;
                                        $celular++;
                                    }
                                } 
                                $ConfigurationVariable = DB::table('ConfigurationVariable') ->where('name', '=', 'PublicPersonCedulaValidation')->first();
                                if($ConfigurationVariable->value==True)
                                {
                                  if($name=='cedula')  
                                  { 
                                      if($descriptions==null||$descriptions=='')
                                      {
                                          return Redirect::to($url)->withErrors('falta una Cedula de un destinatario');
                                      }
                                  } 
                                }
                                if($bandera==3)
                                    {
                                        $bandera=0;
                                        return Redirect::to($url)->withErrors('falta a un destinatario telefono, culular o email, debe contener al menos uno');
                                    }     
                                $bandera=0;  
                      }                   
            }
        //se extraer y crea el grupo con el nombre del archivo
        if($data["groupName"]==null){
        $publicPersonGroup = new publicPersonGroup;
        $fn = explode(".", $filename);
        $t2=count($fn);
        $publicPersonGroup->name=$fn[$t2-2];
        }
        else
        {
            $publicPersonGroup = new publicPersonGroup;
            $publicPersonGroup->name=$data["groupName"];
        }


        $publicPersonGroup->idPersonCreator=$user->id;
        $publicPersonGroup->idAccount =$idAccountUSer;
        $publicPersonGroup->save();

        if(is_int($idCampaign)==true){
            //tabla intermedia
            $publicPersonGroupCampaign = new publicPersonGroupCampaign;
            $publicPersonGroupCampaign->idPublicPersonGroup=$publicPersonGroup->id;
            $publicPersonGroupCampaign->idCampaign=$idCampaign;
            $publicPersonGroupCampaign->save();
        }
        
        //dd($resultado);
        foreach ($resultado  as $rows) {
            $publicPerson = new publicPerson;
            $publicPersonPublicPersonGroup = new publicPersonPublicPersonGroup;
            $publicPersonPublicPersonGroup->idPublicPersonGroup=$publicPersonGroup->id;
                    foreach ($rows as $name => $descriptions){
                            //echo $name . $descriptions;   
                                if($name=='nombre')  
                                { $publicPerson->firstName=$descriptions; }
                                elseif($name=='apellido')  
                                { $publicPerson->lastName=$descriptions; } 
                                elseif($name=='email')  
                                { 
                                  if($descriptions==null||$descriptions=='')
                                  {
                                    $publicPerson->email='';
                                  }
                                  else
                                  {
                                    $publicPerson->email=$descriptions; 
                                  } 
                                } 
                                elseif($name=='telefono')  
                                { 
                                  if($descriptions==null||$descriptions=='')
                                  {
                                    $publicPerson->phoneNumber='';
                                  }
                                  else
                                  {
                                    $publicPerson->phoneNumber=$descriptions; 
                                  }
                                 } 
                                elseif($name=='celular')  
                                { 
                                  if($descriptions==null||$descriptions=='')
                                  {
                                    $publicPerson->cellPhone='';
                                  }
                                  else
                                  {
                                    $publicPerson->cellPhone=$descriptions; 
                                  }  
                                } 
                                elseif($name=='cedula')  
                                { 
                                   if($descriptions==null||$descriptions=='')
                                  {
                                    $publicPerson->cedula='';
                                  }
                                  else
                                  {
                                    $publicPerson->cedula=$descriptions; 
                                  }
                                } 
                                elseif($name=='twitter')  
                                { 
                                  if($descriptions==null||$descriptions=='')
                                  {
                                    $publicPerson->twitter='';
                                  }
                                  else
                                  {
                                    $publicPerson->twitter=$descriptions; 
                                  }
                                }         
                      } 
                      //dd( $publicPerson);
                        $publicPerson->save();
                        $publicPersonPublicPersonGroup->idPublicPerson=$publicPerson->id;

                        $publicPersonPublicPersonGroup->save();
                      
            }
            
        //dd($resultado);
        //return redirect('publicPersonGroup/show ')->withResultado($resultado);
            if($celular!=0||$email!=0||$telefono!=0)
            {
              return View::make('publicPersonGroup/show')->withResultado($resultado)->withErrors('Algunos datos quedaron vacios ');
            }
            else
            {
              return View::make('publicPersonGroup/show')->withResultado($resultado);
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
       //$publicPersonGroup = publicPersonGroup::find($id);
        
        /*if (is_null($publicPersonGroup)) App::abort(404);
        
      return View::make('publicPersonGroup/show', array('publicPersonGroup' => $publicPersonGroup));*/

      return View::make('publicPersonGroup/show');
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
