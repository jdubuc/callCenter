<?php

namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use App\MessageSend;
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


class messageSendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$currentuser= Auth::user(); 
        if($currentuser->isValidAdmin($currentuser)==false)
        {
            return Redirect::to('/errors')->withInput()->withErrors('you cant do that');
        }

        //if($currentuser->pOperator=='9000' )
        if($currentuser->isSuperSuperAdmin($currentuser)==true )
        {
            $users = User::paginate(10);
        }
        //if($currentuser->pOperator=='4500' )
        if($currentuser->isSuperAdmin($currentuser)==true )
        {
            $users = User::where('idAccount', '=', $currentuser->idAccount)->Paginate(15);
        }
         
        return View::make('user/list')->with('users', $users);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $messageSend = new MessageSend;
        return View::make('messageSend/form')->with('messageSend', $messageSend);
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
        $messageSend = new MessageSend;
        $validation = new validation;
        if ($validation->superSuperAdmin($currentuser,$currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        // Obtenemos la data enviada 
        $data = Input::all();
        //dd($data);
        foreach ($data['idmSendZombi'] as $idMessageSend ) {
            $messageSend = MessageSend::find($idMessageSend);
            $messageSend->delete();
        }
        //$url = Session::get('backUrl');
        return Redirect::route('messageSend.create');
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
        $currentuser = Auth::user();
        $messageSend = MessageSend::find($id);
        if ($messageSend->isValidUser($currentuser)==FALSE)
        {
            return Redirect::to('/errors')->withInput()->withErrors('No tienes permiso');
        }
        if (is_null ($messageSend))
        {
            App::abort(404);
        }
        
        $messageSend->delete();

        if (Request::ajax())
        {
            return Response::json(array (
                'success' => true,
                'msg'     => 'Usuario ' . $messageSend->firstName . $messageSend->lastName . ' eliminado',
                'id'      => $messageSend->id
            ));
        }
        else
        {
            return Redirect::route('home');
        }
    }
}
