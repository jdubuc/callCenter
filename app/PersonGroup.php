<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PersonGroup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'PersonGroup';

    public $errors;
    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
    
    public function isValidFile($data)
    {
        $rules = array(
            'file' => 'mimes:xls,csv,xlsx'
        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }

     public function isValid($data)
    {
        $rules = array(
            'name' => 'min:1',
            'idPerson' => 'min:1',
            'idPersonCreator' => 'min:1',
            'idAccount' => 'min:1'
            
        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
    protected $fillable = array('idPerson', 'name', 'idPersonCreator', 'idAccount');
}
