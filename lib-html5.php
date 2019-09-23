<?Php
function HTML5_doctype(){ 
	echo "<!doctype html>\n<html lang='fr'>\n<head>\n";
}
function HTML5_title($titre){
	echo "<title>$titre</title>\n";
}
function HTML5_meta($nom,$valeur){
	echo "<meta $nom=\"$valeur\">\n";
}
function HTML5_meta_charset($valeur){
	echo "<meta charset=\"$valeur\">\n";
}
// La fonction ci-dessous à été ajouté pour prendre en charge l'ecriture de la balise meta 
// avec la forme name="nom" content="contenu"
// Vérifié la spec w3c pour les balises de mobile et tablette
function HTML5_meta_content($nom,$valeur){
	echo "<meta name=\"$nom\" content=\"$valeur\">\n";
}
function HTML5_headlink($rel,$href){
	echo "<link rel=\"$rel\" href=\"$href\">\n";
}

function HTML5_head_off(){
	echo "</head>\n";
}
function HTML5_body($etat){
	if ($etat){ echo "<body>\n"; } else { echo "</body>\n"; }
}
function HTML5_html_off(){
	echo "</html>\n";
}
/*
id 	Specifies a unique id for an element
class 	Specifies one or more classnames for an element (refers to a class in a style sheet)
style 	Specifies an inline CSS style for an element
title 	Specifies extra information about an element


accesskey 	Specifies a shortcut key to activate/focus an element
contenteditable 	Specifies whether the content of an element is editable or not
contextmenu 	Specifies a context menu for an element. The context menu appears when a user right-clicks on the element
dir 	Specifies the text direction for the content in an element
draggable 	Specifies whether an element is draggable or not
dropzone 	Specifies whether the dragged data is copied, moved, or linked, when dropped
hidden 	Specifies that an element is not yet, or is no longer, relevant
lang 	Specifies the language of the element's content
spellcheck 	Specifies whether the element is to have its spelling and grammar checked or not
tabindex 	Specifies the tabbing order of an element
translate	Specifies whether an element's value are to be translated when the page is localized, or not.
*/
function HTML5_nav($id,$class,$style,$title,$retour){

	$element = "<nav ";
	if (trim($id)!=""){ $element.=' id="$id" '; }
	if (trim($class)!=""){ $element.=" class=\"".$class."\" "; }
	if (trim($style)!= "") {$element.=" style=\"".$style."\" ";}
	if (trim($title)!= "") {$element.=" title=\"".$title."\" ";}
	$element.=">\n";

	if ($retour==1) {return $element;} else {echo $element;}
}
function HTML5_nav_off(){
	echo "</nav>\n";
}
function HTML5_href(){
	
	$nbarg = func_num_args();
	$ar_arg = func_get_args();
	
	$retour   = $ar_arg[0]; // Type de fonction : 0 affiche directement, 1 renvoie la valeur
	$typeanc  = $ar_arg[1]; // Type de lien : html ou java
	$title    = $ar_arg[2]; // Bulle jaune d'aide
	$afficher = $ar_arg[3]; // Chaine a afficher pour le lien (Texte ou image)
	$style    = $ar_arg[4]; // Style du lien 
	$class    = $ar_arg[5]; // Classe du lien 
	
	if ($typeanc == "java"){
		$nomscript = $ar_arg[6];
		$ar_param  = $ar_arg[7];
		if (isset($ar_arg[8]) AND $ar_arg[8]!==""){  $balisedownload = "download=\"".$ar_arg[8]."\""; } else { $balisedownload="";}	
		if (isset($ar_arg[9]) AND $ar_arg[9]!==""){  $idhref = "id=\"".$ar_arg[9]."\""; } else { $idhref="";}
		$box =  "<a title=\"$title\" ${balisedownload} ${idhref} href=\"javascript:".$nomscript."(";
		if (is_array($ar_param)){
			$raccord = "'".implode("','",$ar_param)."'";
			$box .= $raccord;
			$box = str_replace("'event'","event",$box);
			$box = str_replace("'this.form'","this.form",$box);
		}
		$box = $box.')"';
	} else {
		$url = $ar_arg[6];
		$target = $ar_arg[7];
		if (isset($ar_arg[8]) AND $ar_arg[8]!==""){  $balisedownload = "download=\"".$ar_arg[8]."\""; } else { $balisedownload="";}
		if (isset($ar_arg[9]) AND $ar_arg[9]!==""){  $idhref = "id=\"".$ar_arg[9]."\""; } else { $idhref="";}
		$box = "<a title=\"$title\" href=\"".$url."\"";
		if ($target!="") {$box=$box." ${balisedownload}  ${idhref} target=\"".$target."\"";} else {$box=$box." target=\"_self\"";}
	}

	if ($style!=""){$box.="style='".$style."'";}
	if ($class!=""){$box.="class='".$class."'";}
	$box.= ">\n".$afficher."</a>";
	if ($retour == 1) {return $box;} else {echo $box;}
}
// A utiliser en dernier recour
function HTML5_Div() {

	$ar_arg = func_get_args();

	$id		  = $ar_arg[0];
	$class    = $ar_arg[1];
	$style 	  = $ar_arg[2];
	$position = $ar_arg[3];
	$top	  = $ar_arg[4];
	$left	  = $ar_arg[5];
	$width	  = $ar_arg[6];
	$height	  = $ar_arg[7];
	$overflow = $ar_arg[8];
	$action	  = $ar_arg[9];
	$retour	  = $ar_arg[10];

	$element = "<div ";
	if (trim($id)!="")		{$element .= " id=\"$id\" ";}
	if (trim($class)!="")	{$element .= " class=\"$class\" ";}
	if (trim($action)!= "") {$element .= " $action ";}
	
	if (trim($top)!="")     {$style .= " top:$top;";}
	if (trim($left)!="")    {$style .= " left:$left;";}
	if (trim($width)!="")   {$style .= " width:$width;";}
	if (trim($height)!="")  {$style .= " height:$height;";}
	if (trim($position)!=""){$style .= " position:$position;";}

	/*
	visible // L'élément sera agrandi de manière à ce que son contenu soit complètement visible dans tous les cas.
	hidden // L'élément sera coupé s'il dépasse les limites.
	scroll // L'élément sera coupé s'il dépasse les limites. Le navigateur WWW doit pourtant proposer des barres de défilement, un peu comme dans une fenêtre cadre incorporée.
	auto // Le navigateur Web doit décider en cas de conflit, comment l'élément sera affiché. Proposer des barres de défilement est également permis.
	*/

	if (trim($overflow)!=""){ $style .= " overflow: $overflow;";}
	if ($style!="")   { $element .= "style= \"$style\" ";}
	$element.=">\n";
	if ($retour==1) {return $element; } else {echo $element;}
}
function HTML5_Div_off(){
	echo "</div>\n";
}
function HTML5_P() {

	$ar_arg = func_get_args();

	if (is_array($ar_arg[0]) && count($ar_arg) == 1) {
		$arg = $ar_arg[0];
		$retour = 0;
		$element = "<p ";
		foreach ($arg as $key => $value) {
			switch ($key) {
				case 'retour': $retour = $value; break;
				case 'action': $element .= " $value "; break;
				case "texte": break;
				default: $element .= $key."= \"".$value."\" "; break;
			}
		}
		$element .= ">".$arg['texte'];
		$element .= "</p>";
	}
	else
	{
		$id		  = $ar_arg[0];
		$style 	  = $ar_arg[1];
		$retour	  = $ar_arg[2];
		$texte	  = $ar_arg[3];
		if (isset($ar_arg[4])){ $action = $ar_arg[4]; }
		
		$element = "<p ";
		if (trim($id)!="")		{$element.=" id=\"$id\" ";}
		if (trim($style)!= "") 	{$element.=" style=\"".$style."\" ";}
		if (isset($action)!= "") {$element.=" $action ";}
		$element.=">";
		if (trim($texte)!= "") 	{$element.=$texte."</p>";}
	}
	if ($retour==1) {return $element; } else {echo $element;}
	
}
function HTML5_Img() {
	
	$ar_arg = func_get_args();
	
	$id     = $ar_arg[0];
	$style  = $ar_arg[1];
	$retour = $ar_arg[2];
	$src    = $ar_arg[3];
	$width  = $ar_arg[4];
	$height = $ar_arg[5];
	$alt    = $ar_arg[6];
	$action = $ar_arg[7];
	$usemap = $ar_arg[8];
	if (isset($ar_arg[9])){$title=$ar_arg[9];}
	
	$element = "<img src=\"$src\"";
	if (trim($id)!= "")		{ $element .= " id=\"$id\""; }
	if (trim($style)!= "")	{ $element .= " style=\"$style\""; }
	if (trim($width)!= "") 	{ $element .= " width=".$width; }
	if (trim($height)!= "") { $element .= " height=".$height; }
	if (trim($alt)!= "") 	{ $element .= " alt=\"$alt\""; } else { $element .= " alt=\"-\""; }
	if (trim($action)!= "") { $element .= " ".$action; }
	if (trim($usemap)!= "") { $element .= " usemap=\"$usemap\""; }
	if (isset($title) AND trim($title)!="") { $element .= " title=\"$title\""; }
	$element .= ">\n";
	if ($retour==1) { return $element; } else {echo $element;}

}

function HTML5_script($fichier){
	echo "<script src=\"$fichier\"></script>\n";
}
function HTML5_balise_java($position){
	if ($position==1){ 
		echo "<script type=\"text/javascript\">\n<!--\n"; 
	} else {
		echo "//-->\n</script>\n";
	}
}
function HTML5_StopCache() {
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date dans le passé
}
function HTML5_comment($commentaire){
	echo "<!-- ${commentaire} -->";
}
?>