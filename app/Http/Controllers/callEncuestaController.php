<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\PublicPersonGroupCampaign;
use App\User;
use App\MessageSend;
use App\PersonCampaign;
use App\Campaign;
use Session;
use App\Person;
use App\PersonGroup;
use App\PersonPersonGroup;
use App\PersonGroupCampaign;
use Validator;
use DB;
use View;
use Auth;
use App\validation;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

if (Session::has('backUrl')) {
   Session::keep('backUrl');}


class callEncuestaController extends Controller
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
        $user = new user;
        $publicPerson = new publicPerson;
        if ($user->isOperator($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Creamos un nuevo objeto 
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        $url = Session::get('backUrl');
        $idCampaign= $data['campaign'];
        //$campaign =  Campaign::find($idCampaign);
        $PublicPersonGroupCampaign = PublicPersonGroupCampaign::where('idCampaign','=',$idCampaign)->first();
        //buscar el grupo para asignarlo en la tabla intermedia, campaña-CG-grupo-GPP-publicperson
        $publicPersonGroup = PublicPersonGroup::where('id','=',$PublicPersonGroupCampaign->idPublicPersonGroup)->first();
           $url2 =  url('/callEncuesta/to/'.$idCampaign);
           $url3 =  url('/callEncuestaForm/to/'.$idCampaign);
        /*$validation = new validation;
        $idPublicPersonGroup=$validation->urlData(); 
        $url2 =  url('/publicPersonGroupShowGroup/'.$idPublicPersonGroup);*/
        //dd($publicPersonGroup);
        // Revisamos si la data es válido
        if ($publicPerson->isValid($data))
        {
            // Si la data es valida se la asignamos al modelo
            $publicPerson->fill($data);
            //dd($publicPerson);
            // Guardamos el modelo
            $publicPerson->save();
            //se crea la tabla intermedia para asignarlo al grupo
            $PublicPersonPublicPersonGroup = new PublicPersonPublicPersonGroup; 
            $PublicPersonPublicPersonGroup->idPublicPersonGroup=$publicPersonGroup->id;
            $PublicPersonPublicPersonGroup->idPublicPerson=$publicPerson->id;
            $PublicPersonPublicPersonGroup->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            //return Redirect::route('publicPerson.show', array($publicPerson->id));
            return Redirect::to($url2);
            //return View::make('callEncuesta/form')->with('campaign', $campaign);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::to($url3)->withInput()->withErrors($publicPerson->errors);
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
     public function callEncuestaForm($id)
    {
        $campaign =  Campaign::find($id);
        //dd($campaign);
      return View::make('callEncuesta/form')->with('campaign', $campaign);
    }
     public function callEncuesta($id)
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
            return Redirect::to('/errors')->withInput()->withErrors('no se pueden realizar mas llamadas para esta campaña');
        }
        $messageSend=messageSend::find($obj->algo);
        //dd($messageSend);
        if($messageSend==null)
        {
            return Redirect::to('/errors')->withInput()->withErrors('Error al realizar la llamada');
        }
        return View::make('call/form')->with('messageSend', $messageSend);
    }
}
