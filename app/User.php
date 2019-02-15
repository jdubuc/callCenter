<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;
    //use SoftDeletingTrait;
    protected $softDelete = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Person';

   protected $dates = ['deleted_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getAuthIdentifier()
        {
            return $this->getKey();
        }

    public function getAuthPassword()
        {
            return $this->password;
        }

    public function getRememberToken()
        {
            return $this->remember_token;
        }

    public function setRememberToken($value)
        {
            $this->remember_token = $value;
        }

    public function getRememberTokenName()
        {
            return "remember_token";
        }

    public function getReminderEmail()
        {
            return $this->email;
        }


 public function setPasswordAttribute($value)
    {
        if ( ! empty ($value))
        {
            $this->attributes['password'] = Hash::make($value);
        }
    }

     public function isValidAdmin($user)
    { $validation = new validation;
        return $validation->isAdmin($user, $this); 
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
    
    public $errors;
    public function emailIsValid()
    {
        $op=user::where('email','=',$this->email)->first();
            if($op!=null)
            {
                if($op->email==$this->email)
                {
                  //echo '-- existe--';
                    return false;
                }
                else
                {
                    //echo 'n-o existe'; //realmente no deberia entrar aca nunca
                    return true;
                }
            }
                else
                {
                    //echo '//no existe';
                    return true;
                }
    }
    public function isValid($data)
    {
        $rules = array(
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            //'email'     => 'required|email|unique:Person',
            'email'  =>  'required|email|unique:Person',
            'password'  => 'min:8|max:255|confirmed',
            //'password_confirmation' => 'required|min:8|max:255',
            'phoneNumber' => 'required|min:11|max:255',
            //'cellPhone' => 'min:11|max:12',
            'idTelegram' => 'required|max:255',
            'cedula' => 'max:255',
            'pOperator' => 'max:255',
            'twitter' => 'min:1|max:255'
            //'idAccount' => 'min:1|max:255'
        );

        // Si el usuario existe:
        if ($this->exists) {
            //Evitamos que la regla “unique” tome en cuenta el email del usuario actual
            $rules['email'] .= ',email,' . $this->id;
        } else { // Si no existe...
            // La clave es obligatoria:
            $rules['password'] .= '|required';
        }
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->passes())
        {
            return true;
        }
        
        $this->errors = $validator->errors();
        
        return false;
    }
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   /* protected $fillable = ['name', 'email', 'password'];*/
   
    protected $fillable = array('email', 'firstName', 'password','lastName','phoneNumber','idTelegram','cedula','pOperator','twitter','idPersonCreator','idPersonModificator');
}
