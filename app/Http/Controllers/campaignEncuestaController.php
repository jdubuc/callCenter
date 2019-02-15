<?php


namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use App\PublicPersonGroup;
use App\PublicPersonGroupCampaign;
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

class campaignEncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaign = Campaign::paginate();
        return View::make('campaignEncuesta/list')->with('campaign', $campaign);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $campaign = new Campaign;
        return View::make('campaignEncuesta/form')->with('campaign', $campaign);
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
        //dd($data);
        // Revisamos si la data es válido
        if ($campaign->isValid($data))
        {
            // Si la data es valida se la asignamos 
            $campaign->fill($data);
            $campaign->active = false;
            $campaign->type='Encuesta';
            $campaign->idAccount = Auth::user()->idAccount;
            $campaign->idPersonModificator = Auth::user()->id;
            $campaign->idPersonCreator = Auth::user()->id; 
            //se guarda los datos para crear la campaña
            $campaign->save();
            //se crea la relacion entre el usuario y la campaña
            $id = Auth::user()->id;
                $PersonCampaign= new PersonCampaign;
                $PersonCampaign->idPerson=$id;
                $PersonCampaign->idCampaign=$campaign->id;
                $PersonCampaign->save();
            //se crea el grupo de public person por defecto ya que es encuesta
            $publicPersonGroup= new PublicPersonGroup;
                $publicPersonGroup->name=$campaign->name;
                $publicPersonGroup->idPersonCreator=$id;
                $publicPersonGroup->idAccount=$currentuser->idAccount;
                $publicPersonGroup->save();
            //se rea la tabla intermedia entre el grupo de destinatario y la campaña
            $publicPersonGroupCampaign= new PublicPersonGroupCampaign;
                $publicPersonGroupCampaign->idCampaign=$campaign->id;
                $publicPersonGroupCampaign->idPublicPersonGroup=$publicPersonGroup->id;
                $publicPersonGroupCampaign->save();
            // Y Devolvemos una redirección a la pagina anterior
            //return Redirect::route('/home', array($campaign->id));
            $url = Session::get('backUrl');
            return Redirect::to('/home');
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
            return Redirect::route('campaignEncuesta.create')->withInput()->withErrors($campaign->errors);
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
        return View::make('campaignEncuesta/show', array('campaign' => $campaign));
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
        return View::make('campaignEncuesta/form')->with('campaign', $campaign);
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
            return Redirect::route('campaignEncuesta.edit', $campaign->id)->withInput()->withErrors('No tienes permiso');
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
            return Redirect::route('campaignEncuesta.show', array($campaign->id));
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('campaignEncuesta.edit', $campaign->id)->withInput()->withErrors($campaign->errors);
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
            return Redirect::route('campaignEncuesta.edit', $campaign->id)->withInput()->withErrors('No tienes permiso');
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
}
