<?php
namespace App\Forms;

class ForgotPassword
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "", // Vous pouvez spécifier l'URL de soumission ici
                "method" => "POST",
                "submit" => "Envoyer le lien de réinitialisation"
            ],
            "inputs" => [
                "email" => [
                    "type" => "email",
                    "min" => 8,
                    "max" => 320,
                    "placeholder" => "Votre email",
                    "required" => true,
                    "error" => "Votre email doit faire entre 8 et 320 caractères"
                ]
            ]
        ];
    }
}
