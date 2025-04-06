<?php
// Set the content-type to display HTML
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - /matchs</title>
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
        .Exemple, .code-block {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .Exemple {
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
    <h1>API Documentation: `/fdm` Endpoint</h1>
</header>

<div class="content">
    <h2>Endpoint Description</h2>
    <p>L'endpoint `/matchs` permet de gérer la structure de donnée "Feuilles de Matchs"</p>
    <p><strong>Les données renvoyés sont formatées</strong></p>

    <h2>Authentication</h2>
    <p>Une authentification de type JWT Bearer Token est requise</p>

    <h2>Methods</h2>
    <h3>GET</h3>
    <p>The GET method permet de récupérer des matchs selon les critères</p>

    <h2>Paramêtres de requête</h2>
    <p>Peuvent être utilisés : </p>
    <ul class="parameter-list">
        <li><strong>#Vide</strong>Renvoie tous les matchs</li>
        <li><strong>idMatch</strong> (optional, string): Renvoie le match défini par l'idMatch</li>
        <li><strong>limit</strong> (optional, string): Renvoie les matchs avec resultats et ceux à venir limités par le paramètre</li></ul>

    <h3>cURL Command Exemple</h3>
    <div class="Exemple">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/matchs" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/matchs?idMatch=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/matchs?limit=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>

    <h3>Exemple 1: Requête `/matchs?limit=?` : Pour les matchs à venir et ceux ayant un résultat</h3>
    <p>Renvoie une liste de matchs dans resultats & avenir</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Match récupéré avec succès",
                "data": {
                    "resultats": [],
                    "avenir": [
                        {
                            "idMatch": 8,
                            "dateHeure": "15/08/2024 à 19:00",
                            "adversaire": "Fidji",
                            "lieu": "Exterieur",
                            "resultat": null,
                            "valider": 0,
                            "archive": 0
                        },
                        {
                            "idMatch": 7,
                            "dateHeure": "30/10/2024 à 20:00",
                            "adversaire": "Argentine",
                            "lieu": "Domicile",
                            "resultat": null,
                            "valider": 0,
                            "archive": 0
                        }
                    ]
                }
            }
        </pre>
    </div>

    <h3>Exemple 2: Requête /matchs (avec param. idMatch ou non)</h3>
    <p>Renvoie une liste de matchs</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Match récupéré avec succès",
                "data": [
                    {
                        "idMatch": 3,
                        "dateHeure": "02/03/2025 à 16:00",
                        "adversaire": "Ecosse",
                        "lieu": "Domicile",
                        "resultat": null,
                        "valider": 0,
                        "archive": 0
                    }
                ]
            }
        </pre>
    </div>

    <h3>POST</h3>
    <p>La méthode POST créé un match</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour créer un match</p>
    <ul class="parameter-list">
        <li><strong>dateHeure</strong> (DateTime): </li>
        <li><strong>lieu</strong> (ENUM LIEU): </li>
        <li><strong>adversaire</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de matchs</p>
    <div class="response">
        <pre class="code-block">
            {
                "dateHeure" : "2025-02-10 17:30",
                "lieu" : "DOMICILE",
                "adversaire" : "Le Mechant"
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie le match créé</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 201,
                "response": "Match créé avec succès",
                "data": [
                    {
                        "idMatch": 9,
                        "dateHeure": "10/02/2025 à 17:30",
                        "adversaire": "Le Mechant",
                        "lieu": "Domicile",
                        "resultat": null,
                        "valider": 0,
                        "archive": 0
                    }
                ]
            }
        </pre>
    </div>

    <h3>PUT</h3>
    <p>La méthode PUT modifie un match</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour modifier un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
        <li><strong>dateHeure</strong> (DateTime): </li>
        <li><strong>lieu</strong> (ENUM LIEU): </li>
        <li><strong>adversaire</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch" : 50,
                "dateHeure" : "2025-02-10 17:30",
                "lieu" : "DOMICILE",
                "adversaire" : "ALL-BLACKS"
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie le match</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 201,
                "response": "Match modifié avec succès",
                "data": [
                    {
                        "idMatch": 9,
                        "dateHeure": "10/02/2025 à 17:30",
                        "adversaire": "ALL-BLACKS",
                        "lieu": "Domicile",
                        "resultat": null,
                        "valider": 0,
                        "archive": 0
                    }
                ]
            }
        </pre>
    </div>

    <h3>PATCH</h3>
    <p>La méthode PATCH valide un match et met un résultat</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour valider un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
        <li><strong>resultat</strong> (ENUM RESULTAT): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch":2,
                "resultat":"VICTOIRE"
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie le match</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 201,
                "response": "Match validé avec succès",
                "data": [
                    {
                        "idMatch": 2,
                        "dateHeure": "10/02/2025 à 17:30",
                        "adversaire": "ALL-BLACKS",
                        "lieu": "Domicile",
                        "resultat": VICTOIRE,
                        "valider": 0,
                        "archive": 0
                    }
                ]
            }
        </pre>
    </div>

    <h3>DELETE</h3>
    <p>La méthode DELETE supprime un match</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour supprimer un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <p>Here are some Exemple responses for different requests:</p>
    <h3>Exemple 1: Request `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Body</p>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch": 1,
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Request `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie un boolean sur l'état de la suppression</p>
    <div class="response">
        <pre class="code-block">
            {
            "status": 200,
            "response": "Match supprimé avec succès",
            "result": true/false,
            }
        </pre>
    </div>


    <h2>Echec</h2>
    <p>Sont renvoyés dans certains cas, les erreurs:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>:Token Invalide</li>
        <li><strong>404 Not Found</strong>:Si le joueur avec l'idMatch n'existe pas (DELETE)</li>
        <li><strong>400 Bad Request</strong>Si les paramètres dans le corps de la requête ne sont pas définis ou au mauvais format</li>
        <li><strong>405 Method Not Allowed</strong>:Si la méthode n'est pas GET,PUT,PATCH,DELETE,POST,OPTIONS</li>
    </ul>
</div>

</body>
</html>
