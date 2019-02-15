<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Account';

	public $errors;

    protected $perPage = 10;

    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
     public function isValid($data)
    {
        $rules = array(
            'contactName' => 'required|min:1',
            'name' => 'required|max:255',
            'email' => 'required|min:7|max:255',
            'rif' => 'min:8|max:255',
            'address' => 'max:255',
            'idStatus' => 'min:1|max:255',
            'idArrangement' => 'min:1|max:255'
        );
        $validator = Validator::make($data, $rules);
        if ($validator->passes())
        {
            return true;
        }
        $this->errors = $validator->errors();
        return false;
    }
    protected $fillable = array('name', 'contactName', 'email', 'rif','address');
}
