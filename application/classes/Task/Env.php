<?php

/**
 * # Accès aux variables d'environnement
 *
 * Récupérer une variable d'environnment.
 *
 * ## Exemples
 *
 * Récupérer une variable
 *
 * ~~~
 * ./minion env --key=HOME
 * ~~~
 *
 * --key=<STRING>       Nom de la variable à récupérer
 *
 * @package  Guidoline
 * @category  Task
 */

  class Task_env extends Minion_task {

  protected $_options= array(
    'key' => NULL,
  );

  protected function _execute(array $params)
  {
    $key = strtoupper(Arr::get($params, 'key', NULL));
    // Minion_CLI::write($key);

    if ($key == NULL)
    {
      Minion_CLI::write("Veuillez indiqué une variable d'environnement");
      return NULL;
    }

    // $value = exec("printenv $key");

    // if ($value !== NULL)
    // {
    //   Minion_CLI::write("Récupéré en environnement global");
    //   Minion_CLI::write($value);
    //   return $value;
    // }

    $value = getenv($key);

    if ($value !== NULL)
    {
      return $value;
    }

    Minion_CLI::write("Impossible de récupérer la variable d'environnement $key");
  }


  /**
   * TODO: Ajouter deux méthodes ? :
   *
   *  - is_global   Indique si la variable est défini en globale
   *  - is_dotenv   Indique si la varaible est définie en local
   */
 }
