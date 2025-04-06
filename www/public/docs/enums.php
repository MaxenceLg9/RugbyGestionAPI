<?php
// Set the content-type to display HTML
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - /enums</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        h1, h2, h3 {
            color: #333;
        }
        .content {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }
        .example, .code-block {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .example {
            margin-bottom: 20px;
        }
        .parameter-list {
            margin-left: 20px;
        }
        .response {
            margin-top: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<header>
    <h1>API Documentation: `/enums` Endpoint</h1>
</header>

<div class="content">
    <h2>Endpoint Description</h2>
    <p>The `/enums` endpoint retrieves different sets of enumerations, such as "postes," "statuts," "resultats," or "lieux." Each enumeration corresponds to a specific set of key-value pairs that are defined within the API.</p>

    <h2>Authentication</h2>
    <p>This endpoint requires **Bearer Token** authentication for security purposes.</p>

    <h2>Methods</h2>
    <h3>GET</h3>
    <p>The GET method retrieves a list of key-value pairs corresponding to an enum type (e.g., "postes," "statuts," etc.).</p>

    <h2>Parameters: Request</h2>
    <p>The following query parameter is required:</p>
    <ul class="parameter-list">
        <li><strong>value</strong> (string): Specifies which enum type to retrieve. Valid values are:</li>
        <ul class="parameter-list">
            <li><code>postes</code>: Retrieves a list of rugby positions.</li>
            <li><code>statuts</code>: Retrieves a list of statuses.</li>
            <li><code>resultats</code>: Retrieves a list of results.</li>
            <li><code>lieux</code>: Retrieves a list of locations.</li>
        </ul>
    </ul>

    <h3>cURL Command Example</h3>
    <div class="example">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alwaysdata.net/enums?value=postes" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Response</h2>
    <p>Here are some example responses for different requests:</p>

    <h3>Example 1: Request `/enums?value=postes`</h3>
    <div class="response">
        <pre class="code-block">{
    "status": 200,
    "response": "Liste des postes récupérée avec succès",
    "data": {
        "PILIER": "Pilier",
        "TALONNEUR": "Talonneur",
        "DEUXIEME_LIGNE": "Deuxième ligne",
        "TROISIEME_LIGNE_AILE": "Troisième ligne aile",
        "TROISIEME_LIGNE_CENTRE": "Troisième ligne centre",
        "DEMI_MELEE": "Demi de mêlée",
        "DEMI_OUVERTURE": "Demi d'ouverture",
        "CENTRE": "Centre",
        "AILIER": "Ailier",
        "ARRIERE": "Arrière"
    }
}</pre>
    </div>

    <h3>Example 2: Request `/enums?value=statuts`</h3>
    <div class="response">
        <pre class="code-block">{
    "status": 200,
    "response": "Liste des statuts récupérée avec succès",
    "data": {
        "ACTIF": "Actif",
        "INACTIF": "Inactif"
    }
}</pre>
    </div>

    <h3>Example 3: Request `/enums?value=invalid_value` (Invalid Parameter)</h3>
    <div class="response">
        <pre class="code-block">{
    "status": 400,
    "response": "Les paramètres sont invalides",
    "data": []
}</pre>
    </div>

    <h3>Example 4: Request `/enums` (Missing Parameter)</h3>
    <div class="response">
        <pre class="code-block">{
    "status": 400,
    "response": "Les paramètres sont invalides",
    "data": []
}</pre>
    </div>

    <h2>Failures</h2>
    <p>The following error codes may be returned in certain situations:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>: If the Bearer token is missing or invalid.</li>
        <li><strong>400 Bad Request</strong>: If the `value` parameter is missing or has an invalid value.</li>
        <li><strong>405 Method Not Allowed</strong>: If the request method is not GET.</li>
    </ul>
</div>

</body>
</html>
