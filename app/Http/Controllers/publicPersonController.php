<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\User;
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

if (Session::has('backUrl')) {
   Session::keep('backUrl');}

class publicPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $publicPerson = publicPerson::paginate();
        return View::make('publicPerson/list')->with('publicPerson', $publicPerson);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $publicPerson = new publicPerson;
      return View::make('publicPerson/form')->with('publicPerson', $publicPerson);
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
        if ($user->createOperator($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Creamos un nuevo objeto 
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        $validation = new validation;
        $idPublicPersonGroup=$validation->urlData(); 
        $url2 =  url('/publicPersonGroupShowGroup/'.$idPublicPersonGroup);
        //var_dump($data);
        // Revisamos si la data es válido
        if ($publicPerson->isValid($data))
        {
            // Si la data es valida se la asignamos al modelo
            $publicPerson->fill($data);
            // Guardamos el modelo
            $publicPerson->save();
            //se crea la tabla intermedia para asignarlo al grupo
            $PublicPersonPublicPersonGroup = new PublicPersonPublicPersonGroup; 
            $PublicPersonPublicPersonGroup->idPublicPersonGroup=$idPublicPersonGroup;
            $PublicPersonPublicPersonGroup->idPublicPerson=$publicPerson->id;
            $PublicPersonPublicPersonGroup->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            //return Redirect::route('publicPerson.show', array($publicPerson->id));
            return Redirect::to($url2);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('publicPerson.create')->withInput()->withErrors($publicPerson->errors);
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
        $publicPerson = publicPerson::find($id);
        
        if (is_null($publicPerson)) App::abort(404);
        
      return View::make('publicPerson/show', array('publicPerson' => $publicPerson));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publicPerson = publicPerson::find($id);
        if (is_null ($publicPerson))
        {
        App::abort(404);
        }

        return View::make('publicPerson/form')->with('publicPerson', $publicPerson);
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
        $currentuser = Auth::user();
        $publicPerson = publicPerson::find($id);
        if ($publicPerson->createPP($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($publicPerson))
        {
            App::abort(404);
        }
        
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        
        // Revisamos si la data es válido
        if ($publicPerson->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $publicPerson->fill($data);
            // Guardamos el usuario
            $publicPerson->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::route('publicPerson.show', array($publicPerson->id));
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('publicPerson.edit', $publicPerson->id)->withInput()->withErrors($publicPerson->errors);
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
        $url = Session::get('backUrl');
        $currentuser = Auth::user();
         $publicPerson = publicPerson::find($id);
        if ($publicPerson->createPP($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        if (is_null ($publicPerson))
        {
            App::abort(404);
        }
        $ppg= PublicPersonPublicPersonGroup::where("idPublicPerson","=",$publicPerson->id);
        $ppg->delete();
        $publicPerson->delete();

      
            //return Redirect::route('home');
            return Redirect::to($url);
        
    }
}
