<?php

namespace App\Core;

use App\Forms\Register;

class Form
{
    private $config;
    private $errors = [];

    public function __construct(String $name)
    {
        if (!file_exists("../Forms/" . $name . ".php")) {
            die("Le form " . $name . ".php n'existe pas dans le dossier ../Forms");
        }
        include "../Forms/" . $name . ".php";
        $name = "App\\Forms\\" . $name;
        $this->config = $name::getConfig();
    }

    public function build(): string
    {
        $html = "";

        if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
                $html .= "<li>" . $error . "</li>";
            }
        }

        $html .= "<form action='" . $this->config["config"]["action"] . "' method='" . $this->config["config"]["method"] . "'>";

        foreach ($this->config["inputs"] as $name => $input) {
            $html .= "
                <input 
                    type='" . $input["type"] . "' 
                    name='" . $name . "' 
                    placeholder='" . $input["placeholder"] . "'
                    " . (($input["required"]) ? "required" : "") . "
                    ><br>
            ";
        }

        $html .= "<input type='submit' value='" . htmlentities($this->config["config"]["submit"]) . "'>";
        $html .= "</form>";

        return $html;
    }

    public function isSubmitted(): bool
    {
        if ($this->config["config"]["method"] == "POST" && !empty($_POST)) {
            return true;
        } else if ($this->config["config"]["method"] == "GET" && !empty($_GET)) {
            return true;
        } else {
            return false;
        }
    }

    public function isValid(): bool
    {
        // Est-ce que j'ai exactement le même nb de champs
        if (count($this->config["inputs"]) != count($_POST)) {
            $this->errors[] = "Tentative de Hack";
        }

        foreach ($this->config["inputs"] as $name => $inputConfig) {
            // Est-ce qu'il s'agit d'un champ que je lui ai donné ?
            if (!isset($_POST[$name])) {
                $this->errors[] = "Tentative de Hack, le champ " . $name . " n'est pas autorisé";
                continue;
            }

            $dataSent = $_POST[$name];

            // Est ce que ce n'est pas vide si required
            if (isset($inputConfig["required"]) && empty($dataSent)) {
                $this->errors[] = "Le champ " . $name . " ne doit pas être vide";
            }

            // Est ce que le min correspond
            if (isset($inputConfig["min"]) && strlen($dataSent) < $inputConfig["min"]) {
                $this->errors[] = $inputConfig["error"];
            }

            // Est ce que le max correspond
            if (isset($inputConfig["max"]) && strlen($dataSent) > $inputConfig["max"]) {
                $this->errors[] = $inputConfig["error"];
            }

            // Est ce que la confirmation correspond
            if (isset($inputConfig["confirm"]) && $dataSent != $_POST[$inputConfig["confirm"]]) {
                $this->errors[] = $inputConfig["error"];
            } else {
                // Est ce que le format email est OK
                if ($inputConfig["type"] == "email" && !filter_var($dataSent, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "Le format de l'email est incorrect";
                }
                // Est ce que le format password est OK
                if ($inputConfig["type"] == "password" &&
                    (!preg_match("#[a-z]#", $dataSent) ||
                        !preg_match("#[A-Z]#", $dataSent) ||
                        !preg_match("#[0-9]#", $dataSent))
                ) {
                    $this->errors[] = $inputConfig["error"];
                }
            }
        }

        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }
}
