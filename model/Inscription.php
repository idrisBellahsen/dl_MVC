<?php

require_once('Requete.php');

class Inscription {

    private $nom;
    private $prenom;
    private $pseudo;
    private $email;
    private $motPass1;
    private $motPass2;
    private $password;
    private $error;
    private $resultat;

    public function __construct($nom, $prenom, $pseudo, $email, $motPass1, $motPass2) {
        $this->nom = trim(htmlspecialchars($nom));
        $this->prenom = trim(htmlspecialchars($prenom));
        $this->pseudo = trim(htmlspecialchars($pseudo));
        $this->email = trim(htmlspecialchars($email));
        $this->motPass1 = $motPass1;
        $this->motPass2 = $motPass2;
    }

    /**
     * verifie si le nom et prenom exist deja dans la table dl_afpa
     * @param type $nom mom a chercher
     * @param type $prenom prenom a chercher
     * @return id corespon a son id de la table principal si ca existe
     */
    public function isNomPrenDLafpa() {
        $result = NULL;
        $res = Requete::getresultSelect('information', 'user_forename,id_information,status', "user_name = {$this->nom}");
        if ($res) {
            if ($res[0]['user_forename'] == $this->prenom && $res[0]['status']=='DlAfpa') {
                
                $result = $res[0]['id_information'];
            } else {
                $this->error = "Le nom et le prenom n'existe pas dans la table information";
            }
        } else {
            $this->error = "le nom : '{$this->nom}' n'existe pas dans  table information";
        }
        return $result;
    }

    /**
     * savoir si un pseudo existe ou pas
     * @return boolean vrais si ca existe
     */
    public function isPseudoUtilisateur() {
        $result = FALSE;
        $res = Requete::getResultSelect('user', 'id_user', "pseudo = {$this->pseudo}");
        if ($res) {
            $result = TRUE;
            //print_r($res);
        }
        return $result;
    }

    /**
     * verification des condition pour insert
     */
    public function verife() {


        $tes = FALSE;
        if (strlen($this->nom) < 50 && strlen($this->prenom) < 50 && strlen($this->pseudo) < 20 && strlen($this->pseudo) > 2) { //verification de nom et prenom et pseudo
            $syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
            if (preg_match($syntaxe, $this->email)) {//ferification de l'email
                if (strlen($this->motPass1) > 7 && $this->motPass1 == $this->motPass2) {//verifivation du mot de pass
                    if (!$this->isPseudoUtilisateur()) {
                        $tes = TRUE;
                        $this->password = password_hash($this->motPass1, PASSWORD_DEFAULT);
                        $this->motPass1 = NULL;
                        $this->motPass2 = NULL;
                    } else {
                        $this->error = "Ce pseudo existe deja ";
                    }
                } else {
                    $this->error = "Les deux mot de passe ne correspond pas ou est trop court,Il doit etre supérieur a 8 caractere ";
                }
            } else {
                $this->error = "L'email doit avoir une format caractere@caractere.caractere";
            }
        } else {
            $this->error = "longueur max du nom est de 50 caractere, longeur max du prenom est de 50 caractere, La taille du pseudo doit etre entre 20 a 50 caractere ";
        }
        return $tes;
    }

    /**
     * 
     * @return string return 'ok' c'est inscri et valider et 'encour' pour encour de validation 
     */
    public function insertion_User_ou_DL_afpa() {
        $result = FALSE;
        if ($this->verife()) {
            $id = $this->isNomPrenDLafpa(); // retourn id dlafpa si existe
            if ($id) {
                if (Requete::inser("user ", "passwoes, pseudo, email_inscription, id_information ", "{$this->password}, {$this->pseudo}, {$this->email}, {$id}")) {
                    $result = TRUE;
                    $this->resultat = "felicitation vous êtes inscrit ";
                } else {
                    $this->error = Requete::getErreur();
                }
            } else {
                //insertion dans la liste d'attente
                $nom = $this->nom;
                $prenom = $this->prenom;
                $pseudo = $this->pseudo;
                $email = $this->email;
                $password = $this->password;
                if (Requete::inser("liste_attente ", "nom, pn_utilisateur, pseudo, email, mot_de_pass, validation ", "'{$nom}', '{$prenom}', '{$pseudo}', '{$email}', '{$password}', 'encour'")) {
                    $result = TRUE;
                    $this->resultat = "Vous êtes inscrit sur la liste d'attente ";
                } else {
                    $this->error = Requete::getErreur();
                }
            }
        }
        return $result;
    }

    public function getError() {
        return $this->error;
    }

    public function getResultat() {
        return $this->resultat;
    }

}
?>

