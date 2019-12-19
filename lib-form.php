<?Php
function FRM_form(){
	$nbarg = func_num_args();
	$ar_arg = func_get_args();

	$method = $ar_arg[0]; // GET ou POST, par défaut POST
	$name   = $ar_arg[1]; // Nom du formulaire
	$action = $ar_arg[2]; // Action par défaut pour submit
	$class  = $ar_arg[3]; // Classe du formulaire
	$target = $ar_arg[4]; // Cible après la soumission du formulaire
	$retour = $ar_arg[5]; // Affiche ou renvoi le résultat de la fonction
	
	if (isset($ar_arg[6])){
		$onsubmit = $ar_arg[6];
	}

	if (trim($method) == ""){ $method = "post"; }
	
	$element = "<form method=\"$method\"";
	if (trim($name) != "") {$element .= " name=\"$name\"";}
	if (trim($action) != "") {$element .= " action=\"$action\"";}
	if (trim($class) != "") {$element .= " class=\"$class\"";}
	if (trim($target) != "") {$element .= " target=\"$target\"";}
	if (isset($onsubmit)){ $element .= " ".$onsubmit." "; }	
	$element .= ">\n";
	if ($retour == 1) { return $element; } else { echo $element; }

}
function FRM_form_off(){ echo "</form>\n"; }
// Prototype : FRM_it($text,$class,$size,$max,$name,$valeur,$retour,$action,$style,$autocomplete,$type,$readonly);
function FRM_it(){

	$ar_arg = func_get_args();

	$text   = $ar_arg[0]; // Texte àffiché devant l'elememt
	$class  = $ar_arg[1]; // Nom de la classe (optionnel) 
	$size	= $ar_arg[2]; // Taille de l'élément  (optionnel)
	$max    = $ar_arg[3]; // Nombre de caractère maximum permis  (optionnel)
	$name   = $ar_arg[4]; // Nom java de l'élément  (optionnel)
 	$valeur = $ar_arg[5]; // Valeur de l'élément  (optionnel)
	$retour = $ar_arg[6]; // Renvoie la balise ou l'affiche directement
	$action = $ar_arg[7]; // Script associé  (optionnel)
	$style  = $ar_arg[8]; // Style 
	if (isset($ar_arg[9]) AND ($ar_arg[9] == 1)){ $autocomplete = "off"; }
	// Parametre ajouté voire http://www.w3schools.com/tags/att_input_type.asp
	if (isset($ar_arg[10]) AND (trim($ar_arg[10]) != "")){ 
		$element = "$text<input type=\"".$ar_arg[10]."\" ";
	} else {
		$element = "$text<input type=\"text\" ";
	}
	if (isset($ar_arg[11]) AND ($ar_arg[11] == 1)){ $element .= " readonly"; }
	if (isset($ar_arg[12]) AND ($ar_arg[12] != "")){ $placeholder = $ar_arg[12]; }
	
	if ($class!="")  { $element.= " class=\"$class\"";}
	if ($size!="") 	 { $element.= " size=\"$size\"";}
	if ($style!="")  { $element.= " style=\"$style\"";}
	if ($max!="") 	 { $element.= " maxlength=\"$max\"";}
	if ($name!="") 	 { $element.= " name=\"$name\"";}
	if ($valeur!="") { $element.= " value='".htmlspecialchars($valeur,ENT_QUOTES)."'";}  
	if ($action!="") { $element.= " $action";}
	if (isset($autocomplete)) { $element.= " autocomplete=\"off\"";}
	if (isset($placeholder)) { $element.= " placeholder=\"".$placeholder."\"";}

	$element .=">\n";
	if ($retour == 1) { return $element; } else { echo $element; }
	
}
function FRM_se() {
	
	$ar_arg = func_get_args();
	
	$name     = $ar_arg[0];
	$class    = $ar_arg[1];
	$size     = $ar_arg[2];
	$multiple = $ar_arg[3];
	$action   = $ar_arg[4];
	$retour   = $ar_arg[5];
	$style    = $ar_arg[6];
	if (isset($ar_arg[7]) AND $ar_arg[7] == 'disabled'){ $disabled = "disabled"; } else {$disabled="";}

	$element = "<select ";
	if ($disabled!="")		{$element.=" disabled ";}
	if ($name!="")		{$element.=" name='$name'";}
	if ($class!="")		{$element.=" class='$class'";}
	if ($size!="") 		{$element.=" size='$size'";}
	if ($style!="")     {$element.=" style='$style'";}
	if ($multiple==1)	{$element.=" multiple='multiple'";}
	if ($action!="")	{$element.=" ".$action;}
	$element.= ">";
	if ($retour == 1) { return $element; } else { echo $element; }

}
function FRM_se_fin(){ echo "</select>\n"; }
function FRM_opt() {
	
	$ar_arg = func_get_args();
	
	$class    	= $ar_arg[0];
	$valeur   	= $ar_arg[1];
	$selection	= $ar_arg[2];
	$texte		= $ar_arg[3];
	$retour   	= $ar_arg[4];
	$style    	= $ar_arg[5];
	if (isset($ar_arg[6])) { $data = $ar_arg[6]; }
	
	$element = "<option value=\"$valeur\"";
	if ($style!="")     {$element.= " style=\"$style\"";}
	if ($selection) {$element .=" selected=\"selected\" ";}
	if (trim($class)!=""){$element.=" class=\"".$class."\" ";}
	if (isset($ar_arg[6])) { $element.= $data." "; }
	$element.=">".$texte."&nbsp;&nbsp;</option>\n";
	if ($retour == 1) { return $element; } else { echo $element; }
}
/**
* Affiche un élément de formulaire de type input
* @param string $class
* @param string $type submit par défaut
* @param string $name 
* @param integer $valeur 
* @param integer $action 
*
* FRM_bt($class,$type,$name,$valeur,$action,$retour,$style,[$id],[$dsbld],[$data])
* 
*/
function FRM_bt() {

	$ar_arg = func_get_args();

	$class    	= $ar_arg[0];
	$type    	= $ar_arg[1];
	$name   	= $ar_arg[2];
	$valeur		= $ar_arg[3];
	$action		= $ar_arg[4];
	$retour   	= $ar_arg[5];
	$style    	= $ar_arg[6];
	if (isset($ar_arg[7])){ $id = $ar_arg[7]; } else { $id=""; }
	if (isset($ar_arg[8])){ $dsbld = $ar_arg[8]; } else { $dsbld = ""; }
	if (isset($ar_arg[9])){ $data = $ar_arg[9]; } else { $data = ""; }

	if (trim($type)=="")	{ $type="submit"; }
	if (trim($name)=="")	{ $name="boutton_soumission"; }
	if (trim($valeur)=="")	{ $valeur="Ok"; }
	$element = "<input";
	if (trim($id)!=""){ $element .= " id=\"$id\" "; }
	$element .= " type=\"$type\" class=\"$class\" name=\"$name\" value=\"$valeur\" ".$action." style=\"$style\" $dsbld $data>\n";
	if ($retour == 1) { return $element; } else { echo $element; }

}
function FRM_hidden() {

	$ar_arg = func_get_args();

	$name    	= $ar_arg[0];
	$valeur    	= $ar_arg[1];
	$retour   	= $ar_arg[2];
	if (isset($ar_arg[3])){ $id = $ar_arg[3]; } else { $id=""; }


	$element = "<input type=\"hidden\"";
	if (trim($id)!=""){ $element .= " id=\"$id\" "; }
	
	$element .= "name=\"$name\" value=\"$valeur\"/>\n";
	if ($retour == 1) { return $element; } else { echo $element; }
}
function FRM_pword(){
	
	$ar_arg = func_get_args();
	$class 	= $ar_arg[0];
	$name	= $ar_arg[1];
	$valeur	= $ar_arg[2];
	$taille	= $ar_arg[3];
	$style	= $ar_arg[4];
	$retour	= $ar_arg[5];
	if (isset($ar_arg[6])){$action = $ar_arg[6];}else{$action="";}


	$element = "<input type=\"password\"";
	if ($style!="") {$element .=" style=\"$style\"";}
	if ($class!="")	{$element .=" class=\"$class\""; }
	if ($name!="")	{$element .=" name=\"$name\"";  }
	if ($valeur!=""){$element .=" valeur=\"$valeur\"";}
	if ($taille!=""){$element .=" size=\"$taille\"";}
	if ($action!=""){$element .=" $action";}
	
	$element .= "/>\n";

	if ($retour == 1) {return $element;} else {echo $element;}
}
function FRM_cb() {

	$ar_arg = func_get_args();
	
	$class   = $ar_arg[0];
	$style   = $ar_arg[1];
	$name	 = $ar_arg[2];
	$checked = $ar_arg[3];
	$text	 = $ar_arg[4];
	$action	 = $ar_arg[5];
	$retour	 = $ar_arg[6];
	if (isset($ar_arg[7])){$valeur=$ar_arg[7];}

	$element = "<input type=\"checkbox\"";
	if ($class!="")	{$element .=" class=\"$class\""; }
	if ($style!="") {$element .=" style=\"$style\"";}
	if ($name!="")	{$element .=" name=\"$name\"";}
	if ($checked==1){$element .= "checked";}
	if ($action!=""){$element .=" ".$action;}
	if (isset($valeur)){$element .="  value=\"$valeur\"";}
	if (isset($ar_arg[8]) AND ($ar_arg[8] == 1)){ $element .= "onclick='return false;'"; } // Equivalent de readonly
	$element .= "/>".$text."\n";
	if ($retour == 1) { return $element; } else { echo $element; }

}
function FRM_ir() {
	
	$ar_arg = func_get_args();

	$class   = $ar_arg[0];
	$style   = $ar_arg[1];
	$name	 = $ar_arg[2];
	$checked = $ar_arg[3];
	$value	 = $ar_arg[4];
	$text	 = $ar_arg[5];
	$action	 = $ar_arg[6];
	$retour	 = $ar_arg[7];
	$id		 = $ar_arg[8];

	$element = "<input type=\"radio\"";
	if ($class!="")	{$element .=" class=\"$class\""; }
	if ($style!="") {$element .=" style=\"$style\"";}
	if ($name!="")	{$element .=" name=\"$name\"";}
	if ($value!="")	{$element .=" value=\"$value\"";}
	if ($checked==1){$element .= " checked";}
	if (trim($action)!=""){$element .= " ".$action; }
	if (trim($id)!=""){ $element .= " id=\"$id\""; }
	
	$element .= ">".$text."\n";
	if ($retour == 1) { return $element; } else { echo $element; }
}
function FRM_ta() {

	$ar_arg = func_get_args();

	$style 	 = $ar_arg[0]; 
	$name 	 = $ar_arg[1]; 
	$class 	 = $ar_arg[2];
	$rows 	 = $ar_arg[3];
	$cols 	 = $ar_arg[4];
	$contenu = $ar_arg[5];
	$retour  = $ar_arg[6];
	$action  = $ar_arg[7];
	
	$element = "<textarea";
	if (isset($ar_arg[8]) AND ($ar_arg[8] == 1)){ $element .= " readonly"; }
	if (trim($style)!="") $element.= " style=\"$style\"";
	if (trim($name)!="")  $element.= " name=\"$name\"";
	if (trim($class)!="") $element.= " class=\"$class\"";
	if (trim($rows)!="")  $element.= " rows=\"$rows\"";
	if (trim($cols)!="")  $element.= " cols=\"$cols\"";
	if (trim($action)!="") $element.= " $action";
	$element .= ">".$contenu."</textarea>\n";
	
	if ($retour == 1) { return $element; } else { echo $element; }
}
/**
* Affiche un Formulaire de téléchargement de fichier
* @param string $ar_param['name']
* @param string $ar_param['action']
* @param string $ar_param['class']
* @param string $ar_param['target']
* @param string $ar_param['idfile']
* @param string $ar_param['namefile']
* @param integer $ar_param['multiple']  0 ou 1
* @param string $ar_param['idbutton']
* @param string $ar_param['valuebutton'] // Si vide autorempli
* @param integer $ar_param['retour']
* @param string $ar_param['stylebtinput']  // Si vide autorempli
* @param string $ar_param['stylebtbutton'] // Si vide autorempli
* @param string $ar_param['maxfilesize']

*
* FRM_upload($name,$action,$class,$target,$idfile,$namefile,$multiple,$idbutton,$valuebutton,$stylebtinput,$stylebtbutton, $maxfilesize, $retour)
* 
*/
function FRM_upload($ar_param) {

	$ar_param['stylebtinput']="";
	/*if (!isset($ar_param['stylebtinput'])){
		$ar_param['stylebtinput'] = "margin: 5px; padding: 5px; background: white; border: 1px solid black; color: black; font-size: 100%; text-shadow: 2px 2px #cacaca; box-shadow: 2px 2px 2px #000000; text-align:center; height:38px; ";
	}*/

	$element = "<form method=\"post\" enctype=\"multipart/form-data\"";
	if (isset($ar_param['name'])){ $element .= " name=\"".$ar_param['name']."\""; }
	if (isset($ar_param['action'])){ $element .= " action=\"".$ar_param['action']."\""; }
	if (isset($ar_param['class'])){ $element .= " class=\"".$ar_param['class']."\""; }
	if (isset($ar_param['target'])){ $element .= " target=\"".$ar_param['target']."\""; }
	$element .= ">\n";

	if (!isset($ar_param['idfile'])){ echo "Erreur, manque le parametre idfile"; exit(); }
	if (!isset($ar_param['namefile'])){ echo "Erreur, manque le parametre namefile"; exit(); }
	$element .= "<input type=\"file\" id=\"".$ar_param['idfile']."\" name=\"".$ar_param['namefile']."\"";
	if (isset($ar_param['multiple']) AND $ar_param['multiple']){ $element .= " multiple/ "; }
	$element .= "style=\"".$ar_param['stylebtinput']."\"";
	$element .= ">\n";

	$element .= FRM_hidden("max_file_size",$ar_param['maxfilesize'],1);


	$ar_param['stylebtbutton']="";
	/*if (!isset($ar_param['stylebtbutton'])){
		$ar_param['stylebtbutton'] = "margin: 5px; padding: 5px; background: grey; border: 1px solid black; color: white; font-size: 100%; text-shadow: 2px 2px black; box-shadow: 2px 2px 2px #000000; text-align:center; height:38px; float:right; ";
	}*/

	if (!isset($ar_param['idbutton'])){ echo "Erreur, manque le parametre idbutton"; exit(); }
	$element .= "<br/><input class=\"btflat\" type=\"submit\" id=\"".$ar_param['idbutton']."\" ";
	$element .= "id=\"".$ar_param['idbutton']."\" name=\"btsubmitfile\" ";
	$element .= "value=\"".$ar_param['valuebutton']."\" ";
	$element .= "style=\"float: right; margin-top:15px;\">";
	

	if (isset($ar_param['retour'])){
		if ($ar_param['retour']==1){ return $element; }
	} 
	echo $element;

}
/*
function FRM_fieldset($OnOff,$Legend,$Affiche,$Style){
	if ($OnOff) { 
		$element = "<fieldset style=\"$Style\">\n"; 
		if (trim($Legend)!=""){ $element.= "<legend>&nbsp;$Legend&nbsp;</legend>\n"; }
	} else { 
		$element = "</fieldset>\n"; 
	}
	if ($Affiche) { echo $element; } else { return $element; }
}
*/
?>