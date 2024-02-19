<?Php
function emailCheck($email) {
	if ($email == "") {
		return "L'email est vide";
	}
	if (!preg_match("/^[-a-z0-9\._]+@[-a-z0-9\.]+\.[a-z]{2,4}$/i", $email)) {
		return "Le format de l'email n'est pas valide";
	}
	list($nom, $dom) = explode("@", $email);
 	if (!checkdnsrr($dom)) {
 		return "Le nom de domaine de l'email n'existe pas";
 	}
 	return "Ok";
}
function phoneCheck($numero,$international){
	
	if (!$international){
		$ar_pc['numero'] = preg_replace("/[^0-9]/", "", $numero);
		$ar_pc['numspace'] = substr($ar_pc['numero'],0,2)." ".substr($ar_pc['numero'],2,2)." ".substr($ar_pc['numero'],4,2)." ".substr($ar_pc['numero'],6,2)." ".substr($ar_pc['numero'],8,2);
		$ar_pc['numpoint'] = str_replace(" ",".",$ar_pc['numspace']);
		if (strlen($ar_pc['numero'])!=10){ $ar_pc['conforme']=-1; } else { $ar_pc['conforme']=1; }
		if (substr($ar_pc['numero'],0,1)!=0){ $ar_pc['conforme']=-1; } else{ $ar_pc['conforme']=1; }
		return $ar_pc;
	} else {
		
	}
}
/**
* CONTROLE ET FORMATE UNE DATE
* @param string $date
* @param string $typed ('humain','mysql')
* @param integer $avecheure (true,false)
* @param integer $inverse si true renvoie au format AAAA-MM-DD HH:II sinon DD-MM-AAAA HH:II
*/
function DateCheck($date,$typed,$avecheure,$inverse){
	$date = preg_replace("/[^0-9]/", "", $date);
	$sdate = ""; $sheure = "";
	$j=0; $m=0; $a=0; $h=0; $u=0; $s=0; 
	if ($avecheure){
		if (strlen($date)!= 14){ return("Erreur;Le format de la date est incorrecte"); exit; }
		$sheure = substr($date,-6);
		$sdate = substr($date,0,8);
		$h=substr($sheure,0,2); $u=substr($sheure,2,2); $s=substr($sheure,-2);
	} else {
		if (strlen($date)== 6){  $date = substr($date,0,4)."20".substr($date,-2); } // "Pouet pouet et le bug de l'an 3000"
		if (strlen($date)!= 8){ return("Erreur;Le format de la date est incorrecte"); exit; }
		$sdate = $date;
	}
	if ($typed=='humain'){
		$j = substr($sdate,0,2); $m = substr($sdate,2,2); $a = substr($sdate,-4);
	} else {
		$j = substr($sdate,-2); $m = substr($sdate,4,2); $a = substr($sdate,0,4);
	}

	if (!checkdate($m,$j,$a)){ return("Erreur;Le format de la date est incorrecte"); exit; }
	
	if ($h>23){ return("Erreur;Le format de la date est incorrecte"); exit; }
	if ($u>59){ return("Erreur;Le format de la date est incorrecte"); exit; }
	if ($s>59){ return("Erreur;Le format de la date est incorrecte"); exit; }
	
	if ($inverse){ // Renvoie la date au format AAAA-MM-DD HH:II
		$retour = "OK;".sprintf("%04s",$a)."-".sprintf("%02s",$m)."-".sprintf("%02s",$j);
		if ($avecheure){
			$retour .= " ".sprintf("%02s",$h).":".sprintf("%02s",$u).":".sprintf("%02s",$s);
		}
		return($retour);
	} else { // Renvoie la date au format DD-MM-AAAA HH:II
		$retour = "OK;".sprintf("%02s",$j)."-".sprintf("%02s",$m)."-".sprintf("%04s",$a);
		if ($avecheure){
			$retour .= " ".sprintf("%02s",$h).":".sprintf("%02s",$u).":".sprintf("%02s",$s);
		}
		return($retour);
	}
}
function RightCheck(){
	/* A déplacer dans lib x-Prim : code spécifique à une appli */
	$ar_arg = func_get_args();
	$ledroit = $ar_arg[0];
	$mysqli = $ar_arg[1];
	$test = DTBS_select_join(TB_RH_PERSONNEL2,TB_RH_PERS_PRIV,"t1.*,t2.*","t1.id_ndvd = t2.rhnp_id_ndvd","t1.login = '".$_SESSION['login']."' AND t2.rhnp_privilege_id = ".$ledroit,"",$mysqli);

	if (!$test['statut'] OR $test['nbrec']==0){ return false; }
	$valtest = mysqli_fetch_assoc($test['resultat']);
	if ($valtest['valeurprivilege']==1){return true;}else{return false;}
	
}
function AlphaNumCheck($chaine){

	$chaine = preg_replace("#[^0-9A-Za-z]#", "", $chaine);
	return($chaine);

}
// Remplace la fonction de lib-dv.php creemotdepasse(x)
function PwdGet($nbcaracteres) {
	
	$pwd="";
	$caracterespossibles = "abcdefghjkmnpqrstuvwxyz123456789"; 
	srand((double)microtime()*1000000);
	for($i=0; $i<$nbcaracteres; $i++) {
		$pwd .= $caracterespossibles[rand()%strlen($caracterespossibles)]; 
	}
	return $pwd;
}
function AfficheRequete($req){

	$req = str_replace("SELECT","SELECT<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("FROM","<br/>FROM<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("LEFT JOIN","<br/>LEFT JOIN<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("WHERE","<br/>WHERE<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("GROUP BY","<br/>GROUP BY<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("ORDER BY","<br/>ORDER BY<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	return($req);

}
?>
