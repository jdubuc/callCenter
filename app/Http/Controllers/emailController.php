<?php
namespace App\Http\Controllers;
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\MessageSend;
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\PublicPersonGroupCampaign;
use Session;
use Mail;
use DB;
use PDO;
use Illuminate\Http\Request;
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

class emailController extends Controller
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
        //
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
    public function emailCampaignSend()
    {
       $campaign=campaign::where('type','=','Email')->where('active', '=', true)->get();

       foreach ($campaign as $cam) {
        //DB::connection()->setFetchMode(PDO::FETCH_NUM);
        $MSendPP=DB::table('MessageSend')->where('idCampaign', '=', $cam->id)->where('emailSend', '=', true)->select('idPublicPerson')->get();
         $mesaggeSendpp=array();
         foreach ($MSendPP as $mpp) 
         {
            $mesaggeSendpp[]=$mpp->idPublicPerson;
         }
       // DB::connection()->setFetchMode(PDO::FETCH_OBJ;);
              //dd($MSendPP->all());
           $ppg = DB::table('PublicPerson')
            ->join('PublicPersonPublicPersonGroup', 'PublicPerson.id', '=', 'PublicPersonPublicPersonGroup.idPublicPerson')
            ->join('PublicPersonGroupCampaign', 'PublicPersonPublicPersonGroup.idPublicPersonGroup', '=', 'PublicPersonGroupCampaign.idPublicPersonGroup')
            
            ->where('PublicPersonGroupCampaign.idCampaign', '=', $cam->id)
            ->whereNotIn('PublicPerson.id',$mesaggeSendpp)
            ->select('PublicPerson.id','PublicPerson.firstName','PublicPerson.lastName','PublicPerson.email','PublicPerson.phoneNumber','PublicPerson.cellPhone')
            ->get();
        $fails=array();
        $EmSend=array();
        $results=array();
        $messageCampaign=array();
        //dd($ppg);

        foreach ($ppg as $publicPerson ) {
            $MessageSend= MessageSend::where('idPublicPerson','=',$publicPerson->id)->where('idCampaign','=',$cam->id)->first();
        if($MessageSend==null)
        {
            $mSend=new MessageSend;
            $mSend->message = $cam->emailSubject;
            $mSend->hang =false;
            $mSend->answer = false;
            $mSend->tries = 1;
            $mSend->dateTimeStart =date("Y-m-d H:i:s");
            $mSend->dateTimeEnd =date("Y-m-d H:i:s");
            $mSend->idPublicPerson = $publicPerson->id;
            $mSend->idCampaign = $cam->id;
            $mSend->idPerson = $cam->idPersonCreator;
            $mSend->dateTimeModification =date("Y-m-d H:i:s");
            $mSend->duration = 0;
            $mSend->durationDialing = 0;
        }
        else
        {
            $MessageSend->tries = $MessageSend->tries+1;//si existe y tries se le puede seguir aumentando
            $MessageSend->dateTimeStart =date("Y-m-d H:i:s");
            $MessageSend->dateTimeEnd =date("Y-m-d H:i:s");
            $MessageSend->dateTimeModification =date("Y-m-d H:i:s");
        }   
           

            $data = array(
                    'firstName' => $publicPerson->firstName,
                    'lastName' => $publicPerson->lastName,
                    'email' => $publicPerson->email,
                    'messageCampaign' => $cam->campaignMessage,
                    'emailSubject' => $cam->emailSubject,
                    );
            $email=$publicPerson->email;

            Mail::send('emails.campaign', $data, function($message)use($data) {
            $message->to($data['email'], $data['firstName'])
                ->subject($data['emailSubject']);
            });

            if( count(Mail::failures()) > 0 ) 
            {
              foreach(Mail::failures as $email_address) 
                {
                    if($MessageSend==null)
                    {           
                        $mSend->emailSend = false;
                        $mSend->save();
                        $fails[]=$mSend->id;
                    }
                    else
                    {
                        $MessageSend->emailSend = false;
                        $MessageSend->save();
                        $fails[]=$mSend->id;
                    }
                    //echo "Mail failures!";
                }

            } else 
            {
                if($MessageSend==null)
                    {           
                        $mSend->emailSend = true;
                        $mSend->save();
                        $EmSend[]=$mSend->id;
                    }
                    else
                    {
                        $MessageSend->emailSend = true;
                        $MessageSend->save();
                        $EmSend[]=$mSend->id;
                    }
                //echo "Mail sent successfully!";
            }


        }
        //dd($results);
       $results[] = [ 'fails' => $fails, 'Msend' => $EmSend ];
       }
       
        header('Content-Type: application/json');
        return response()->json($results);
    }
}
