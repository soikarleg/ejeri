<?php
class DataValidator
{
  // Vérifie et nettoie l'identifiant de compte
  public static function validateIdCompte($idcompte)
  {
    if (!preg_match('/^[A-Z][0-9]{5,6}$/', $idcompte)) {
      throw new InvalidArgumentException("Identifiant de compte invalide. Il doit commencer par une lettre suivie de quatre chiffres.");
    }
    return $idcompte; // Retourne l'identifiant valide
  }

  // Vérifie et nettoie un numéro client
  public static function validateClientNumber($clientNumber)
  {
    if (!preg_match('/^\d{6}$/', $clientNumber)) {
      throw new InvalidArgumentException("Numéro client invalide. Il doit contenir 6 chiffres.");
    }
    $clientNumber = str_pad($clientNumber, 6, '0', STR_PAD_LEFT);
    return $clientNumber; // Retourne le numéro client valide
  }

  // Vérifie et nettoie un float
  public static function validateFloat($value)
  {
    if (!is_numeric($value)) {
      throw new InvalidArgumentException("Valeur invalide. Elle doit être un nombre flottant.");
    }
    return floatval($value); // Retourne la valeur flottante
  }

  // Purge une chaîne de caractères pour éviter les injections SQL
  public static function purgeStr($value)
  {
    // Enlève les caractères pouvant nuire à une base de données MySQL
    $purgedValue = preg_replace('/[^a-zA-Z0-9À-ÿ\s\'-]/', '', $value);
    return $purgedValue; // Retourne la chaîne purgée
  }

  // Vérifie et nettoie une adresse
  public static function validateAdresse($str)
  {

    if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\'-]+$/', $str)) {
      // Seuls les caractères autorisés sont les chiffres, les lettres, les accents, les apostrophes et les tirets.
      throw new InvalidArgumentException("Adresse invalide.");
    }
    return  $str; // Retourne l'adresse valide
  }
  public static function validateVille($str)
  {

    if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\'-]+$/', $str)) {
      // Seuls les caractères autorisés sont les chiffres, les lettres, les accents, les apostrophes et les tirets.
      throw new InvalidArgumentException("Ville invalide.");
    }
    return  $str; // Retourne l'adresse valide
  }
  public static function validateNom($str)
  {

    if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\'-]+$/', $str)) {
      //  Seuls les caractères autorisés sont les chiffres, les lettres, les accents, les apostrophes et les tirets.
      throw new InvalidArgumentException("Nom invalide.");
    }
    $formatted = preg_replace_callback(
      '/(?:\b[a-zA-Z0-9À-ÿ\']+\b)|(?:[ -])/', // Match un mot ou un séparateur
      function ($matches) {
        $part = $matches[0];
        if (preg_match('/[ -]/', $part)) {
          // C'est un séparateur (espace ou tiret), on le garde tel quel
          return $part;
        }
        // Sinon, formatage du mot avec une majuscule au début
        return ucfirst(strtolower($part));
      },
      $str
    );

    return $formatted;
  }
  public static function validatePrenom($str)
  {

    if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\'-]+$/', $str)) {
      // Seuls les caractères autorisés sont les chiffres, les lettres, les accents, les apostrophes et les tirets.
      throw new InvalidArgumentException("Prénom invalide.");
    }
    $formatted = preg_replace_callback(
      '/(?:\b[a-zA-Z0-9À-ÿ\']+\b)|(?:[ -])/', // Match un mot ou un séparateur
      function ($matches) {
        $part = $matches[0];
        if (preg_match('/[ -]/', $part)) {
          // C'est un séparateur (espace ou tiret), on le garde tel quel
          return $part;
        }
        // Sinon, formatage du mot avec une majuscule au début
        return ucfirst(strtolower($part));
      },
      $str
    );

    return $formatted;
  }


  public static function validateCP($codePostal)
  {
    // Vérifie que le code postal est un nombre à 5 chiffres, commençant par un 0
    // Il doit être un nombre à 5 chiffres compris entre 01000 et 95999.
    if (!preg_match('/^(0[1-9][0-9]{3}|[1-8][0-9]{4}|9[0-5][0-9]{3})$/', $codePostal)) {
      throw new InvalidArgumentException("Code postal invalide.");
    }
    return $codePostal; // Retourne le code postal valide
  }
  // Vérifie et nettoie un numéro de téléphone français
  public static function validateTelephone($phoneNumber)
  {
    // Il doit commencer par 01 à 05 ou 09 et contenir 10 chiffres au total.
    if (!preg_match('/^(0[1-5]|09)\d{8}$/', $phoneNumber)) {
      throw new InvalidArgumentException("Numéro de téléphone fixe invalide.");
    }
    return $phoneNumber; // Retourne le numéro de téléphone valide
  }

  // Vérifie et nettoie un numéro de portable
  public static function validatePortable($mobileNumber)
  {
    // Il doit commencer par 06 ou 07 et contenir 10 chiffres au total.
    if (!preg_match('/^(06|07)\d{8}$/', $mobileNumber)) {
      throw new InvalidArgumentException("Numéro de portable invalide.");
    }
    return $mobileNumber; // Retourne le numéro de portable valide
  }

  // Vérifie et nettoie une adresse email
  public static function validateEmail($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException("Adresse email invalide.");
    }
    return $email; // Retourne l'email valide
  }
}
