<?php


namespace App\Repositories;


use http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ClientRepository
{
    public $response, $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Jan. 04, 2021
     * @author john kevin paunel
     * Client Credentials Grant Tokens for super admin
     *
     * */
    public function requestToken()
    {
        $this->response = Http::asForm()->post(config('dreamhomeguide.api_base_url').'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('dreamhomeguide.client_id'),
            'client_secret' => config('dreamhomeguide.client_secret'),
            'scope' => 'create-client view-client edit-client delete-client',
        ]);

        $this->response = $this->response->json();
        return $this;
    }


    //save access token through cookie
    public function saveAdminAccessToken($message)
    {
        return \response($message)->withCookie(cookie(
            'hello',
            $this->response['access_token'],
            $this->response['expires_in']
        ));
    }

//    public function getCookieToken(){
//        $this->requestToken()->saveAccessToken();
//        $value = $this->request->cookie('name');
//        echo $value;
//    }
}
