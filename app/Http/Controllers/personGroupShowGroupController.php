<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Champaign;
use App\Person;
use App\PersonGroup;
use App\PersonPersonGroup;
use App\PersonGroupCampaign;
use App\User;
use App\validation;
use Session;
use Validator;
use View;
use App\Http\Requests;
use App\File;
use Auth;
use Excel;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
//use Maatwebsite\Excel\Facades\Excel;

if (Session::has('backUrl')) {
   Session::keep('backUrl');
};

class PersonGroupShowGroupController extends Controller
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
        //se crea el usuario operador desde la vista formCreateOperator
        $usertype=array( 9000 => 4500,4500 => 1,1 => 2);
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');

        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $url2 =  url('/personGroupShowGroup/'.$idPersonGroup);
        //dd($url2);
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = new User;
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        //dd($data);
        // Revisamos si la data es válido
        if ($user->isValid($data))
        {

            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            if($currentuser->isSuperSuperAdmin($currentuser)==true)
            {
                $user->idAccount=$data['idAccount'];
            }
            elseif($currentuser->isValidAdmin($currentuser)==true)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            elseif($currentuser->isValidAdmin($currentuser)==false)
            {
                $user->idAccount=$currentuser->idAccount;
            }
            $confirmation_code = str_random(30);
            $user->pOperator=$usertype[$currentuser->pOperator];
            $user->idPersonCreator=$currentuser->id;
            $user->idPersonModificator=$currentuser->id;
            $user->confirmation_code=$confirmation_code;
            // Guardamos el usuario
            $user->save();
            $validation = new validation;
            if($validation->ConfigurationVariable('sendEmailConfirmation')=='true')
            {
                $data ['confirmation_code'] = $confirmation_code;
                Mail::send('emails.verify', $data, function($message) {
                $message->to(Input::get('email'), Input::get('firstName'))
                    ->subject('Verifica tu dirección de correo Call Center');
                });
            }
            //se crea la tabla intermedia para asignarlo al grupo
            $personPersonGroup = new personPersonGroup; 
            $personPersonGroup->idPersonGroup=$idPersonGroup;
            $personPersonGroup->idPerson=$user->id;
            $personPersonGroup->save();
            // Y Devolvemos una redirección a la pagina de miembro del grupo

             return Redirect::to($url2);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::to($url)->withInput()->withErrors($user->errors);
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
         $personPersonGroup = personPersonGroup::where('idPersonGroup', '=', $id)->get();

         $personGroup = PersonGroup::find($id);
        
        /*if (is_null($publicPersonGroup)) App::abort(404);
        
      return View::make('publicPersonGroup/show', array('publicPersonGroup' => $publicPersonGroup));*/

      return View::make('personGroupShowGroup/show')->with('personPersonGroup',$personPersonGroup)->with('personGroup',$personGroup);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');

        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $url2 =  url('/personGroupShowGroup/'.$idPersonGroup);
        //dd($url2);
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $user = User::find($id);
        if (is_null ($user))
        {
        App::abort(404);
        }
        //return Redirect::url('/user/editOperator')->with('user', $user);
        //return View::make('user/form')->with('user', $user);
        return View::make('personGroupShowGroup/form')->with('user', $user);

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
        $currentuser= Auth::user(); 
        $url = Session::get('backUrl');

        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $url2 =  url('/personGroupShowGroup/'.$idPersonGroup);
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        // Creamos un nuevo objeto para nuestro nuevo usuario
        $user = User::find($id);
        
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($user))
        {
            App::abort(404);
        }
        
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        
        // Revisamos si la data es válido
        if ($user->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $user->fill($data);
            $user->idPersonModificator=$currentuser->id;
            // Guardamos el usuario
            $user->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            return Redirect::to($url2);
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            //return Redirect::to($url)->withInput()->withErrors($user->errors);
             return Redirect::route('personGroupShowGroup.edit', $user->id)->withInput()->withErrors($user->errors);
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
        $data = Input::all();

         $url = Session::get('backUrl');
         $currentuser= Auth::user(); 
        $validation = new validation;

        $idPG=$validation->urlData(); 
        //dd($idPersonGroup);
        $user = User::find($id);
        if($user==null)
        {
            return Redirect::to('/errors')->withInput()->withErrors('Error');
        }
        if($currentuser->isOperator($user)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        $personPersonGroup =  personPersonGroup::where('idPerson','=',$user->id)
        ->where('idPersonGroup','=',$idPG)->first();
        $personPersonGroup->delete(); 
         return Redirect::to($url);
        
    }
    public function autocomplete(){
    $term = Input::get('term');
    $currentuser= Auth::user(); 
    $results = array();
    
  /*  $queries = DB::table('Person')
        ->where('idAccount','=',$currentuser->idAccount)
        ->where('pOperator','=','2')
        ->orwhere('firstName', 'LIKE', '%'.$term.'%')
        ->orWhere('lastName', 'LIKE', '%'.$term.'%')
        ->orWhere('email', 'LIKE', '%'.$term.'%')
        ->get();*/

     $queries = DB::table('Person')
        ->where('idAccount','=',$currentuser->idAccount)
        ->where('pOperator','=','2')
        ->where('deleted_at','=',null)
        ->where(function($query) use ($term){
                 $query->orWhere('firstName', 'LIKE', '%'.$term.'%')
                 ->orWhere('lastName', 'LIKE', '%'.$term.'%')
                 ->orWhere('email', 'LIKE', '%'.$term.'%');
             })->get();
     //dd($results);
  
    foreach ($queries as $query)
    {
        $results[] = [ 'value' => $query->id, 'label' => $query->firstName.' '.$query->lastName.' '.$query->email ];
    }

    header('Content-Type: application/json');
            return response()->json($results);
    //return Response::json($results);
    }

    public function searchUser(Request $request){
        $data = Input::all();
        $name = $request->input('alt');
         $url = Session::get('backUrl');
         $currentuser= Auth::user(); 
        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $id=$data['alter'];
        $user = User::find($id);
        if($user==null)
        {
            return Redirect::to('/errors')->withInput()->withErrors('Error');
        }
        if($currentuser->isOperator($user)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }
        if($currentuser->createOperator($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tiene permisos para realizar esta operación');
        }

         //se crea la tabla intermedia para asignarlo al grupo
            $personPersonGroup = new personPersonGroup; 
            $personPersonGroup->idPersonGroup=$idPersonGroup;
            $personPersonGroup->idPerson=$user->id;
            $personPersonGroup->save();
       //return View::make('search');
            return Redirect::to($url);
    }
}
