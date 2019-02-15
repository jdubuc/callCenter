<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\PersonCampaign;
use App\ReporteDestinatario;
use App\PublicPersonGroup;
use App\PublicPersonGroupCampaign;
use App\Campaign;
use Validator;
use DB;
use View;
use DateTime;
use App\Http\Requests;
use Session;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

if (Session::has('backUrl')) {
   Session::keep('backUrl');}
   

class reporteDestinatarioController extends Controller
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
        //$reporteDestinatario = new ReporteDestinatario;

        $currentuser= Auth::user(); 
        $PersonCampaign=PersonCampaign::where('idPerson', '=', $currentuser->id)->get();
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        
        return View::make('reporteDestinatario/form')->with('PersonCampaign', $PersonCampaign);
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
        // Obtenemos la data enviada por el usuario
        //$db=DB::raw('select * from "rptResultCampEvaluacion"(5,\'20170507\',\'20170508\')');
        //$V=DB::select(DB::raw('select * from "rptResultCampEvaluacion"(5,\'20170507\',\'20170508\')'));
        //dd($V);
        //$V2=DB::select(DB::raw('select * from "rptResultCampPreguntas"(5,\'20170507\',\'20170508\')'));
        $data = Input::all();
        //dd($data);
        $reporteDestinatario = new ReporteDestinatario;
        if ($reporteDestinatario->isValid($data))
        {
            $where = "";
            $numGroups= PublicPersonGroupCampaign::where('idCampaign','=',$data['idCampaign'])->count();

            if(count($data['grupoDestinatario']) != $numGroups) //si la cadena trae menos que la cantidad total de grupos de la campaña
            {
                if(count($data['grupoDestinatario'])==1)
                {
                    $ng=$data['grupoDestinatario'][0];
                    $where = 'WHERE "idPersonGroup" IN ('.$ng.')';
                }
                else
                {
                    $ng=implode("','",$data['grupoDestinatario']);
                    $where = 'WHERE "idPersonGroup" IN ('.$ng.')';  
                }
            }
            //dd($data);
            $idCampaign=$data['idCampaign'];

            if($data['dateTimeReporteStart']=='')
            {
                $campaign = Campaign::find($idCampaign);
                $date=date_create($campaign->dateTimeStart);
                //dd(date_format($date, 'Y-m-d'));
                $data['dateTimeReporteStart']=date_format($date, 'Y-m-d');
            }
            if($data['dateTimeReporteEnd']=='')
            {
                $campaign = Campaign::find($idCampaign);
                $date=date_create($campaign->dateTimeEnd);
                $data['dateTimeReporteEnd']=date_format($date, 'Y-m-d');
            }
            
            $inicio=str_replace('-', '', $data['dateTimeReporteStart']);//str_replace('-', '', "2017-05-07");//
            $final=str_replace('-', '', $data['dateTimeReporteEnd']);//str_replace('-', '', "2017-05-08");//
            //$date = new DateTime('20170508');
            //dd($date);
            $V=DB::select(DB::raw('select * from "rptResultCampEvaluacion"(' . $idCampaign .',\''.$inicio.'\',\''.$final .'\')'. $where .''));
            $V2=DB::select(DB::raw('select * from "rptResultCampPreguntas"(' . $idCampaign .',\''.$inicio.'\',\''.$final .'\')'. $where .''));
            //dd($V2);
            //dd($V);
            $data=array('v'=>$V, 'v2'=>$V2, 'idCampaign'=>$idCampaign, 'inicio'=>$inicio,'final'=>$final);
            //dd($data);
            return View::make("reporteDestinatario/show")->with('data',$data);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
            return Redirect::route('reporteDestinatario.create')->withInput()->withErrors($reporteDestinatario->errors);
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
        return View::make('reporteDestinatario/show');
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
