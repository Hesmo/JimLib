<?Php
/**
 * Renvoie une balise html doctype et une balise head pour d�marrer une page HTML
 *
 */
function HTML5_doctype(){ 
	echo "<!doctype html>\n<html lang='fr'>\n<head>\n";
}
/**
 * Renvoie une balise html titre 
 *
 * @param string $titre Contenu de la balise titre
 */
function HTML5_title($titre){
	echo "<title>$titre</title>\n";
}
/**
 * Renvoie une balise html meta avec les valeurs sp�cifi�s.
 *
 * @param string $nom Nom de la balise meta
 * @param string $valeur Valeur de la balise meta
 */
function HTML5_meta($nom,$contenu){
	echo "<meta name=\"$nom\" content=\"$contenu\">";
}
/**
 * Renvoie une balise HTML <meta> avec l'attribut charset sp�cifi�.
 *
 * @param string $charset Le jeu de caract�res � utiliser.
 * 
 */
function HTML5_meta_charset($charset) {
    echo "<meta charset=\"$charset\">\n";
    
}
// La fonction ci-dessous � �t� ajout� pour prendre en charge l'ecriture de la balise meta 
// avec la forme name="nom" content="contenu"
// V�rifi� la spec w3c pour les balises de mobile et tablette
function HTML5_meta_content($nom,$valeur){
	echo "<meta name=\"$nom\" content=\"$valeur\">\n";
}
/**
 * Renvoie une balise HTML <link> avec les valeurs sp�cifi�es pour les attributs rel et href.
 *
 * @param string $rel La relation de la balise link.
 * @param string $href L'URL de destination de la balise link.
  */
function HTML5_headlink($rel, $href) {
    echo "<link rel=\"$rel\" href=\"$href\">\n";
}
/**
 * Ferme la balise <head> HTML.
 *
  */
function HTML5_head_off() {
    echo "</head>\n";
}
/**
 * Affiche la balise d'ouverture ou de fermeture <body> HTML en fonction de l'�tat sp�cifi�.
 *
 * @param bool $etat Indique si la balise d'ouverture (<body>) doit �tre affich�e (true) ou la balise de fermeture (false).
 * 
 */
function HTML5_body($etat) {
    if ($etat) {
        echo  "<body>\n";
    } else {
        echo "</body>\n";
    }
}
/**
 * G�n�re une balise HTML <nav> avec les attributs sp�cifi�s.
 *
 * @param string $id L'identifiant de la balise nav (optionnel).
 * @param string $class La classe CSS de la balise nav (optionnel).
 * @param string $style Le style CSS de la balise nav (optionnel).
 * @param string $title Le titre de la balise nav (optionnel).
 * @param bool $retour Indique si la balise nav doit �tre retourn�e (true) ou affich�e (false).
 * @return string|null La balise nav g�n�r�e si $retour est true, sinon rien.
 */
function HTML5_nav($id = '', $class = '', $style = '', $title = '', $retour = false) {
    $element = "<nav";
    
    if (trim($id) != "") {
        $element .= ' id="' . $id . '"';
    }
    if (trim($class) != "") {
        $element .= ' class="' . $class . '"';
    }
    if (trim($style) != "") {
        $element .= ' style="' . $style . '"';
    }
    if (trim($title) != "") {
        $element .= ' title="' . $title . '"';
    }
    
    $element .= ">\n";

    if ($retour) {
        return $element;
    } else {
        echo $element;
    }
}
/**
 * Ferme la balise <nav> HTML.
 *
 */
function HTML5_nav_off() {
    echo "</nav>\n";
}
/**
 * Cr�e et affiche ou retourne un lien HTML5 avec les attributs sp�cifi�s.
 *
 * @param int $retour Type de fonction : 0 pour afficher directement, 1 pour renvoyer la valeur (obligatoire).
 * @param string $typeanc Type de lien : "html" pour un lien HTML classique, "java" pour un lien JavaScript (obligatoire).
 * @param string $title Texte de l'infobulle (optionnel).
 * @param string $afficher Texte ou image � afficher pour le lien (obligatoire).
 * @param string $style Style CSS du lien (optionnel).
 * @param string $class Classe CSS du lien (optionnel).
 * @param string $nomscript Nom du script JavaScript � appeler (obligatoire si $typeanc est "java").
 * @param array $ar_param Param�tres du script JavaScript (obligatoire si $typeanc est "java").
 * @param string $balisedownload Attribut "download" du lien (optionnel).
 * @param string $idhref ID de l'�l�ment (optionnel).
 * @return string|bool Le lien HTML5 avec les attributs sp�cifi�s, si $retour est d�fini � 1, sinon true si il est affich� directement.
 */
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
		// Construit une balise HREF qui appel un script javascript
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
		// Construit une balise HREF qui fait un lien vers un fichier
		// La variable target d�finit la fenetre de destination, si target est vide alors la destination est _self sinon une nouvelle fenetre de navigateur s'ouvrira
		$url = $ar_arg[6];
		$target = $ar_arg[7];
		if (isset($ar_arg[8]) AND $ar_arg[8]!==""){  $balisedownload = "download=\"".$ar_arg[8]."\""; } else { $balisedownload="";}
		if (isset($ar_arg[9]) AND $ar_arg[9]!==""){  $idhref = "id=\"".$ar_arg[9]."\""; } else { $idhref="";}
		$box = "<a title=\"$title\" href=\"".$url."\"";
		if ($target!="") {$box=$box." ${balisedownload}  ${idhref} target=\"".$target."\"";} else {$box=$box." target=\"_self\"";}
	}

	if ($style!=""){$box.="style='".$style."'";}
	if ($class!=""){$box.="class='".$class."'";}
	$box.= ">".$afficher."</a>";
	if ($retour == 1) {return $box;} else {echo $box; return true;}
}
/**
 * Cr�e et affiche ou retourne une balise <div> HTML5 avec les attributs sp�cifi�s.
 *
 * @param string $id ID de l'�l�ment (optionnel).
 * @param string $class Classe de l'�l�ment (optionnel).
 * @param string $style Style CSS de l'�l�ment (optionnel).
 * @param string $position Position CSS de l'�l�ment (optionnel).
 * @param string $top Position top CSS de l'�l�ment (optionnel).
 * @param string $left Position left CSS de l'�l�ment (optionnel).
 * @param string $width Largeur CSS de l'�l�ment (optionnel).
 * @param string $height Hauteur CSS de l'�l�ment (optionnel).
 * @param string $overflow D�bordement CSS de l'�l�ment (optionnel).
 * @param string $action Action associ�e � l'�l�ment (optionnel).
 * @param int $retour Contr�le si la balise est retourn�e (1) ou affich�e directement (0) (optionnel).
 * @param string $title Titre de l'�l�ment (optionnel).
 * @return string|bool La balise <div> avec les attributs sp�cifi�s, si $retour est d�fini � 1, sinon true si elle est affich�e directement.
 */
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
	if (isset($ar_arg[11])){ $title = $ar_arg[11]; }

	$element = "<div ";
	if (trim($id)!="")		{$element .= "id=\"$id\" ";}
	if (trim($class)!="")	{$element .= "class=\"$class\" ";}
	if (trim($action)!= "") {$element .= "$action ";}
	
	if (trim($top)!="")     {$style .= "top:$top; ";}
	if (trim($left)!="")    {$style .= "left:$left; ";}
	if (trim($width)!="")   {$style .= "width:$width; ";}
	if (trim($height)!="")  {$style .= "height:$height; ";}
	if (trim($position)!=""){$style .= "position:$position; ";}

	/*
	visible // L'�l�ment sera agrandi de mani�re � ce que son contenu soit compl�tement visible dans tous les cas.
	hidden // L'�l�ment sera coup� s'il d�passe les limites.
	scroll // L'�l�ment sera coup� s'il d�passe les limites. Le navigateur WWW doit pourtant proposer des barres de d�filement, un peu comme dans une fen�tre cadre incorpor�e.
	auto // Le navigateur Web doit d�cider en cas de conflit, comment l'�l�ment sera affich�. Proposer des barres de d�filement est �galement permis.
	*/

	if (isset($title)!=""){ $element .= "title= \"$title\" ";}
	if (trim($overflow)!=""){ $style .= "overflow: $overflow; ";}
	if ($style!="")   { $element .= "style= \"$style\" ";}
	$element.=">\n";
	if ($retour==1) {return $element; } else {echo $element; return true; }
}
/**
 * G�n�re une balise de fin </div> HTML5.
 *
 * Cette fonction g�n�re une balise de fin </div> pour fermer un bloc div ouvert pr�c�demment.
 * Elle est utilis�e pour maintenir la structure correcte du code HTML g�n�r�.
 */
function HTML5_Div_off(){
	echo "</div>\n";
}
/**
 * Cr�e et affiche ou retourne un paragraphe HTML5 avec les attributs sp�cifi�s.
 *
 * @param string|array $id ID de l'�l�ment ou tableau associatif des attributs (optionnel).
 * @param string $style Style CSS de l'�l�ment (optionnel).
 * @param int $retour Contr�le si le paragraphe est retourn� (1) ou affich� directement (0) (obligatoire).
 * @param string $texte Texte � afficher dans le paragraphe (obligatoire).
 * @param string $action Action associ�e � l'�l�ment (optionnel).
 * @return string|bool Le paragraphe HTML5 avec les attributs sp�cifi�s, si $retour est d�fini � 1, sinon true s'il est affich� directement.
 */
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
	} else {
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
	if ($retour==1) {return $element; } else {echo $element; return true;}
	
}
/**
 * Cr�e et affiche ou retourne une balise <img> HTML5 avec les attributs sp�cifi�s.
 *
 * @param string $id ID de l'�l�ment (optionnel).
 * @param string $style Style CSS de l'�l�ment (optionnel).
 * @param int $retour Contr�le si la balise est retourn�e (1) ou affich�e directement (0) (obligatoire).
 * @param string $src URL de la source de l'image (obligatoire).
 * @param string $width Largeur de l'image (optionnel).
 * @param string $height Hauteur de l'image (optionnel).
 * @param string $alt Texte alternatif pour l'image (optionnel).
 * @param string $action Action associ�e � l'�l�ment (optionnel).
 * @param string $usemap Nom de la map utilis�e pour l'image (optionnel).
 * @param string $title Titre de l'image (optionnel).
 * @return string|bool La balise <img> HTML5 avec les attributs sp�cifi�s, si $retour est d�fini � 1, sinon true si elle est affich�e directement.
 */
function HTML5_Img() {
	
	$ar_arg = func_get_args();
	// Passage en mode objet
	if (isset($ar_arg[10])){
		if ($ar_arg[10]){
			$img = new CLS_HTML5_Img();
			$img->Set_CLS_HTML5_Img($ar_arg[0], $ar_arg[1], $ar_arg[3], $ar_arg[4], $ar_arg[5], $ar_arg[6], $ar_arg[7]);
			$img->Set_CLS_HTML5_Img_Element(false);
			return $img;
		}
	}

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
	if ($retour==1) { return $element; } else { echo $element; return true;}

}
/**
 * Repr�sente une balise <img> HTML5 avec ses attributs associ�s.
 */
class CLS_HTML5_Img {
    
    public $id, $style, $src, $width, $height, $alt, $data, $element, $classe;
    /**
     * D�finit les valeurs des attributs de la balise <img>.
     *
     * @param string $id L'identifiant de l'�l�ment <img>.
     * @param string $style Le style CSS � appliquer � l'�l�ment <img>.
     * @param string $src L'URL de la source de l'image.
     * @param string $width La largeur de l'image.
     * @param string $height La hauteur de l'image.
     * @param string $alt Le texte alternatif de l'image (en r�alit� le title).
     * @param string $data Les donn�es suppl�mentaires � inclure dans la balise <img>.
     */
	function Set_CLS_HTML5_Img($id, $style, $src, $width, $height, $alt, $data) {
       $this->id = $id;
       $this->style = $style;
       $this->src = $src;
       $this->width = $width;
       $this->height = $height;
       $this->alt = $alt; // contient le title
       $this->data = $data;
	}
	/**
     * G�n�re la balise <img> en utilisant les valeurs des attributs d�finies pr�c�demment.
     *
     * @param bool $view Indique si la balise doit �tre affich�e directement (true) ou stock�e dans la propri�t� $element (false).
     */
    function Set_CLS_HTML5_Img_Element($view){
		$this->element = "<img src=\"$this->src\"";
		if (trim($this->id)!= "")	  { $this->element .= " id=\"$this->id\""; }
		if (trim($this->classe)!= "") { $this->element .= " class=\"$this->classe\""; }
		if (trim($this->style)!= "")  { $this->element .= " style=\"$this->style\""; }
		if (trim($this->width)!= "")  { $this->element .= " width=".$this->width; }
		if (trim($this->height)!= "") { $this->element .= " height=".$this->height; }
		if (trim($this->alt)!= "") 	  { 
			$this->element .= " alt=\"$this->alt\""; 
		} else { $this->element .= " alt=\"-\""; }
		if (trim($this->data)!= "")   { $this->element .= " ".$this->data; }
		if (trim($this->alt))         { $this->element .= " title=\"$this->alt\""; }
		$this->element .= ">\n";
    	if ($view){ echo $this->element; }
    }

}
/**
 * G�n�re une balise <script> HTML5 pour inclure un fichier JavaScript.
 *
 * @param string $fichier Le chemin vers le fichier JavaScript � inclure.
 */
function HTML5_script($fichier){
	echo "<script src=\"$fichier\"></script>\n";
}
/**
 * G�n�re une balise <script> HTML5 pour inclure du code JavaScript.
 *
 * @param int $position La position de la balise dans le document HTML : 1 pour l'ouverture, 0 pour la fermeture.
 */
function HTML5_balise_java($position) {
    // V�rifie la position de la balise
    if ($position == 1) {
        // Si la position est 1, g�n�re la balise d'ouverture avec le type de script et les commentaires de d�but
        echo "<script type=\"text/javascript\">\n<!--\n";
    } else {
        // Sinon, g�n�re les commentaires de fin et la balise de fermeture
        echo "//-->\n</script>\n";
    }
}
/**
 * Envoie les en-t�tes HTTP pour emp�cher la mise en cache de la page.
 * Utile pour s'assurer que le contenu de la page est toujours rafra�chi et ne soit pas mis en cache par le navigateur.
 */
function HTML5_StopCache() {
	header('Pragma: no-cache');
	header('Cache-Control: no-cache, must-revalidate');
	// Sp�cifie que la page a expir� dans le pass� pour forcer son rechargement
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	// Indique l'heure de la derni�re modification de la page
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	
}
/**
 * G�n�re un commentaire HTML.
 *
 * @param string $commentaire Le texte du commentaire � inclure.
 */
function HTML5_comment($commentaire) {
    // Affiche un commentaire HTML avec le texte sp�cifi�
    echo "<!-- $commentaire -->";
}
/**
 * Genere des balises ouvrantes ou fermante pour du code css 
 * 
 * @param boolean $etat true ou false (Ouvre ou ferme)
 * 
 */
function HTML5_styleinline($etat){
	if ($etat){
			echo "          <style type=\"text/css\">\n             <!--";
	} else {
			echo "          -->\n           </style>\n";
	}
}
function HTML5_html_off(){
	echo "</html>";
}

?>