<?Php
/* Liste des codes erreurs et traduction en langage courant */
$ar_errmysql[1451] = "Suppression impossible, enregistrement utilisé dans une autre table";
$ar_errmysql[1062] = "Ajout impossible, enregistrement déjà présent";
$ar_errmysql[1452] = "Action impossible, en raison d'une contrainte de clé etrangère";


/**
 * Construit et execute une instruction SQL select simple
 *
 * @param      string  $table      la table sur laquel porte la requete
 * @param      string  $champ      les champs retournés, si vide retourne tout les champs
 * @param      string  $condition  le filtre de la requete
 * @param      string  $groupby    clause de regroupement
 * @param      string  $tri        clause de tri
 * @param      integer  $pointeur   pointeur vers une connexion mysql
 *
 * @return     array  $ar_retour Tableau avec des informations sur le retour de la requete (statut, erreur, requete, nbrec, resultat)
 */
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
	
	$ar_retour['statut'] = true;
	$ar_retour['erreur'] = "";
	$ar_retour['requete'] = $requete;
	$ar_retour['nbrec'] = 0;
	$ar_retour['resultat'] = 0;
	
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		if (strtoupper(substr($requete,0,6))=="SELECT"){ 
			$ar_retour['nbrec'] = @mysqli_num_rows($ar_retour['resultat']);
		} else {
			$ar_retour['nbrec'] = @mysqli_affected_rows($pointeur);	
		}
		
	}
	return $ar_retour;

}
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

	$listechamp = mysqli_query($pointeur, "DESCRIBE $table");

	while ($r=mysqli_fetch_assoc($listechamp)) {
		$chaine_field .= "`".$r['Field']."`, ";
		if (substr($ar_field[$cpt],0,5)=="MD5('"){
			$chaine_value .= $ar_field[$cpt].", ";
		} elseif (substr($ar_field[$cpt],0,6)=="SHA2('") {
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
/**
 * Construit et execute une instruction SQL d'insertion
 *
 * ATTENDU :
 * @param      string  $ar_arg[0]	la table sur laquel porte la requete
 * @param      array   $ar_arg[1]   un tableau des valeurs à insérer (rien pour l'auto-increment )
 *
 * @return     array  $ar_retour Tableau avec des informations sur le retour de la requete (statut, erreur, requete, nbrec, resultat)
 */
function DTBS_add_rec(){
	
	global $mysqli;
	$ar_arg = func_get_args();

	$table 	 = $ar_arg[0];
	$ar_nval = $ar_arg[1];
	
	
	$ar_retour['statut'] = true;
	$ar_retour['erreur'] = "";
	$ar_retour['requete']= "";
	$ar_retour['resultat'] = 0;
	$ar_retour['insert_id'] = -1;
		
	//Insere le tableau ar_nval dans la table $table, renvoi le dernier id
	$chaine_field = "INSERT INTO $table (";
	$chaine_value="";
	$cpt=0;

	$listechamp = mysqli_query($mysqli, "DESCRIBE $table");
	//  SHOW FULL COLUMNS FROM client; permet d'avoir la colonne commentaire
	while ($r=mysqli_fetch_assoc($listechamp)) {
		if ($r['Extra']!="auto_increment"){
			if (isset($ar_nval[$cpt]) AND $ar_nval[$cpt]==""){ $ar_nval[$cpt]=$r['Default']; }
			$chaine_field .= "`".$r['Field']."`, ";
			if (substr($ar_nval[$cpt],0,5)=="MD5('"){
				$chaine_value .= $ar_nval[$cpt].", ";
			} elseif (substr($ar_nval[$cpt],0,6)=="SHA2('") {
				$chaine_value .= $ar_nval[$cpt].", ";
			} else {
				if (substr($r['Type'],0,7)=='varchar'){
					$chaine_value .= "'".addslashes($ar_nval[$cpt])."', ";
				} elseif (substr($r['Type'],0,3)=='char'){
					$chaine_value .= "'".addslashes($ar_nval[$cpt])."', ";
				} elseif ($r['Type']=='text'){
					$chaine_value .= "'".addslashes($ar_nval[$cpt])."', ";
				} elseif ($r['Type']=='mediumtext'){
					$chaine_value .= "'".addslashes($ar_nval[$cpt])."', ";
				} else {
					$chaine_value .= "'".$ar_nval[$cpt]."', ";
				}
			}
			$cpt++;
		}
	}
	
	$chaine_value  = str_replace("'NOW()'","NOW()",$chaine_value);
	$chaine_value  = str_replace("'-NULL-'","NULL",$chaine_value);
	$chaine_field  = substr($chaine_field,0,strlen($chaine_field)-2);
	$chaine_field .= " ) VALUES (";
	$chaine_value  = substr($chaine_value,0,strlen($chaine_value)-2);
	$chaine_value .= ")";

	$ar_retour['requete'] = $chaine_field.$chaine_value;
	$ar_retour['resultat'] = mysqli_query($mysqli,$chaine_field.$chaine_value);
	$ar_retour['insert_id'] = mysqli_insert_id($mysqli);

	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($mysqli);
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
/**
 * Gere les modes de transaction
 *
 * @param      string  $action  start, cancel ou valid
 * @return     string  Renvoi ok ou une chaine en cas d'erreur
 * 
 */
function DTBS_transaction($action){

	global $mysqli;
	switch ($action){
		case 'start':
			$resultat = mysqli_query($mysqli, "SET autocommit = 0;");
			if (!$resultat){ return "Echec de l'activation du mode transactionnel"; }
			$resultat = mysqli_query($mysqli,"START TRANSACTION;");
			if (!$resultat){ return "Echec du démarrage de la transaction"; }
			return "Ok";
		break;
		case 'cancel':
			$resultat = mysqli_query($mysqli, "ROLLBACK;");
			$resultat = mysqli_query($mysqli, "SET autocommit = 1;");
			return "Ok";
		break;
		case 'valid':
			$resultat = mysqli_query($mysqli, "COMMIT;");
			if (!$resultat){ return "Echec du COMMIT des données"; }
			$resultat = mysqli_query($mysqli, "SET autocommit = 1;");
			return "Ok";
		break;
		default:
			return "Parametre inattendu";
		break;
	}
}
?>