<?Php
// Fonction a remplacé par une focntion integré de php
function clean_field($chaine) {
	$chaine  = str_replace("'"," ",$chaine);
	$chaine  = str_replace("[","",$chaine);
	$chaine  = str_replace("]","",$chaine);
	$chaine  = str_replace("+","",$chaine);
	$chaine  = str_replace("*","",$chaine);
	$chaine  = str_replace("^","",$chaine);
	$chaine  = str_replace("$","",$chaine);
	$chaine  = str_replace("\\","",$chaine);
	$chaine  = str_replace(")","",$chaine);
	$chaine  = str_replace("(","",$chaine);
	$chaine  = str_replace("&","",$chaine);
	$chaine  = str_replace("\"","",$chaine);
	$chaine  = str_replace("~","",$chaine);
	$chaine  = str_replace("#","",$chaine);
	$chaine  = str_replace("{","",$chaine);
	$chaine  = str_replace("_","",$chaine);
	$chaine  = str_replace("}","",$chaine);
	$chaine  = str_replace("=","",$chaine);
	$chaine  = str_replace(",","",$chaine);
	$chaine  = str_replace(";","",$chaine);
	$chaine  = str_replace(":","",$chaine);
	$chaine  = str_replace("!","",$chaine);
	return $chaine;
}
function get_record($table,$champ,$condition,$tri) {
	if (trim($table)==""){return -1;}
	if (trim($champ)==""){$champ="*";}
	$rek = "SELECT $champ FROM $table ";
	if (trim($condition)!=""){$rek = $rek."WHERE ".$condition;}
	$rek = $rek." ".$tri;

	//if (isset($_SESSION['user'])){
	//	if ($_SESSION['user']=='hesmo'){
	//		syslog(LOG_WARNING, "la valeur : ".$rek);
	//	}
	//}

	//echo $rek;
	$requete = mysql_query($rek);
	if (!$requete)
		{return -1;} // Renvoi -1 si la requete est fausse
	if (mysql_num_rows($requete)==0)
		{return -2;} // Renvoi -2 si le nombre de rec est egal à 0
	return $requete;
}
function get_record_join($table1,$table2,$champ,$jointure,$condition,$tri){

	if (trim($table1) == ""){ return -1; }
	if (trim($table2) == ""){ return -1; }
	if (trim($champ)==""){$champ="t1.*,t2.*";}
	$rek = "SELECT $champ FROM $table1 AS t1 LEFT JOIN $table2 AS t2 ON $jointure ";
	if (trim($condition)!=""){$rek = $rek."WHERE ".$condition;}
	$rek = $rek." ".$tri;

	
	//define_syslog_variables();
	//syslog(LOG_WARNING, "la valeur : ".$rek);
	

	$requete = mysql_query($rek);
	if (!$requete)
		{return -1;} // Renvoi -1 si la requete est fausse
	if (mysql_num_rows($requete)==0)
		{return -2;} // Renvoi -2 si le nombre de rec est egal à 0
	return $requete;

	// Ligne suivante pour créer la fonction a supprimer ensuite
	//	SELECT t1.*,t2.* FROM ". TB_BLABLA." AS t1 LEFT JOIN ".TB_BLABLA2." AS t2 ON t1.lechamp = t2.lechamp WHERE CLAUSEICI
	
}

/**********************************************************
* GENERE et EXECUTE une REQUEST 'SELECT' SQL
*
* Renvoi un tableau
* 	0: (Chaine)	Chaine de la requete
* 	1: (Entier)	-1 si la requete declenche une erreur ou 1
* 	2: (Entier)  	Nombre d'enregistrement retourné
* 	3: (Entier)   	-1 pour un echec ou identifiant de la requete
*
* @param string $table - Nom de la table de la requete
* @param string $champ - Liste des champs désirés (* ou champ séparés par ,)
* @param string $condition - clause WHERE de la requette (optionnel)
* @param string $tri - clause ORDER de la requette (optionnel)
*/
function sel_record($table,$champ,$condition,$tri) {
	if (trim($table)==""){return -1;}
	if (trim($champ)==""){$champ="*";}
	$rek = "SELECT $champ FROM $table ";
	if (trim($condition)!=""){$rek = $rek."WHERE ".$condition;}
	$rek = $rek." ".$tri;
	$ar_ret=array($rek);
	$requete = mysql_query($rek);
	if (!$requete) {
		array_push($ar_ret,-1);  // Ajoute  -1 si la requete comporte une erreur
		array_push($ar_ret,0,-1);
	} else {
		array_push($ar_ret,1);  // Ajoute 1 si la requete est correcte
		array_push($ar_ret,mysql_num_rows($requete),$requete);
	}
	return $ar_ret;
}
function kill_record($table,$condition) {
	$rek = "DELETE FROM $table WHERE $condition";
	$kill = mysql_query($rek);
	return $kill;
}
function insereligne($table,$ar_field) {
	/*$ar_t=error_get_last();*/


	// Laisse une trace dans le fichier /var/log/debug (slackware)
	//$a=syslog(LOG_DEBUG,"TABLE : ".$table." - script appelant : ".$_SERVER['SCRIPT_FILENAME']." Adresse IP : ".$_SERVER['REMOTE_ADDR']);
	//Insere le tableau ar_field dans la table table, renvoi le dernier id
	$chaine_field="INSERT INTO $table (";
	$chaine_value="";
	$cpt=0;
	$listechamp = mysql_query("DESCRIBE $table");

	//if (count($ar_field)!=mysql_num_rows($listechamp)){
		//define_syslog_variables();
	//	syslog(LOG_WARNING, "IL : ".str_replace("/home/hesmo/www/lorge.org/www","",$_SERVER['SCRIPT_FILENAME']));
	//	syslog(LOG_WARNING, "IL :   Nb var Param = ".count($ar_field));
	//	syslog(LOG_WARNING, "IL : Nb Field Table = ".mysql_num_rows($listechamp));
	//}

	while ($r=mysql_fetch_array($listechamp)) {
		$chaine_field = $chaine_field."`".$r[0]."`, ";
		$chaine_value = $chaine_value."'".$ar_field[$cpt]."', ";
		$cpt++;
	}
	$chaine_value = str_replace("'NOW()'","NOW()",$chaine_value);
	$chaine_field=substr($chaine_field,0,strlen($chaine_field)-2);
	$chaine_field=$chaine_field." ) VALUES (";
	$chaine_value=substr($chaine_value,0,strlen($chaine_value)-2);
	$chaine_value = $chaine_value.")";
	//echo $chaine_field.$chaine_value."<br/><br/><br/>";
	$leretour = mysql_query($chaine_field.$chaine_value);

	// Laisse une trace dans le fichier /var/log/debug (slackware)
	//$a=syslog(LOG_DEBUG,"RETOUR : ".$chaine_field.$chaine_value);
	return $leretour;
}
function insereligne3($table,$ar_field,$liensql) { // Même que précédente mais avec précision du flag mysql_lien
	//Insere le tableau ar_field dans la table table, renvoi le dernier id
	$chaine_field="INSERT INTO $table (";
	$chaine_value="";
	$cpt=0;
	$listechamp = mysql_query("DESCRIBE $table");
	while ($r=mysql_fetch_array($listechamp)) {
		$chaine_field = $chaine_field."`".$r[0]."`, ";
		$chaine_value = $chaine_value."'".addslashes($ar_field[$cpt])."', ";
		$cpt++;
	}
	$chaine_value = str_replace("'NOW()'","NOW()",$chaine_value);
	$chaine_field=substr($chaine_field,0,strlen($chaine_field)-2);
	$chaine_field=$chaine_field." ) VALUES (";
	$chaine_value=substr($chaine_value,0,strlen($chaine_value)-2);
	$chaine_value = $chaine_value.")";
	//echo $chaine_field.$chaine_value."<br/><br/><br/>";
	$leretour = mysql_query($chaine_field.$chaine_value,$liensql);

	return $leretour;
}
function insereligne2($table,$ar_field) {
	//MEME KE PRECEDENTE POUR PHP3
	$chaine_field="INSERT INTO $table (";
	$chaine_value="";
	$cpt=0;
	$listechamp = mysql_query("DESCRIBE $table");
	while ($r=mysql_fetch_array($listechamp)) {
		$chaine_field = $chaine_field.$r[0].", ";
		$chaine_value = $chaine_value."'".$ar_field[$cpt]."', ";
		$cpt++;
	}
	$chaine_value = str_replace("'NOW()'","NOW()",$chaine_value);
	$chaine_field=substr($chaine_field,0,strlen($chaine_field)-2);
	$chaine_field=$chaine_field." ) VALUES (";
	$chaine_value=substr($chaine_value,0,strlen($chaine_value)-2);
	$chaine_value = $chaine_value.")";
	$leretour = mysql_query($chaine_field.$chaine_value);
	return $leretour;
}
function majligne($table,$ar_field,$liensql){
	// La clef est le champ, la valeur la nouvelle valeur
	// La première paire du tableau $ar_field contient le champ a modifié (clause WHERE)
	$cpt=0; $rek = "UPDATE ".$table." SET ";
	foreach ($ar_field as $clef=>$valeur){
		if ($cpt!=0){ $rek=$rek.$clef." = '".$valeur."', "; } else { $tmp[0]=$clef; $tmp[1]=$valeur; }
		$cpt++;
	}
	// Supprime la derniere virgule
	$rek=substr($rek,0,strlen($rek)-2);
	// Corrige la chaine now
	$rek = str_replace("'NOW()'","NOW()",$rek);
	$rek=$rek." WHERE ".$tmp[0]." = '".$tmp[1]."'";

//	define_syslog_variables();
//	syslog(LOG_WARNING, "IL : ".$rek);

	$maj = mysql_query($rek,$liensql);
	return $maj;
}
function bondelivraison_corpsbl($corpsbl) { // Renvoi le corps d'un bl (array) à partir d'un champ bondelivraison.corpsbl
	$ar_final = array();
	$ar_ligne = explode("\n",trim($corpsbl));
	foreach ($ar_ligne as $laligne) {
		$corps = explode("###",$laligne);
		array_push($ar_final,$corps[0],$corps[1],$corps[2]);
	}
	return $ar_final;
}

function clientdelacommande($cmd_id) { //Renvoi un client (string) à partir d'un numéro de commande
	$leclient = mysql_query("SELECT t1.*,t2.* FROM ".TB_CLIENT." as t1, ".TB_COMMANDECLIENT." as t2 WHERE t1.clt_id=t2.clt_id AND t2.cmd_id =".$cmd_id);
	$lc = mysql_fetch_array($leclient);
	return $lc;
}

//		Fonction a Revoir (trop de spécificité)														//
function adressedubl($ccc_id) { //Renvoi une adresse (string) à partir d'un id de corps_commandeclt
	$ligneccc=mysql_query("SELECT * FROM ".TB_COMMANDECLT." WHERE ccc_id = ".$ccc_id);
	$lgccc = mysql_fetch_array($ligneccc);
	if ($lgccc["add_id"]=-10) {
		$laddclient = mysql_query("SELECT t1.*,t2.* FROM ".TB_CLIENT." as t1, ".TB_COMMANDECLIENT." as t2 WHERE t1.clt_id=t2.clt_id AND t2.cmd_id =".$lgccc["cmd_id"]);
		$adcl = mysql_fetch_array($laddclient);
		$adresse = $adcl["Nom"]."\n".$adcl["adresse"]."\n".$adcl["CodePostal"]." ".$adcl["Ville"];
		return $adresse;
	} else {
		$laddclient = mysql_query("SELECT * FROM ".TB_ADRESSES." WHERE adr_id = ".$ad_li["add_id"]);
		$adcl = mysql_fetch_array($laddclient);
		$adresse = $adcl["adresse"];
		return $adresse;
	}
}
function get_field($base,$table){
	mysql_select_db($base);
	$listfield=mysql_query("DESCRIBE ".$table);
	$ar_frm=array();
	while($r=mysql_fetch_array($listfield)){
		array_push($ar_frm,$r[0]);
	}
	return $ar_frm;
}
function get_field_enum($table,$field){
	$ress = mysql_query("SHOW COLUMNS FROM ".$table." LIKE '".$field."'");
	$ligne = mysql_fetch_array($ress);
	$ligne = str_replace("enum(","",$ligne[1]);
	$ar_listenum = explode(",",substr($ligne,0,strlen($ligne)-1));
	return($ar_listenum);
}
?>