<?php
class GP_Translation extends GP_Thing {

	var $per_page = 15;
	var $table_basename = 'translations';
	var $field_names = array( 'id', 'original_id', 'translation_set_id', 'translation_0', 'translation_1', 'translation_2', 'translation_3', 'translation_4', 'translation_5','user_id', 'status', 'date_added', 'date_modified', 'warnings');
	var $non_updatable_attributes = array( 'id', );

	static $statuses = array('current', 'waiting', 'rejected', 'fuzzy', 'old', );
	static $number_of_plural_translations = 6;

	function normalize_fields( $args ) {
		$args = (array)$args;
		if ( isset( $args['translations'] ) && is_array( $args['translations'] ) ) {
		    foreach( range( 0, $this->get_static( 'number_of_plural_translations') ) as $i ) {
		        if ( isset( $args['translations'][$i] ) ) $args["translation_$i"] = $args['translations'][$i];
		    }
			unset( $args['translations'] );
		}
	    foreach( range( 0, $this->get_static( 'number_of_plural_translations' ) ) as $i ) {
	        if ( isset( $args["translation_$i"] ) ) {
				$args["translation_$i"] = $this->fix_translation( $args["translation_$i"] );
			}
	    }
		if ( gp_array_get( $args, 'warnings' ) == array() ) {
			$args['warnings'] = null;
		}
		return $args;
	}

	function prepare_fields_for_save( $args ) {
		$args = parent::prepare_fields_for_save( $args );
		if ( is_array( gp_array_get( $args, 'warnings' ) ) ) {
			$args['warnings'] = serialize( $args['warnings'] );
		}
		return $args;
	}

	function fix_translation( $translation ) {
		// when selecting some browsers take the newlines and some don't
		// that's why we don't want to insert too many newlines for each ↵
		$translation = str_replace( "↵\n", "↵", $translation );
		return str_replace( '↵', "\n", $translation );
	}

	function restrict_fields( $translation ) {
		$translation->translation_0_should_not_be( 'empty' );
		$translation->status_should_not_be( 'empty' );
		$translation->original_id_should_be( 'positive_int' );
		$translation->translation_set_id_should_be( 'positive_int' );
		$translation->user_id_should_be( 'positive_int' );
	}


	function set_fields( $db_object ) {
		parent::set_fields( $db_object );
		if ( $this->warnings ) {
			$this->warnings = maybe_unserialize( $this->warnings );
		}
	}

	function for_export( $project, $translation_set, $filters =  null ) {
		return GP::$translation->for_translation( $project, $translation_set, 'no-limit', $filters? $filters : array( 'status' => 'current_or_untranslated' ) );
	}

	function for_translation($project, $translation_set, $page, $filters = array(), $sort = array(), $user_id = null) {
		global $gpdb;
		if ( $user_id ) {
			unset($filters['user_login']);
		} else {
			$locale = GP_Locales::by_slug( $translation_set->locale );
		}
		$status_cond = '';
		$join_type = 'INNER';

		$sort_bys = array('original' => 'o.singular %s', 'translation' => 't.translation_0 %s', 'priority' => 'o.priority %s, o.date_added DESC',
			'random' => 'o.priority DESC, RAND()', 'translation_date_added' => 't.date_added %s', 'original_date_added' => 'o.date_added %s',
			'references' => 'o.references' );
		$sort_by = gp_array_get( $sort_bys, gp_array_get( $sort, 'by' ), 'o.priority %1$s, o.date_added %1$s' );
		$sort_hows = array('asc' => 'ASC', 'desc' => 'DESC', );
		$sort_how = gp_array_get( $sort_hows, gp_array_get( $sort, 'how' ), 'DESC' );

		$where = array();
		if ( gp_array_get( $filters, 'term' ) ) {
			$like = "LIKE '%" . ( $gpdb->escape( like_escape ( gp_array_get( $filters, 'term' ) ) ) ) . "%'";
			$where[] = '(' . implode( ' OR ', array_map( lambda('$x', '"($x $like)"', compact('like')), array('o.singular', 't.translation_0', 'o.plural', 't.translation_1', 'o.context', 'o.references' ) ) ) . ')';
		}
		if ( gp_array_get( $filters, 'before_date_added' ) ) {
			$where[] = $gpdb->prepare( 't.date_added > %s', gp_array_get( $filters, 'before_date_added' ) );
		}
		if ( gp_array_get( $filters, 'translation_id' ) ) {
			$where[] = $gpdb->prepare( 't.id = %d', gp_array_get( $filters, 'translation_id' ) );
		}
		if ( gp_array_get( $filters, 'original_id' ) ) {
			$where[] = $gpdb->prepare( 'o.id = %d', gp_array_get( $filters, 'original_id' ) );
		}
		if ( 'yes' == gp_array_get( $filters, 'warnings' ) ) {
			$where[] = 't.warnings IS NOT NULL';
		} elseif ( 'no' == gp_array_get( $filters, 'warnings' ) ) {
			$where[] = 't.warnings IS NULL';
		}
		if ( 'yes' == gp_array_get( $filters, 'with_context' ) ) {
			$where[] = 'o.context IS NOT NULL';
		}
		if ( 'yes' == gp_array_get( $filters, 'with_comment' ) ) {
			$where[] = 'o.comment IS NOT NULL AND o.comment <> ""';
		}

		if ( gp_array_get( $filters, 'user_login' ) ) {
			$user = GP::$user->by_login( $filters['user_login'] );
			// do not return any entries if the user doesn't exist
			$where[] = $gpdb->prepare( 't.user_id = %d', ($user && $user->id)? $user->id : -1 );
		}

		if ( !GP::$user->current()->can( 'write', 'project', $project->id ) ) {
		    $where[] = 'o.priority > -2';
		}

		$join_where = array();
		$status = gp_array_get( $filters, 'status', 'current_or_waiting_or_fuzzy_or_untranslated' );
		$statuses = explode( '_or_', $status );
		if ( in_array( 'untranslated', $statuses ) ) {
			if ( $statuses == array( 'untranslated' ) ) {
				$where[] = 't.translation_0 IS NULL';
			}
			$join_type = 'LEFT';
			$join_where[] = 't.status != "rejected"';
			$statuses = array_filter( $statuses, lambda( '$x', '$x != "untranslated"' ) );
		}
		
		$statuses = array_filter( $statuses, lambda( '$s', 'in_array($s, $statuses)', array( 'statuses' => $this->get_static( 'statuses' ) ) ) );
		if ( $statuses ) {
			$statuses_where = array();
			foreach( $statuses as $single_status ) {
				$statuses_where[] = $gpdb->prepare( 't.status = %s', $single_status );
			}
			$statuses_where = '(' . implode( ' OR ', $statuses_where ) . ')';
			$join_where[] = $statuses_where;
		}

		$where = implode( ' AND ', $where );
		if ( $where ) {
			$where = 'AND '.$where;
		}

		$join_where = implode( ' AND ', $join_where );
		if ( $join_where ) {
			$join_where = 'AND '.$join_where;
		}
		
		if ( is_array($translation_set) ) {
			$tswhere = 'AND t.translation_set_id IN (';
			$first = true;
			foreach ( $translation_set as $ts ) {
				if ( $first ) {
					$first = false;
				} else {
					$tswhere .= ', ';
				}
				$tswhere .= $gpdb->escape($translation_set->id);
			}
			$tswhere .= ')';
		} else if ( $translation_set ) {
			$tswhere = 'AND t.translation_set_id = ' . $gpdb->escape($translation_set->id);
		} else {
			$tswhere = '';
		}

		$sql_sort = sprintf( $sort_by, $sort_how );
		$limit = $this->sql_limit_for_paging( $page );
		$sql_for_translations = "
			SELECT SQL_CALC_FOUND_ROWS t.*, o.*, t.id as id, o.id as original_id, t.status as translation_status, o.status as original_status, t.date_added as translation_added, o.date_added as original_added
		    FROM $gpdb->originals as o
		    $join_type JOIN $gpdb->translations AS t ON o.id = t.original_id $tswhere $join_where
			WHERE ";
		if ( $user_id ) {
			$sql_for_translations .= 't.user_id = ' . $gpdb->escape($user_id) . ' AND ';
		}
		if ( $project ) {
			$sql_for_translations .= 'o.project_id = ' . $gpdb->escape($project->id) . ' AND ';
		}
		$sql_for_translations .= "o.status LIKE '+%' $where ORDER BY $sql_sort $limit";
		$rows = $this->many_no_map( $sql_for_translations );
		$this->found_rows = $this->found_rows();
		$translations = array();
		foreach( (array)$rows as $row ) {
			if ( $row->user_id && $this->per_page != 'no-limit' ) {
				$user = GP::$user->get( $row->user_id );
				if ( $user ) $row->user_login = $user->user_login;
			} else {
				$row->user_login = '';
			}
			$row->translations = array();
			if ( !$locale ) {
				$tempts = GP::$translation_set->get($row->translation_set_id);
				$locale = GP_Locales::by_slug($tempts->locale);
			}
			for( $i = 0; $i < $locale->nplurals; $i++ ) {
				$row->translations[] = $row->{"translation_".$i};
			}
			$row->references = preg_split('/\s+/', $row->references, -1, PREG_SPLIT_NO_EMPTY);
			$row->extracted_comment = $row->comment;
			$row->warnings = $row->warnings? maybe_unserialize( $row->warnings ) : null;
			unset($row->comment);
			foreach( range( 0, $this->get_static( 'number_of_plural_translations' ) ) as $i ) {
				$member = "translation_$i";
				unset($row->$member);
			}
			$row->row_id = $row->original_id . ( $row->id? "-$row->id" : '' );
			$translations[] = new Translation_Entry( (array)$row );
		}
		unset( $rows );
		return $translations;
	}

	function count_by_user($user_id, $project_id = null, $translation_set_id = null, $locale_slug = null) {
        global $gpdb;
        $sql = "SELECT COUNT(*) FROM $gpdb->originals AS o
			LEFT JOIN $gpdb->translations AS t ON o.id = t.original_id";
        if ( $locale_slug ) {
            $sql .= " LEFT JOIN $gpdb->translation_sets AS ts ON ts.id = t.translation_set_id";
        }
        $sql .= ' WHERE t.user_id = %d';
        if ( $project_id ) {
            $sql .= ' AND o.project_id = ' . $gpdb->escape($project_id);
        }
        if ( $translation_set_id ) {
            $sql .= ' AND t.translation_set_id = ' . $gpdb->escape($translation_set_id);
        }
        if ( $locale_slug ) {
            $sql .= ' AND ts.locale = \'' . $gpdb->escape($locale_slug) . '\'';
        }
        $sql .= ' AND o.status LIKE \'+%%\'';
		return $this->value($sql, $user_id);
	}

	function set_as_current() {
		return $this->update( array('status' => 'old'),
			array('original_id' => $this->original_id, 'translation_set_id' => $this->translation_set_id, 'status' => 'current') )
		&& 	$this->update( array('status' => 'old'),
				array('original_id' => $this->original_id, 'translation_set_id' => $this->translation_set_id, 'status' => 'waiting') )
	    && $this->update( array('status' => 'old'),
			array('original_id' => $this->original_id, 'translation_set_id' => $this->translation_set_id, 'status' => 'fuzzy') )
		&& $this->update( array('status' => 'current') );
	}

	function reject() {
		$this->set_status( 'rejected' );
	}

	function set_status( $status ) {
		if ( 'current' == $status )
			return $this->set_as_current();
		else
			return $this->update( array( 'status' => $status ) );
	}

	function translations() {
		$translations = array();
	    foreach( range( 0, $this->get_static( 'number_of_plural_translations' ) ) as $i ) {
	        $translations[$i] = isset( $this->{"translation_$i"} )? $this->{"translation_$i"} : null;
	    }
		return $translations;
	}
}
GP::$translation = new GP_Translation();
