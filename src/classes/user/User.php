<?php

namespace iutnc\netvod\user;

use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;
class User
{
    protected string $email, $passwrd;

    /**
     * @param string $email
     * @param string $passwrd
     */
    public function __construct(string $email, string $passwrd)
    {
        $this->email = $email;
        $this->passwrd = $passwrd;
    }


    function getSeries(): array
    {
        ConnectionFactory::setConfig("config.ini");
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("select * from serie");
        $c1->execute();
        $array = [];
        while ($d = $c1->fetch()) {
            array_push($array, $d['titre']);$array[] = $d['titre'];
        }
        return $array;
    }

    /**
     * @return string email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}