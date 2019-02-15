<?php

namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use Session;
use Illuminate\Http\Request;
use App\Campaign;
use Validator;
use View;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
}


class campaignEmailController extends Controller
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
        $campaign = new Campaign;
       
      return View::make('campaignEmail/form')->with('campaign', $campaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $campaign = new Campaign;
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //var_dump($data);
        // Revisamos si la data es válido
        if ($campaign->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $campaign->fill($data);
            $campaign->active = false;
            $campaign->type = 'Email';
            $campaign->idAccount = Auth::user()->idAccount;
            $campaign->idPersonModificator = Auth::user()->id;
            $campaign->idPersonCreator = Auth::user()->id; 
            $campaign->save();
            $id = Auth::user()->id;
               
                $PersonCampaign= new PersonCampaign;
                $PersonCampaign->idPerson=$id;
                $PersonCampaign->idCampaign=$campaign->id;
                $PersonCampaign->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            //return Redirect::route('/home', array($campaign->id));
            $url = Session::get('backUrl');
            //dd($url);
            return Redirect::to($url);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('campaignEmail.create')->withInput()->withErrors($campaign->errors);
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
        $campaign = Campaign::find($id);
        
        if (is_null($campaign)) App::abort(404);
        
      return View::make('campaignEmail/show', array('campaign' => $campaign));
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

        return View::make('campaignEmail/form')->with('campaign', $campaign);
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
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $campaign = Campaign::find($id);
        
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        
        // Revisamos si la data es válido
        if ($campaign->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $campaign->fill($data);
            // Guardamos el usuario
            $campaign->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('campaignEmail.show', array($campaign->id));
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('campaignEmail.edit', $campaign->id)->withInput()->withErrors($campaign->errors);
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
        //
    }
}
