<?php

namespace App\Controllers;

class Modele {
    public $num_modele;
    public $nom_modele;
    public $url_photo;
    public $annee_debut;
    public $annee_fin;
    public $nbr_produit;
    public $puissance_max;
    public $puissance_min;
    public $prix_neuf;
    public $prix_actuel;
    public $description;

    public function __construct($num_modele, $nom_modele, $url_photo, $annee_debut, $annee_fin, $nbr_produit, $puissance_max, $puissance_min, $prix_neuf, $prix_actuel, $description) {
        $this->num_modele = $num_modele;
        $this->nom_modele = $nom_modele;
        $this->url_photo = $url_photo;
        $this->annee_debut = $annee_debut;
        $this->annee_fin = $annee_fin;
        $this->nbr_produit = $nbr_produit;
        $this->puissance_max = $puissance_max;
        $this->puissance_min = $puissance_min;
        $this->prix_neuf = $prix_neuf;
        $this->prix_actuel = $prix_actuel;
        $this->description = $description;
    }

    public static function getModeleByNum($num_modele) {
        $vehicules = Catalogue::getVoitures();
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($modele['num_modele'] == $num_modele) {
                    return new self(
                        $modele['num_modele'],
                        $modele['nom_modele'],
                        $modele['url_photo'],
                        $modele['annee_debut'],
                        $modele['annee_fin'],
                        $modele['nbr_produit'],
                        $modele['puissance_max'],
                        $modele['puissance_min'],
                        $modele['prix_neuf'],
                        $modele['prix_actuel'],
                        $modele['description']
                    );
                }
            }
        }
        return null;
    }
}
