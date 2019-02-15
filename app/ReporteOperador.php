<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;

use App\validation;

use Illuminate\Database\Eloquent\Model;

class reporteOperador extends Model
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
     public function isValid($data)
    {
        $rules = array(
            'idCampaign' => 'required|min:1',
            'dateTimeReporteStart' => 'required|max:255',
            'dateTimeReporteEnd' => 'required|max:255'
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
