
<?php $title = "connection"; ?>

<?php ob_start(); ?>
<div>
    <form action="index.php" method="post" >
        <input name="pseudo" type="text" placeholder="pseudo"><br>        
        <input name="motdepass" type="password" placeholder="mot de passe"><br>
        <button type="submit">Connection</button><br>
        <?php
        if (isset($error)) {
            echo $error;
        }
        ?>
    </form>

</div>

<?php $content = ob_get_clean(); ?>
<?php require('template.php'); ?>

