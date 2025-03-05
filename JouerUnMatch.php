<?php

require_once "../db/DAOJouerUnMatch.php";

class JouerUnMatch {


    private $idMatch;
    private Joueur $joueur;

    private bool $titulaire;

    private int $numero;
    private float $note;

    private bool $archive;

    function __construct(int $idMatch,Joueur $joueur, bool $titulaire, string $numero, float $note, bool $archive) {
        $this->idMatch = $idMatch;
        $this->joueur = $joueur;
        $this->titulaire = $titulaire;
        $this->numero = $numero;
        $this->note = $note;
        $this->archive = $archive;
    }

    /**
     * @param bool $archive
     */
    public function setArchive(bool $archive): void
    {
        $this->archive = $archive;
    }

    /**
     * @return bool
     */
    public function isArchive(): bool{
        return $this->archive;
    }

    /**
     * @return Joueur
     */
    public function getJoueur(): Joueur
    {
        return $this->joueur;
    }

    /**
     * @param Joueur $joueur
     */
    public function setJoueur(Joueur $joueur): void
    {
        $this->joueur = $joueur;
    }

    /**
     * @param int $idMatch
     */
    public function setIdMatch(int $idMatch): void
    {
        $this->idMatch = $idMatch;
    }

    /**
     * @return int
     */
    public function getIdMatch(): int
    {
        return $this->idMatch;
    }

    /**
     * @return float
     */
    public function getNote(): float
    {
        return $this->note;
    }

    /**
     * @param float $note
     */
    public function setNote(float $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getNumero(): string
    {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setnumero(string $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @param bool $titulaire
     */
    public function setTitulaire(bool $titulaire): void
    {
        $this->titulaire = $titulaire;
    }

    /**
     * @return bool
     */
    public function isTitulaire(): bool
    {
        return $this->titulaire;
    }

    public function save(): void {
        $DAOJouer = new DAOJouerUnMatch();
        if (DAOJouerUnMatch::existJoueur($this)) {
            $DAOJouer -> update($this);
        }
        $DAOJouer -> create($this);
    }

    //Warning : unsafe method
    public function update(): void{
        DAOJouerUnMatch::update($this);
    }
    public function insert(): void{
        DAOJouerUnMatch::create($this);
    }

    public function delete(): void {
        if (DAOJouerUnMatch::existJoueur($this)) {
            DAOJouerUnMatch::delete($this);
        }
    }

    public static function getJouerByMatch(int $idMatch): array {
        $DAOJouerUnMatch = new DAOJouerUnMatch();
        return $DAOJouerUnMatch -> readAllByMatch($idMatch);
    }

    public static function getJouerByJoueur(Joueur $joueur): array{
        $DAOJouerUnMatch = new DAOJouerUnMatch();
        return $DAOJouerUnMatch->readAllByJoueur($joueur);
    }

    public static function isArchiveFDM(int $idMatch): bool {
        return DAOJouerUnMatch::isArchiveFDM($idMatch);
    }
}