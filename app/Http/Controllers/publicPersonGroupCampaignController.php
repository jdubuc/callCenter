<?php

namespace App\Http\Controllers;

use App\User;
use App\PersonCampaign;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Campaign;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonGroupCampaign;
use Validator;
use App\validation;
use View;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
if (Session::has('backUrl')) {
   Session::keep('backUrl');
}
class publicPersonGroupCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $idAccountUSer=$user->idAccount;
        $idUser=$user->id;
        $publicPersonGroup = publicPersonGroup::where('idPersonCreator', '=', $idUser)->get();
        return View::make('publicPersonGroupCampaign/list')->with('publicPersonGroup', $publicPersonGroup);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $publicPersonGroupCampaign = new publicPersonGroupCampaign;
      return View::make('publicPersonGroupCampaign/form')->with('publicPersonGroupCampaign', $publicPersonGroupCampaign);
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
    if (is_null($campaign)) App::abort(404);
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors($campaign->errors);
        }
    // Creamos un nuevo objeto para la tabla intermedia 
    $publicPersonGroupCampaign = new publicPersonGroupCampaign; 
        //recordar transaccion
        //$publicPersonGroupCampaign->fill($data);
    \DB::table('PublicPersonGroupCampaign')->where('idCampaign', '=', $idCampaign)->delete();        
        //$publicPersonGroupCampaign->save();
        //var_dump($data);$data["idPublicPersonGroup"]
    //dd(array_key_exists('idPublicPersonGroup', $data));
    if (array_key_exists('idPublicPersonGroup', $data)==false) {
    return Redirect::to($url);
    }
        foreach ($data["idPublicPersonGroup"]  as $data["idPublicPersonGroup"]) {
                //echo $data["idPublicPersonGroup"];
                $publicPersonGroupCampaign = new publicPersonGroupCampaign; 
                $publicPersonGroupCampaign->idCampaign=$idCampaign;
                $publicPersonGroupCampaign->idPublicPersonGroup=$data["idPublicPersonGroup"];
                $publicPersonGroupCampaign->save();
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
         $publicPersonGroupCampaign = new publicPersonGroupCampaign;
         $publicPersonGroupCampaign->idCampaign=$campaign->id;
      return View::make('publicPersonGroupCampaign/show')->with('publicPersonGroupCampaign', $publicPersonGroupCampaign);
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
