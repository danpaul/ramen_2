<?php

require_once($GLOBALS['config']['models']. '/base.php');

class Taxonomy extends Eloquent
{
	//CATEGORIES
	const STATEMENT_INSERT_CATEGORY = 'INSERT INTO Categories(name, category_type, parent) VALUES (:name, :category_type, :parent)';
	
	const STATEMENT_SELECT_CATEGORY_BY_NAME = 'SELECT * FROM Categories WHERE name=:name AND category_type=:category_type LIMIT 1';
	const STATEMENT_SELECT_CATEGORY_BY_ID = 'SELECT * FROM Categories WHERE id=:id LIMIT 1';
	const STATEMENT_SELECT_ALL_CATEGORIES = 'SELECT * FROM Categories WHERE category_type=:category_type';
		
	const STATEMENT_UPDATE_CATEGORY_NAME = 'UPDATE Categories SET name=:new_name WHERE id=:id';
	const STATEMENT_UPDATE_CATEGORY_PARENT = 'UPDATE Categories SET parent=:new_parent WHERE id=:id';

	//TAGS
	const STATEMENT_DELETE_TAG = 'DELETE FROM Tags WHERE category=:category AND type=:type AND name=:name';	
	const STATEMENT_INSERT_TAG = 'INSERT INTO Tags(category, type, name) VALUES (:category, :type, :name)';
	const STATEMENT_SELECT_ALL_TAGS = 'SELECT * FROM Tags WHERE category=:category';
	const STATEMENT_SELECT_TAG_BY_NAME = 'SELECT * FROM Tags WHERE type=:type AND name=:name';
	const STATEMENT_SELECT_TAGS_BY_NAMES = 'SELECT id FROM Tags WHERE type=? AND name IN (???)';
	const STATEMENT_UPDATE_TAG_NAME = 'UPDATE Tags SET name=:new_name WHERE category=:category AND type=:type AND name=:name';
	
	public function __construct()
	{
		parent::__construct();
	}

/********************************************************************************

						CATEGORY

********************************************************************************/

	// http://stackoverflow.com/questions/4843945/php-tree-structure-for-categories-and-sub-categories-without-looping-a-query
	public function build_category_tree(&$categories) {

	    $map = array(
	        0 => array('subcategories' => array())
	    );

	    foreach ($categories as &$category) {
	        $category['subcategories'] = array();
	        $map[$category['id']] = &$category;
	    }

	    foreach ($categories as &$category) {
	    	if( $category['parent'] !== NULL)
	    	{
	    		$map[$category['parent']]['subcategories'][] = &$category;
	    	}	        
	    }
	    return $map[0]['subcategories'];
	}


	public function get_categories($category_type)
	{
		$params = array('category_type' => $category_type);
		$statement = $this->db->prepare(self::STATEMENT_SELECT_ALL_CATEGORIES);
		if(! ($statement->execute($params)) ){ return FALSE; }
		$categories = $statement->fetchAll();
		usort($categories, 'Taxonomy_model::category_list_sort');
		$this->build_category_tree($categories);
		return($categories);
	}

	/*
		Takes a category name and returns an array of category `id`s including
			the parent category.
	*/
	public function get_categories_by_name($category_type, $category_name)
	{
		$category = $this->fetch_category_by_name($category_type, $category_name);
		if( $category === FALSE ){ return FALSE; }
		$categories = $this->get_categories($category_type);
		$children = $this->find_category_children($categories, $category['id']);
		array_push($children, $category['id']);
		return $children;
	}

	/*
		Same as `get_categories_by_name` except takes an array of category names
	*/
	public function get_categories_by_names($category_type, $category_names)
	{
		$categories = array();
		if( !isset($category_names[0]) ){ return FALSE; }

		foreach ($category_names as $category_name)
		{
			$results = $this->get_categories_by_name($category_type, $category_name);
			if( !is_array($results) ){ $results = array(); }
			$categories = array_merge($categories, $results);
		}

		return array_unique($categories);
	}

	public function get_category_list($category_type)
	{
		$params = array('category_type' => $category_type);
		$statement = $this->db->prepare(self::STATEMENT_SELECT_ALL_CATEGORIES);
		if( !($statement->execute($params)) ) return FALSE;		
		$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
		usort($categories, 'Taxonomy_model::category_list_sort');
		return($categories);
	}

	public function add_category($name, $category_type, $parent = NULL)
	{

		$params = array('name' => $name, 
						'category_type' => $category_type, 
						'parent' => $parent);
		$statement = $this->db->prepare(self::STATEMENT_INSERT_CATEGORY);
		return $statement->execute($params);
	}

	public function delete_category($category_type, $id)
	{
		$categories = $this->get_categories($category_type);
		$to_delete = $this->find_category_children($categories, $id);
		array_push($to_delete, $id);
		$questionmarks = str_repeat("?,", count($to_delete)-1) . "?";
		$stmt = $this->db->prepare("DELETE FROM Categories WHERE id IN ($questionmarks)");
		if( !$stmt->execute($to_delete) ){ return FALSE; }
	}

	public function move_category($id, $new_parent)
	{
		//confirm `$new_parent` is not a child or self
		if( $new_parent === $id || $this->is_a_child_category($id, $new_parent))
		{
			return FALSE;
		}
		$statement = $this->db->prepare(self::STATEMENT_SELECT_CATEGORY_BY_ID);
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_CATEGORY_PARENT);
		return $statement->execute(array('id' => $id, 'new_parent' => $new_parent));
	}

	public function rename_category($id, $new_name)
	{
		$params = array('id' => $id, 'new_name' => $new_name);
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_CATEGORY_NAME);
		return $statement->execute($params);
	}

	/*
		Takes category type and and name and returns category record
	*/
	private function fetch_category_by_name($category_type, $name)
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_CATEGORY_BY_NAME);
		if( $statement->execute(array( 'name' => $name, 'category_type' => $category_type )) )
		{
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		return FALSE;
	}

	protected function is_a_child_category($parent, $child)
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_CATEGORY_BY_ID);
		$statement->execute(array('id' => $parent));
		$parent_row = $statement->fetch(PDO::FETCH_ASSOC);
		$category_type = $parent_row['category_type'];
		$category_tree = $this->get_categories($category_type);
		$children = $this->find_category_children($category_tree, $parent);
		if( in_array($child, $children) ){ return TRUE; }
		return FALSE;
	}

	protected static function category_list_sort($category_1, $category_2)
	{
		return strcmp($category_1['name'], $category_2['name']);
	}

	/*
		Takes a category tree (generated by `$this->get_categories()`)
			and retunrs an array of child ids. Returns an empty array if there
			are no children
	*/
	protected function find_category_children($category_tree, $id)
	{
		$children = array();
		foreach ($category_tree as $category) {
			//find match
			if( $category['parent'] === $id )
			{
				array_push($children, $category['id']);
				if( !empty($category['subcategories']) )
				{
					$children = array_merge($children, $this->get_child_ids($category['subcategories'][0]));
				}
			}
		}
		return $children;
	}

	protected function get_child_ids($category)
	{
		$ids = array();
		if( isset($category['id']) )
		{
			array_push($ids, $category['id']);
		}
		if( !empty($category['subcategories']) )
		{
			foreach ($category['subcategories'] as $subcategory)
			{
				array_push($ids, $subcategory['id']);
				if( !empty($subcategory['subcategories']) )
				{
					$ids = array_merge($ids, $this->get_child_ids($subcategory['subcategories'][0]));
				}			
			}
		}
		return $ids;
	}



/********************************************************************************

						TAGS

********************************************************************************/


	/*

		Takes: 
			`$tag_category`: a string representing the category of tags to return
		Returns:
			A sorted associative array of tags grouped by type or FALSE if 
				there is an error.
	*/
	public function get_tags($category)
	{
		$params = array('category' => $category);
		$statement = $this->db->prepare(self::STATEMENT_SELECT_ALL_TAGS);
		if(! ($statement->execute($params)) ){ return FALSE; }
		$tags = $statement->fetchAll(PDO::FETCH_ASSOC);
		usort($tags, 'Taxonomy_model::tag_sort');
		return($this->group_tags($tags));
	}


	/*

		Takes: 
			`$tag_category`: a string representing the category of tags to return
			`$tag_type`: the type of tag the new tag belongs to
			`tag_name`: the name of the new tag
		Returns:
			`TRUE` on success `FALSE` on error
	*/
	public function add_tag($category, $type, $name)
	{
		$params = array('category' => $category, 'type' => $type, 'name' => $name);
		$statement = $this->db->prepare(self::STATEMENT_INSERT_TAG);
		if($statement->execute($params)){ return TRUE; }
		return FALSE;
	}



	/*

		Takes: 
			`category`: a string representing the category of tags to delete
			`type`: the type of tag that is being deleted
			`old_name`: the name of tag to be deleted
		Returns:
			`TRUE` on success `FALSE` on error
	*/
	public function delete_tag($category, $type, $name)
	{
		$params = array('category' => $category, 'type' => $type, 'name' => $name);
		$statement = $this->db->prepare(self::STATEMENT_DELETE_TAG);
		if($statement->execute($params)){ return TRUE; }
		return FALSE;
	}


	/*

		Takes: 
			`category`: a string representing the category of tags to rename
			`type`: the type of tag that is being renamed
			`old_name`: the name of tag to be renamed
			`new_name`: the new name for the tag being renamed
		Returns:
			`TRUE` on success `FALSE` on error
	*/
	public function rename_tag($category, $type, $name, $new_name)
	{
		$params = array('category' => $category, 
						'type' => $type,
						'name' => $name,
						'new_name' => $new_name);
		$statement = $this->db->prepare(self::STATEMENT_UPDATE_TAG_NAME);
		return $statement->execute($params);
	}

	/*
		Takes tag type and name and returns the tag's record
	*/
	public function get_tag_by_name($type, $tag_name)
	{
		$statement = $this->db->prepare(self::STATEMENT_SELECT_TAG_BY_NAME);
		if( !$statement->execute(array('type' => $type, 'name' => $tag_name)) )
		{
			return FALSE;
		}
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	/*
		Takes an associative array where they type is the key and the value
			is an array of tag names within that type. Returns an array of tag_ids
	*/
	public function get_tags_by_names($types_tags)
	{		
		$tag_ids = array();

		foreach($types_tags as $type => $tags)
		{
			$statement = str_replace('???',
									 $this->generate_question_marks(count($tags)),
									 self::STATEMENT_SELECT_TAGS_BY_NAMES);
			$statement = $this->db->prepare($statement);
			array_unshift($tags, $type);
			$statement->execute($tags);
			$results = $statement->fetchAll(PDO::FETCH_COLUMN);

			if( $results )
			{ 
				$tag_ids = array_merge($tag_ids, $results);
			}
		}

		return array_unique($tag_ids);

	}

	// Groups tags by type
	protected function group_tags($tags)
	{
		$grouped_tags = array();
		foreach($tags as $tag)
		{
			$type = $tag['type'];
			if( !isset($grouped_tags[$type]) ){ $grouped_tags[$type] = array();	}
			array_push($grouped_tags[$type], $tag);
		}
		return $grouped_tags;
	}

	// Custom function for usort to sort tags returned by PDO as an associative
	//	 array
	protected static function tag_sort($tag_1, $tag_2)
	{
		if( $tag_1['type'] !== $tag_1['type'] )
		{
			return strcmp($tag_1['type'], $tag_2['type']);
		}
		return strcmp($tag_1['name'], $tag_2['name']);
	}

}