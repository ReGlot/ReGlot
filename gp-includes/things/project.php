<?php
class GP_Project extends GP_Thing {
	
	var $table_basename = 'projects';
	var $field_names = array( 'id', 'name', 'slug', 'path', 'description', 'parent_project_id', 'source_url_template', 'active' );
	var $non_updatable_attributes = array( 'id' );

	/**
	 * Deletes project and all subprojects
	 */
	function delete() {
		// delete all originals
		$originals = GP::$original->by_project_id($this->id);
		foreach ( $originals as $original ) {
			/** @ToDo need to check whether delete succeded */
			$original->delete();
		}
		// delete all translation sets for this project
		$translation_sets = GP::$translation_set->by_project_id($this->id);
		foreach ( $translation_sets as $translation_set ) {
			/** @ToDo need to check whether delete succeded */
			$translation_set->delete();
		}
		$subprojects = $this->sub_projects();
		foreach ( $subprojects as $child ) {
			/** @ToDo need to check whether delete succeded */
			$child->delete();
		}
		gp_delete_meta($this->id, 'elgg_version', null, 'gp_project');
		gp_delete_meta($this->id, 'elgg_manifest', null, 'gp_project');
		GP::$permission->delete_all(array('object_type' => 'project', 'object_id' => $this->id));
		return parent::delete();
	}

	function restrict_fields( $project ) {
		$project->name_should_not_be('empty');
	}
	
	// Additional queries

	function by_path( $path ) {
		return $this->one( "SELECT * FROM $this->table WHERE path = '%s'", trim( $path, '/' ) );
	}
	
	function sub_projects() {
		return $this->many( "SELECT * FROM $this->table WHERE parent_project_id = %d ORDER BY active DESC, id ASC", $this->id );
	}
	
	function top_level() {
		return $this->many( "SELECT * FROM $this->table WHERE parent_project_id IS NULL ORDER BY name ASC" );
	}

	// Triggers
	
	function after_save() {
		// TODO: pass the update args to after/pre_save?		
		// TODO: only call it if the slug or parent project were changed
		return !is_null( $this->update_path() );
	}
	
	function after_create() {
		// TODO: pass some args to pre/after_create?
		if ( is_null( $this->update_path() ) ) return false;
	}

	// Field handling

	function normalize_fields( $args ) {
		$args = (array)$args;
		if ( isset( $args['parent_project_id'] ) ) {
			$args['parent_project_id'] = $this->force_false_to_null( $args['parent_project_id'] );
		}
		if ( isset( $args['slug'] ) && !$args['slug'] ) {
			$args['slug'] = gp_sanitize_for_url( $args['name'] );
		}
		if ( ( isset( $args['path']) && !$args['path'] ) || !isset( $args['path'] ) || is_null( $args['path'] )) {
			unset( $args['path'] );
		}
		if ( isset( $args['active'] ) ) {
			if ( 'on' == $args['active'] ) $args['active'] = 1;
			if ( !$args['active'] ) $args['active'] = 0;
		}
		return $args;
	}

	// Helpers
	
	/**
	 * Updates this project's and its chidlren's paths, according to its current slug.
	 */
	function update_path() {
		global $gpdb;
		$old_path = isset( $this->path )? $this->path : '';
		$parent_project = $this->get( $this->parent_project_id );
		if ( $parent_project )
			$path = gp_url_join( $parent_project->path, $this->slug );
		elseif ( !$gpdb->last_error )
			$path = $this->slug;
		else
			return null;
		$this->path = $path;
		$res_self = $this->update( array( 'path' => $path ) );
		if ( is_null( $res_self ) ) return $res_self;
		// update children's paths, too
		if ( $old_path ) {
			$query = "UPDATE $this->table SET path = CONCAT(%s, SUBSTRING(path, %d)) WHERE path LIKE %s";
			return $this->query( $query, $path, strlen($old_path) + 1, like_escape( $old_path).'%' );
		} else {
			return $res_self;
		}
	}
	
	/**
	 * Regenrate the paths of all projects from its parents slugs
	 */
	function regenerate_paths( $parent_project_id = null ) {
		// TODO: do it with one query. Use the tree generation code from GP_Route_Main::_options_from_projects()
		if ( $parent_project_id ) {
			$parent_project = $this->get( $parent_project_id );
			$path = $parent_project->path;
		} else {
			$path = '';
			$parent_project_id = null;
		}
		$projects = $this->find( array( 'parent_project_id' => $parent_project_id ) );
		foreach( (array)$projects as $project ) {
			$project->update( array( 'path' => gp_url_join( $path, $project->slug ) ) );
			$this->regenerate_paths( $project->id );
		}
	}
	
	function source_url( $file, $line ) {
		if ( $this->source_url_template() ) {
			return str_replace( array('%file%', '%line%'), array($file, $line), $this->source_url_template() );
		}
		return false;
	}
	
	function source_url_template() {
		if ( isset( $this->user_source_url_template ) )
			return $this->user_source_url_template;
		else {
			if ( $this->id && GP::$user->logged_in() && ($templates = GP::$user->current()->get_meta( 'source_url_templates' ))
					 && isset( $templates[$this->id] ) ) {
				$this->user_source_url_template = $templates[$this->id];
				return $this->user_source_url_template;
			} else {
				return $this->source_url_template;
			}
		}
	}
	
	/**
	 * Gives an array of project objects starting from the current project
	 * then its parent, its parent and up to the root
	 * 
	 * @todo Cache the results. Invalidation is tricky, because on each project update we need to invalidate the cache
	 * for all of its children.
	 * 
	 * @return array
	 */
	function path_to_root() {
		$path = array();
		if ( $this->parent_project_id ) {
			$parent_project = $this->get( $this->parent_project_id );
			$path = $parent_project->path_to_root();
		}
		return array_merge( array( &$this ), $path );
	}
	
	function set_difference_from( $other_project ) {
		$this_sets = (array)GP::$translation_set->by_project_id( $this->id );
		$other_sets = (array)GP::$translation_set->by_project_id( $other_project->id );
		$added = array();
		$removed = array();
		foreach( $other_sets as $other_set ) {
			$vars = array( 'locale' => $other_set->locale, 'slug' => $other_set->slug );
			if ( !gp_array_any( lambda('$set', '$set->locale == $locale && $set->slug == $slug', $vars ), $this_sets ) ) {
				$added[] = $other_set;
			}
		}
		foreach( $this_sets as $this_set ) {
			$vars = array( 'locale' => $this_set->locale, 'slug' => $this_set->slug );
			if ( !gp_array_any( lambda('$set', '$set->locale == $locale && $set->slug == $slug', $vars ), $other_sets ) ) {
				$removed[] = $this_set;
			}
		}
		return compact( 'added', 'removed' );
	}

	function sets_by_locale($locale) {
		$sets_table = GP::$translation_set->table;
		return $this->many("SELECT DISTINCT p.id, p.path, p.name, %1\$s AS `locale` FROM $this->table p LEFT JOIN $sets_table s ON p.id = s.project_id WHERE s.locale = %1\$s;", $locale);
	}

	function sets_by_slug($slug) {
		$sets_table = GP::$translation_set->table;
		return $this->many("SELECT DISTINCT p.id, p.path, p.name, %1\$s AS `slug` FROM $this->table p LEFT JOIN $sets_table s ON p.id = s.project_id WHERE s.slug = %1\$s;", $slug);
	}

	function sets_by_both($locale, $slug) {
		$sets_table = GP::$translation_set->table;
		return $this->many("SELECT DISTINCT p.id, p.path, p.name, %1\$s AS `locale`, %2\$s AS `slug` FROM $this->table p LEFT JOIN $sets_table s ON p.id = s.project_id WHERE s.locale = %1\$s AND s.slug = %2\$s;", $locale, $slug);
	}
	
	/* counter functionality from GP_Translation_Set */
	function waiting_count($locale, $slug) {
		if ( !$locale && !$slug ) return;
		if ( !isset( $this->waiting_count[$locale . $slug] ) ) $this->update_status_breakdown($locale, $slug);
		return $this->waiting_count[$locale . $slug];
	}
	
	function untranslated_count($locale, $slug) {
		if ( !$locale && !$slug ) return;
		if ( !isset( $this->untranslated_count[$locale . $slug] ) ) $this->update_status_breakdown($locale, $slug);
		return $this->untranslated_count[$locale . $slug];
	}
	
	function current_count($locale, $slug) {
		if ( !$locale && !$slug ) return;
		if ( !isset( $this->current_count[$locale . $slug] ) ) $this->update_status_breakdown($locale, $slug);
		return $this->current_count[$locale . $slug];
	}

	function warnings_count($locale, $slug) {
		if ( !$locale && !$slug ) return;
		if ( !isset( $this->warnings_count[$locale . $slug] ) ) $this->update_status_breakdown($locale, $slug);
		return $this->warnings_count[$locale . $slug];
	}

	function all_count() {
		return GP::$original->count_by_project_id( $this->id );
		// This does not work - it shifts the number by one digit at every iteration (?!?!)
//		if ( !isset( $this->all_count ) ) $this->all_count = GP::$original->count_by_project_id( $this->id );
//		return $this->all_count;
	}

	function percent_translated($locale, $slug) {
		$original_count = $this->all_count();
		// This does not work - it shifts the number by one digit at every call (?!?!)
//		return sprintf( _x( '%d%%', 'language translation percent' ), $original_count ? $this->current_count($locale, $slug) / $original_count * 100 : 0 );
		return sprintf( _x( '%d%%', 'language translation percent' ), $original_count ? $this->current_count / $original_count * 100 : 0 );
	}

	function update_status_breakdown($locale, $slug) {
		$counts = wp_cache_get($this->id . $locale . $slug, 'translation_bundle_status_breakdown');
		if ( !is_array( $counts ) ) {
			/*
			 * TODO:
			 *  - calculate weighted coefficient by priority to know how much of the strings are translated
			 * 	- calculate untranslated
			 */
			$t = GP::$translation->table;
			$s = GP::$translation_set->table;
			$o = GP::$original->table;

			$counts_sql1 = "
				SELECT t.status as translation_status, COUNT(*) as n
				FROM $t AS t INNER JOIN $o AS o ON t.original_id = o.id LEFT JOIN $s s ON t.translation_set_id = s.id
				WHERE s.project_id = %d AND ";
			$counts_sql2 = " AND o.status LIKE '+%%' GROUP BY t.status";
			$warning_count_sql1 = "
				SELECT COUNT(*)
				FROM $t AS t INNER JOIN $o AS o ON t.original_id = o.id LEFT JOIN $s s ON t.translation_set_id = s.id
				WHERE s.project_id = %d AND ";
			$warning_count_sql2 = " AND o.status LIKE '+%%' AND (t.status = 'current' OR t.status = 'waiting') AND warnings IS NOT NULL";
			if ( $locale && $slug ) {
				$counts_sql = "$counts_sql1 s.locale = %s AND s.slug = %s $counts_sql2";
				$warning_count_sql = "$warning_count_sql1 s.locale = %s AND s.slug = %s $warning_count_sql2";
				$counts = GP::$translation->many_no_map($counts_sql, $this->id, $locale, $slug);
				$warnings_count = GP::$translation->value_no_map($warning_count_sql, $this->id, $locale, $slug);
			} else if ( $locale ) {
				$counts_sql = "$counts_sql1 s.locale = %s $counts_sql2";
				$warning_count_sql = "$warning_count_sql1 s.locale = %s $warning_count_sql2";
				$counts = GP::$translation->many_no_map($counts_sql, $this->id, $locale);
				$warnings_count = GP::$translation->value_no_map($warning_count_sql, $this->id, $locale);
			} else if ( $slug ) {
				$counts_sql = "$counts_sql1 s.slug = %s $counts_sql2";
				$warning_count_sql = "$warning_count_sql1 s.slug = %s $warning_count_sql2";
				$counts = GP::$translation->many_no_map($counts_sql, $this->id, $slug);
				$warnings_count = GP::$translation->value_no_map($warning_count_sql, $this->id, $slug);
			}
			$counts[] = (object)array( 'translation_status' => 'warnings', 'n' => $warnings_count );
			$counts[] = (object)array( 'translation_status' => 'all', 'n' => $this->all_count() );
			wp_cache_set($this->id . $locale . $slug, $counts, 'translation_bundle_status_breakdown');
		}
		$statuses = GP::$translation->get_static( 'statuses' );
		$statuses[] = 'warnings';
		$statuses[] = 'all';
		foreach( $statuses as $status ) {
			$this->{$status.'_count'}[$locale . $slug] = 0;
		}
		$this->untranslated_count[$locale . $slug] = 0;
		foreach( $counts as $count ) {
			if ( in_array( $count->translation_status, $statuses ) ) {
				$this->{$count->translation_status.'_count'}[$locale . $slug] = $count->n;
			}
		}
		$this->untranslated_count[$locale . $slug] = $this->all_count() - $this->current_count[$locale . $slug];
	}

}
GP::$project = new GP_Project();