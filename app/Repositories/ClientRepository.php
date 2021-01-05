<?php


namespace App\Repositories;


use App\AdminAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ClientRepository
{
    public $response, $request,
            $token;
    private $access_token;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->access_token = AdminAccessToken::where('key','privilege');
        $this->checkIfAccessTokenExists()->checkIfAccessTokenExpired();

    }

    /**
     * Jan. 05, 2021
     * @author john kevin paunel
     * check if there is already available access token in admin_access_token table
     *
     * */
    private function checkIfAccessTokenExists()
    {
        $count = $this->access_token->count();
        if($count < 1)
        {
            $this->saveAdminAccessToken();
        }
        return $this;
    }

    private function checkIfAccessTokenExpired()
    {
        $expiry_date = $this->access_token->first()->expires_in;
        $date_now = Carbon::now();

        if($expiry_date < $date_now)
        {
            //this will update the access token and expiry date
            $this->saveAdminAccessToken();
        }
        return $this;
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

        return $this->response->json();
    }


    //save access token through cookie
    public function saveAdminAccessToken()
    {
        $this->requestToken();//load request token to get the value of response

        AdminAccessToken::updateOrCreate(
            ['key' => 'privilege'],
            [
                'access_token'  => $this->response['access_token'],
                'expires_in'    => Carbon::now()->addSeconds($this->response['expires_in']),
            ]
        );
    }


    public function getAccessToken()
    {
        $token = $this->access_token->first();
        return $token->expires_in;
    }

}
