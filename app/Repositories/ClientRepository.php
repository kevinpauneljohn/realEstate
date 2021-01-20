<?php


namespace App\Repositories;

use App\AdminAccessToken;
use App\Repositories\RepositoryInterface\AccessTokenClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ClientRepository implements AccessTokenClientInterface
{
    /**
     * this will hold the value of the submitted form
     * @var $client
     */
    public $client;
    /**
     * this variable will be used for instantiating the model action (eg. create, edit,delete,view)
     * @var $requestResponse
     */
    public $requestResponse;
    public $response, $request,
        $method, $id, $role, $url;

    /**
     * this will hold the saved access token
     * @var $access_token
     */
    private $access_token;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->access_token = AdminAccessToken::where('key','privilege');
        $this->checkIfAccessTokenExists()->checkIfAccessTokenExpired();
        $this->url = config('dreamhomeguide.api_base_url').'/api';
    }


    /**
     * check if there is already available access token in admin_access_token table
     * @author john kevin paunel
     * @return $this
     */
    private function checkIfAccessTokenExists(): self
    {
        $count = $this->access_token->count();
        if($count < 1)
        {
            $this->saveAccessToken();
        }
        return $this;
    }

    /**
     * if the token was expired it will automatically request for another
     * token to the provider and will save it
     * @return $this
     */
    private function checkIfAccessTokenExpired(): self
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
     * Client Credentials Grant Tokens for super admin
     * @return array|mixed
     */
    public function requestToken()
    {
        $this->response = Http::asForm()->post(config('dreamhomeguide.api_base_url').'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('dreamhomeguide.client_id'),
            'client_secret' => config('dreamhomeguide.client_secret'),
            'scope' => 'create-user view-user delete-user edit-user create-project view-project edit-project delete-project create-builder view-builder edit-builder delete-builder create-payment view-payment edit-payment delete-payment',
        ]);
        return $this->response->json();
    }


    /**
     *save access token through cookie
     */
    private function saveAccessToken(): void
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


    /**
     * will return the access_token only
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token->first()->access_token;
    }

    /**
     * @return mixed
     */
    public function getAccessTokenExpiry()
    {
        // TODO: Implement getAccessTokenExpiry() method.
        return $this->access_token->first()->expires_in;
    }

    /**
     * set the http header to make an action on the DHG server
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function setHttpHeader():\Illuminate\Http\Client\PendingRequest
    {

        return Http::withHeaders(array(
            'content-type' => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.$this->getAccessToken()
        ));
    }


    /**
     * this will check if the request is unauthenticated and will return true if it is
     * @return bool
     */
    protected function tokenUnauthenticated(): bool
    {
        //it means the token was revoked or purged from the server
        //that's why we will update the save access token from our database
        if(array_key_exists('message', $this->requestResponse) && $this->requestResponse['message'] === 'Unauthenticated.') {
            $this->saveAccessToken();
            return true;
        }
        //the token from our database is active
        return false;
    }

}
