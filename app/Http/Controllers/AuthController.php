<?php

  namespace App\Http\Controllers;

  use App\Http\Controllers\Controller;
  use Illuminate\Foundation\Auth\AuthenticatesUsers;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Session;
  use App\Models\User;

  class AuthController extends Controller
  {
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

    public function index()
    {
      return view('login');
    }

    public function actionLogin(Request $request)
    {
      $input = $request->all();

      $this->validate($request, [
        'username' => 'required',
        'password' => 'required',
      ]);

      if(auth()->attempt(array('name' => $input['username'], 'password' => $input['password'])))
      {
        if (auth()->user()->role === "admin") {
          return redirect()->route('dashboard');
        }else{
          return redirect()->route('dashboard');
        }
      }else{
        return redirect()->route('login')
          ->with('error','Username atau password salah.');
      }
    }

    public function logout(){
      Auth::logout();
      Session::flush();
      return redirect('/login');
    }
  }
