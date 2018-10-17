<?Php
function TB_table() {

	$ar_arg = func_get_args();

	$id 	= $ar_arg[0];
	$class  = $ar_arg[1];
	$style  = $ar_arg[2];
	$retour = $ar_arg[3];

	$element = "<table ";
	if (trim($id)!= "") { $element .= " id=\"$id\""; }
	if (trim($class)!=""){ $element .= " class=\"$class\"";}
	if (trim($style)!="") 	 { $element .= " style=\"$style\"";}
	$element .= " >\n";
	if ($retour == 1) { return $element; } else { echo $element; }

}
function TB_head(){

	$ar_arg = func_get_args();
	$ouvre 	= $ar_arg[0];
	$retour = $ar_arg[1];
	
	if ($ouvre) { $element = "<thead>"; } else { $element = "</thead>"; }
	if ($retour) { return $element; } else { echo $element; }
	
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

	if (isset($ar_arg[0]) && is_array($ar_arg[0]) && count($ar_arg) == 1)
	{
		$arg = $ar_arg[0];

		if ($arg["ouvre"])
		{
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
		} 
		else { $element = "</tbody>"; }
	}
	else
	{
		$ouvre 	= $ar_arg[0];
		$retour = $ar_arg[1];
		if ($ouvre) { $element = "<tbody>"; } else { $element = "</tbody>"; }
	}
	
	if ($retour) { return $element; } else { echo $element; }
	
}
function TB_table_fin($class,$alignement,$nbcol,$texte){

	if ($nbcol>0){
		TB_ligne("","",0);
			TB_cellule("",$class,"",$nbcol,0,0);
				echo $texte;
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
	$element .= ">\n";
	
	if ($retour == 1) { return $element; } else { echo $element; }
}
function TB_ligne_fin(){ echo "</tr>\n"; }
function TB_cellule() {

	$ar_arg = func_get_args();

	//envoie des param via un tableau 
	if (is_array($ar_arg[0]) && count($ar_arg) == 1)
	{
		$arg = $ar_arg[0];

		$retour = 0;		if (isset($arg['entete']) && $arg['entete'] == 'th')
			$element = "<th ";
		else
			$element = "<td ";

		foreach ($arg as $key => $value) {
			switch ($key) {
				case 'entete':
					break;
				case 'action':
					$element .= " $value ";
					break;
				case 'retour':
					$retour = $value;
					break;
				default:
					$element .= " $key=\"$value\" ";
					break;
			}
		}
	}
	//envoie des param en ligne
	else
	{
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

	}
	
	$element .= ">";
	if ($retour == 1) { return $element; } else { echo $element; }
	
	
}
function TB_cellule_fin()
{
 	$ar_arg = func_get_args();
 	if (isset( $ar_arg[0])){echo "</th>\n"; }
 	else{ echo "</td>\n"; }

}
?>