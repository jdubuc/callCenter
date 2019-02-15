<?php

namespace App\Http\Controllers;

use App\User;
use App\PersonCampaign;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Campaign;
use App\Person;
use App\PersonGroup;
use App\PersonPersonGroup;
use App\PersonGroupCampaign;
use Validator;
use View;
use Auth;
use App\validation;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
if (Session::has('backUrl')) {
   Session::keep('backUrl');}

class personGroupCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('personGroupCampaign/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personGroupCampaign = new personGroupCampaign;
      return View::make('personGroupCampaign/form')->with('personGroupCampaign', $personGroupCampaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Obtenemos la data enviada por el usuario
    $data = Input::all();
    //se saca el idCampaign de la url
    $url = Session::get('backUrl');
    /*$claves = explode("/", $url);
    $t=count($claves);
    $idCampaign = $claves[$t-1];*/
    $validation = new validation;
    $idCampaign=$validation->urlData(); 
    $currentuser = Auth::user();
    $campaign= Campaign::find($idCampaign);
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
    // Creamos un nuevo objeto para la tabla intermedia 
    $personGroupCampaign = new personGroupCampaign; 
        //recordar transaccion
        //$personGroupCampaign->fill($data);
    \DB::table('CampaignPersonGroup')->where('idCampaign', '=', $idCampaign)->delete();        
        //$personGroupCampaign->save();
        //var_dump($data);$data["idpersonGroup"]
    //dd($data["idPersonGroup"]);
    if (array_key_exists('idPersonGroup', $data)==false) {
    return Redirect::to($url);
    }
        foreach ($data["idPersonGroup"]  as $data["idPersonGroup"]) {
                //echo $data["idpersonGroup"];
                $personGroupCampaign = new personGroupCampaign; 
                $personGroupCampaign->idCampaign=$idCampaign;
                $personGroupCampaign->idPersonGroup=$data["idPersonGroup"];
                $personGroupCampaign->save();
            }
            //dd($data);
             return Redirect::to($url);
    
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
         // Creamos un nuevo objeto 
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
         $personGroupCampaign = new personGroupCampaign;
         $personGroupCampaign->idCampaign=$campaign->id;
      return View::make('personGroupCampaign/show')->with('personGroupCampaign', $personGroupCampaign);
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
