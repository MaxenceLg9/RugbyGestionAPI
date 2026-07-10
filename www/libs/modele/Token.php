<?php

namespace Token {

    require $_SERVER["DOCUMENT_ROOT"]."/../vendor/autoload.php";
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\ClientException;

    function apiVerifyToken(): void
    {
        $token = get_bearer_token() ?? "";
        $client = new Client([
            'base_uri' => 'https://rugbygestionauth.alwaysdata.net/',
            'timeout' => 2.0,
            'verify' => false
        ]);

        try {
            $response = $client->post('/', [
                'body' => json_encode([
                    'token' => $token
                ]),
                'headers' => [
                    'Authorization' => $_COOKIE["token"] ?? "",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'API_TOKEN' => "pass"
                ]
            ]);
            if (json_decode($response->getBody(), true)["valid"]) {
                return;
            }else{
                http_response_code(401);
                $message = array("status" => 401, "response" => "Unauthorized", "data" => [], "result" => []);
                echo json_encode($message);
                die();
            }
        }catch (ClientException $e){
            http_response_code(401);
            $message = array("status" => 401, "response" => "Unauthorized", "data" => [], "result" => []);
            echo json_encode($message);
            die();
        }
    }

    function apiReloadToken(): string {
        $token = get_bearer_token() ?? "";
        $client = new Client([
            'base_uri' => 'https://rugbygestionauth.alwaysdata.net/',
            'timeout'  => 2.0,
            'verify' => false
        ]);

        $response = $client->put('/',[
            ['body' => json_encode(
                [
                    'token' => $token
                ]
            )],
            'headers' => [
                'Authorization' => $_COOKIE["token"] ?? "",
                'Accept' => 'application/json',
                "Content-Type: application/json",
                "API_TOKEN" => ""
            ]
        ]);
        return json_decode($response->getBody(),true)["token"];
    }

    function get_authorization_header(): ?string {
        $headers = null;
        if (isset($_COOKIE["token"])) {
            return $_COOKIE["token"];
        }
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        return $headers;
    }

    function get_bearer_token(): ?string {
        $headers = get_authorization_header();

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                if ($matches[1] == 'null') //$matches[1] est de type string et peut contenir 'null'
                    return null;
                else
                    return $matches[1];
            }
        }
        return null;
    }
}