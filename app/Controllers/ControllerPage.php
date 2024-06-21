<?php

namespace App\Controllers;

class ControllerPage {
    private $Controller;

    public function __construct(private string $basePath, $Controller) {
        $this->Controller = $Controller;
    }

    private function redirection(string $lien): string {
        ob_start();
        $dataController = $this->Controller;
        include $this->basePath . $lien;
        return ob_get_clean();
    }

    public function afficherMenuAdmin(): string {
        return $this->redirection('/views/admin.php');
    }

    public function afficherAdminDouble(string $select, int $id): string {
        if ($select == 'marques') {
            $_GET['marque'] = $id;
        } else {
            $_GET['page'] = $id;
        }
        $_GET['select'] = $select;
        return $this->redirection('/views/admin.php');
    }

    public function afficherAdmin(string $select): string {
        $_GET['marque'] = null;
        $_GET['select'] = $select;
        return $this->redirection('/views/admin.php');
    }

    public function afficherAjouter(): string {
        return $this->redirection('/views/formulaire.php');
    }

    public function afficherModifier(int $id): string {
        $_GET['modele'] = $id;
        return $this->redirection('/views/formulaire.php');
    }

    public function afficherMarque(int $id): string {
        $_GET["marque"] = $id;
        return $this->redirection('/views/marque.php');
    }

    public function afficherModele(int $id): string {
        $_GET['modele'] = $id;
        return $this->redirection('/views/modele.php');
    }

    public function afficherTousLesModeles(): string {
        return $this->redirection('/views/tous_les_modeles.php');
    }

    public function afficherTousLesModelesPage(int $id): string {
        $_GET['page'] = $id;
        return $this->redirection('/views/tous_les_modeles.php');
    }

    public function afficherAccueil(): string {
        return $this->redirection('/views/accueil.php');
    }

    public function afficherConnexion(): string {
        return $this->redirection('/requetes/connexion.php');
    }

    public function afficherDeconnexion(): string {
        return $this->redirection('/requetes/deconnexion.php');
    }
}
