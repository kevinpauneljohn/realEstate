<?php


namespace App\Repositories\RepositoryInterface;


interface AccessTokenClientInterface
{
    public function requestToken();

    public function getAccessToken();

    public function getAccessTokenExpiry();

    public function setHttpHeader();
}
