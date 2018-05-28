<?php $title = "liste des messages"; ?>

<?php ob_start(); ?>
<div class="titre" > <h2> <?= $nomSujet[0]['nom_sujet']; ?> </h2> </div>
<div class="posts" >
    <?php foreach ($listeMessages as $message) { ?>
        <div class="post" > <p> <?= $message['contenue']; ?> </p> </div>
    <?php } ?>
</div> 

<?php if (isset($_SESSION['pseudo'])) { ?>
    <div class="creatSujet"  >
        <form method="post" action="index.php?idSuj=<?= $idSujet; ?>">
            <textarea name="message" rows="10" cols="40" id="message" required placeholder="votre message" ></textarea> <br>
            <input type="submit" value="repondre" />
        </form>
    </div>

<?php } ?>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>