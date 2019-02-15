<?php

namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use Session;
use DB;
use Illuminate\Http\Request;
use App\Campaign;
use App\Question;
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

class questionController extends Controller
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
          $question = new Question;
      return View::make('question/form')->with('question', $question);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //// Creamos un nuevo objeto para nuestro nuevo usuario
        $question = new question;
        // Obtenemos la data enviada por el usuario
        $data = Input::all();
        if(array_key_exists('allowNotAnswer', $data)==false)
        {
            $data['allowNotAnswer'] = false;
        }
        $currentuser = Auth::user();
        //$questionChampaign= champaign::find($idCampaign);
        if ($question->isValidUser($currentuser,$data["idCampaign"])==FALSE)
        {
            return Redirect::route('question.create', $question->id)->withInput()->withErrors('No tienes permiso');
        }
        /*PRUEBA*/
        //$url = $data['url'];
        $url = Session::get('backUrl');
        //dd($url);
        //$arrayCondition = array();
        // Revisamos si la data es válido0
        if($data["idQuestionType"]=='10')
        {
            if ($question->isValid($data))
            {
                $arrayQuestionDestinatary = array('0');
                $questionCondition = question::find($data["idQuestionCondition"]);
                    if($questionCondition->idQuestionType==8)
                    {
                        $arrayQuestionDestinatary=array ('0',$data["idQuestionSi"],$data["idQuestionNo"]);
                        $arrayCondition= implode(",", $arrayQuestionDestinatary);
                        $question->QuestionDestinatary=$arrayCondition;
                    }
                    elseif($questionCondition->idQuestionType==9)
                    {
                        $questionOption = explode( PHP_EOL, $questionCondition->option );
                        for( $a=0; $a < count($questionOption); $a++)
                            {
                                array_push($arrayQuestionDestinatary,$data["idQuestion". $a  ]);
                            }  
                        $arrayCondition= implode(",", $arrayQuestionDestinatary);
                        $question->QuestionDestinatary=$arrayCondition;
                    }
                $question->data=$data["data"];
                $question->idQuestionType=$data["idQuestionType"];
                $question->idQuestionCondition=$data["idQuestionCondition"];
                $question->order=$data["order"];
                $question->idCampaign=$data["idCampaign"];
                $question->idPersonModificator = $currentuser->id;
                $question->idPersonCreator = $currentuser->id;
                //$question->fill($data);
                //dd($question);
                $questionlist= question::where('idCampaign', '=', $question->idCampaign)->orderBy('order')->get();
                foreach ($questionlist as $questionlist)
                {
                    if($question->order<=$questionlist->order)
                    {
                        //$questionlist->order=($questionlist->order-1);
                        $questionUP = question::find($questionlist->id);
                        $questionUP->order=($questionlist->order+1);
                        $questionUP->save();
                    }
                }
                //$question->save();
                //dd($questionlist);
                //$question->fill($data);
                //$url = $question->url;

                //$url = Session::get('backUrl');
                //dd($url);
                return Redirect::to($url);
            }
            else
            {
                // En caso de error regresa a la acción create con los datos y los errores encontrados
                return Redirect::route('/question/createCondition')->withInput()->withErrors($question->errors);
            }
        }
        elseif ($question->isValid($data))
        {
            // Si la data es valida se la asignamos al usuario
            $question->idPersonModificator = $currentuser->id;
            $question->idPersonCreator = $currentuser->id;
            $question->fill($data);
            // Guardamos el usuario
            $question->save();
            // Y Devolvemos una redirección a la acción show para mostrar el usuario
            //return Redirect::route('/home', array($question->id));
            //$url = Session::get('backUrl');
            //$url =  $question->url;
            return Redirect::to($url);
        }
        else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
        return Redirect::route('question.create')->withInput()->withErrors($question->errors);
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
        $question = question::find($id);
        $question->urlData();
        //dd($question->url);
        if (is_null ($question))
        {
        App::abort(404);
        }
        return View::make('question/form')->with('question', $question);
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
        $question = question::find($id);
        if (is_null ($question))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        $data = Input::all();
        if ($question->isValidUser($currentuser,$data["idCampaign"])==FALSE)
        {
            return Redirect::route('question.edit', $question->id)->withInput()->withErrors('No tienes permiso');
        }
        
        //dd($data);
        $url = $data['url'];
        if ($question->isValid($data))
        {
            $question->fill($data);
            $question->idPersonModificator = $currentuser->id;
            $question->save();
            //$url = Session::get('backUrl');
            //$url =  $question->url;
        //dd($url);
        return Redirect($url);
            //return redirect()->back();
        }
        else
        {
            // En caso de error regresa a la acción edit con los datos y los errores encontrados
            return Redirect::route('question.edit', $question->id)->withInput()->withErrors($question->errors);
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
        /*$url = Session::get('backUrl');
        $claves = explode("/", $url);
        $t=count($claves);
        $idCampaign = $claves[$t-1];*/
        $validation = new validation;
          $idCampaign=$validation->urlData(); 
          //dd($idCampaign);
         // Creamos un nuevo objeto 
        $campaign = Campaign::find($idCampaign);
        // Si no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        //dd($currentuser);
        if ($campaign->isValidUserCampaign($currentuser,$campaign)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        $question = question::find($id);
        $questionlist= question::where('idCampaign', '=', $idCampaign)->orderBy('order')->get();
        $questionNumer = DB::table('Question') ->where('idCampaign', '=', $idCampaign)->count();
        
        if (is_null ($question))
        {
            $url = Session::get('backUrl');
            //$url =  $question->url;
            return Redirect($url);
        }

            foreach ($questionlist as $questionlist)
            { 
                if($questionlist->order==($question->order))
                    {
                        //$questionlist->order=($questionlist->order-1);
                        $questionDelete = question::find($questionlist->id);
                        $questionDelete->delete();
                    }
                if($questionlist->order>($question->order))
                    {
                        //$questionlist->order=($questionlist->order+1);
                        $questionDown = question::find($questionlist->id);
                        $questionDown->order=($questionlist->order-1);
                        $questionDown->save();
                        //dd($questionUP);
                    }
            }
            //dd($questionlist);
                //return Redirect::to('/');
                //return redirect()->back();
                $url = Session::get('backUrl');
                //$url =  $question->url;
                return Redirect($url);
    }

    public function up($id)
    {
        /*$url = Session::get('backUrl');
        $claves = explode("/", $url);
        $t=count($claves);
        $idCampaign = $claves[$t-1];*/
          // Creamos un nuevo objeto 
        $validation = new validation;
          $idCampaign=$validation->urlData(); 
        $campaign = Campaign::find($idCampaign);
        // Si el usuario no existe entonces lanzamos un error 404 :(
        if (is_null ($campaign))
        {
            App::abort(404);
        }
        $currentuser = Auth::user();
        if ($campaign->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        $question = question::find($id);
        $questionlist= question::where('idCampaign', '=', $idCampaign)->orderBy('order')->get();
        $questionNumer = DB::table('Question') ->where('idCampaign', '=', $idCampaign)->count();
        if (is_null ($question))
        {
            $url = Session::get('backUrl');
            return Redirect($url);
        }
        if($question->order!=1)
            {
            foreach ($questionlist as $questionlist)
            {
                if($questionlist->order==($question->order-1))
                    {
                        //$questionlist->order=($questionlist->order+1);
                        $questionDown = question::find($questionlist->id);
                        $questionDown->order=($questionlist->order+1);
                        $questionDown->save();
                        //dd($questionUP);
                    }
                if($questionlist->order==($question->order))
                    {
                        //$questionlist->order=($questionlist->order-1);
                        $questionUP = question::find($questionlist->id);
                        $questionUP->order=($questionlist->order-1);
                        $questionUP->save();
                    }
            }
            //dd($questionlist);
                //return Redirect::to('/');
                //return redirect()->back();
                $url = Session::get('backUrl');
                return Redirect($url);
            }
        else{
            $url = Session::get('backUrl');
                return Redirect($url);
            }
    }

    public function down($id)
    {
        /* $url = Session::get('backUrl');
        $claves = explode("/", $url);
        $t=count($claves);
        $idCampaign = $claves[$t-1];*/
        $validation = new validation;
          $idCampaign=$validation->urlData(); 
          // Creamos un nuevo objeto 
        $campaign = Campaign::find($idCampaign);
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
        $question = question::find($id);
        $questionlist= question::where('idCampaign', '=', $idCampaign)->orderBy('order')->get();
        $questionNumer = DB::table('Question') ->where('idCampaign', '=', $idCampaign)->count();
        if (is_null ($question))
        {
            $url = Session::get('backUrl');
            return Redirect($url);
        }

         if($question->order!=$questionNumer)
            {
            foreach ($questionlist as $questionlist)
            {
                if($questionlist->order==($question->order+1))
                    {
                        //$questionlist->order=($questionlist->order+1);
                        $questionDown = question::find($questionlist->id);
                        $questionDown->order=($questionlist->order-1);
                        $questionDown->save();
                        //dd($questionUP);
                    }
                if($questionlist->order==($question->order))
                    {
                        //$questionlist->order=($questionlist->order-1);
                        $questionUP = question::find($questionlist->id);
                        $questionUP->order=($questionlist->order+1);
                        $questionUP->save();
                    }
            }
            //dd($questionlist);
                //return Redirect::to('/');
                //return redirect()->back();
                $url = Session::get('backUrl');
                return Redirect($url);
            }
        else{
            $url = Session::get('backUrl');
                return Redirect($url);
            }
    }

    public function createCondition($id)
    {
        $question = Question::find($id);
        $questionChampaign= question::where('idCampaign','=', $question->idCampaign)->get();
       // dd( $questionChampaign);
        $url = Session::get('backUrl');
        foreach ($questionChampaign as $questionlist)
            {
                if($questionlist->idQuestionCondition==$question->id)
                {
                    $url = Session::get('backUrl');
                return Redirect($url)->withInput()->withErrors('ya esa pregunta tiene una condicion');
                    
                }
            }

        return View::make('questionCondition/form')->with('question', $question)->with('url', $url);
    }

    public function editCondition($id)
    {
        $question = question::find($id);
        if ($question->idQuestionType!=10)
        {
            return Redirect::to('/errors')->withInput()->withErrors('no tienes permiso');
        }
        if (is_null ($question))
        {
        App::abort(404);
        }
        return View::make('questionCondition/formEdit')->with('question', $question);
    }
}
