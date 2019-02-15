<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\validation;
use App\Campaign;
use Session;

class Question extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Question';

	public $errors;

    public function urlData(){
        //URL::previous()
       $url = Session::get('backUrl');
        $this->url=$url;
    }

      public function getOption()
      {
        return $this->option;
      }

      public function isValidUser($user,$idCampaign)
      { 
        $campaign= campaign::find($idCampaign);
        /*if($champaign->idPersonCreator==$user->id)
          {return true;}
        else
          {return false;}*/
          $validation = new validation;
          return $validation->isSameUserCampaign($user, $campaign); 
      }
        public function isSuperSuperAdmin($user)
      { $validation = new validation;
          return $validation->superSuperAdmin($user, $this); 
      }
      public function isSuperAdmin($user)
      { $validation = new validation;
          return $validation->superAdmin($user, $this); 
      }
      public function isUser($user)
      { $validation = new validation;
          return $validation->isUser($user, $this); 
      }
     public function isValid($data)
    {
        $rules = array(
            'data' => 'required|min:2|max:255',
            'order' => 'required'
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
    protected $fillable = array('data', 'order','option','idCampaign','idQuestionType','idQuestionCondition','idQuestionSi','idQuestionNo','QuestionDestinatary');
}
