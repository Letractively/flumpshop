<?php

/**
*  Provides an interface for basic category data
*
*  This file is part of Flumpshop.
*
*  Flumpshop is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  Flumpshop is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
*
*
*  @Name Category.class.php
*  @Version 1.02
*  @author Lloyd Wallis <flump5281@gmail.com>
*  @copyright Copyright (c) 2009-2011, Lloyd Wallis
*  @package Flumpshop
*/

class Category {
	/**
	* @var int contains the unique ID of the category
	*/
	var $id = 0;
	/**
	* @var string The name of this category
	*/
	var $name = "Uncategorised";
	/**
	* @var string The full name of this category, including parent categories, e.g. Electronics -> Televisions. Not available if noparent is passed.
	*/
	var $fullName = "Uncategorised";
	/**
	* @var int The ID of the parent category, or 0 if there is no parent
	*/
	var $parent = 0;
	/**
	* @var string The full text description of this category
	*/
	var $description = "Details for this category are unavailable.";
	/**
	* @var string The full name of this category, with hyperlinks to parent categories. Not available if noparent is passed.
	*/
	var $breadcrumb = "<a href='javascript:void(0);'>Uncategorised</a>";
	/**
	* @var bool Whether the category is enabled, that is, whether it should appear on public lists.
	*/
	var $enabled = true;
	/**
	* @var int The weight of the category, defining where it appears in the main navigation bar.
	*/
	var $weight = 0;
	/**
	* @var string The category's keywords, used in meta tahs
	* @since 1.1
	*/
	var $keywords;
	/**
	 * @var bool Stores whether the category should have a 'search' box
	 * @since 1.02
	 */
	var $searchable = true;
	
	/**
	* Instantiates a Category object for the given ID
	* @since 1.0
	* @param int $id Required. The ID of the category.
	* @param string $params Optional. If set to noparent, improves speed by disabling fullname & breadcrumbs
	* It also means the category URL with rewrite will not include the full path
	* @return No return value.
	*/
	function Category($id,$params = "") {
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
			$this->keywords = $result['keywords'];
			$this->searchable = $result['searchable'];
			if (isset($result['enabled'])) $this->enabled = $result['enabled'];
		}
	}
	
	/**
	* Depreciated. Used in the original import system to import a category
	* @since 1.0
	* @param None.
	* @return bool Whether the import was a success
	*/
	function import() {
		global $dbConn;
		if (!isset($this->enabled)) $this->enabled = true;
		if ($dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE id='".$this->id."' LIMIT 1"))) {
			$query = "UPDATE `category` SET name='".$this->name."', description='".$this->description."', parent='".$this->parent."' WHERE id=".$this->id.", enabled='".intval($this->enabled)."' LIMIT 1";
		} else {
			$query = "INSERT INTO `category` (id,name,description,parent,enabled) VALUES (".$this->id.",'".$this->name."','".htmlentities($this->description,ENT_QUOTES)."',".$this->parent.",".intval($this->enabled).");";
		}
		return $dbConn->query($query);
	}
	
	/**
	* Returns the ID of the category
	* @since 1.0
	* @param None.
	* @return int The Category ID
	*/
	function getID() {
		return $this->id;
	}
	
	/**
	* Returns the name of the category
	* @since 1.0
	* @param None.
	* @return string The Category Name
	*/
	function getName() {
		return htmlentities($this->name);
	}
	
	/**
	* Returns the weight of the category
	* @since 1.0
	* @param No arguments.
	* @return integer The weight of the category
	*/
	function getWeight() {
		return $this->weight;
	}
	
	/**
	* Returns the full name of the category, including that of its parents. Disabled if noparent was applied
	* @since 1.0
	* @param None.
	* @return string The full name of the category
	*/
	function getFullName() {
		return htmlentities($this->fullName);
	}
	
	/**
	* Returns the description of the category
	* @since 1.0
	* @param None.
	* @return string The category description
	*/
	function getDescription() {
		return $this->description;
	}
	
	/**
	* Returns the ID of the Parent Category, if there is one
	* @since 1.0
	* @param None.
	* @return int The parent category id or 0 if it has no parent
	*/
	function getParent() {
		return $this->parent;
	}
	
	/**
	* Returns the absolute URL to the category page
	* @since 1.0
	* @param None.
	* @return string The category URL
	*/
	function getURL() {
		global $config;
		if ($config->getNode('server','rewrite')) {
			return $config->getNode('paths','root')."/category/".$this->id."/".htmlentities(str_replace(array(" -> "," "),array("/","_"),$this->fullName))."/";
		} else {
			return $config->getNode('paths','root')."/category/?id=".$this->id;
		}
	}
	
	/**
	* Get the breadcrumb path of the category.
	* @since 1.0
	* @param None.
	* @return string The category breadcrumb
	*/
	function getBreadcrumb() {
		return $this->breadcrumb;
	}
	
	/**
	* Returns an array of child category IDs
	* @since 1.0
	* @param bool $recursive Optional. Default true. Whether to search for children of child categories
	* @return array An array of category IDs
	*/
	function getChildren($recursive = true) {
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
	
	/**
	* Returns an array of feature IDs applied to this category
	* @since 1.0
	* @param bool $inherited Optional. Default true. Whether to include features inherited from parent
	* @return array An array of Feature IDs
	*/
	function getFeatures($inherited = true) {
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
	
	/**
	* Returns a list of products in the category
	* @since 1.1
	* @return array An array of Product IDs
	*/
	function getProducts() {
		global $dbConn;
		$result = $dbConn->query('SELECT itemid FROM item_category where catid='.$this->getID());
		
		$array = array();
		while ($row = $dbConn->fetch($result)) {
			$array[] = $row['itemid'];
		}
		return $array;
	}
	
	/**
	* Returns the category keywords
	* @since 1.1
	* @return string The category keywords
	*/
	function getKeywords() {
		return $this->keywords;
	}
	
	/**
	* Sets the category keywords
	* @since 1.1
	* @param string $keywords The category keywords
	*/
	function setKeywords($keywords) {
		global $dbConn;
		$this->keywords = $keywords;
		$keywords = str_replace('"','""',$keywords);
		$dbConn->query('UPDATE category SET keywords="'.$keywords.'" WHERE id='.$this->getID().' LIMIT 1');
	}
}
?>