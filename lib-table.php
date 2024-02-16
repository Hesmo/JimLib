<?Php
/**
 * G�n�re une balise de tableau HTML avec les attributs sp�cifi�s.
 *
 * @param string $id L'identifiant de la balise (optionnel).
 * @param string $class La classe de la balise (optionnel).
 * @param string $style Le style CSS de la balise (optionnel).
 * @param int $return Si la valeur est 1, la balise g�n�r�e sera retourn�e au lieu d'�tre affich�e (optionnel, par d�faut � 0).
 * @return string|null Si $return est d�fini � 1, retourne la balise g�n�r�e, sinon affiche la balise et retourne null.
 */
function TB_table() {

	$args = func_get_args();

    $id     = isset($args[0]) ? $args[0] : '';
    $class  = isset($args[1]) ? $args[1] : '';
    $style  = isset($args[2]) ? $args[2] : '';
    $return = isset($args[3]) ? $args[3] : 0;

    $element = "<table";
    if (!empty($id)) { 
        $element .= " id=\"$id\"";
    }
    if (!empty($class)) {
        $element .= " class=\"$class\"";
    }
    if (!empty($style)) {
        $element .= " style=\"$style\"";
    }
    $element .= ">\n";

    if ($return == 1) {
        return $element;
    } else {
        echo $element;
    }

}
/**
 * G�n�re une balise d'en-t�te de tableau HTML.
 *
 * @param bool $ouvre Indique s'il s'agit d'une balise d'ouverture (true) ou de fermeture (false).
 * @param bool $return Si la valeur est true, la balise g�n�r�e sera retourn�e au lieu d'�tre affich�e (optionnel, par d�faut � false).
 * @return string|null Si $return est true, retourne la balise g�n�r�e, sinon affiche la balise et retourne null.
 */
function TB_head() {
    $args = func_get_args();

    $ouvre  = isset($args[0]) ? $args[0] : true;
    $return = isset($args[1]) ? $args[1] : false;

    $element = $ouvre ? "<thead>" : "</thead>";

    if ($return) {
        return $element;
    } else {
        echo $element;
        return null;
    }
}
function TB_foot(){

	$ar_arg = func_get_args();
	$ouvre 	= $ar_arg[0];
	$retour = $ar_arg[1];
	
	if ($ouvre) { $element = "<tfoot>"; } else { $element = "</tfoot>"; }
	if ($retour) { return $element; } else { echo $element; }
	
}
function TB_body(){
	$ar_arg = func_get_args();

	if (isset($ar_arg[0]) && is_array($ar_arg[0]) && count($ar_arg) == 1) {
		$arg = $ar_arg[0];
		if ($arg["ouvre"]) {
			$element = "<tbody";
			foreach ($arg as $key => $value) {
				switch ($key) {
					case 'retour':
						$retour = $value;
						break;
					case 'ouvre':
						break;
					default:
						$element .= " $key=\"$value\" ";
						break;
				}
			}
			$element .=">";
		} else {
			$element = "</tbody>";
		}
	} else {
		$ouvre 	= $ar_arg[0];
		$retour = $ar_arg[1];
		if ($ouvre) { $element = "<tbody>"; } else { $element = "</tbody>"; }
	}
	
	if ($retour) { return $element; } else { echo $element; }
	
}
/**
 * Ferme un tableau HTML en ajoutant une balise de fermeture et affiche le texte sp�cifi�.
 *
 * @param string $class La classe CSS de la cellule (obligatoire).
 * @param string $alignement L'alignement du contenu de la cellule (obligatoire).
 * @param int $nbcol Le nombre de colonnes de la cellule (obligatoire).
 * @param string $texte Le texte � afficher dans la cellule (obligatoire).
 * @return void
 */
function TB_table_fin($class, $alignement, $nbcol, $texte) {
    
	if ($nbcol > 0) {
        TB_ligne("", "", 0);
        TB_cellule("", $class, $alignement, $nbcol, 0, 0);
        	echo htmlspecialchars($texte, ENT_QUOTES, 'ISO-8859-1');
        TB_cellule_fin();
        TB_ligne_fin();
    }
    echo "</table>\n";
}

function TB_ligne() {

	$ar_arg = func_get_args();

	$class 	= $ar_arg[0];
	$style  = $ar_arg[1];
	$retour = $ar_arg[2];
	if (isset($ar_arg[3])){ $idligne = $ar_arg[3]; } else { $idligne = ""; }
	if (isset($ar_arg[4])){ $action = $ar_arg[4]; } else { $action = ""; }

 	$element = "<tr";
 	if (trim($idligne)!="") { $element .= " id=\"$idligne\""; }
 	if (trim($class)!="") { $element .= " class=\"$class\""; }
	if (trim($style)!="") { $element .= " style=\"$style\""; }
	if (trim($action)!="") { $element .= " $action "; }

	if (isset($ar_arg[5]) and is_array($ar_arg['5'])){ 
		foreach ($ar_arg[5] as $clef=>$value){
			$element .= " data-".$clef."=\"".$value."\"";
		}
	}


	$element .= ">\n";
	
	if ($retour == 1) { return $element; } else { echo $element; }
}
function TB_ligne_fin(){ echo "</tr>\n"; }
function TB_cellule() {

	$ar_arg = func_get_args();

	//envoie des param via un tableau 
	if (is_array($ar_arg[0]) && count($ar_arg) == 1) {
		$arg = $ar_arg[0];
		$retour = 0;		
		if (isset($arg['entete']) && $arg['entete'] == 'th') {
			$element = "<th ";
		} else {
			$element = "<td ";
		}

		foreach ($arg as $key => $value) {
			switch ($key) {
				case 'entete': break;
				case 'action': $element .= " $value "; break;
				case 'retour': $retour = $value; break;
				default: $element .= " $key=\"$value\" "; break;
			}
		}

	} else {
		
		$id 	 = $ar_arg[0];
		$class 	 = $ar_arg[1];
		$style 	 = $ar_arg[2];
		$colspan = $ar_arg[3];
		$rowspan = $ar_arg[4];
		$retour  = $ar_arg[5];
		if (isset($ar_arg[6])){ $dujava = $ar_arg[6]; } else { $dujava = ""; }
		if (isset($ar_arg[7])){ $th = "th"; } else { $th = "td"; }

		$element = "<$th";
		if (trim($id) != "") 	{ $element .= " id=\"$id\""; }
		if (trim($class) != "") { $element .= " class=\"$class\""; }
		if (trim($style)!="") 	{ $element .= " style=\"$style\""; }
		if ($colspan > 0) 		{ $element .= " colspan=\"$colspan\""; }
		if ($rowspan > 0) 		{ $element .= " rowspan=\"$rowspan\""; }
		if (trim($dujava)!="") 	{ $element .= " $dujava"; }
		
		if (isset($ar_arg[8]) and is_array($ar_arg['8'])){ 
			foreach ($ar_arg[8] as $clef=>$value){
				$element .= " data-".$clef."=\"".$value."\"";
			}
		}

	}
	
	$element .= ">";
	if ($retour == 1) { return $element; } else { echo $element; }
	
}
function TB_cellule_fin() {
 	
 	$ar_arg = func_get_args();
 	if (isset( $ar_arg[0])){echo "</th>\n"; }
 	else{ echo "</td>\n"; }

}
?>