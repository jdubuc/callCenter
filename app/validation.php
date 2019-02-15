<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Session;

class validation //extends Model
{
    public function isSameUserCreator($user, $value){

        if($value->idPersonCreator==$user->id)
            {return true;}
        else
            {return false;}
    }
     public function isSameUser($user, $value){

        if($user->pOperator==1)
            {return $value->idPersonCreator==$user->id;}
        elseif($user->pOperator==4500)
            {return $value->idAccount==$user->idAccount;}
        elseif($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function isSameUserCampaign($user, $value){

        if($user->pOperator==1)
            {return $value->idPersonCreator==$user->id;}
        elseif($user->pOperator==4500)
            {return $value->idPersonCreator==$user->id;}
        elseif($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function isAdmin($user, $value){

        if($user->pOperator==1)
            {return false;}
        elseif($user->pOperator==4500)
            {return true;}
        elseif($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function createCampaign($user, $value){

        if($user->pOperator==1)
            {return true;}
        elseif($user->pOperator==4500)
            {return true;}
        elseif($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function create($user){

        if($user->pOperator==1)
            {return true;}
        elseif($user->pOperator==4500)
            {return true;}
        elseif($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function superSuperAdmin($user, $value){
        if($user->pOperator==9000)
            {return true;}
        return false;
    }

    public function isUser($user, $value){
        if($user->pOperator==1)
            {return true;}
        return false;
    }
    public function isOperator($user, $value){
        if($user->pOperator==2)
            {return true;}
        return false;
    }

    public function superAdmin($user, $value){

        if($user->pOperator==1)
            {return false;}
        elseif($user->pOperator==4500)
            {return true;}
        elseif($user->pOperator==9000)
            {return false;}
        return false;
    }

    public function ConfigurationVariable($value){

        $cf=DB::table('ConfigurationVariable')->where('name', '=', $value)->first();
        /*if($cf->value=='true')
        {return true;}
        else
        {return false;}*/
        return $cf->value;
    }

    public function ConfigurationVariableTipe($value,$tipe){
        $valor=$this->ConfigurationVariable($value);
        if($tipe=='integer')
            { return intval($valor->value);}
        elseif($tipe=='string')
            { return $valor->value;}
        elseif($tipe=='bool')
            { 
                if($valor->value=='true')
                {return true;}
                else
                {return false;}
            }
        elseif($tipe=='float')
            { return floatval($valor->value);}
        elseif($tipe=='array')
            { return str_split($valor->value);}
        else
            {return false;}
    }

    public function ConfigurationVariableTipeArray($value,$tipe,$array){
        $valor=$this->ConfigurationVariable($value);

        if($tipe=='array')
        { 
            if(is_int($array)==true){
                return str_split($valor->value, $array);
            }
            else
            {
                return str_split($array,$valor->value);
            }
        }
        else
            {return false;}
    }

    public function urlData(){

       $url = Session::get('backUrl');
        $claves = explode("/", $url);
        $t=count($claves);
        $id= $claves[$t-1];
        return $id;
    }
    public function urlGetCampaign(){
        $url = "http://192.168.0.106:8881/getCampaign";
        //$url = "testcallcenter.delcos.com.ve/communicationdaemon/getCampaign";
        return $url;
    }
    public function urlGetNextCall(){
        $url = "http://192.168.0.106:8881/getNextCall";
        //$url = "testcallcenter.delcos.com.ve/communicationdaemon/getNextCall";
        return $url;
    }
    public function urlSaveCall(){
        $url = "http://192.168.0.106:8881/saveCall";
        //$url = "testcallcenter.delcos.com.ve/communicationdaemon/saveCall";
        return $url;
    }
    public function urlGetCallInfo(){
        $url = "http://192.168.0.106:8881/getCallInfo";
        //$url = "testcallcenter.delcos.com.ve/communicationdaemon/getCallInfo";
        return $url;
    }

}