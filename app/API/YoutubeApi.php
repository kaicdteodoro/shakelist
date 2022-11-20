<?php

namespace App\API;


use Google\Exception;
use Google_Client;

class YoutubeApi
{
    private Google_Client $client;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('API code samples');
        $this->client->setDeveloperKey(env("GOOGLE_API_KEY"));

// Exchange authorization code for an access token.
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->client->setAccessToken($accessToken);

// Define service object for making API requests.
        $service = new Google_Service_YouTube($this->client);

        $queryParams = [
            'id' => 'UC_x5XG1OV2P6uZZ5FSM9Ttw'
        ];

        $response = $service->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
        print_r($response);
    }

    private function apiAuthConfigClient(string $redirect_uri): string
    {
        $this->client->setAuthConfig(
            'client_secret_570922685551-s8suqp1oa06d16noii37f1chkc7g2g1e.apps.googleusercontent.com.json'
        );
        $this->client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
        ]);
        $this->client->setAccessType('offline');
        $this->client->setRedirectUri($redirect_uri);
        
        return $this->client->createAuthUrl();
    }
    
    public function authorization(string $redirect_uri): string
    {
        return $this->apiAuthConfigClient($redirect_uri);
    }
}
