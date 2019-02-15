<?php

namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use App\PublicPersonGroup;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Campaign;
use Validator;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\validation;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}

class campaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaign = Campaign::paginate();
        return View::make('campaign/list')->with('campaign', $campaign);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $campaign = new Campaign;
        return View::make('campaign/form')->with('campaign', $campaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentuser = Auth::user();
        
        // Creamos un nuevo objeto 
        $campaign = new Campaign;
        if ($campaign->createCampaign($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Obtenemos la data enviada 
        $data = Input::all();
        /*var_dump($data);
        return;*/
        
        //dd($data);
        // Revisamos si la data es válido
        $url = $data['url'];
        if ($campaign->isValid($data))
        {
            // Si la data es valida se la asignamos 
            $campaign->fill($data);
            $campaign->active = false;
            $campaign->idAccount = Auth::user()->idAccount;
            $campaign->idPersonModificator = Auth::user()->id;
            $campaign->idPersonCreator = Auth::user()->id; 
            // validar user creator usermodificator
            $campaign->save();
            $id = Auth::user()->id;
                $PersonCampaign= new PersonCampaign;
                $PersonCampaign->idPerson=$id;
                $PersonCampaign->idCampaign=$campaign->id;
                $PersonCampaign->save();
            // Y Devolvemos una redirección a la pagina anterior
            //return Redirect::route('/home', array($campaign->id));
            //$url = Session::get('backUrl');
            //$url = $campaign->url;
            return Redirect::to($url);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
            return Redirect::route('campaign.create')->withInput()->withErrors($campaign->errors);
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
        $currentuser = Auth::user();
        $campaign = Campaign::find($id);
        if (is_null($campaign)) App::abort(404);
        $currentuser = Auth::user();
        if ($campaign->isValidUserCampaign($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        return View::make('campaign/show', array('campaign' => $campaign));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = campaign::find($id);
        if (is_null ($campaign))
        {
        App::abort(404);
        }
        return View::make('campaign/form')->with('campaign', $campaign);
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
        // Creamos un nuevo objeto 
        $campaign = Campaign::find($id);
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::route('campaign.edit', $campaign->id)->withInput()->withErrors('No tienes permiso');
        }
            // Obtenemos la data enviada 
        $data = Input::all();
        // Revisamos si la data es válido
        if ($campaign->isValid($data))
        {
            // Si la data es valida se la asignamos 
            $campaign->fill($data);
            // Guardamos el usuario
            $campaign->idPersonModificator = Auth::user()->id;
            $campaign->active = '0';
            $campaign->save();
            // Y Devolvemos una redirección a la acción show para mostrar
            return Redirect::route('campaign.show', array($campaign->id));
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('campaign.edit', $campaign->id)->withInput()->withErrors($campaign->errors);
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
        $currentuser = Auth::user();
        $campaign = Campaign::find($id);
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::route('campaign.edit', $campaign->id)->withInput()->withErrors('No tienes permiso');
        }
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        
        $campaign->delete();

        if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'Usuario ' . $campaign->firstName . $campaign->lastName . ' eliminado',
                'id'      => $campaign->id
            ));
        }
        else
        {
            return Redirect::route('home');
        }
    }
    public function type($id)
    {
      $campaign = new Campaign;
      if($id=='callCenter')
      {
        return View::make('campaign/form')->with('campaign', $campaign);
      }
      if($id=='email')
      {
        return View::make('campaignEmail/form')->with('campaign', $campaign);
      }
      if($id=='sms')
      {
        return View::make('campaignSms/form')->with('campaign', $campaign);
      }
      if($id=='Encuesta')
      {
        return View::make('campaignEncuesta/form')->with('campaign', $campaign);
      }
    }

    public function activar($id)
    {
        /* $url = Session::get('backUrl');
        $claves = explode("/", $url);
        $t=count($claves);
        $idCampaign = $claves[$t-1];*/
        //$validation = new validation;
          //$idCampaign=$validation->urlData(); 
          // Creamos un nuevo objeto 
        $campaign = Campaign::find($id);
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors($campaign->errors);
        }
        //$dia=date("d");
        //$mes=date("m");
        //$ano=date("Y");
        date_default_timezone_set("America/New_York"); 
        //dd(date("Y-m-d H:i:s"));

        if($campaign->dateTimeEnd<=date("Y-m-d H:i:s"))
        {
            return Redirect::to('/errors')->withInput()->withErrors('No se puede activar, porque ya paso la fecha de finalización');
        }
        $campaign->active=true;
        $campaign->save();
                //$url = Session::get('backUrl');
                return Redirect::to("/home");
    }
    public function desactivar($id)
    {
        //$validation = new validation;
          //$idCampaign=$validation->urlData(); 
          // Creamos un nuevo objeto 
        $campaign = Campaign::find($id);
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors($campaign->errors);
        }
        //$mSend=messageSend::where('idCampaign', '=', $idCampaign)->get();
        /*$mSend=DB::table('MessageSend') ->where('idCampaign', '=', $campaign->id)->get();
        if($mSend!=null)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No se puede desactivar, porque ya se realizo una llamada');
        }*/
        $campaign->active=false;
        $campaign->save();
                return Redirect::to("/home");
    }
    public function campaignDate (Request $request){
        $data = Input::all();
        $idCampaign = $request->input('campaignSelect');
        $currentuser= Auth::user(); 
        $validation = new validation;
        $campaign = Campaign::find($idCampaign);
        //dd($campaign);
        $results = [ 'value' => $campaign->id, 'fechaInicio' => $campaign->dateTimeStart, 'fechaFinal' => $campaign->dateTimeEnd ];
        header('Content-Type: application/json');
        return response()->json($results);
    }

    public function campaignDestinatary (Request $request){
        $data = Input::all();
        $idCampaign = $request->input('campaignDestinatary');
        //dd($idCampaign);
        $currentuser= Auth::user(); 
        $validation = new validation;
        $results=array();
        /* $ppgc = DB::table('PublicPersonGroupCampaign')->where('idCampaign','=',$idCampaign)->get();
        $results=array();
        dd( $ppgc);
        foreach ($ppgc as $key ) {
            $ppg= PublicPersonGroup:: where('id','=',$key->idPublicPersonGroup);
            $results[] = [ 'value' => $ppg->id, 'nombre' => $ppg->name ];
        }
        dd($results); */

        $ppg = DB::table('PublicPersonGroup')
            ->join('PublicPersonGroupCampaign', 'PublicPersonGroup.id', '=', 'PublicPersonGroupCampaign.idPublicPersonGroup')
            ->where('PublicPersonGroupCampaign.idCampaign', '=', $idCampaign)
            ->select('PublicPersonGroup.id','name')
            ->get();
        //dd($ppg);
        foreach ($ppg as $key ) {
            $results[] = [ 'idPpg' => $key->id, 'name' => $key->name ];
        }
        //dd($results);


        header('Content-Type: application/json');
        return response()->json($results);


    }

    public function campaignOperator (Request $request){
        $data = Input::all();
        $idCampaign = $request->input('campaignOperator');
        //dd($idCampaign);
        $currentuser= Auth::user(); 
        $validation = new validation;
        $results=array();
        /* $ppgc = DB::table('PublicPersonGroupCampaign')->where('idCampaign','=',$idCampaign)->get();
        $results=array();
        dd( $ppgc);
        foreach ($ppgc as $key ) {
            $ppg= PublicPersonGroup:: where('id','=',$key->idPublicPersonGroup);
            $results[] = [ 'value' => $ppg->id, 'nombre' => $ppg->name ];
        }
        dd($results); */

        $ppg = DB::table('PersonGroup')
            ->join('CampaignPersonGroup', 'PersonGroup.id', '=', 'CampaignPersonGroup.idPersonGroup')
            ->where('CampaignPersonGroup.idCampaign', '=', $idCampaign)
            ->select('PersonGroup.id','name')
            ->get();
        //dd($ppg);
        foreach ($ppg as $key ) {
            $results[] = [ 'idPg' => $key->id, 'name' => $key->name ];
        }
        //dd($results);


        header('Content-Type: application/json');
        return response()->json($results);


    }
    public function campaignOperatorList (Request $request){
        $data = Input::all();
        $idCampaign = $request->input('campaignOperator');
        //dd($idCampaign);
        $currentuser= Auth::user(); 
        $validation = new validation;
        $results=array();
        /* $ppgc = DB::table('PublicPersonGroupCampaign')->where('idCampaign','=',$idCampaign)->get();
        $results=array();
        dd( $ppgc);
        foreach ($ppgc as $key ) {
            $ppg= PublicPersonGroup:: where('id','=',$key->idPublicPersonGroup);
            $results[] = [ 'value' => $ppg->id, 'nombre' => $ppg->name ];
        }
        dd($results); */

        $ppg = DB::table('Person')
            ->join('PersonPersonGroup', 'Person.id', '=', 'PersonPersonGroup.idPerson')
            ->join('CampaignPersonGroup', 'PersonPersonGroup.idPersonGroup', '=', 'CampaignPersonGroup.idPersonGroup')
            ->where('CampaignPersonGroup.idCampaign', '=', $idCampaign)
            ->select('Person.id','firstName','lastName')
            ->distinct()->get();
        //dd($ppg);
        foreach ($ppg as $key ) {
            $results[] = [ 'idPg' => $key->id, 'firstName' => $key->firstName, 'lastName' => $key->lastName ];
        }
        //dd($results);


        header('Content-Type: application/json');
        return response()->json($results);


    }
}
