<?php
require_once('Requete.php');
class Connection {

    private $motdepass;
    private $pseudo;
    private $error;
    private $result;
    private $idUser;

    public function __construct($motpass, $pseudo) {
        $this->pseudo = $pseudo;
        $this->motdepass = $motpass;
    }

    public function getPseudo() {
        $res = NULL;
        if (isset($this->pseudo)) {
            $res = $this->pseudo;
        }
        return $res;
    }

    public function conn() {
        $res = FALSE;
        if (isset($this->pseudo)) {
            $result = Requete::getresultSelect("user", "id_user, password", "pseudo ={$this->pseudo}");

            if ($result) {
                if (password_verify($this->motdepass, $result[0]['password'])) {
                    $this->result = "Connection réussie";
                    $this->idUser = $result[0]['id_user'];
                    $res = TRUE;
                } else {
                    $this->error = "isNotPassword";
                }
            } else {
                $this->error = "isNotPseudo";
            }
        }
        return $res;
    }

    public function getResult() {
        return $this->result;
    }

    public function getError() {
        return $this->error;
    }

    public function getIdUser() {
        return $this->idUser;
    }

}
