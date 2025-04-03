<?php

namespace Token {

    use GuzzleHttp\Client;

    function apiVerifyToken(): bool {
        $token = get_bearer_token() ?? "";
        $client = new Client([
            'base_uri' => 'https://rugbygestionauth.alwaysdata.net/',
            'timeout'  => 2.0,
            'verify' => false
        ]);

        $response = $client->post('/',[
            'query' => [
                'token' => $token,
            ],
            'headers' => [
                'Authorization' => $_COOKIE["token"] ?? "",
                'Accept' => 'application/json',
                "Content-Type: application/json",
                "API_TOKEN" => password_hash("", PASSWORD_BCRYPT)
            ]
        ]);
        return json_decode($response->getBody(),true)["valid"];
    }

    function apiReloadToken(): string {
        $token = get_bearer_token() ?? "";
        $client = new Client([
            'base_uri' => 'https://rugbygestionauth.alwaysdata.net/',
            'timeout'  => 2.0,
            'verify' => false
        ]);

        $response = $client->put('/',[
            'query' => [
                'token' => $token,
            ],
            'headers' => [
                'Authorization' => $_COOKIE["token"] ?? "",
                'Accept' => 'application/json',
                "Content-Type: application/json",
                "API_TOKEN" => password_hash("", PASSWORD_BCRYPT)
            ]
        ]);
        return json_decode($response->getBody(),true)["token"];
    }
}