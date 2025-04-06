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
    <h2>Description de l'endpoint</h2>
    <p>L'endpoint enums permet de récupérer les différents enums utilisés par l'API pour assurer la concordance des données></p>

    <h2>Authentication</h2>
    <p>Une authentification de type JWT Bearer Token est requise</p>

    <h2>Methodes</h2>
    <h3>GET</h3>
    <p>La méthode GET permet de récupérer les différents enums utilisés par l'API pour assurer la concordance des données></p>

    <h2>Paramêtres de Requête</h2>
    <p>Le paramètre suivant est requis</p>
    <ul class="parameter-list">
        <li><strong>value</strong> (string): L'enum à récupérer, les valeurs possibles sont</li>
        <ul class="parameter-list">
            <li><code>postes</code>: Retrieves a list of rugby positions.</li>
            <li><code>statuts</code>: Retrieves a list of statuses.</li>
            <li><code>resultats</code>: Retrieves a list of results.</li>
            <li><code>lieux</code>: Retrieves a list of locations.</li>
        </ul>
    </ul>

    <h3>cURL Command Exemple</h3>
    <div class="example">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alwaysdata.net/enums?value=postes" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>

    <h3>Exemple 1: Requête `/enums?value=postes`</h3>
    <div class="response">
        <pre class="code-block">
            {
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
            }
        </pre>
    </div>

    <h3>Exemple 2: Requête `/enums?value=statuts`</h3>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Liste des statuts récupérée avec succès",
                "data": {
                    "ACTIF": "Actif",
                    "INACTIF": "Inactif"
                }
            }
        </pre>
    </div>

    <h3>Exemple 3: Requête `/enums?value=invalid_value` (Paramêtre invalide)</h3>
    <div class="response">
        <pre class="code-block">
            {
                "status": 400,
                "response": "Les paramètres sont invalides",
                "data": []
            }
        </pre>
    </div>

    <h3>Exemple 4: Requête `/enums` (Paramêtre manquant)</h3>
    <div class="response">
        <pre class="code-block">
            {
                "status": 400,
                "response": "Les paramètres sont invalides",
                "data": []
            }
        </pre>
    </div>

    <h2>Echec</h2>
    <p>Sont renvoyés dans certains cas, les erreurs:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>:Token Invalide</li>
        <li><strong>400 Bad Requête</strong>: Le paramètre value n'est pas définie</li>
        <li><strong>405 Method Not Allowed</strong>: La méthode n'est pas GET/OPTIONS</li>
    </ul>
</div>

</body>
</html>
