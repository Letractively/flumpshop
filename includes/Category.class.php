<?php
/*
* =============================================================
*  Name        : Category.class.php
*  Description : Provides a single location for common category
*				 logic
*  Version     : 1.0
*
*  Copyright (c) 2009-2010 Lloyd Wallis. Licensed for use in
*  Flumpnet systems only. This code cannot be copied, used, or
*  otherwise reproduced in any way without the author's
*  permission. lloyd@theflump.com
* =============================================================
*/
class Category {
	var $id = 0;
	var $name = "Uncategorised";
	var $fullName = "Uncategorised";
	var $parent = 0;
	var $description = "Details for this category are unavailable.";
	var $breadcrumb = "<a href='javascript:void(0);'>Uncategorised</a>";
	var $enabled = true;
	var $weight = 0;
	
	function Category($id,$params = "") {
		/**
		* Instantiates a Category object for the given ID
		* @since 1.0
		* @param $id. Required. The ID of the category.
		* @param $params. Optional. If set to noparent, improves speed by disabling fullname & breadcrumbs
		* It also reduces means the category URL with rewrite will not include the full path
		* @return Category Object
		*/
		$params = strtolower($params);
		global $dbConn, $config;
		$this->id = $id;
		$this->name = $config->getNode('messages','defaultCategoryName');
		$this->descripiton = $config->getNode('messages','defaultCategoryDesc');
		$result = $dbConn->query("SELECT * FROM `category` WHERE id='$id' LIMIT 1");
		$result = $dbConn->fetch($result);
		if (is_array($result)) {
			$this->fullName = "";
			if (!strstr($params,"noparent")) {
				$this->breadcrumb = "";
				if ($result['parent'] != 0) {
					$temp = new Category($result['parent']);
					$this->fullName .= $temp->getFullName()." -> ";
					$this->breadcrumb .= $temp->getBreadcrumb()." -> ";
				}
			} else {
				$this->breadcrumb = "Error: noparent Enabled.";
			}
			$this->fullName .= $result['name'];
			$this->breadcrumb .= "<a href='".$this->getURL()."'>".$result['name']."</a>";
			$this->name = $result['name'];
			$this->description = $result['description'];
			$this->parent = $result['parent'];
			$this->weight = $result['weight'];
			if (isset($result['enabled'])) $this->enabled = $result['enabled'];
		}
	}
	
	function import() {
		/**
		* Depreciated. Used in the original import system to import a category
		* @since 1.0
		* @param None.
		* @return Whether the import was a success
		*/
		global $dbConn;
		if (!isset($this->enabled)) $this->enabled = true;
		if ($dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE id='".$this->id."' LIMIT 1"))) {
			$query = "UPDATE `category` SET name='".$this->name."', description='".$this->description."', parent='".$this->parent."' WHERE id=".$this->id.", enabled='".intval($this->enabled)."' LIMIT 1";
		} else {
			$query = "INSERT INTO `category` (id,name,description,parent,enabled) VALUES (".$this->id.",'".$this->name."','".htmlentities($this->description,ENT_QUOTES)."',".$this->parent.",".intval($this->enabled).");";
		}
		return $dbConn->query($query);
	}
	
	function getID() {
		/**
		* Returns the ID of the category
		* @since 1.0
		* @param None.
		* @return The Category ID (integer)
		*/
		return $this->id;
	}
	
	function getName() {
		/**
		* Returns the name of the category
		* @since 1.0
		* @param None.
		* @return The Category Name (string)
		*/
		return htmlentities($this->name);
	}
	
	function getWeight() {
		/**
		* Returns the weight of the category
		* @since 1.0
		* @param No arguments.
		* @return integer The weight of the category
		*/
		return $this->weight;
	}
	
	function getFullName() {
		/**
		* Returns the full name of the category, including that of its parents. Disabled if noparent was applied
		* @since 1.0
		* @param None.
		* @return The full name of the category (string)
		*/
		return htmlentities($this->fullName);
	}
	
	function getDescription() {
		/**
		* Returns the description of the category
		* @since 1.0
		* @param None.
		* @return The category description (string)
		*/
		return $this->description;
	}
	
	function getParent() {
		/**
		* Returns the ID of the Parent Category, if there is one
		* @since 1.0
		* @param None.
		* @return The parent category id or 0 (integer)
		*/
		return $this->parent;
	}
	
	function getURL() {
		/**
		* Returns the absolute URL to the category page
		* @since 1.0
		* @param None.
		* @return The category URL (string)
		*/
		global $config;
		if ($config->getNode('server','rewrite')) {
			return $config->getNode('paths','root')."/category/".$this->id."/".str_replace(array(" -> "," "),array("/","_"),$this->fullName)."/";
		} else {
			return $config->getNode('paths','root')."/category/?id=".$this->id;
		}
	}
	
	function getBreadcrumb() {
		/**
		* Depreciated. Used in the original import system to import a category
		* @since 1.0
		* @param None.
		* @return Whether the import was a success
		*/
		return $this->breadcrumb;
	}
	
	function getChildren($recursive = true) {
		/**
		* Returns an array of child category IDs
		* @since 1.0
		* @param $recursive. Optional. Default true. Whether to search for children recursively.
		* @return An array of category IDs
		*/
		global $dbConn;
		$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$this->id."'");
		$return = array();
		while ($row = $dbConn->fetch($result)) {
			$cat = new Category($row['id']);
			$return[] = $cat->getID();
			if ($recursive) array_merge($return,$cat->getChildren()); //Recursive
		}
		return $return;
	}
	
	function getFeatures($inherited = true) {
		/**
		* Returns an array of feature IDs applied to this category
		* @since 1.0
		* @param $inherited. Optional. Default true. Whether to include features inherited from parent
		* @return An array of Feature IDs
		*/
		global $dbConn;
		$features = array();
		$result = $dbConn->query("SELECT feature_id FROM `category_feature` WHERE category_id = ".$this->getID());
		while ($row = $dbConn->fetch($result)) $features[] = $row['feature_id'];
		
		unset($row,$result);
		
		if ($inherited && $this->getParent() != 0) {
			$parent = new Category($this->getParent(),"noparent");
			$inheritedFeatures = $parent->getFeatures();
			unset($parent);
			return array_merge($features,$inheritedFeatures);
		} else {
			return $features;
		}
	}
}
?>