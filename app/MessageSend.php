<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageSend extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'MessageSend';

    public function isValidUser($user)
    { $validation = new validation;
        return $validation->isSameUser($user, $this); 
    }
    
	public $errors;

     public function isValid($data)
    {
        $rules = array(
            'message' => 'min:10|max:255'
            //'hang' => 'required',
            //'answer' => 'required',
           // 'tries' => 'required',
           // 'dateTimeStart' => 'required|min:10|max:128',
            //'dateTimeEnd' => 'required|min:10|max:128'
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
    protected $fillable = array('message','hang','answer','tries','dateTimeStart','dateTimeEnd');
}
