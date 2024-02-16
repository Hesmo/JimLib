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
/**
 * G�n�re une balise <tr> HTML avec des attributs et des donn�es facultatifs.
 *
 * Cette fonction prend en charge plusieurs param�tres facultatifs :
 *  - $class : la classe CSS de la balise <tr>.
 *  - $style : le style CSS de la balise <tr>.
 *  - $retour : un indicateur de retour. Si d�fini sur 1, la balise est retourn�e ; sinon, elle est affich�e directement.
 *  - $idligne : l'ID de la balise <tr>.
 *  - $action : l'action associ�e � la balise <tr>.
 *  - $dataAttributes : un tableau associatif des attributs de donn�es (data-*).
 * 
 * @return string|null La balise <tr> si $retour est 1, null sinon.
 */
function TB_ligne() {
    // Fonction d'�chappement HTML avec l'encodage ISO-8859-1
    $escapeHtml = function ($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'ISO-8859-1');
    };

    $ar_arg = func_get_args();

    $class  = isset($ar_arg[0]) ? $escapeHtml($ar_arg[0]) : '';
    $style  = isset($ar_arg[1]) ? $escapeHtml($ar_arg[1]) : '';
    $retour = isset($ar_arg[2]) ? $ar_arg[2] : 0;
    $idligne = isset($ar_arg[3]) ? $escapeHtml($ar_arg[3]) : '';
    $action = isset($ar_arg[4]) ? $escapeHtml($ar_arg[4]) : '';
    $dataAttributes = isset($ar_arg[5]) && is_array($ar_arg[5]) ? $ar_arg[5] : array();

    $element = "<tr";
    if (!empty($idligne)) { 
        $element .= " id=\"$idligne\""; 
    }
    if (!empty($class)) { 
        $element .= " class=\"$class\""; 
    }
    if (!empty($style)) { 
        $element .= " style=\"$style\""; 
    }
    if (!empty($action)) { 
        $element .= " $action "; 
    }

    foreach ($dataAttributes as $key => $value) {
        // �chappement des cl�s et valeurs des attributs de donn�es
        $escapedKey = $escapeHtml($key);
        $escapedValue = $escapeHtml($value);
        $element .= " data-" . $escapedKey . "=\"" . $escapedValue . "\"";
    }

    $element .= ">\n";
    
    if ($retour == 1) { 
        return $element; 
    } else { 
        echo $element; 
    }
}
function TB_ligne_fin(){ echo "</tr>\n"; }

/**
 * G�n�re une balise <td> ou <th> HTML avec des attributs et des donn�es facultatifs.
 *
 * Cette fonction prend en charge deux modes d'utilisation :
 * 1. Passer les param�tres via un tableau associatif.
 * 2. Passer les param�tres individuellement.
 * 
 * @param mixed $id       L'ID de la balise <td> ou <th>.
 * @param mixed $class    La classe CSS de la balise <td> ou <th>.
 * @param mixed $style    Le style CSS de la balise <td> ou <th>.
 * @param int   $colspan  Le nombre de colonnes que la cellule doit couvrir.
 * @param int   $rowspan  Le nombre de lignes que la cellule doit couvrir.
 * @param int   $retour   Indicateur de retour. Si 1, la balise est retourn�e ; sinon, elle est affich�e directement.
 * @param string $dujava  Attribut de donn�es sp�cifique.
 * @param string $th      Type de balise � g�n�rer, 'th' pour une cellule d'en-t�te, 'td' par d�faut.
 * @param array  $dataAttributes Tableau associatif des attributs de donn�es (data-*).
 * 
 * @return string|null La balise <td> ou <th> si $retour est 1, null sinon.
 */
function TB_cellule() {
    
	$ar_arg = func_get_args();

    // V�rifier si les param�tres sont pass�s via un tableau associatif
    if (is_array($ar_arg[0]) && count($ar_arg) == 1) {
        $arg = $ar_arg[0];
        $retour = 0;
        $th = isset($arg['entete']) && $arg['entete'] === 'th' ? 'th' : 'td'; // V�rifier si la cellule doit �tre une cellule d'en-t�te
        
		$element = "<$th ";
        foreach ($arg as $key => $value) {
            switch ($key) {
                case 'entete': break;
                case 'retour': break;
                case 'action': $element .= " $value "; break;
                default:
                    $element .= " $key=\"" . htmlspecialchars($value, ENT_QUOTES, 'ISO-8859-1') . "\" ";
                break;
            }
        }
    } else {
        // Si les param�tres sont pass�s individuellement
        $id = isset($ar_arg[0]) ? htmlspecialchars($ar_arg[0], ENT_QUOTES, 'ISO-8859-1') : '';
        $class = isset($ar_arg[1]) ? htmlspecialchars($ar_arg[1], ENT_QUOTES, 'ISO-8859-1') : '';
        $style = isset($ar_arg[2]) ? htmlspecialchars($ar_arg[2], ENT_QUOTES, 'ISO-8859-1') : '';
        $colspan = isset($ar_arg[3]) ? $ar_arg[3] : '';
        $rowspan = isset($ar_arg[4]) ? $ar_arg[4] : '';
        $retour = isset($ar_arg[5]) ? $ar_arg[5] : 0;
        $dujava = isset($ar_arg[6]) ? htmlspecialchars($ar_arg[6], ENT_QUOTES, 'ISO-8859-1') : '';
		$th = isset($ar_arg[7]) && $ar_arg[7] === 'th' ? 'th' : 'td'; // V�rifier si la cellule doit �tre une cellule d'en-t�te

        $element = "<$th";
        if (!empty($id)) { $element .= " id=\"$id\""; }
        if (!empty($class)) { $element .= " class=\"$class\""; }
        if (!empty($style)) { $element .= " style=\"$style\""; }
        if (!empty($colspan)) { $element .= " colspan=\"$colspan\""; }
        if (!empty($rowspan)) { $element .= " rowspan=\"$rowspan\""; }
        if (!empty($dujava)) { $element .= " $dujava"; }

        if (isset($ar_arg[8]) && is_array($ar_arg[8])) {
            foreach ($ar_arg[8] as $clef => $value) {
                $element .= " data-" . htmlspecialchars($clef, ENT_QUOTES, 'ISO-8859-1') . "=\"" . htmlspecialchars($value, ENT_QUOTES, 'ISO-8859-1') . "\"";
            }
        }
    }
    $element .= ">";
    if ($retour == 1) { return $element; } else { echo $element; }
}
function OLDTB_cellule() {

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