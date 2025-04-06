<?php
// Set the content-type to display HTML
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - /fdm</title>
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
    <h1>API Documentation: `/fdm` Endpoint</h1>
</header>

<div class="content">
    <h2>Desc. de l'endpoint</h2>
    <p>L'endpoint `/matchs` permet de gérer la structure de donnée "Match"</p>
    <p><strong>Les données renvoyés sont formatées</strong></p>

    <h2>Authentication</h2>
    <p>Une authentification de type JWT Bearer Token est requise</p>

    <h2>Methodes</h2>
    <h3>GET</h3>
    <p>The GET method permet de récupérer des feuilles de matchs selon les critères</p>

    <h2>Paramêtres de Requête</h2>
    <p>Sont les paramêtres suivants:</p>
    <ul class="parameter-list">
        <li><strong>#Vide</strong>Renvoie toutes les feuilles de match</li>
        <li><strong>idMatch</strong> (optional, string): Renvoie les feuilles de matchs du match</li>
        <ul class="parameter-list">
            <li><strong>numero</strong> (optional, string): Renvoie la feuille de match définie par le match et le numéro</li>
        </ul>
        <li><strong>idJoueur</strong> (optional, string): Renvoie les feuilles de matchs pour le joueur : les feuilles de matchs doivent sur un match validé</li></ul>

    <h3>cURL Command Example</h3>
    <div class="example">
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/fdm" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/fdm?idMatch=4&numero=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/fdm?idMatch=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
        <p>For player-specific statistics:</p>
        <pre class="code-block">curl -X GET "https://rugbygestionapi.alaysdata.net/fdm?idJoueur=4" -H "Authorization: Bearer YOUR_TOKEN"</pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>

    <h3>Exemple 1: Requete `/fdm` : Pour les feuilles de matchs : DEFAULT structure</h3>
    <p>Renvoie une liste de matchs avec attribut feuilles contenant les feuilles de matchs</p>
    <p>Dans l'attribut matchs : les matchs sont définis par leur id</p>
    <p>Dans l'attribut feuilles : les feuilles de matchs sont définies par leur numero</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 201,
                "response": "Feuille de Match récupérée avec succès",
                "data": {
                    "matchs": {
                        "2": {
                            "feuilles": {
                                "1": {
                                    "note": -1,
                                    "idJoueur": 2,
                                    "numeroLicence": 2002,
                                    "nom": "Colombe",
                                    "prenom": "Georges-Henri",
                                    "dateNaissance": "1998-04-17",
                                    "taille": 190,
                                    "poids": 125,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Un jeune joueur en plein essor.",
                                    "url": "Colombe_Georges-Henri_1998-04-17.png"
                                },
                                "2": {
                                    "note": -1,
                                    "idJoueur": 5,
                                    "numeroLicence": 2005,
                                    "nom": "Wardi",
                                    "prenom": "Reda",
                                    "dateNaissance": "1995-09-16",
                                    "taille": 183,
                                    "poids": 115,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Excellent soutien en mêlée fermée.",
                                    "url": "Wardi_Reda_1995-09-16.png"
                                }
                            }
                        }
                    }
                }
            }
        </pre>
    </div>

    <h3>POST</h3>
    <p>La méthode POST "remplit une feuille de matchs"</p>

    <h2>Paramêtres de corps de requête</h2>
    <p>Les paramètres suivants doivent être définis pour créer un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (DateTime): </li>
        <li><strong>feuilles</strong> (Tableau associatif avec clé=numero & valeur=idJoueur) : le numéro doit être compris entre 1 & 23</li>
    </ul>

    <h2>Body</h2>
    <h3>Example 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie une liste de matchs</p>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch" : 2,
                "feuilles" : {
                    "1" : 2,
                    "2" : 5
                }
            }
        </pre>
    </div>
    <p>Info complémentaire : cette méthode compare les feuilles de match du match dans la BD et la compare à celle donnée</p>
    <p>A partir de cette comparaison, elle détermine les associations à créer, celles à modifications et celles à supprimer en fonction de la présence dans la feuille de match de la BD & celle donnée</p>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Example 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie les fdm créées</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 201,
                "response": "Feuille de Match créé avec succès",
                "data": {
                    "matchs": {
                        "2": {
                            "feuilles": {
                                "1": {
                                    "note": -1,
                                    "idJoueur": 2,
                                    "numeroLicence": 2002,
                                    "nom": "Colombe",
                                    "prenom": "Georges-Henri",
                                    "dateNaissance": "1998-04-17",
                                    "taille": 190,
                                    "poids": 125,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Un jeune joueur en plein essor.",
                                    "url": "Colombe_Georges-Henri_1998-04-17.png"
                                },
                                "2": {
                                    "note": -1,
                                    "idJoueur": 5,
                                    "numeroLicence": 2005,
                                    "nom": "Wardi",
                                    "prenom": "Reda",
                                    "dateNaissance": "1995-09-16",
                                    "taille": 183,
                                    "poids": 115,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Excellent soutien en mêlée fermée.",
                                    "url": "Wardi_Reda_1995-09-16.png"
                                }
                            }
                        }
                    }
                }
            }
        </pre>
    </div>

    <h3>PUT</h3>
    <p>La méthode PUT archive les feuilles de matchs d'un match</p>

    <h2>Paramêtres de corps de requête</h2>
    <p>Les paramètres suivants doivent être définis pour modifier un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Example 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch" : 2
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Example 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie les fdms</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Feuille de match validée avec succès",
                "data": {
                    "matchs": {
                        "2": {
                            "feuilles": {
                                "1": {
                                    "note": -1,
                                    "idJoueur": 2,
                                    "numeroLicence": 2002,
                                    "nom": "Colombe",
                                    "prenom": "Georges-Henri",
                                    "dateNaissance": "1998-04-17",
                                    "taille": 190,
                                    "poids": 125,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Un jeune joueur en plein essor.",
                                    "url": "Colombe_Georges-Henri_1998-04-17.png"
                                },
                                "2": {
                                    "note": -1,
                                    "idJoueur": 5,
                                    "numeroLicence": 2005,
                                    "nom": "Wardi",
                                    "prenom": "Reda",
                                    "dateNaissance": "1995-09-16",
                                    "taille": 183,
                                    "poids": 115,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Excellent soutien en mêlée fermée.",
                                    "url": "Wardi_Reda_1995-09-16.png"
                                }
                            }
                        }
                    }
                }
            }
        </pre>
    </div>

    <h3>PATCH</h3>
    <p>La méthode PATCH saisie les notes pour les fdm</p>

    <h2>Paramêtres de corps de requête</h2>
    <p>Les paramètres suivants doivent être définis pour valider un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
        <li><strong>feuilles</strong> (Tableau associatif avec clé=numero & valeur=note) : les notes doivent être des nombres compris entre 0 & 20 & le numéro entre 1 & 23 </li>
    </ul>

    <h2>Body</h2>
    <h3>Example 1: Requête `/fdm` : Pour n'importe quel paramètre/méthode</h3>
    <div class="response">
        <pre class="code-block">
            {
                "idMatch" : 4,
                "feuilles" : {
                    "1" : 5,
                    "5" : 8
                }
            }
        </pre>
    </div>

    <h2>Réponse</h2>
    <p>Voici des exemples des différentes requêtes</p>
    <h3>Example 1: Requête `/fdm` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie le match</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Feuille de Match modifié avec succès",
                "data": {
                    "matchs": {
                        "2": {
                            "feuilles": {
                                "1": {
                                    "note": 5,
                                    "idJoueur": 2,
                                    "numeroLicence": 2002,
                                    "nom": "Colombe",
                                    "prenom": "Georges-Henri",
                                    "dateNaissance": "1998-04-17",
                                    "taille": 190,
                                    "poids": 125,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Un jeune joueur en plein essor.",
                                    "url": "Colombe_Georges-Henri_1998-04-17.png"
                                },
                                "2": {
                                    "note": -1,
                                    "idJoueur": 5,
                                    "numeroLicence": 2005,
                                    "nom": "Wardi",
                                    "prenom": "Reda",
                                    "dateNaissance": "1995-09-16",
                                    "taille": 183,
                                    "poids": 115,
                                    "statut": "ACTIF",
                                    "postePrefere": "PILIER",
                                    "estPremiereLigne": 1,
                                    "commentaire": "Excellent soutien en mêlée fermée.",
                                    "url": "Wardi_Reda_1995-09-16.png"
                                }
                            }
                        }
                    }
                }
            }
        </pre>
    </div>

    <h3>DELETE</h3>
    <p>La méthode DELETE supprime les fdm pour un match</p>

    <h2>Paramêtres de corps de requête</h2>
    <p>Les paramètres suivants doivent être définis pour supprimer un match</p>
    <ul class="parameter-list">
        <li><strong>idMatch</strong> (string): </li>
    </ul>

    <h2>Body</h2>
    <h3>Example 1: Requête `/matchs` : Pour n'importe quel paramètre/méthode</h3>
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
    <h3>Example 1: Requête `/fdm` : Pour n'importe quel paramètre/méthode</h3>
    <p>Renvoie un boolean sur l'état de la suppression</p>
    <div class="response">
        <pre class="code-block">
            {
                "status": 200,
                "response": "Feuille de Match supprimé avec succès",
                "result": true
            }
        </pre>
    </div>


    <h2>Echec</h2>
    <p>Sont renvoyés dans certains cas, les erreurs:</p>
    <ul>
        <li><strong>401 Unauthorized</strong>:Token Invalide</li>
        <li><strong>404 Not Found</strong>:Si la fdm n'est pas trouvée pour un match</li>
        <li><strong>422 Unprocessable Entity</strong>:La fdm est validée donc immuable ou la fdm n'est pas validée et donc on ne peut saisir de notes</li>
        <li><strong>400 Bad Requête</strong>Si les paramètres dans le corps de la requête ne sont pas définis ou au mauvais format</li>
        <li><strong>405 Method Not Allowed</strong>:Si la méthode n'est pas GET,PUT,PATCH,DELETE,POST,OPTIONS</li>
    </ul>
</div>

</body>
</html>