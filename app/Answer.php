<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class answer extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'answer';

    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
    
	public $errors;

     public function isValid($data)
    {
        $rules = array(
            'data' => 'required|min:10|max:255'
            //'active'     => 'required',
            //'identification' => 'required|min:140|max:5000',
            //'twitter' => 'required|min:2|max:100'
        );
        

        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
    protected $fillable = array('data');
}
