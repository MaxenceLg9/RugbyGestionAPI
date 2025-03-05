<?php

    require '../modele/Joueur.php';
    include '../db/DAOCommentaire.php';

    class Commentaire {

        private int $idCommentaire;
        private string $commentaires;
        private Joueur $joueur;

        public function  __construct(int $idCommentaire, string $commentaires, Joueur $joueur) { 
            $this -> idCommentaire = $idCommentaire;
            $this -> commentaires = $commentaires;
            $this -> joueur = $joueur;
        }

        public function getIdCommentaire(): int {
            return $this -> idCommentaire;
        }

        public function getCommentaires(): string {
            return $this -> commentaires;
        }

        public function setCommentaires(string $commentaires): void {
            $this -> commentaires = $commentaires;
        }

        public function getJoueur(): Joueur {
            return $this -> joueur;
        }

        // partie DAO
        public function saveCommentaire(): void {
            $daoCommentaire = new DAOCommentaire();
            if ($daoCommentaire -> readByJoueur($this -> getJoueur()) != null) {
                $daoCommentaire -> update($this);
            }
            $daoCommentaire -> create($this);
        }

        public function deleteCommentaire(): void {
            $daoCommentaire = new DAOCommentaire();
            $daoCommentaire -> delete($this);
        }

        public function getCommentaireByJoueur(): Commentaire {
            $daoCommentaire = new DAOCommentaire();
            return $daoCommentaire -> readByJoueur($this -> getJoueur());
        }

    }
