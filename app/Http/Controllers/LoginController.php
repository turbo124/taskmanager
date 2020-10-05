<?php

namespace App\Http\Controllers;

use App\Models\AccountUser;
use App\Models\CompanyToken;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use JWTAuthException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function doLogin(LoginRequest $request)
    {
        $this->forced_includes = ['company_users'];

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return response()->json(['message' => 'Too many login attempts, you are being throttled'], 401);
        }

        if ($token = auth()->attempt($request->all())) {
            $token = $this->getToken($request->email, $request->password);
            $user = auth()->user();
            $user->auth_token = $token;
            $user->save();

            $default_account = $user->accounts->first()->domains->default_company;
            //$user->setAccount($default_account);

            $accounts = AccountUser::whereUserId($user->id)->with('account')->get();

            CompanyToken::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'is_web'     => true,
                    'token'      => $token,
                    'user_id'    => $user->id,
                    'account_id' => $default_account->id,
                    'domain_id'  => $user->accounts->first()->domains->id
                ]
            );

            $response = [
                'success' => true,
                'data'    => [
                    'account_id'    => $default_account->id,
                    'id'            => $user->id,
                    'auth_token'    => $user->auth_token,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'accounts'      => $accounts,
                    'currencies'    => Currency::all()->toArray(),
                    'languages'     => Language::all()->toArray(),
                    'countries'     => Country::all()->toArray(),
                    'payment_types' => PaymentMethod::all()->toArray(),
                    'gateways'      => PaymentGateway::all()->toArray(),
                    'users'         => User::where('is_active', '=', 1)->get(
                        ['first_name', 'last_name', 'phone_number', 'id']
                    )->toArray()
                ]
            ];

            return response()->json($response, 201);
        }

        return response()->json(['success' => false, 'data' => 'Record doesnt exists']);
    }

    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json(
                    [
                        'response' => 'error',
                        'message'  => 'Password or email is invalid',
                        'token'    => $token
                    ]
                );
            }
        } catch (JWTAuthException $e) {
            return response()->json(
                [
                    'response' => 'error',
                    'message'  => 'Token creation failed',
                ]
            );
        }
        return $token;
    }

    public function showLogin()
    {
        // show the form
        return View::make('login');
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

}
