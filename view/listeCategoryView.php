
<?php $title = "liste des forum"; ?>

<?php ob_start(); ?>

<div class="titre" ><h2>Liste des forums</h2></div>
<?php
$temp = NULL;
foreach ($listCategorieSoucategrie as $list) {
    if (isset($temp) && $temp != $list['nom_catégorie']) {
        $temp = NULL;
    }
    if (!isset($temp)) {
        ?>               
        <div class="titre" >
            <h3> <?=
                $list['nom_catégorie'];
                $temp = $list['nom_catégorie'];
                ?>
            </h3>
        </div>
    <?php }
    ?>
    <div class="elemen" > 
        <div class="soustitre">   <a href="index.php?idSousCategory=<?= $list['id_sous_catégorie'] ; ?> "> 
                <?= $list['nom_sous_catégorie'] . "<br>" . $list['description_sous_catégorie']; ?>  </a>
        </div>
        <div class="dernierPost"> <a href="#" > dernier poste : </a> </div>
    </div>
<?php }
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>


