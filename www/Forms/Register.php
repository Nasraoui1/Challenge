<?php

namespace App\Forms;

class Register
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "method" => "POST",
                "action" => "/register",
                "submit" => "Register"
            ],
            "inputs" => [
                "firstname" => [
                    "type" => "text",
                    "placeholder" => "First Name",
                    "required" => true
                ],
                "lastname" => [
                    "type" => "text",
                    "placeholder" => "Last Name",
                    "required" => true
                ],
                "email" => [
                    "type" => "email",
                    "placeholder" => "Email",
                    "required" => true
                ],
                "password" => [
                    "type" => "password",
                    "placeholder" => "Password",
                    "required" => true,
                    "min" => 8,
                    "error" => "Password must be at least 8 characters long and include at least one number, one uppercase and one lowercase letter"
                ],
                "passwordConfirm" => [
                    "type" => "password",
                    "placeholder" => "Confirm Password",
                    "required" => true,
                    "confirm" => "password",
                    "error" => "Passwords do not match"
                ],
                "date_of_birth" => [
                    "type" => "date",
                    "placeholder" => "Date of Birth",
                    "required" => true
                ],
                "address" => [
                    "type" => "text",
                    "placeholder" => "Address",
                    "required" => true
                ],
                "phone" => [
                    "type" => "text",
                    "placeholder" => "Phone",
                    "required" => true
                ]
            ]
        ];
    }
}
