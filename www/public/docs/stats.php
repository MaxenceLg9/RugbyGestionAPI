<?php
// Set the content-type to display HTML
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - /stats</title>
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
    <h1>API Documentation: `/stats` Endpoint</h1>
</header>

<div class="content">
    <h2>Desc de l'endpoint</h2>
    <p>L'endpoint '/stats' met à disposition les stats de l'équipe mais aussi individuelles, comme le % de victoires, le nb de victoires/défaites/nuls, les notes moyennes...</p>

    <h2>Authentication</h2>
    <p>Une authentification de type JWT Bearer Token est requise</p>

    <h2>Methods</h2>
    <h3>GET</h3>
    <p>La Méthode GET récupère les statistiques d'équipe ou pour un joueur</p>

    <h2>Paramêtres: Requête</h2>
    <p>Les paramètres possibles sont</p>
    <ul class="parameter-list">
        <li><strong>idJoueur</strong> (optional, string): le joueur sur lequel obtenir des stats</li>
    </ul>

    <h3>cURL Command Exemple</h3>
    <div class="Exemple">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alwaysdata.net/stats" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>Les statistiques pour un joueur</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alwaysdata.net/stats?idJoueur=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>

    <h3>Exemple 1: Requête `/stats` (Statistiques d'équipe)</h3>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Statistiques pour l'équipe",
                "data": {
                    "joueurs": {
                        "1": {
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
                            "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Atonio_Uini_1990-03-26.png",
                            "avg_note": 0,
                            "victories": "0",
                            "titulaires": "0",
                            "remplaçants": "0",
                            "victory_ratio": "0.0000%",
                            "totalMatches": 0,
                            "max_consecutive_matches": 0
                        },
                        "2": {
                            "numeroLicence": 2002,
                            "nom": "Colombe",
                            "prenom": "Georges-Henri",
                            "dateNaissance": "17/04/1998",
                            "taille": 190,
                            "poids": 125,
                            "statut": "Actif",
                            "postePrefere": "Pilier",
                            "estPremiereLigne": "Oui",
                            "commentaire": "Un jeune joueur en plein essor.",
                            "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Colombe_Georges-Henri_1998-04-17.png",
                            "avg_note": -1,
                            "victories": "0",
                            "titulaires": "1",
                            "remplaçants": "0",
                            "victory_ratio": "0.0000%",
                            "totalMatches": 0,
                            "max_consecutive_matches": 1
                        },
                        "3": {
                            "numeroLicence": 2003,
                            "nom": "Gros",
                            "prenom": "Jean-Baptiste",
                            "dateNaissance": "25/05/1999",
                            "taille": 185,
                            "poids": 115,
                            "statut": "Actif",
                            "postePrefere": "Pilier",
                            "estPremiereLigne": "Oui",
                            "commentaire": "Technique et solide en mêlée.",
                            "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Gros_Jean-Baptiste_1999-05-25.png",
                            "avg_note": 0,
                            "victories": "0",
                            "titulaires": "0",
                            "remplaçants": "0",
                            "victory_ratio": "0.0000%",
                            "totalMatches": 0,
                            "max_consecutive_matches": 0
                        },
                        ...
                    },
                    "matchs": {
                        "totalMatches": 8,
                        "matchesWon": 0,
                        "matchesLoss": 0,
                        "matchesDrawed": 0,
                        "winLossRatio": "0.000%"
                    },
                    "stats": {
                        "actifs_joueurs": 40,
                        "differents_joueurs": 2,
                        "joueurs": []
                    }
                }
            }
        </pre>
    </div>

    <h3>Exemple 2: Requête `/stats?idJoueur=4` (Stats pour un joueur)</h3>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Statistiques pour le Joueur",
                "data": {
                    "idJoueur": 4,
                    "numeroLicence": 2004,
                    "nom": "Tatafu",
                    "prenom": "Tevita",
                    "dateNaissance": "15/11/1997",
                    "taille": 182,
                    "poids": 118,
                    "statut": "Actif",
                    "postePrefere": "Pilier",
                    "estPremiereLigne": "Oui",
                    "commentaire": "Polyvalent et puissant.",
                    "url": "https://rugbygestionapi.alwaysdata.net/img/joueurs/Tatafu_Tevita_1997-11-15.png",
                    "avg_note": 0,
                    "victories": "0",
                    "titulaires": "0",
                    "remplaçants": "0",
                    "victory_ratio": "0.0000%",
                    "totalMatches": 0,
                    "max_consecutive_matches": 0
                }
            }
        </pre>
    </div>

    <h2>Echec</h2>
    <p>Sont renvoyés dans certains cas, les erreurs:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>:Token Invalide</li>
        <li><strong>404 Not Found</strong>:Si le joueur idJoueur n'existe pas</li>
        <li><strong>405 Method Not Allowed</strong>:Mauvaise méthode : autre qu'OPTIONS/GET</li>
    </ul>
</div>

</body>
</html>
