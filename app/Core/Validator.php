<?php

/**
 * Validator
 *
 * Classe utilitaire pour valider les données entrantes
 * (formulaires, paramètres, etc.)
 */

class Validator
{
    /* =====================================================
       VALIDATIONS SIMPLES
       ===================================================== */

    /**
     * Vérifier qu’une valeur n’est pas vide
     */
    public static function required($value): bool
    {
        return isset($value) && trim((string)$value) !== '';
    }

    /**
     * Vérifier un email
     */
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Vérifier une longueur minimale
     */
    public static function minLength(string $value, int $length): bool
    {
        return mb_strlen(trim($value)) >= $length;
    }

    /**
     * Vérifier une longueur maximale
     */
    public static function maxLength(string $value, int $length): bool
    {
        return mb_strlen(trim($value)) <= $length;
    }

    /**
     * Vérifier un identifiant numérique (> 0)
     */
    public static function id($value): bool
    {
        return is_numeric($value) && (int)$value > 0;
    }

    /* =====================================================
       VALIDATIONS SPÉCIFIQUES
       ===================================================== */

    /**
     * Vérifier un mot de passe simple
     */
    public static function password(string $password): bool
    {
        return self::minLength($password, 6);
    }

    /**
     * Vérifier un message de chat
     */
    public static function message(string $message): bool
    {
        return self::required($message) && self::maxLength($message, 1000);
    }

    /**
     * Vérifier un nom
     */
    public static function name(string $name): bool
    {
        return self::required($name) && self::minLength($name, 2);
    }
}
