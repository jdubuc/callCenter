<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PublicPersonGroup extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PublicPersonGroup';

	public $errors;
    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
     public function isValid($data)
    {
        $rules = array(
            'name' => 'required|max:255',
            'idPersonCreator' => 'required|max:255'

        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
    protected $fillable = array('name'.'idPersonCreator');
}
