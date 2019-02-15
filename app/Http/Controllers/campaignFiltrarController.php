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

class campaignFiltrarController extends Controller
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
        //
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
        // Obtenemos la data enviada 
        $data = Input::all();
        //dd($data);
        $start=str_replace('-', '', $data['start']);
        $end=str_replace('-', '', $data['end']);
        $mensaje=$data['mensaje'];
        $tipo=$data['tipo'];
        $active=$data['estado'];
        $whereTipo = "";
        $whereMsg = "";
        $whereEstado = "";
        $whereEnd = "";
         // Creamos un nuevo objeto 
        $campaign = new Campaign;
        if ($campaign->createCampaign($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
      
        if( !empty ( $data['tipo'] ) )
            {
                $whereTipo = ' and "Campaign"."type" = \''.$tipo.'\'';  
            }
        if( !empty ( $data['mensaje'] ) )
            {
                $whereMsg = ' and "Campaign"."campaignMessage" LIKE \'%'.$mensaje.'%\'';  
            }
        if( !empty ( $data['estado'] ) )
            {
                $whereEstado = ' and "Campaign"."active" = \''.$active.'\'';  
            }

        if( !empty ( $data['start'] ) )
            {
                 if( !empty ( $data['end'] ) )
                {
                    $whereEnd = ' and "Campaign"."created_at" between  \''.$start.'\' and \''.$end.'\'';  
                }  
                else
                {
                     return Redirect::to($url)->withInput()->withErrors('Si se ingresa fecha de desde se debe ingresar una de hasta');
                }
            }

          
        $PersonCampaign =DB::select(DB::raw('SELECT *from "Campaign" 
        inner join "PersonCampaign" on "Campaign".id="PersonCampaign"."idCampaign"
        where "PersonCampaign"."idPerson"=' .$currentuser->id.''.$whereTipo.''.$whereMsg.''.$whereEstado.''.$whereEnd.''));
        //dd($PersonCampaign);
        //return View::make('campaignFiltrar/show', array('PersonCampaign' => $PersonCampaign));
            return View::make('campaignFiltrar/list')->with('PersonCampaign', $PersonCampaign);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         return View::make('campaignFiltrar/show');
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
