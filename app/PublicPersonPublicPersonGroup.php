<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PublicPersonPublicPersonGroup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PublicPersonPublicPersonGroup';

    public $errors;
    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
     public function isValid($data)
    {
        $rules = array(
            'idPublicPerson' => 'min:1',
            'idPublicPersonGroup' => 'min:1'
            
        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
    protected $fillable = array('idPublicPersonGroup', 'idPublicPerson');
}
