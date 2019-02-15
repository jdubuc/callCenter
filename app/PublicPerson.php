<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\validation;

class PublicPerson extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */

    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
     public function createPP($user)
    { $validation = new validation;
        return $validation->create($user); 
    }
    protected $table = 'PublicPerson';

	public $errors;

     public function isValid($data)
    {
        $rules = array(
            'firstName' => 'required|max:40',
            'lastName' => 'required|max:40',
            'email'     => 'required|email|min:5',
            'phoneNumber' => 'required|min:11',
            'cellPhone' => 'min:11',
            'identification' => 'max:255',
            'cedula' => 'required|max:255',
            'twitter' => 'min:2|max:100'
        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
    protected $fillable = array('email', 'firstName','lastName','phoneNumber','cellPhone','cedula','twitter','identification');
}
