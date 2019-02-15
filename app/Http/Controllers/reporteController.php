<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\PersonCampaign;
use App\reporte;
use App\ReporteOperador;
use App\reporteTiempoOperador;
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
   
class reporteController extends Controller
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
         $currentuser= Auth::user(); 
        $PersonCampaign=PersonCampaign::where('idPerson', '=', $currentuser->id)->get();
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        
        return View::make('reporte/form')->with('PersonCampaign', $PersonCampaign);
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
        $data = Input::all();
        $reporte = new reporte;
        if ($reporte->isValid($data))
        {


            //dd($data);
            $idCampaign=$data['idCampaign'];

            if($data['dateTimeReporteStart']=='')
            {
                $campaign = Campaign::find($idCampaign);
                $date=date_create($campaign->dateTimeStart);
                //dd(date_format($date, 'Y-m-d'));
                $data['dateTimeReporteStart']=date_format($date, 'Y-m-d');
                //dd($data['dateTimeReporteStart']);
            }
            if($data['dateTimeReporteEnd']=='')
            {
                $campaign = Campaign::find($idCampaign);
                $date=date_create($campaign->dateTimeEnd);
                $data['dateTimeReporteEnd']=date_format($date, 'Y-m-d');
            }
            $inicio=str_replace('-', '', $data['dateTimeReporteStart']);
            $final=str_replace('-', '', $data['dateTimeReporteEnd']);
               
            $answer = DB::table('Answer')
                ->join('MessageSend', 'Answer.idMessageSend', '=', 'MessageSend.id')
                ->join('Question', 'Answer.idQuestion', '=', 'Question.id')
                ->where('MessageSend.idCampaign', '=', $idCampaign)//faltan fechas inicio final
                ->where('MessageSend.created_at', '>=', $inicio)
                ->where('MessageSend.created_at', '<=', $final)
                ->select('Answer.id','Answer.data','Answer.idQuestion','Answer.idMessageSend','Question.order','Question.idQuestionType')
                ->orderBy('idMessageSend', 'desc')->orderBy('Question.order', 'asc')->get();
            //dd($inicio);
            $data=array('answer'=>$answer,'idCampaign'=>$idCampaign, 'inicio'=>$inicio,'final'=>$final);
            //dd($data);
            return View::make("reporte/show")->with('data',$data);
         }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
            return Redirect::route('reporte.create')->withInput()->withErrors($reporte->errors);
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
        return View::make('reporte/show');
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
