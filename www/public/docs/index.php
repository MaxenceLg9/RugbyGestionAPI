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
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }
    </style>
</head>
<body>

<header>
    <h1>API Documentation: Page d'accueil</h1>
</header>

<div class="content">
    <h2>Desc. de l'API</h2>
    <p>Bienvenue sur l'API De RugbyGestion. Cette api vous permet de gérer votre équipes de rugby, au travers d'entités comme les Joueurs, les Matchs & les Feuilles de matchs.

    <p>Cette API est construite en PHP et utilise une base de données MySQL pour stocker les informations. Les données sont échangées au format JSON.</p>

    <p>L'Api utilise composer & GuzzleHTTP avec les fichiers composer dans le dossier /public/</p>
    <p>Il en va de même pour l'Application exploitant l'API qui utilise Guzzle pour charger les données statiques. Les fichiers composer sont dans le dossier /public/</p>