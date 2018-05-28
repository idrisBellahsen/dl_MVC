<?php

require_once("ConnectionSingleton.php");

class Requete {

    private static $erreur;

    private function __construct() {
        
    }

    /**
     * cet methode premet d'executer les requete pour recupere les donne
     * @param type $query la requete en entre
     * @return type null si pas de reponce ou une reponce en forme de tableau
     */
    public static function getResults(string $query) {
        $res = NULL;
        $query = trim($query);
        if (isset($query) && $query != "") {
            $bdb = ConnectionSingleton::getInstance(); // demande de connection
            $reponse = $bdb->query($query); //execution de la requete
            if ($reponse) { // test de resulta
                $reponse->setFetchMode(PDO::FETCH_ASSOC);
                $res = $reponse->fetchall();
                $reponse = NULL;
            } else {
                self::$erreur = $bdb->errorInfo();
            }
        }

        ConnectionSingleton::close();
        return $res;
    }

    /* private static function getResults1($query) {
      $res = NULL;
      $bdb = ConnectionSingleton::getInstance();
      $reponse = $bdb->query($query);
      if (isset($reponse) && $reponse != "") {

      $reponse->setFetchMode(PDO::FETCH_ASSOC);
      $res = $reponse->fetch();


      $reponse = NULL;
      }
      ConnectionSingleton::close();
      return $res;
      } */

    /**
     * permet de recupere des resultat d'une requete en forme de tableau
     * @param type $from est la table ou en veut chercher les donnes 'OBLIGATOIR'
     * @param type $select liste de colone a recupere 'Optionnell'
     * @param type $where condition de le requete
     * @return string retourne tableau de chane de caractére de resultat
     */
    public static function getResultSelect($from, $select = "", $where = "") {
        $result = NULL;
        $from = trim($from);
        if (isset($from) && $from != "" && self::isTable($from)) {//verifier si la table existe
            if ($select) {

                $select = self::isColonne($select, $from); // verifie si la selection existe
                if ($select) {
                    $query = "select " . $select . " from {$from}"; //constitution de la 2 em requete valide
                    if ($where) {
                        $condition = self::extraction($where, "="); // separation de colonne et valeur
                        if ($condition) {
                            $col = trim($condition[0]);
                            $val = trim($condition[1]);
                            $test = self::isColonne($col, $from);
                            if ($test) {
                                $query .= " where {$col}='{$val}'";
                                $result = self::getResults($query);
                            } else {
                                return "le champ '{$val}'  n'existe pas ";
                            }
                        }
                    } else {
                        $result = self::getResults($query);
                    }
                } else {
                    return "un des champ de votre select n'existe pas ";
                }
            } else { //recuperation de tout la colone
                $query = "select * from {$from}";  //constitution de la 1 er requete valide
                $result = self::getResults($query);
            }
        } else {
            self::$erreur = "table inexistante";
        }

        return $result;
    }

    private static function extraction(string $source, $separateur) {
        $source = trim($source);
        $separateur = trim($separateur);
        $res = NULL;
        if ($separateur && $source) {
            $tab = explode($separateur, $source);

            if ($tab) {
                foreach ($tab as $value) {
                    $value = trim($value);
                    if ($value != "") {
                        $res[] .= $value;
                    }
                }
            } else {
                self::$erreur = "verifie '{$source}' ou '{$separateur}'";
            }
        } else {
            self::$erreur = " la'{$source}' ou '{$separateur}' n'existe pas";
        }


        return $res;
    }

    /**
     * recupere les liste table de la base 
     * @return string en tableau la liste de table existe dans la base
     */
    public static function listTable() {
        return self::getResults("SHOW TABLES FROM dlcommu");
    }

    /**
     * permet de savoir si une table existe dans la base
     * @param  $nomTable table a verifier de type string
     * @return vrai si la table existe dans la base
     */
    public static function isTable(string $nomTable) {
        $bool = FALSE;
        $nomTable = trim($nomTable);
        $lisTable = self::listTable();
        if ($lisTable && $nomTable) {
            foreach ($lisTable as $value) {
                foreach ($value as $tab) {
                    if ($tab == $nomTable) {
                        $bool = TRUE;
                    }
                }
            }
            self::$erreur = "la table {$nomTable} n'existe pas ";
        }

        return $bool;
    }

    /**
     * recupere la liste de colonne lié a une table
     * @param type $table la table ou en vas recupere la liste des colonne
     * @return type une tableau de chaine de caractére 
     */
    public static function listColonTable($table) {
        $res = NULL;
        if (self::isTable($table)) {

            $query = "SHOW COLUMNS FROM {$table}";
            $resul = self::getResults($query);
            foreach ($resul as $tab) {
                $res[] .= $tab['Field'];
            }
            return $res;
        } else {
            self::$erreur = "la table '{$table}' n'existe pas dans la base de donnée";
            return $res;
        }
    }

    /**
     * 
     * @param type $select chaine de caractére separé par un virgule de la liste de colonne a selectionné
     * @param type $table est la table qui  conrespon au selection ou on veut trouver
     * @return type une chaine de chane de caratére du liste a selectioné ou null
     */
    public static function isColonne($select, $table) {

        $res = NULL;
        $select = trim($select);
        $listColon = self::listColonTable($table);
        if ($select && $listColon) {
            $result = NULL;
            $selec = self::extraction($select, ",");
            foreach ($selec as $valSelec) {
                foreach ($listColon as $valListCol) {
                    if (trim($valSelec) == $valListCol) {
                        $result[] .= trim($valSelec);
                    }
                }
            }
            if ($result && count($result) == count($selec)) {
                $res = implode(",", $result);
            } else {
                self::$erreur = "la selection '{$select}' n'existe pas dans la table '{$table}'";
            }
        } else {
            self::$erreur = "la selection que vous avez entrez est vide";
        }
        return $res;
    }

    /**
     * 
     * @return type ereur si existe
     */
    public static function getErreur() {
        $erreur = NULL;
        if (isset(self::$erreur)) {
            $erreur = self::$erreur;
        }
        self::$erreur = NULL;
        return $erreur;
    }

    /**
     * execute les requete d'insersion
     * @param type $query est la requete d'insersion
     * @return boolean vrai si ca c'est bien inserer
     */
    public static function inserte($query) {
        $bool = FALSE;
        $bdd = ConnectionSingleton::getInstance();
        $bdd->beginTransaction();
        if ($bdd->exec($query)) {
            $bdd->commit();
            $bool = TRUE;
        } else {
            self::$erreur = $bdd->errorInfo();
        }
        ConnectionSingleton::close();
        return $bool;
    }

    // exemple $sql = "INSERT INTO  dl_afpa ( nom_utilisateur, pn_utilisateur ) VALUES ('zara','judi')";
    /**
     * 
     * @param type $table
     * @param type $colonne
     * @param type $values
     * @return string ok si c'est fait
     */
    public static function inser($table, $colonne, $values) {
        $table = trim($table);
        $colonne = trim($colonne);
        $bool = FALSE;
        if ($table && $colonne && $values && self::isTable($table)) {/* verifie si la table existe */
            $colonne = self::isColonne($colonne, $table); //recupere un chaine            
            if ($colonne) {//compare longeur de 2 tableau  
                $sql = "INSERT INTO {$table} ({$colonne}) VALUES ({$values}) ";
                if (self::inserte($sql)) {
                    $bool = TRUE;
                }
            } else {
                self::$erreur = "erreur de valeur dans colonne ou values ";
            }
        } else {
            self::$erreur = "la table '{$table}' n'existe pas ";
        }
        return $bool;
    }

    /**
     * 
     * @param type $tableau amettre des ''
     * @return type chaine de caracter
     */
    private static function metrelesGuemet($tableau) {
        $result = NULL;
        if ($tableau) {
            $tab = NULL;
            foreach ($tableau as $value) {
                $temp = trim($value);
                $tab[] .= "'{$temp}'";
            }
            $result = implode(",", $tab);
        }
        return $result;
    }

    public static function listCategorieSoucategrie() {

        $sql = "SELECT category_name , sub_category_name , sub_category_description, id_sub_category\n"
                . "FROM category INNER JOIN sub_category ON category.id_category = sub_category.id_category";
        $result = self::getResults($sql);
        if (!$result) {
            $result = NULL;            
        }
        return $result;
    }

}
