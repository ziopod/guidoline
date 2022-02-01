<?php
/**
 * Maijet driver api for contact
 *
 * Note: Attention à bien déclarer les propriétés et les listes dans Mailjet
 * https://app.mailjet.com/contacts/lists/properties
 * https://app.mailjet.com/contacts
 *
 *
 * @package    Guidoline
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2021 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class External_Driver_Contact_Mailjet implements External_Driver_Contact_Interface {

  private $_cURL = null;

  private $_config = null;

  /**
   *
   * @param Array Koahan config group
   */
  public function __construct($config = null)
  {
    if (!$config)
    {
      $config= Kohana::$config->load('mailjet');
    }

    $this->_config = $config;
  }
  /**
   * Connexion auprès du service
   *
   *
   * Documentation :
   * - https://dev.mailjet.com/email/reference/overview/errors/
   *
   * @return Boolean Succes or fail
   */

  public function _open()
  {
    $this->_cURL = curl_init();
    curl_setopt_array($this->_cURL, array(
      CURLOPT_RETURNTRANSFER => true,   // return web page
      CURLOPT_HEADER         => false,  // don't return headers
      CURLOPT_FOLLOWLOCATION => true,   // follow redirects
      CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
      CURLOPT_ENCODING       => '',     // handle compressed
      CURLOPT_USERAGENT      => $this->_config->useragent, // name of client
      CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
      CURLOPT_TIMEOUT        => 120,    // time-out on response
      CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
      // CURLOPT_USERNAME       => $this->_config->api_key,
      // CURLOPT_PASSWORD       => $this->_config->api_secret,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Basic ' .  base64_encode($this->_config->api_key . ':' . $this->_config->api_secret)
      ),
    ));
  }

  private function _close()
  {
    curl_close($this->_cURL);
    $this->_cURL = null;
  }

  /**
   * Account information
   *
   * @return Array Account profile data
   */
  public function informations()
  {
    $this->_open();
    $url = $this->_config->url . '/user';
    $url = $this->_config->url . '/myprofile';
    curl_setopt($this->_cURL, CURLOPT_URL, $url);
    return json_decode(curl_exec($this->_cURL));
    $this->_close();
  }

  /**
   * Récupère les données du contact
   * https://dev.mailjet.com/email/reference/contacts/contact-properties#v3_get_contactdata
   *
   * @param String $email Identifiant email du contact
   * @return Array Informations
   */
  public function get($email)
  {
    if (!$email) return NULL;

    $this->_open();
    $url = $this->_config->url . 'contactdata/' . $email;
    curl_setopt($this->_cURL, CURLOPT_URL, $url);
    $result = json_decode(curl_exec($this->_cURL));
    $this->_close();
    if (!isset($result->StatusCode)) return $result;
    if ($result->StatusCode === 404) return NULL;
    return $result;
  }

  /**
   * Récupère les liste d'un contact
   * https://dev.mailjet.com/email/reference/contacts/subscriptions#v3_get_contact_contact_ID_getcontactslists
   *
   * @param String $email Identifiant email du contact
   * @return Array List
   */
  public function getContactsLists($email) {
    $this->_open();
    curl_setopt_array($this->_cURL, array(
      CURLOPT_URL => $this->_config->url . 'contact/' . $email . '/getContactsLists'
    ));

    $result = json_decode(curl_exec($this->_cURL));

    if (curl_errno($this->_cURL)) {
      throw new Kohana_Exception(curl_error($this->_cURL));
    }

    return isset($result->Data) ? $result->Data : [];
  }

  /**
   * Déplace un contact dans une liste
   *
   * @todo Prévoir une méthode de création automatique de liste
   * @param String $email Identifiant email du contact
   * @param String $list Nom de la liste (fichier de configuration)
   */
  public function moveToList($email, $list = 'members') {

    $listID = $this->_config->list[$list];
    $this->_open();
    curl_setopt_array($this->_cURL, array(
      CURLOPT_URL => $this->_config->url . 'listrecipient',
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode(array(
        'ContactAlt' => $email,
        'ListID' => $listID
        ))
      ));

    $result = json_decode(curl_exec($this->_cURL));
    $status = curl_getinfo($this->_cURL, CURLINFO_HTTP_CODE);

    if (curl_errno($this->_cURL)) {
      throw new Kohana_Exception(curl_error($this->_cURL));
    }

    $this->_close();
  }

  /**
   * Sauvegarde des métadonnées d'un contact
   *
   * @param String $email Identifiant email d'un contact
   * @param Array $data Tableau de méta données
   */

  public function saveData($email, $data) {
    // Mise à jour des données distantes
    $this->_open();
    curl_setopt_array($this->_cURL, array(
      CURLOPT_URL => $this->_config->url . 'contactdata/' . $email,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS => json_encode(array(
        'Data' => array(
          array(
            'Name' => 'firstname',
            'Value' => $data['firstname']
          ),
          array(
            'Name' => 'lastname',
            'Value' => $data['lastname']
          ),
          array(
            'Name' => 'last_membership',
            'Value' => $data['last_membership']
          ),
          array(
            'Name' => 'active_membership',
            'Value' => $data['is_active']
          ),
          array(
            'Name' => 'volunteer',
            'Value' => $data['is_volunteer']
          ),
        )
      ))
    ));

    $result = json_decode(curl_exec($this->_cURL));

    if (curl_errno($this->_cURL)) {
      throw new Kohana_Exception(curl_error($this->_cURL));
    }

    $this->_close();
  }
  /**
   * Créer un contact
   * https://dev.mailjet.com/email/reference/contacts/contact/
   *
   * @param String $email Identifiant email du contact
   * @param Array $data Liste des données à enregistrer
   */
  public function create($email, $data)
  {
    // Création du contact
    $this->_open();
    curl_setopt_array($this->_cURL, array(
      CURLOPT_URL => $this->_config->url . 'contact',
      CURLOPT_POST => TRUE,
      CURLOPT_POSTFIELDS => json_encode(array(
        'Email' => $email,
        'Name' => $data['fullname']
      ))
    ));

    $result = json_decode(curl_exec($this->_cURL));
    $status = curl_getinfo($this->_cURL, CURLINFO_HTTP_CODE);

    if (curl_errno($this->_cURL)) {
      throw new Kohana_Exception(curl_error($this->_cURL));
    }

    $this->_close();

    // Enregistrement des métas données
    $this->saveData($email, $data);

    // Ajout du contact à une liste
    // @todo: création de la liste
    $this->moveToList($email);
  }

  /**
   * Mise à jour des données du contact
   *
   * @param String $email Identifiant email du contact
   * @param Array $data Données à mettre à jour
   */
  public function update($email, $data)
  {
    // Enregistrement des métas données
    $this->saveData($email, $data);

    // Déplacer le contact dans la liste de contact membres si besoin
    if (!in_array($this->_config->list['members'], array_map(
      function($e) { return $e->ListID; },
      $this->getContactsLists($email)
    )))
    {
      $this->moveToList($email);
    }
  }
}
