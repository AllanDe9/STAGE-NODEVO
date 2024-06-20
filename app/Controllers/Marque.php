<?php

namespace App\Controllers;

use App\Controllers\Catalogue;

class Marque {
    public $num_marque;
    public $nom_marque;
    public $url_logo_marque;
    public $descri_marque;
    public $modeles;

    public function __construct($num_marque, $nom_marque, $url_logo_marque, $descri_marque, $modeles = []) {
        $this->num_marque = $num_marque;
        $this->nom_marque = $nom_marque;
        $this->url_logo_marque = $url_logo_marque;
        $this->descri_marque = $descri_marque;
        $this->modeles = $modeles;
    }

    public static function getMarqueByNum($num_marque) {
        $vehicules = Catalogue::getVoitures();
        foreach ($vehicules['marques'] as $marque) {
            if ($marque['num_marque'] == $num_marque) {
                return $marque;
            }
        }
        return null;
    }

    public static function updateMarque($num_marque, $nom_marque, $url_logo_marque, $descri_marque) {
        $vehicules = Catalogue::getVoitures();
        foreach ($vehicules['marques'] as &$marque) {
            if ($marque['num_marque'] == $num_marque) {
                $marque['nom_marque'] = $nom_marque;
                $marque['url_logo_marque'] = $url_logo_marque;
                $marque['descri_marque'] = $descri_marque;
                Catalogue::saveVoitures($vehicules);
                return true;
            }
        }
        return false;
    }

    public static function addMarque($nom_marque, $url_logo_marque, $descri_marque) {
        $vehicules = Catalogue::getVoitures();
        $next_num_marque = max(array_column($vehicules['marques'], 'num_marque')) + 1;
        $nouvelle_marque = [
            'num_marque' => $next_num_marque,
            'nom_marque' => $nom_marque,
            'url_logo_marque' => $url_logo_marque,
            'descri_marque' => $descri_marque,
            'modeles' => []
        ];
        $vehicules['marques'][] = $nouvelle_marque;
        Catalogue::saveVoitures($vehicules);
    }
    public static function ajouterOuModifierMarque() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $num_marque = isset($_POST['num_marque']) ? (int)$_POST['num_marque'] : null;
            $nom_marque = $_POST['nom_marque'];
            $url_logo_marque = $_POST['url_logo_marque'];
            $descri_marque = $_POST['descri_marque'];

            if ($num_marque) {
                Marque::updateMarque($num_marque, $nom_marque, $url_logo_marque, $descri_marque);
            } else {
                Marque::addMarque($nom_marque, $url_logo_marque, $descri_marque);
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

