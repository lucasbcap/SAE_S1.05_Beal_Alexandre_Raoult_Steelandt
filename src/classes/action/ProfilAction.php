<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class ProfilAction extends \iutnc\netvod\action\Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res ="";
        if($this->http_method == 'GET') $res = $this->modifProfil();
        else if ($this->http_method == 'POST') {
            $res = $this->modifProfil();
            $res .= $this->enregistrerProfil();
            $res .="<h3 style='color:green'>Profile enregistré</h3>";
        }
        return $res;
    }

    function modifProfil() : string{
        $res = "<h2>Profil : </h2> ";
        $res .= "<form id='formPro' action='?action=profil' method='POST' >
                 <label><b>Nom :</b></label>
                 <input id='input' type='text' placeholder='Entrer votre nom' name='nom'>
                 <label><b>Prénom :</b></label>       
                 <input id= 'input' type='text' placeholder='Entrer votre prénom' name='prenom'><br>
                 <label><b>Genre préféré :</b></label>
                 <input id='input' type='text' placeholder='Entrer votre genre préféré' name='genrepref'>
                   
                 <input type='submit' value='Sauvegarder'>";


        $res .= "</form>";

        return $res;
    }

    function enregistrerProfil() : string{
        $res ="";
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['genrepref'])){
            $bdd = ConnectionFactory::makeConnection();
            $nom = filter_var($_POST['nom']);
            $prenom = filter_var($_POST['prenom']);
            $genrepref = filter_var($_POST['genrepref']);
            $emailUser = unserialize($_SESSION['user'])->email;
            $c2 = $bdd->prepare("update user set nom= ? , prenom=?, genrePrefere = ? where email = ?");
            $c2->bindParam(1,$nom);
            $c2->bindParam(2,$prenom);
            $c2->bindParam(3,$genrepref);
            $c2->bindParam(4,$emailUser);
            $c2->execute();
        }
        return $res;
    }
}