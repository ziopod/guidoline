<?php

/**
 * Afficher la liste des adhérents
 */

class View_Members_Index extends View_Master {

  /**
   * @var String $title   Titre de la page
   */
  public $title = "Adhérents — Guidoline";

  /**
   * @var Array $_members   Liste des membres
   */
  protected $_members;

  /**
   * @var Integer   $_limit   Limite pour la requête
   */
  protected $_limit = 100;

  /**
   * Le filtre courant
   *
   * Aide également à peupler le l'URL `action` du formulaire.
   * @return String
   */
  public function current_filter()
  {
    $filter = Request::current()->param('filter');
    return $filter ? $filter : NULL;
  }

  /**
   * Affihchage du filtre courant
   *
   * @return String
   */
  public function current_filter_label()
  {
    return Arr::get(array(
      '' => 'tous',
      'actifs' => 'actifs',
      'inactifs' => 'inactifs',
      'benevoles' => 'bénévoles',
    ), $this->current_filter());
  }

  /**
   * Requête de recherche courante
   *
   * Aide notamment à peupler l'URL `action` du formulaire.
   *
   * @return String
   */
  public function current_search()
  {
    return Request::current()->query('rechercher');
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

  public function current_query_search()
  {
    return URL::query(Request::current()->query());
  }

  /**
   * Requête pour les adhérents
   *
   * @return ORM
   */
  protected function _members_query()
  {
    $is_active = $this->current_filter();
    $is_volunteer = $this->current_filter() === 'benevoles';
    $members = ORM::factory('Member');

    if ($is_active !== NULL)
    {
      $members->where('is_active', '=', $is_active === 'actifs');
    }

    if ($is_volunteer)
    {
      $members->where('is_volunteer', '=', 1);
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

    // Ordering
    $members->order_by('idm', 'desc');

    return $members;
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
        'total_count' => $this->_members_query()->count_all(),
        'records' => array(),
        'paginate' => array(),
      );

      // Pagination
      $this->_members['paginate'] = (new Paginate(array(
        'url_prefix' => URL::site(
          Route::get('members')->uri(array(
            'filter' => $this->current_filter()
          )),
          TRUE) . '/'
        )))
        ->create(
          Request::current()->param('folio'),
          $this->_limit,
          $this->_members['total_count']
        );

      $members = $this->_members_query()
        ->offset($this->_members['paginate']['offset'])
        ->limit($this->_members['paginate']['limit'])
        ->find_all();

      foreach ($members as $member)
      {
        $this->_members['records'][]['member'] = $member->as_array('dues');
      }

      $this->_members['records_count'] = count($this->_members['records']);
    }

    return $this->_members;

  }
}
