<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;

use App\validation;

use Illuminate\Database\Eloquent\Model;

class reporte extends Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Campaign';

	public $errors;

    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
    public function isValidUserCampaign($user)
    { $validation = new validation;
        return $validation->isSameUserCampaign($user, $this); 
    }
    public function createCampaign($user)
    { $validation = new validation;
        return $validation->createCampaign($user, $this); 
    }

    public function secToMin($seconds)
    { 
        /*$minutes = floor($sec/60);
        $secondsleft = $sec%60;
        if($minutes<10)
         { $minutes = "0" . $minutes;}
        if($secondsleft<10)
         { $secondsleft = "0" . $secondsleft;}
        $time=$minutes.':'.$secondsleft;
        return $time;*/
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        return $timeFormat;
    }
     public function isValid($data)
    {
        $rules = array(
            'idCampaign' => 'required|min:1',
            'dateTimeReporteStart' => 'sometimes|max:255',
            'dateTimeReporteEnd' => 'sometimes|max:255'
            //'tipo' => 'required'
            //'dateTimeStart' => 'required|min:10|max:255',
            //'dateTimeEnd' => 'required|min:10|max:255',
            //'type' => 'min:1|max:255',
            //'tries' => 'min:1|max:255'//,
            //'idPersonCreator' => 'required',
            //'idPersonModificator' => 'required',
            //'active'     => 'required'
        );
        $validator = Validator::make($data, $rules);
        if ($validator->passes())
        {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }
   protected $fillable = array('idCampaign', 'dateTimeReporteStart', 'dateTimeReporteEnd');
} 
