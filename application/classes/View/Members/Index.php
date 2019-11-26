<?php

/**
 * Afficher la liste des adhérents
 */

class View_Members_Index extends View_Master {

  /**
   * @var String
   */
  public $title = "Adhérents — Guidoline";

  /**
   * Liste des membres
   *
   * @return Array
   */

  protected $_members;

  protected function _members_query()
  {
    // Add query here
    $filters = array(
      'actifs' => 1,
      'inactifs' => 0,
      'tous' => NULL
    );

    $filter = $filters[$this->current_filter()];

    $members = ORM::factory('Member');

    if ($filter !== NULL)
    {
      $members->where('is_active', '=', $filter);
    }

    // Requête de recherche
    $search = $this->current_search();
    $search_fields = array(
      'idm',
      'firstname',
      'lastname',
      'email',
      'phone',
      'street',
      'zipcode',
      'city',
      'country',
      'gender',
      'birthdate',
      'created',
    );


    /**
     * Search features
     *
     *  - non sensible à la casse ;
     *  - recherche partie de mot ;
     *  - recherche mot complet avec `""` (guillemets doubles) ;
     *  - recherche combinées AND avec des espaces entre les mots ;
     *  - recherche combinées OR avec un `|` (pipe) entre les mots.
     */
    /**
     * Aurtres pistes
     *
     * Note à propos des rechercher :
     * Les recherches suivante devrais possilbes :
     *  - contenant : Jean => Jean-Claude (LIKE %Jean% | CONTAINS Jean)
     *  - exact, mot isolé : "Jean" => Jean Robert ([[:<:]]Jean[[:>:]])
     *  - exact, champ complet : [Jean] => Jean (=)
     *  - inverser : !
     *  - champ vide : phone: (IS NULL | LIKE ''
     *  - dans un champ : idm:123; #:123 ou n°:123 => champ idm (CONTAINS(idm, "123"))
     *  - exact dans un champ : idm:"12" => idm 12
     *  - permettre de rechercher dans des champs non chargé à l'écran : active:true
     *  - chercher une les liaison : has:
     *  - opérateurs : birthdate > 01/01/1977
     *  - Combiner : firstname:Jean + lastname:robert (`and_where` overkill)
     *  - plus grand ou plus petit que : birthdate > 01/01/1977
     */

    $search = preg_replace('/[\s]{2,}/', ' ', $search);

    if ($search)
    {
      // preg_match_all('^\".*\"|.*^', $search, $terms);
      // Supprimmer les espaces blancs en trop
      $terms = explode(' ', trim($search));
      $members->and_where_open();
      $or_mode = FALSE;

      foreach ($terms as $term)
      {
        // Detecter les doubles guillemets
        preg_match('^\".*\"^', $term, $strict);
        $strict = ! empty($strict);
        // Supprimer les guillemets doubles
        $term = preg_replace('/(^"+|"+$)/', '', $term);
        $query = $strict ? "$term" : "%$term%";

        // Recherche OR
        if ($term === '|')
        {
          // Basculer sur le mode OR
          $or_mode = TRUE;
          continue;
        }

        if ($or_mode)
        {
          $members->or_where_open();
        }
        else
        {
          $members->and_where_open();
        }

        foreach ($search_fields as $field)
        {
          $members->or_where($field, 'LIKE', $query);
        }

        if ($or_mode)
        {
          $members->or_where_close();
          $or_mode = FALSE;
        }
        else
        {
          $members->and_where_close();
        }
      }

      $members->and_where_close();
    }

    return $members;
  }

  /**
   * Le filtre courant
   *
   * @return String
   */
  public function current_filter()
  {
    return Request::current()->param('filter');
  }

  /**
   * Le contenu de le recherche courante
   *
   * Pour récupérer la recherche courante sous forme de requête :
   *
   * ~~~
   * URL::query(Request::current()->query())
   * ~~~
   *
   * @return String
   */
  public function current_search()
  {
    return Request::current()->query('rechercher');
  }

  public function current_query_search()
  {
    return URL::query(Request::current()->query());
  }
  /**
   * Lambdas pour les filtres
   *
   * https://github.com/bobthecow/mustache.php/wiki/Mustache-Tags#lambdas
   *
   * @return string
   */
  public function is_filter_active()
  {
    return function($filter, $helper)
    {
      if ($filter === Request::current()->param('filter'))
      {
        return "is-active";
      }

      return FALSE;
      echo Debug::vars(Request::current()->param('filter'));
      echo Debug::vars($filter);
    };
  }

  /**
   * @todo  Créer un helper pour wrapper les résultats d'ORM dans un
   * tableau JSON friendly.
   * Considérer : les queries, les données à embarquer et la pagination.
   *
   */
  public function members()
  {
    if ( ! $this->_members)
    {
      $this->_members = array(
        'records_count' => 0,
        'total_count' => 0,
        'records' => array(),
        'paginate' => array(),
      );

      $this->_members['total_count'] = $this->_members_query()->count_all();
      $limit = 200;

      $this->_members['paginate'] = (new Paginate(array(
        'url_prefix' => '/adherents/' . Request::current()->param('filter') . '/',
      )))->create(
        Request::current()->param('folio'),
        $limit,
        $this->_members['total_count']
      );

      $members = $this->_members_query()
        ->offset($this->_members['paginate']['offset'])
        ->limit($this->_members['paginate']['limit'])
        ->find_all();

      foreach ($members as $member)
      {
        $this->_members['records'][]['member'] = $member->as_array();
      }

      $this->_members['records_count'] = count($this->_members['records']);
    }

    return $this->_members;

  }
}
