<?php
class Category {
	var $id = 0;
	var $name = "Uncategorised";
	var $fullName = "Uncategorised";
	var $parent = 0;
	var $description = "Details for this category are unavailable.";
	var $breadcrumb = "<a href='javascript:void(0);'>Uncategorised</a>";
	var $enabled = true;
	
	function Category($id,$params = "") {
		$params = strtolower($params);
		//Possible $params values: 'noparent' - disables breadcrumb & fullname features, but reduces queries
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
			if (isset($result['enabled'])) $this->enabled = $result['enabled'];
		}
	}
	
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
	
	function getID() {
		return $this->id;
	}
	
	function getName() {
		return htmlentities($this->name);
	}
	
	function getFullName() {
		return htmlentities($this->fullName);
	}
	
	function getDescription() {
		return $this->description;
	}
	
	function getParent() {
		return $this->parent;
	}
	
	function getURL() {
		global $config;
		if ($config->getNode('server','rewrite')) {
			return $config->getNode('paths','root')."/category/".$this->id."/".str_replace(array(" -> "," "),array("/","_"),$this->fullName)."/";
		} else {
			return $config->getNode('paths','root')."/category/?id=".$this->id;
		}
	}
	
	function getAjaxURL() {
		global $config;
		return $config->getNode('paths','root')."/category/ajax.php?id=".$this->id;
	}
	
	function getBreadcrumb() {
		return $this->breadcrumb;
	}
	
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
}
?>