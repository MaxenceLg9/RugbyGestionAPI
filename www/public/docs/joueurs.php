<?php
// Set the content-type to display HTML
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - /joueurs</title>
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
    <h1>API Documentation: `/joueurs` Endpoint</h1>
</header>

<div class="content">
    <h2>Desc de l'endpoint</h2>
    <p>L'endpoint `/joueurs` permet de gérer la structure de donnée "Joueur"</p>
    <p><strong>Les données renvoyés sont formatées</strong></p>

    <h2>Authentication</h2>
    <p>Une authentification de type JWT Bearer Token est requise</p>

    <h2>Methods</h2>
    <h3>GET</h3>
    <p>The GET method permet de récupérer des joueurs selon les critères</p>

    <h2>Paramêtres de Requête</h2>
    <p>Sont les paramètres suivants</p>
    <ul class="parameter-list">
        <li><strong>#Vide</strong> Renvoie tous les joueurs</li>
        <li><strong>idMatch</strong> (optional, string): The ID of a specific player pour renvoyer le joueur en question</li>
        <li><strong>limit</strong> (optional, string): Renvoie le joueur avec le numéro de licence</li>
        <li><strong>idMatch</strong> (optional, string): Renvoie les joueurs disponibles et les joueurs sur la feuille de matchs dans 2 tableaux séparés</li>
        <li><strong>statut</strong> (optional, string): Renvoie les joueurs ayant le statut : Pour plus d'infos : see /enums</li>
    </ul>

    <h3>cURL Command Exemple</h3>
    <div class="Exemple">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/joueurs" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/joueurs?idMatch=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/joueurs?numeroLicence=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>Pour un statut</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/joueurs?statut=ACTIF" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>Pour un idJoueur</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/joueurs?idJoueur=2" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>

    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode (Team Statistics)</h3>
    <p>Renvoie une liste de joueurs</p>
    <div class="response">
        <pre class="code-block">
            {
            "status": 200,
            "response": "Liste des joueurs récupérés avec succès",
            "data": [
                {
                    "idJoueur": 1,
                    "numeroLicence": 2001,
                    "nom": "Atonio",
                    "prenom": "Uini",
                    "dateNaissance": "26/03/1990",
                    "taille": 196,
                    "poids": 145,
                    "statut": "Actif",
                    "postePrefere": "Pilier",
                    "estPremiereLigne": "Oui",
                    "commentaire": "Puissance brute et PILIER expérimenté.",
                    "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Atonio_Uini_1990-03-26.png"
                }]
            }
        </pre>
    </div>

    <h3>POST</h3>
    <p>La méthode POST créé un joueur</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour créer un joueur</p>
    <ul class="parameter-list">
        <li><strong>numeroLicence</strong> (string): </li>
        <li><strong>nom</strong> (string): </li>
        <li><strong>prenom</strong> (string): </li>
        <li><strong>dateNaissance</strong> (DateTime)</li>
        <li><strong>taille</strong> (int):</li>
        <li><strong>poids</strong> (int): </li>
        <li><strong>statut</strong> (enum):  </li>
        <li><strong>postePrefere</strong> (optional, string):</li>
        <li><strong>estPremiereLigne</strong> (int/bool): </li>
        <li><strong>commentaire</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de joueurs</p>
    <div class="response">
        <pre class="code-block">
            {
                "nom":"aa",
                "prenom":"aa",
                "dateNaissance":"0001-11-11",
                "numeroLicence":1111,
                "taille":11,
                "poids":11,
                "commentaire":"aa",
                "statut":"ACTIF",
                "postePrefere":"PILIER",
                "estPremiereLigne":1
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de joueurs</p>
    <div class="response">
        <pre class="code-block">
            {
            "status": 200,
            "response": "Joueur créé avec succès",
            "data": [
                {
                    "idJoueur": 1,
                    "numeroLicence": 2001,
                    "nom": "Atonio",
                    "prenom": "Uini",
                    "dateNaissance": "26/03/1990",
                    "taille": 196,
                    "poids": 145,
                    "statut": "Actif",
                    "postePrefere": "Pilier",
                    "estPremiereLigne": "Oui",
                    "commentaire": "Puissance brute et PILIER expérimenté.",
                    "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Atonio_Uini_1990-03-26.png"
                }]
            }
        </pre>
    </div>

    <h3>PUT</h3>
    <p>La méthode PUT modifie un joueur</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour créer un joueur</p>
    <ul class="parameter-list">
        <li><strong>idJoueur</strong> (string): </li>
        <li><strong>numeroLicence</strong> (string): </li>
        <li><strong>nom</strong> (string): </li>
        <li><strong>prenom</strong> (string): </li>
        <li><strong>dateNaissance</strong> (DateTime)</li>
        <li><strong>taille</strong> (int):</li>
        <li><strong>poids</strong> (int): </li>
        <li><strong>statut</strong> (enum):  </li>
        <li><strong>postePrefere</strong> (optional, string):</li>
        <li><strong>estPremiereLigne</strong> (int/bool): </li>
        <li><strong>commentaire</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de joueurs</p>
    <div class="response">
        <pre class="code-block">
            {
                "idJoueur": 1,
                "nom":"aa",
                "prenom":"aa",
                "dateNaissance":"0001-11-11",
                "numeroLicence":1111,
                "taille":11,
                "poids":11,
                "commentaire":"aa",
                "statut":"ACTIF",
                "postePrefere":"PILIER",
                "estPremiereLigne":1
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de joueurs</p>
    <div class="response">
        <pre class="code-block">
            {
            "status": 200,
            "response": "Joueur modifié avec succès",
            "data": [
                {
                    "idJoueur": 1,
                    "numeroLicence": 2001,
                    "nom": "Atonio",
                    "prenom": "Uini",
                    "dateNaissance": "26/03/1990",
                    "taille": 196,
                    "poids": 145,
                    "statut": "Actif",
                    "postePrefere": "Pilier",
                    "estPremiereLigne": "Oui",
                    "commentaire": "Puissance brute et PILIER expérimenté.",
                    "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Atonio_Uini_1990-03-26.png"
                }]
            }
        </pre>
    </div>

    <h3>DELETE</h3>
    <p>La méthode DELETE supprime un joueur</p>

    <h2>Paramêtres du corps de Requête</h2>
    <p>Les paramètres suivants doivent être définis pour supprimer un joueur</p>
    <ul class="parameter-list">
        <li><strong>idJoueur</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Body</p>
    <div class="response">
        <pre class="code-block">
            {
                "idJoueur": 1,
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Exemple 1: Requête `/joueurs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie un boolean sur l'état de la suppression</p>
    <div class="response">
        <pre class="code-block">
            {
            "status": 200,
            "response": "Joueur supprimé avec succès",
            "result": true/false,
            }
        </pre>
    </div>


    <h2>Echec</h2>
    <p>Sont renvoyés dans certains cas, les erreurs:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>:Token Invalide</li>
        <li><strong>404 Not Found</strong>:Si le joueur avec l'idJoueur n'existe pas (DELETE)</li>
        <li><strong>400 Bad Requête</strong>Si les paramètres dans le corps de la requête ne sont pas définis ou au mauvais format</li>
        <li><strong>405 Method Not Allowed</strong>:Si la méthode n'est pas GET,PUT,DELETE,POST,OPTIONS</li>
    </ul>
</div>

</body>
</html>
