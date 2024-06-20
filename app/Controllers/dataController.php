<?php

namespace App\Controllers;


class dataController {
    public function afficherUtilisateur() {
        $utilisateurs = User::getUtilisateurs();
        $userView = new userView();
        $userView->displayUsers($utilisateurs);
    }

    public function saveUtilisateur() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $existingUser = User::findEmail($email);
            if ($existingUser) {
                $error = 'Un utilisateur avec cet email existe déjà';
            } else {
                $newUser = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];
                User::saveUser($newUser);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
           
        } 
        return $error;
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = User::findEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: /administrateur');
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect';
            }
        }
        return $error;
    }

    public function supprimerUser() {
        if (isset($_GET['delete'])) {
            $emailToDelete = $_GET['delete'];
            User::deleteUser($emailToDelete);
            header('Location: /administrateur/users');
            exit();
        }
    }
    public function afficherMarques() {
        $vehicules = Catalogue::getVoitures();
        $catalogueView = new CatalogueView();
        $catalogueView->displayMarques($vehicules['marques']);
    }

    public function afficher3Modeles() {
        $vehicules = Catalogue::getVoitures();
        $catalogueView = new CatalogueView();
        $catalogueView->display3Modeles($vehicules);
    }

    public function afficherTousModeles($get) {
        $vehicules = Catalogue::getVoitures();
        $catalogueView = new CatalogueView();
        $catalogueView->displayTousModeles($vehicules, $get);
    }

    public function afficherTousModelesRecherche() {
        $vehicules = Catalogue::getVoitures();
        $catalogueView = new CatalogueView();
        $catalogueView->displayTousModelesRecherche($vehicules);
    }

    public function supprimer() {
        if (isset($_GET['delete'])) {
            $numModele = intval($_GET['delete']);
            Catalogue::deleteModele($numModele);
            $urlWithoutQuery = strtok($_SERVER["REQUEST_URI"], '?');
            echo '<script>window.location.href = "' . $urlWithoutQuery . '";</script>';
        }
    }
    public function afficherMarque() {
        $num_marque = isset($_GET['marque']) ? (int) $_GET['marque'] : 0;
        $marque = Marque::getMarqueByNum($num_marque);
        $marqueView = new MarqueView();
        $marqueView->displayMarque($marque);
    }

    public function afficherAdminMarque($get) {
        $marqueView = new MarqueView();
        $marqueView->displayAdminMarque($get);
    }
    public static function afficherModele() {
        if (isset($_GET['modele'])) {
            $num_modele = (int)$_GET['modele'];
            $modele = Modele::getModeleByNum($num_modele);

            if ($modele === null) {
                echo 'Modèle non trouvé.';
                echo '<a href="tous_les_modeles.php">Retour</a>';
                exit;
            }

            $marque = self::getMarqueByModele($num_modele);
            ModeleView::afficherModele($modele, $marque);
        } else {
            header("Location: /");
            exit();
        }
    }

    public static function ajouterModele() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
         
            $num_marque = intval($_POST['num_marque']);
            $nom_modele = $_POST['nom_modele'];
            $url_photo = $_POST['url_photo'];
            $annee_debut = intval($_POST['annee_debut']);
            $annee_fin = intval($_POST['annee_fin']);
            $nbr_produit = intval($_POST['nbr_produit']);
            $puissance_max = intval($_POST['puissance_max']);
            $puissance_min = intval($_POST['puissance_min']);
            $prix_neuf = intval($_POST['prix_neuf']);
            $prix_actuel = intval($_POST['prix_actuel']);
            $description = $_POST['description'];

         
            $erreurs = self::validerModele($annee_debut, $annee_fin, $nbr_produit, $puissance_max, $puissance_min, $prix_neuf, $prix_actuel);
            
            if (!empty($erreurs)) {
                
                var_dump($erreurs);
            } else {
        
                $vehicules = Catalogue::getVoitures();
                $nouveau_modele = array(
                    "num_modele" => self::getNextNumModele($vehicules['marques'], $num_marque),
                    "nom_modele" => $nom_modele,
                    "url_photo" => $url_photo,
                    "annee_debut" => $annee_debut,
                    "annee_fin" => $annee_fin,
                    "nbr_produit" => $nbr_produit,
                    "puissance_max" => $puissance_max,
                    "puissance_min" => $puissance_min,
                    "prix_neuf" => $prix_neuf,
                    "prix_actuel" => $prix_actuel,
                    "description" => $description
                );
                foreach ($vehicules['marques'] as &$marque) {
                    if ($marque['num_marque'] == $num_marque) {
                        $marque['modeles'][] = $nouveau_modele;
                        break;
                    }
                }
                file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT));
                echo "<h2>Le modèle a été ajouté avec succès!</h2>";
                echo '<a class="retour" href="/modeles">Retour</a>';
                exit;
            }
        } else {
            ModeleView::afficherFormulaireModele('ajouter');
        }
    }

    public static function modifierModele() {
        if (isset($_GET['modele'])) {
            $num_modele = (int)$_GET['modele'];
            $modele = Modele::getModeleByNum($num_modele);

            if ($modele === null) {
                echo 'Modèle non trouvé.';
                echo '<a href="tous_les_modeles.php">Retour</a>';
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom_modele = $_POST['nom_modele'];
                $url_photo = $_POST['url_photo'];
                $annee_debut = intval($_POST['annee_debut']);
                $annee_fin = intval($_POST['annee_fin']);
                $nbr_produit = intval($_POST['nbr_produit']);
                $puissance_max = intval($_POST['puissance_max']);
                $puissance_min = intval($_POST['puissance_min']);
                $prix_neuf = intval($_POST['prix_neuf']);
                $prix_actuel = intval($_POST['prix_actuel']);
                $description = $_POST['description'];

                $erreurs = self::validerModele($annee_debut, $annee_fin, $nbr_produit, $puissance_max, $puissance_min, $prix_neuf, $prix_actuel);

                if (!empty($erreurs)) {
                    var_dump($erreurs);
                } else {
                    $vehicules = Catalogue::getVoitures();
                    foreach ($vehicules['marques'] as &$marque) {
                        foreach ($marque['modeles'] as &$modeleItem) {
                            if ($modeleItem['num_modele'] == $num_modele) {
                                $modeleItem['nom_modele'] = $nom_modele;
                                $modeleItem['url_photo'] = $url_photo;
                                $modeleItem['annee_debut'] = $annee_debut;
                                $modeleItem['annee_fin'] = $annee_fin;
                                $modeleItem['nbr_produit'] = $nbr_produit;
                                $modeleItem['puissance_max'] = $puissance_max;
                                $modeleItem['puissance_min'] = $puissance_min;
                                $modeleItem['prix_neuf'] = $prix_neuf;
                                $modeleItem['prix_actuel'] = $prix_actuel;
                                $modeleItem['description'] = $description;
                                break;
                            }
                        }
                    }
                    file_put_contents('../requetes/data.json', json_encode($vehicules, JSON_PRETTY_PRINT));
                    echo "<h2>Le modèle a été modifié avec succès!</h2>";
                    echo '<a class="retour" href="/modeles">Retour</a>';
                    exit;
                }
            } else {
                $marque = self::getMarqueByModele($num_modele);
                ModeleView::afficherFormulaireModele('modifier', $modele, $marque);
            }
        } else {
            header("Location: /");
            exit();
        }
    }

    private static function validerModele($annee_debut, $annee_fin, $nbr_produit, $puissance_max, $puissance_min, $prix_neuf, $prix_actuel) {
        $erreurs = [];
        if ($annee_debut < 1900 || $annee_debut > 2000) $erreurs[] = "L'année de début est invalide.";
        if ($annee_fin < $annee_debut) $erreurs[] = "L'année de fin est invalide.";
        if ($nbr_produit <= 0) $erreurs[] = "Le nombre produit doit être supérieur à 0.";
        if ($puissance_max <= 0) $erreurs[] = "La puissance max doit être supérieure à 0.";
        if ($puissance_min <= 0) $erreurs[] = "La puissance min doit être supérieure à 0.";
        if ($prix_neuf <= 0) $erreurs[] = "Le prix neuf doit être supérieur à 0.";
        if ($prix_actuel <= 0) $erreurs[] = "Le prix actuel doit être supérieur à 0.";
        return $erreurs;
    }

    private static function getNextNumModele($marques, $num_marque) {
        foreach ($marques as $marque) {
            if ($marque['num_marque'] == $num_marque) {
                $lastModele = end($marque['modeles']);
                return $lastModele['num_modele'] + 1;
            }
        }
        return 1;
    }

    private static function getMarqueByModele($num_modele) {
        $vehicules = Catalogue::getVoitures();
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($modele['num_modele'] == $num_modele) {
                    return $marque;
                }
            }
        }
        return null;
    }
}

