
<?php $title = "inscription"; ?>

<?php ob_start(); ?>
<div>
    <form action="index.php" method="post" >
        <input name="nom" type="text" placeholder="nom"><br>
        <input name="prenom" type="text" placeholder="Prenom"><br>
        <input name="pseudo" type="text" placeholder="pseudo"><br>
        <input name="email" type="email" placeholder="email"><br>
        <input name="motdepass1" type="password" placeholder="motdepass"><br>
        <input name="motdepass2" type="password" placeholder="retape motdepass"><br>
        <button type="submit">insciption</button> <br>
        <?php
        if (isset($error)) {
            if (is_array($error)) {
                echo $error[2];
            } else {
                echo $error;
            }
        }
        ?>

    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>