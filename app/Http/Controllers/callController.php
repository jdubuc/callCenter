<?php

namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use App\MessageSend;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Campaign;
use App\Question;
use Validator;
use App\validation;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}

class callController extends Controller
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
      return View::make('call/form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Input::all();
        $user= Auth::user();
        //$data["tiempoPregunta"]=explode(",", $data["tiempoPregunta"]);
        //dd($data);
        //dd( ['idUser' => $user->id, 'idMessageSend' =>$data["ms"], 'answer' => $data ]);
        //dd(['query' =>['idUser' => $user->id, 'idMessageSend' =>$data["ms"]]+$data );
        $validation = new validation; 
        $client = new Client(); //GuzzleHttp\Client
        $a=['idUser' => $user->id,'email' => $user->email,'pass' => $user->password, 'idMessageSend' =>$data["ms"]];
        foreach ($data  as $key => $value) {
            $a[$key]=$value;
        }
        //dd(['query' => $a]);
        //dd($a);
        $response = $client->get($validation->urlSaveCall(),['query' => $a])->getBody();
        // $response = $client->request('POST', $validation->urlSaveCall(),['idUser' => $user->id, 'idMessageSend' =>$data["ms"], 'answer' => $data ],array())->setBody()->send();
        //$response = $client->request('POST', $validation->urlSaveCall(),['expect' => false], ['query' =>['idUser' => $user->id, 'idMessageSend' =>$data["ms"], 'answer' => $data ]]);
        //dd($response);
        $obj = json_decode($response);
        // dd( $obj);
        if($obj->message=='OK')
        {
            if($data["boton"]=='siguiente')
            {
                $ms=MessageSend::find($data["ms"]);
                $c=campaign::find($ms->idCampaign); 
                if($c->type=='Encuesta')
                {
                    $url=url('/callEncuestaForm/to/'. $ms->idCampaign);
                }
                else
                {
                $url=url('/call/to/'. $ms->idCampaign);
                }//dd($url);
                //return View::make('call/to')->with('c', $c);
                return Redirect::to($url);
            }
            elseif($data["boton"]=='terminar')
            {
                return View::make('/homeOperator');
            }
        }
        /*else
        {
            return Redirect::to('/errors')->withInput()->withErrors('Hubo un error al guardar la llamada');
        }*/
         //si data['boton'] es aceptar se redirecciona de nuevo al create si es colgar al homeOperator
            //return Redirect::to('/homeOperator');
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
          
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
  
    public function call($id)
    {
        $campaign =  Campaign::find($id);
        $currentUser= Auth::user();
        $validation = new validation; 
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get($validation->urlGetNextCall(),['query' => ['idCampaign' => $campaign->id,'idUser' => $currentUser->id,'email' => $currentUser->email,'passw' => $currentUser->password]])->getBody();
        $obj = json_decode($response);
        //dd( $obj); 
        if($obj->algo==null)
        {
            //return Redirect::to('call/errors')->withInput()->withErrors('no se pueden realizar mas llamadas para esta campaña');
            return Redirect::to('/homeOperator')->withErrors('no se pueden realizar mas llamadas para esta campaña');
        }
        $messageSend=messageSend::find($obj->algo);
        //dd($messageSend);
        if($messageSend==null)
        {
            return Redirect::to('/errors')->withInput()->withErrors('Error al realizar la llamada');
        }
      return View::make('call/form')->with('messageSend', $messageSend);
    }

    /*public function loginCall()
    {
        $currentUser= Auth::user();
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->get("http://192.168.0.106:8881/getCampaign",['query' => ['idUser' => $currentUser->id,'email' => $currentUser->email,'passw' => $currentUser->password]])->getBody();
        $obj = json_decode($response);
        dd($obj); 
        $campaign =  Campaign::find($obj);
        //dd($messageSend);
         if($campaign==null)
         {
            return Redirect::to('/errors')->withInput()->withErrors('Error al buscar campaña o no tiene ninguna asignada');
         }
      return View::make('/homeOperator')->with('campaign', $campaign);
    }*/
}
