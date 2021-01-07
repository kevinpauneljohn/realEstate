<?php


namespace App\Repositories;

use App\AdminAccessToken;
use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ClientRepository implements AccessTokenClientInterface
{
    public $response, $request,
            $token, $runMethod,$method,
            $requestResponse; //this variable will be used for instantiating the model action (eg. create, edit,delete,view)
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
            $this->saveAccessToken();
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
            $this->saveAccessToken();
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
    private function saveAccessToken()
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


    //will return the access_token only
    public function getAccessToken()
    {
        return $this->access_token->first()->access_token;
    }

    public function getAccessTokenExpiry()
    {
        // TODO: Implement getAccessTokenExpiry() method.
        return $this->access_token->first()->expires_in;
    }

    //set the http header to make an action on the DHG server
    public function setHttpHeader()
    {

        return Http::withHeaders([
            'content-type' => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.$this->getAccessToken()
        ]);
    }

    //this will check if the request is unauthenticated and will return true if it is
    protected function tokenUnauthenticated()
    {
        if(array_key_exists('message',$this->requestResponse))
        {
            //it means the token was revoked or purged from the server
            //that's why we will update the save access token from our database
            if($this->requestResponse['message'] === 'Unauthenticated.')
            {
                $this->saveAccessToken();
                return true;
            }
        }
        //the token from our database is active
        return false;
    }

}
