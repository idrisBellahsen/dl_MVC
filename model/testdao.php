<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <pre>
            <?php
            /* inclusion de notre classe DAO */
            require_once("Requete.php");
            $tes = Requete::listCategorieSoucategrie(); 
           
            //$sql  ="SELECT * FROM 'dl_afpa'" ;
            //INSERT INTO  dl_afpa ( nom_utilisateur, pn_utilisateur ) VALUES ('zara','judi')
            
           
            if ($tes) {
                while ($row = $tes->fetch() ) {
                    print_r($row);
                }
                
                echo '<br>existe<br>';
            } else {
                print_r(Requete::getErreur());
                echo '<br>pas bon<br>';
            }
            
            ?>
        </pre>
    </body>
</html>
