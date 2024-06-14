<div id="bouton-admin" class="bouton-admin" tabindex="0">
    <img src="../image/icone-admin.svg">
</div>
<div id="admin" class="admin">
    <h2>Administration</h2>
    <?php  
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        echo '<p>Bonjour '.$user['prenom'].' '.$user['nom'].'</p>';
        echo '<a href="/administrateur">- Page Admin -</a>';
        echo '<a href="/deconnexion">- Deconnexion -</a>';
    }else{
        echo '<a href="/connexion">- Connexion -</a>';
    }
    ?>
</div>