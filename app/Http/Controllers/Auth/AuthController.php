<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Input;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------
    public function showLogin()
    {
        // Verificamos que el usuario no esté autenticado
        if (Auth::check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('/');
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return View::make('login');
    }
    /**
     * Valida los datos del usuario.
     */
    public function postLogin()
    {
        // Guardamos en un arreglo los datos del usuario.
        $userdata = array(
            'email' => Input::get('email'),
            'password'=> Input::get('password')
        );

        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        if(Auth::attempt($userdata))
        {

            //$user = User::(Input::get('email'));
            $user = User::where('email', '=', $userdata['email'])->firstorfail();
           //dd($user);
           // setcookie($user->username,$user->id, time() + (86400 * 30), "/"); // 86400 = 1 day
             //dd($userdata);
            //$id = Auth::user()->id;
            //$currentuser = User::find($id);
             // document.cookie = cname + "=" + cvalue + "; " + expires;
            if($user->pOperator=='1' || $user->pOperator=='9000'|| $user->pOperator=='4500'){
            // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/home');

            }
            elseif($user->pOperator=='2')
             {   // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/homeOperator');
                
                //return Redirect( url('/operator'));
            }
            else
             {   // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/');
            }

            
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
       else{
        return Redirect( url('/auth/login'))
                    ->with('mensaje_error', 'Tus datos son incorrectos')
                    ->withInput();
         }       
    }
}
