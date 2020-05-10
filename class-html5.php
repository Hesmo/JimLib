<?Php
class HTML5Img {
	
	public $Retour;
	public $src, $alt; // required attributes
	public $id, $classe, $style, $height, $width, $title, $action;

    function __construct($src,$alt) {
        if (trim($src)=="" OR trim($alt)==""){ die("Attribut 'src' et 'alt' obligatoire dans la classe HTML5Img"); }
        $this->src = $src; $this->alt = $alt;
    }

    function BuildRetour(){

		foreach ($this as $clef => $valeur) { $this->$clef = trim($valeur); }
		$elem  = "<img src=\"".$this->src."\" alt=\"".$this->alt."\"";
		if ($this->id!="") { $elem .= " id=\"".$this->id."\""; }
		if ($this->classe!="") { $elem .= " class=\"".$this->classe."\""; }
		if ($this->style!="") { $elem .= " style=\"".$this->style."\""; }
		if ($this->height!="") { $elem .= " height=".$this->height; }
		if ($this->width!="") { $elem .= " width=".$this->width; }
		if ($this->title!="") { $elem .= " title=\"".$this->title."\""; }
		if ($this->action!="") { $elem .= " ".$this->action; }
		$this->Retour = $elem." >";

    }
			
		
}		
?>