<?php

require_once '../modele/Statut.php';
require_once '../db/DAOJoueur.php';
require_once '../modele/Poste.php';
class Joueur {

    private ?int $idJoueur;
    private int $numeroLicense;
    private string $nom;
    private string $prenom;
    private DateTime $dateNaissance;
    private int $taille;
    private int $poids;
    private Statut $statut;
    private Poste $postePrefere;
    private bool $estPremiereLigne;

    private string $commentaire;

    private string $url;

    public function  __construct(int $idJoueur, string $nom, string $prenom,
                                 DateTime $dateNaissance, int $numeroLicense,
                                 int $taille, int $poids, Statut $statut,
                                 Poste $postePrefere, bool $estPremiereLigne) {
        $this -> idJoueur = $idJoueur;
        $this -> nom = $nom;
        $this -> prenom = $prenom;
        $this -> dateNaissance = $dateNaissance;
        $this -> numeroLicense = $numeroLicense;
        $this -> taille = $taille;
        $this -> poids = $poids;
        $this -> statut = $statut;
        $this -> postePrefere = $postePrefere;
        $this -> estPremiereLigne = $estPremiereLigne;
        $this->commentaire = "";
        $this->url = "";
    }

    public function getIdJoueur(): int {
        return $this -> idJoueur;
    }

    public function getNom(): string {
        return $this -> nom;
    }

    public function setNom(string $nom): void {
        $this -> nom = $nom;
    }

    public function getPrenom(): string {
        return $this -> prenom;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire(string $commentaire): void{
        $this->commentaire = $commentaire;
    }

    public function setURL(string $url): void {
        $this -> url = "../resources/img/joueurs/".$url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        if(is_file($this->url)){
            return $this->url;
        }
        return "";
    }

    /**
     * @return string
     */
    public function getCommentaire(): string
    {
        return $this->commentaire;
    }
    public function setPrenom(string $prenom): void {
        $this -> prenom = $prenom;
    }

    public function getDateNaissance(): DateTime {
        return $this -> dateNaissance;
    }

    public function getNumeroLicense(): String {
        return $this -> numeroLicense;
    }

    public function getTaille(): int {
        return $this -> taille;
    }

    public function setTaille(int $taille): void {
        $this -> taille = $taille;
    }

    public function getPoids(): int {
        return $this -> poids;
    }

    public function setPoids(int $poids): void {
        $this -> poids = $poids;
    }

    public function getStatut(): Statut {
        return $this -> statut;
    }

    public function setStatut(Statut $statut): void {
        $this -> statut = $statut;
    }

    public function getPostePrefere(): Poste {
        return $this -> postePrefere;
    }

    public function setPostePrefere(Poste $postePrefere): void {
        $this -> postePrefere = $postePrefere;
    }

    public function isPremiereLigne(): bool {
        return $this -> estPremiereLigne;
    }

    public function setEstPremiereLigne(bool $estPremiereLigne): void {
        $this -> estPremiereLigne = $estPremiereLigne;
    }

    // partie DAO : utiliser les méthodes de DAOJoueur pour accéder à la base de données
    public function getJoueurByNumeroLicense(): Joueur
    {
        $daoJoueur = new DAOJoueur();
        return $daoJoueur->readByNumeroLicense($this->getNumeroLicense());
    }

    public function saveJoueur(): void {
        $daoJoueur = new DAOJoueur();
        if ($this->getIdJoueur() === -1) {
            $daoJoueur -> create($this);
            return;
        }
        $daoJoueur->update($this);
    }

    public function deleteJoueur(): void {
        $daoJoueur = new DAOJoueur();
        $daoJoueur -> delete($this);
    }

    public static function getById(int $idJoueur): ?Joueur{
        return DAOJoueur::readById($idJoueur);
    }

    public static function findAll():array{
        return DAOJoueur::read();
    }

    public function setNumeroLicense(mixed $numeroLicense):void
    {
        $this->numeroLicense = $numeroLicense;
    }

    public function setDateNaissance(DateTime $date):void
    {
        $this->dateNaissance = $date;
    }

    public static function findAllActif():array{
        return DAOJoueur::readActif();
    }

    public static function findAllNotOnMatch(int $idMatch):array{
        return DAOJoueur::readNonParticiperMatch($idMatch);
    }

}
