<?Php
function DTBS_select($table,$champ,$condition,$groupby,$tri,$pointeur) {

	$ar_retour['statut']= true;
	$ar_retour['erreur']="";
	$ar_retour['requete']="";
	$ar_retour['nbrec']=0;
	$ar_retour['resultat']=0;

	if (trim($table)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($champ)==""){ $champ = "*"; }
	$ar_retour['requete'] = "SELECT $champ FROM $table ";
	
	if (trim($condition)!="")	{ $ar_retour['requete'] .= "WHERE $condition "; }
	if (trim($groupby)!="")	{ $ar_retour['requete'] .= "GROUP BY $groupby "; }
	if (trim($tri)!="")			{ $ar_retour['requete'] .= "ORDER BY $tri "; }
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_num_rows($ar_retour['resultat']);
	}
	return $ar_retour;
}
function DTBS_select_join($table1,$table2,$champ,$condjoin,$condition,$tri,$pointeur) {

	$ar_retour['statut']= true;
	$ar_retour['erreur']="";
	$ar_retour['requete']="";
	$ar_retour['nbrec']=0;
	$ar_retour['resultat']=0;

	if (trim($table1)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($table2)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($condjoin)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Condition de jointure non fournie"; return $ar_retour; }
	if (trim($champ)==""){ $champ = "t1.*,t2.*"; }
	$ar_retour['requete'] = "SELECT $champ FROM $table1 AS t1 LEFT JOIN $table2 AS t2 ON $condjoin ";
	
	if (trim($condition)!="")	{ $ar_retour['requete'] .= "WHERE $condition "; }
	if (trim($tri)!="")			{ $ar_retour['requete'] .= "ORDER BY $tri "; }
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_num_rows($ar_retour['resultat']);
	}
	return $ar_retour;
}
function DTBS_select_join3($table1,$table2,$table3,$champ,$condjoin,$condjoin2,$condition,$tri,$pointeur) {

	$ar_retour['statut']= true;
	$ar_retour['erreur']="";
	$ar_retour['requete']="";
	$ar_retour['nbrec']=0;
	$ar_retour['resultat']=0;

	if (trim($table1)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($table2)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($table3)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }
	if (trim($condjoin)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Condition N° 1 de jointure non fournie"; return $ar_retour; }
	if (trim($condjoin2)==""){ $ar_retour['statut']= false; $ar_retour['erreur'] = "Condition N° 2 de jointure non fournie"; return $ar_retour; }

	if (trim($champ)==""){ $champ = "t1.*,t2.*,t3.*"; }
	$ar_retour['requete'] = "SELECT $champ FROM ($table1 AS t1 LEFT JOIN $table2 AS t2 ON $condjoin) LEFT JOIN $table3 AS t3 ON $condjoin2 ";
	
	if (trim($condition)!="")	{ $ar_retour['requete'] .= "WHERE $condition "; }
	if (trim($tri)!="")			{ $ar_retour['requete'] .= "ORDER BY $tri "; }
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_num_rows($ar_retour['resultat']);
	}
	return $ar_retour;
}
function DTBS_sqlbrut($requete,$pointeur){
	
	$ar_retour['statut']= true;
	$ar_retour['erreur']="";
	$ar_retour['requete']=$requete;
	$ar_retour['nbrec']=0;
	$ar_retour['resultat']=0;
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
			$ar_retour['nbrec'] = @mysqli_num_rows($ar_retour['resultat']);
	}
	return $ar_retour;

}
/*
Desactivé car doublon avec la func du dessous qui est mieux faite  (plus sure)
function DTBS_get_array_enum($table,$field,$pointeur){

	$descfield = mysqli_query($pointeur, "SHOW COLUMNS FROM ".$table." LIKE '".$field."'");
	$df = mysqli_fetch_assoc($descfield);
	
	$ligne = str_replace("enum(","",$df['Type']);
	$ligne = str_replace(")","",$ligne);
	$ligne = str_replace("'","",$ligne);
	
	$ar_listenum = explode(",",$ligne);
	return $ar_listenum;

}*/
function DTBS_get_choice_enum($table,$field,$pointeur){

	// Idée d'amélioration : renvoyer la valeur par défaut du champ pour fixer correctement les listes déroulantes
	$champs = mysqli_query($pointeur,"SHOW COLUMNS FROM ".$table." LIKE '".$field."'");
	$colchamps = mysqli_fetch_assoc($champs);
	
	$colchamps['Type'] = str_replace("enum(","",$colchamps['Type']);
	$colchamps['Type'] = substr($colchamps['Type'],0,strlen($colchamps['Type'])-1);
	$ar_enum = explode(",",$colchamps['Type']);
	for($i=0;$i<count($ar_enum);$i++){
		$ar_enum[$i] = substr($ar_enum[$i],0,strlen($ar_enum[$i])-1);
		$ar_enum[$i] = substr($ar_enum[$i],(strlen($ar_enum[$i])-1)*-1);
	}
	return($ar_enum);
	
}
function DTBS_insert_rec(){
	
	$ar_arg = func_get_args();

	$table 	  = $ar_arg[0];
	$ar_field = $ar_arg[1];
	$pointeur = $ar_arg[2];
	
	$ar_retour['statut'] = true;
	$ar_retour['erreur'] = "";
	$ar_retour['requete']= "";
	$ar_retour['resultat'] = 0;
	$ar_retour['insert_id'] = -1;
		
	//Insere le tableau ar_field dans la table table, renvoi le dernier id
	$chaine_field = "INSERT INTO $table (";
	$chaine_value="";
	$cpt=0;

	// Attention si activé empeche le deroulement de la prod
	$listechamp = mysqli_query($pointeur, "DESCRIBE $table");
	/*if (mysqli_num_rows($listechamp)!=count($ar_field)){
		$ar_retour['statut']= false;
		$ar_retour['erreur']= "Nombre de champ incorrect Tableau : ".count($ar_field).", bdd : ".mysqli_num_rows($listechamp);
		// ."\n".print_r($ar_field)
		return $ar_retour;
	}*/

	while ($r=mysqli_fetch_assoc($listechamp)) {
		$chaine_field .= "`".$r['Field']."`, ";
		if (substr($ar_field[$cpt],0,5)=="MD5('"){
			$chaine_value .= $ar_field[$cpt].", ";
		} else {
			$chaine_value .= "'".$ar_field[$cpt]."', ";
		}
		$cpt++;
	}
	$chaine_value  = str_replace("'NOW()'","NOW()",$chaine_value);
	$chaine_value  = str_replace("'-NULL-'","NULL",$chaine_value);
	$chaine_field  = substr($chaine_field,0,strlen($chaine_field)-2);
	$chaine_field .= " ) VALUES (";
	$chaine_value  = substr($chaine_value,0,strlen($chaine_value)-2);
	$chaine_value .= ")";

	$ar_retour['requete'] = $chaine_field.$chaine_value;
	$ar_retour['resultat'] = mysqli_query($pointeur,$chaine_field.$chaine_value);
	$ar_retour['insert_id'] = mysqli_insert_id($pointeur);
	
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	}
	return $ar_retour;
}
function DTBS_modif_rec(){

	$ar_arg = func_get_args();
	
	$table    = $ar_arg[0];
	$where 	  = $ar_arg[1];
	$ar_field = $ar_arg[2];
	$pointeur = $ar_arg[3];

	$ar_retour['statut'] = true;
	$ar_retour['erreur'] = "";
	$ar_retour['requete'] = "";
	$ar_retour['resultat'] = 0;

	$cpt=0; $ar_retour['requete'] = "UPDATE ".$table." SET ";
	foreach ($ar_field as $clef=>$valeur){ $ar_retour['requete'] .= "`".$clef."` = '".$valeur."', "; }
	// Supprime la derniere virgule
	$ar_retour['requete']  = substr($ar_retour['requete'],0,strlen($ar_retour['requete'])-2);
	$ar_retour['requete'] .= " WHERE ".$where;
	$ar_retour['requete']  = str_replace("'NOW()'","NOW()",$ar_retour['requete']);
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);

	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_affected_rows($pointeur);
	}
	return $ar_retour;

}
function DTBS_efface_rec(){
	
	$ar_arg = func_get_args();
	
	$table    = $ar_arg[0];
	$clause	  = $ar_arg[1];
	$pointeur = $ar_arg[2];
	
	$efface = mysqli_query($pointeur, "DELETE FROM $table WHERE $clause");
	
	return $efface;
		
}
function DTBS_truncate_table($table,$pointeur){

	$ar_retour['statut']   = true;
	$ar_retour['erreur']   = "";
	$ar_retour['requete']  = "TRUNCATE ".$table;
	$ar_retour['resultat'] = 0;
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);

	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	}
	return $ar_retour;

}
function DTBS_table_statut($bdd,$table,$pointeur){

	$ar_retour['statut']   = true;
	$ar_retour['erreur']   = "";
	$ar_retour['requete']  = "SHOW TABLE STATUS FROM ".$bdd." LIKE '".$table."'";
	$ar_retour['resultat'] = 0;
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);

	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	}
	return $ar_retour;

}
?>