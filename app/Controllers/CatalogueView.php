<?php

namespace App\Controllers;

class CatalogueView {
    public function displayMarques($marques) {
        foreach ($marques as $marque) {
            echo '<a href="/marque/'.$marque['num_marque'].'">'.htmlspecialchars($marque['nom_marque']).'</a>';
        }
    }

    public function display3Modeles($vehicules) {
        $count = 1;
        foreach ($vehicules['marques'] as $marque) {
            foreach ($marque['modeles'] as $modele) {
                if ($count < 4) {
                    echo '<div class="modele">';
                    $this->displayModelePhoto($modele);
                    echo '<div class="info-modele"><p>' . htmlspecialchars($marque['nom_marque']) .' - '. htmlspecialchars($modele['nom_modele']) .' - '. htmlspecialchars($modele['annee_debut']) . '</p></div>';
                    echo '<div class="outils-modele"><p><a href="/modifier/'. htmlspecialchars($modele['num_modele']) .'">Modifier</a>'.' - '.'<a href="/detail/'. htmlspecialchars($modele['num_modele']) .'">Voir plus</a>'.' - '.'<a href="/marque/'. htmlspecialchars($marque['num_marque']) .'">Voir la marque</a></p></div>';
                    echo '</div>'; 
                    $count++;
                } else {
                    break;
                }
            }
        }
        echo '</div>'; 
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
        $this->displayPagination($totalPages, $currentPage);
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

        $resultats = [];
        foreach ($vehicules['marques'] as $marque) {
            if ($marqueRecherche === 'Toutes' || $marque['num_marque'] == $marqueRecherche) {
                $modelesFiltres = Catalogue::filterModeles($marque['modeles'], $modeleRecherche, $anneeRecherche);
                if (!empty($modelesFiltres)) {
                    $resultats[] = [
                        'num_marque' => $marque['num_marque'],
                        'nom_marque' => $marque['nom_marque'],
                        'modeles' => $modelesFiltres
                    ];
                    $totalModels += count($modelesFiltres);
                }
            }
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
        if (!empty($resultats)) {
            foreach ($resultats as $marque) {
                foreach ($marque['modeles'] as $modele) {
                    if ($modelsDisplayed >= $start && $modelsDisplayed < $end) {
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
                    $modelsDisplayed++;
                }
            }
        } else {
            echo "<p>Aucun modèle trouvé pour les critères de recherche donnés.</p>";
            $vide = true;
        }
        echo '</div>';
        if (!$vide) {
            $this->displayPagination($totalPages, $currentPage, $searchParams);
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

    private function displayPagination($totalPages, $currentPage, $searchParams = []) {
        echo '<div class="pagination">';
        echo '<div class="bouton-pagination">';
        $queryString = http_build_query($searchParams);
        if ($currentPage > 1) {
            echo '<a href="/modeles/' . ($currentPage - 1) . '?' . $queryString . '"><div class="deplacement"><</div></a>';
        } else {
            echo '<div class="deplacement" id="gris"><</div>';
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<div class="num_page" id="page_select">' . $i . '</div>'; 
            } else {
                echo '<a href="/modeles/' . $i . '?' . $queryString . '"><div class="num_page">' . $i . '</div></a>';
            }
        }
        if ($currentPage < $totalPages) {
            echo '<a href="/modeles/' . ($currentPage + 1) . '?' . $queryString . '"><div class="deplacement">></div></a>';
        } else {
            echo '<div class="deplacement" id="gris">></div>';
        }
        echo '</div>';
        echo '</div>';
    }
}

