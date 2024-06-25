<?php

namespace App\Controllers;

class CatalogueView {
    public function displayMarques($marques) {
    
        usort($marques, function($a, $b) {
            return strcmp($a['nom_marque'], $b['nom_marque']);
        });
    

        foreach ($marques as $marque) {
            echo '<a href="/marque/'.$marque['num_marque'].'">'.htmlspecialchars($marque['nom_marque']).'</a>';
        }
    }
    

    public function display4Modeles($vehicules) {
        $modeles = [];
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                $modeles[] = [
                    'nom_marque' => $marque['nom_marque'],
                    'num_marque'=> $marque['num_marque'],
                    'modele' => $modele,
                ];
            }
        }
        shuffle($modeles);
    
        $count = 0;
        foreach ($modeles as $modeleData) {
            if ($count < 4) {
                $modele = $modeleData['modele'];
                echo '<div class="modele">';
                $this->displayModelePhoto($modele);
                echo '<div class="info-modele"><p>' . htmlspecialchars($modeleData['nom_marque']) .' - '. htmlspecialchars($modele['nom_modele']) .' - '. htmlspecialchars($modele['annee_debut']) . '</p></div>';
                echo '<div class="outils-modele"><p><a href="/modifier/'. htmlspecialchars($modele['num_modele']) .'">Modifier</a>'.' - '.'<a href="/detail/'. htmlspecialchars($modele['num_modele']) .'">Voir plus</a>'.' - '.'<a href="/marque/'. htmlspecialchars($modeleData['num_marque']) .'">Voir la marque</a></p></div>';
                echo '</div>'; 
                $count++;
            } else {
                break;
            }
        }
    }
    
    
    

    public function displayTousModeles($vehicules, $get) {
        echo '<div class="admin-modeles">';
        echo '<div class="liste-modeles-admin">';
        $modelsParPage = 9;
        $totalModels = 0;
        foreach ($vehicules['marques'] as $marque) {
            $totalModels += count($marque['modeles']);
        }

        $totalPages = ceil($totalModels / $modelsParPage);
        $currentPage = isset($get['page']) ? $get['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages)); 
        $start = ($currentPage - 1) * $modelsParPage;
        $end = $start + $modelsParPage;

        $modelsDisplayed = 0;
        $count = 0;
        echo '<div class="row">';
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
                    if ($count > 0 && $count % 3 == 0) {
                        echo '</div><div class="row">'; 
                    }
                    echo '<div class="modele">';
                    $this->displayModelePhoto($modele);
                    echo '<div class="info-modele"><p>' . htmlspecialchars($marque['nom_marque']) .' - '. htmlspecialchars($modele['nom_modele']) .' - '. htmlspecialchars($modele['annee_debut']) . '</p></div>';
                    echo '<div class="outils-modele"><p><a href="/modifier/'. htmlspecialchars($modele['num_modele']) .'">Modifier</a>'.' - '.'<a href="/detail/'. htmlspecialchars($modele['num_modele']) .'">Voir plus</a>'.' - '.'<a href="?delete='. htmlspecialchars($modele['num_modele']) .'">Supprimer</a></p></div>';
                    echo '</div>';
                    $count++;
                }
                $modelsDisplayed++;
            }
        }
        echo '</div>';
        echo '</div>'; 
        $this->displayPagination($totalPages, $currentPage, true);
        echo '</div>';
    }

  

   

    public function displayTousModelesRecherche($vehicules) {
        $modelsParPage = 9;
        $totalModels = 0;
        $vide = false;

        $searchParams = [
            'marque' => isset($_POST['marque']) ? $_POST['marque'] : (isset($_GET['marque']) ? $_GET['marque'] : 'Toutes'),
            'modele' => isset($_POST['modele']) ? $_POST['modele'] : (isset($_GET['modele']) ? $_GET['modele'] : ''),
            'annee' => isset($_POST['annee']) ? $_POST['annee'] : (isset($_GET['annee']) ? $_GET['annee'] : '')
        ];

        $marqueRecherche = $searchParams['marque'];
        $modeleRecherche = $searchParams['modele'];
        $anneeRecherche = $searchParams['annee'];

        // Générer un identifiant unique pour la recherche actuelle
        $searchHash = md5(serialize($searchParams));

        // Générer un identifiant unique pour l'état des véhicules
        $vehiclesHash = md5(json_encode($vehicules));

        // Réinitialiser les résultats dans la session si les critères de recherche ou les véhicules changent
        if (!isset($_SESSION['searchHash']) || $_SESSION['searchHash'] !== $searchHash || 
            !isset($_SESSION['vehiclesHash']) || $_SESSION['vehiclesHash'] !== $vehiclesHash) {
            $_SESSION['searchHash'] = $searchHash;
            $_SESSION['vehiclesHash'] = $vehiclesHash;
            unset($_SESSION['modelOrder']);
        }

        if (!isset($_SESSION['modelOrder'])) {
            $modelOrder = [];
            foreach ($vehicules['marques'] as $marque) {
                if ($marqueRecherche === 'Toutes' || $marque['num_marque'] == $marqueRecherche) {
                    $modelesFiltres = Catalogue::filterModeles($marque['modeles'], $modeleRecherche, $anneeRecherche);
                    if (!empty($modelesFiltres)) {
                        foreach ($modelesFiltres as $modele) {
                            $modelOrder[] = [
                                'num_marque' => $marque['num_marque'],
                                'num_modele' => $modele['num_modele']
                            ];
                            $totalModels++;
                        }
                    }
                }
            }
            shuffle($modelOrder); // Mélange les résultats complets
            $_SESSION['modelOrder'] = $modelOrder;
            $_SESSION['totalModels'] = $totalModels;
        } else {
            $modelOrder = $_SESSION['modelOrder'];
            $totalModels = $_SESSION['totalModels'];
        }

        $totalPages = ceil($totalModels / $modelsParPage);
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages)); 
        $start = ($currentPage - 1) * $modelsParPage;
        $end = $start + $modelsParPage;

        echo '<div class="liste-modeles">';
        echo '<div class="row">';
        $modelsDisplayed = 0;
        $count = 0;

        if (!empty($modelOrder)) {
            foreach ($modelOrder as $index => $modelInfo) {
                if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
                    foreach ($vehicules['marques'] as $marque) {
                        if ($marque['num_marque'] == $modelInfo['num_marque']) {
                            foreach ($marque['modeles'] as $modele) {
                                if ($modele['num_modele'] == $modelInfo['num_modele']) {
                                    if ($count > 0 && $count % 3 == 0) {
                                        echo '</div><div class="row">';
                                    }
                                    echo '<div class="modele">';
                                    $this->displayModelePhoto($modele);
                                    echo '<div class="info-modele"><p>' . htmlspecialchars($marque['nom_marque']) .' - '. htmlspecialchars($modele['nom_modele']) .' - '. htmlspecialchars($modele['annee_debut']) . '</p></div>';
                                    echo '<div class="outils-modele"><p><a href="/modifier/'. htmlspecialchars($modele['num_modele']) .'">Modifier</a>'.' - '.'<a href="/detail/'. htmlspecialchars($modele['num_modele']) .'">Voir plus</a>'.' - '.'<a href="/marque/'. htmlspecialchars($marque['num_marque']) .'">Voir la marque</a></p></div>';
                                    echo '</div>';
                                    $count++;
                                }
                            }
                        }
                    }
                }
                $modelsDisplayed++;
            }
        } else {
            echo "<p>Aucun modèle trouvé pour les critères de recherche donnés.</p>";
            $vide = true;
        }

        echo '</div>';

        if (!$vide) {
            $this->displayPagination($totalPages, $currentPage, false, $searchParams);
        }

        echo '</div>';
    }



    
    private function displayModelePhoto($modele) {
        if (empty($modele['url_photo'])) {
            echo '<img src="https://thumbs.dreamstime.com/b/sch%C3%A9ma-voiture-48227977.jpg" alt="' . htmlspecialchars($modele['nom_modele']) . '">';
        } else {
            echo '<img src="' . htmlspecialchars($modele['url_photo']) . '" alt="' . htmlspecialchars($modele['nom_modele']) . '">';
        }
    }

    private function displayPagination($totalPages, $currentPage, $admin, $searchParams = []) {
        echo '<div class="pagination">';
        echo '<div class="bouton-pagination">';
        $queryString = http_build_query($searchParams);
        if ($admin == true) {
            $chemin = '/administrateur/modeles/';
        } else {
            $chemin = '/modeles/';
        }
        if ($currentPage > 1) {
            echo '<a href="'.$chemin . ($currentPage - 1) . '?' . $queryString . '"><div class="deplacement"><</div></a>';
        } else {
            echo '<div class="deplacement" id="gris"><</div>';
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<div class="num_page" id="page_select">' . $i . '</div>'; 
            } else {
                echo '<a href="'.$chemin . $i . '?' . $queryString . '"><div class="num_page">' . $i . '</div></a>';
            }
        }
        if ($currentPage < $totalPages) {
            echo '<a href="'.$chemin . ($currentPage + 1) . '?' . $queryString . '"><div class="deplacement">></div></a>';
        } else {
            echo '<div class="deplacement" id="gris">></div>';
        }
        echo '</div>';
        echo '</div>';
    }
}

