<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Campaign;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\PublicPersonGroupCampaign;
use App\User;
use Session;
use Validator;
use View;
use App\Http\Requests;
use App\File;
use Auth;
use Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
//use Maatwebsite\Excel\Facades\Excel;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}
use App\Http\Requests\FileUploadRequest as FileUploadRequest;

class publicPersonGroupShowGroupController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $publicPersonGroup = new publicPersonGroup;
      return View::make('publicPersonGroupShowGroup/formUploadFile')->with('publicPersonGroup', $publicPersonGroup); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //agregar al grupo ya existente
         $url = Session::get('backUrl');
          $validation = new validation;
        $idPublicPersonGroup=$validation->urlData(); 
        //dd($idPublicPersonGroup);
        $url2 =  url('/personGroupShowGroup/'.$idPublicPersonGroup);
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
                                elseif($name=='celular')  
                                { 
                                    if($descriptions==null||$descriptions=='')
                                    {
                                        return Redirect::to($url)->withErrors('falta un Celular de un destinatario');
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
            $publicPerson = new publicPerson;
            $publicPersonPublicPersonGroup = new publicPersonPublicPersonGroup;
            $publicPersonPublicPersonGroup->idPublicPersonGroup=$idPublicPersonGroup;
                    foreach ($rows as $name => $descriptions){
                            //echo $name . $descriptions;   
                                if($name=='nombre')  
                                { $publicPerson->firstName=$descriptions; }
                                elseif($name=='apellido')  
                                { $publicPerson->lastName=$descriptions; } 
                                elseif($name=='email')  
                                { $publicPerson->email=$descriptions; } 
                                elseif($name=='telefono')  
                                { $publicPerson->phoneNumber=$descriptions; } 
                                elseif($name=='celular')  
                                { $publicPerson->cellPhone=$descriptions; } 
                                elseif($name=='cedula')  
                                { $publicPerson->cedula=$descriptions; } 
                                elseif($name=='twitter')  
                                { $publicPerson->twitter=$descriptions; }         
                      } 
                        $publicPerson->save();
                        $publicPersonPublicPersonGroup->idPublicPerson=$publicPerson->id;
                        $publicPersonPublicPersonGroup->save();
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
       $publicPersonPublicPersonGroup = PublicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $id)->get();

       $publicPersonGroup = PublicPersonGroup::find($id);
        
        /*if (is_null($publicPersonGroup)) App::abort(404);
        
      return View::make('publicPersonGroup/show', array('publicPersonGroup' => $publicPersonGroup));*/

      return View::make('publicPersonGroupShowGroup/show')->with('publicPersonPublicPersonGroup',$publicPersonPublicPersonGroup)->with('publicPersonGroup',$publicPersonGroup);
    
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
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operaciónt');
        }
        $publicPerson= PublicPerson::find($id);
        $ppppg= PublicPersonPublicPersonGroup::where('idPublicPerson', '=', $publicPerson->id)->get();
        //dd($user);
        //dd($url); 
        if (is_null ($publicPerson))
        {
            App::abort(404);
        }
        else
        {
             if($ppppg!=null)
            {
               return Redirect::to($url)->withInput()->withErrors('no se puede sacar ');
            }
            
            $Msend=MessageSend::where('idPublicPerson','=',$publicPerson->id);
            if($Msend!=null)
            {
                return Redirect::to($url)->withInput()->withErrors('no se puede sacar porque ya esta en una llamada');
            }
            $ppppg->delete();
            $publicPerson->delete();
            return Redirect::to($url);
        }
    }

    public function publicPersonGroupToCsv($id)
    {

    $table = PublicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $id)->get();
    $filename = "GrupoDestinatarios.csv";
    $handle = fopen($filename, 'w+');
    fputcsv($handle, array('cedula', 'nombre', 'apellido', 'email','telefono','celular','twitter'));

    foreach($table as $row) {
         $PublicPerson = PublicPerson::where('id', '=', $row->idPublicPerson)->first();
        fputcsv($handle, array($PublicPerson->cedula,$PublicPerson->firstName, $PublicPerson->lastName, $PublicPerson->email, $PublicPerson->phoneNumber, $PublicPerson->celular, $PublicPerson->twitter));
    }

    fclose($handle);

    $headers = array(
        'Content-Type' => 'text/csv',
    );

    return \Response::download($filename, 'GrupoDestinatarios.csv', $headers);
    }
}
