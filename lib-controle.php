<?Php
declare(strict_types=1);
/**
 * Vérifie le format du courriel et l'existence du domaine (enregistrements MX ou A).
 *
 * @param string $email Adresse courriel à vérifier
 * @return string "Ok" si valide, sinon un message d'erreur lisible
 */
function emailCheck(string $email): string {
	if (trim($email) === "") {
		return "L'email est vide";
	}
	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		return "Le format de l'email n'est pas valide";
	}
	$dom = substr(strrchr($email, "@"), 1);
	if (dns_get_record($dom, DNS_MX | DNS_A) === false) {
		return "Le nom de domaine de l'email n'existe pas";
	}
	return "Ok";
}

/**
 * Vérifie et formate un numéro de téléphone français (10 chiffres commençant par 0).
 *
 * @param string $numero        Numéro de téléphone (avec ou sans séparateurs)
 * @param bool   $international Mode international (non implémenté)
 * @return array|null numero/numspace/numpoint/conforme (1 valide, -1 invalide)
 * @deprecated La branche $international n'est pas implémentée et retourne null.
 */
function phoneCheck(string $numero, bool $international) {

	if (!$international) {
		$ar_pc['numero'] = preg_replace("/[^0-9]/", "", $numero);
		$ar_pc['numspace'] = substr($ar_pc['numero'],0,2)." ".substr($ar_pc['numero'],2,2)." ".substr($ar_pc['numero'],4,2)." ".substr($ar_pc['numero'],6,2)." ".substr($ar_pc['numero'],8,2);
		$ar_pc['numpoint'] = str_replace(" ",".",$ar_pc['numspace']);
		if (strlen($ar_pc['numero'])!=10){ $ar_pc['conforme']=-1; } else { $ar_pc['conforme']=1; }
		if (substr($ar_pc['numero'],0,1)!=0){ $ar_pc['conforme']=-1; } else{ $ar_pc['conforme']=1; }
		return $ar_pc;
	}
	return null;
}

/**
 * Valide et formate une date.
 *
 * @param string $date      Date numérique DDMMAAAA, ou DDMMAAAHHIIS si avec heure
 * @param string $typed     'humain' (JJ-MM-AAAA) ou 'mysql' (AAAA-MM-JJ)
 * @param bool   $avecheure La date contient-elle une heure ?
 * @param bool   $inverse   Si true renvoie AAAA-MM-JJ HH:II:SS, sinon JJ-MM-AAAA HH:II:SS
 * @return string "OK;date" en cas de succès, "Erreur;..." en cas d'échec (ne sort jamais)
 */
function DateCheck(string $date, string $typed, bool $avecheure, bool $inverse): string {
	$date = preg_replace("/[^0-9]/", "", $date);
	$sdate = ""; $sheure = "";
	$j=0; $m=0; $a=0; $h=0; $u=0; $s=0;
	if ($avecheure) {
		if (strlen($date)!= 14){ return "Erreur;Le format de la date est incorrecte"; }
		$sheure = substr($date,-6);
		$sdate = substr($date,0,8);
		$h=substr($sheure,0,2); $u=substr($sheure,2,2); $s=substr($sheure,-2);
	} else {
		if (strlen($date)== 6){  $date = substr($date,0,4)."20".substr($date,-2); } // "Pouet pouet et le bug de l'an 3000"
		if (strlen($date)!= 8){ return "Erreur;Le format de la date est incorrecte"; }
		$sdate = $date;
	}
	if ($typed=='humain') {
		$j = substr($sdate,0,2); $m = substr($sdate,2,2); $a = substr($sdate,-4);
	} else {
		$j = substr($sdate,-2); $m = substr($sdate,4,2); $a = substr($sdate,0,4);
	}

	if (!checkdate((int)$m,(int)$j,(int)$a)){ return "Erreur;Le format de la date est incorrecte"; }

	if ($h>23){ return "Erreur;Le format de la date est incorrecte"; }
	if ($u>59){ return "Erreur;Le format de la date est incorrecte"; }
	if ($s>59){ return "Erreur;Le format de la date est incorrecte"; }

	if ($inverse) { // Retourne la date au format AAAA-MM-JJ HH:II
		$retour = "OK;".sprintf("%04s",$a)."-".sprintf("%02s",$m)."-".sprintf("%02s",$j);
		if ($avecheure) {
			$retour .= " ".sprintf("%02s",$h).":".sprintf("%02s",$u).":".sprintf("%02s",$s);
		}
		return $retour;
	} else { // Retourne la date au format JJ-MM-AAAA HH:II
		$retour = "OK;".sprintf("%02s",$j)."-".sprintf("%02s",$m)."-".sprintf("%04s",$a);
		if ($avecheure) {
			$retour .= " ".sprintf("%02s",$h).":".sprintf("%02s",$u).":".sprintf("%02s",$s);
		}
		return $retour;
	}
}
/**
 * Vérifie le privilège de l'utilisateur connecté pour un id de privilège donné.
 * NOTE : code spécifique à une application (tables RH) à déplacer hors de cette lib.
 *
 * @param int    $ledroit Id du privilège à vérifier
 * @param mysqli $mysqli  Connexion base de données active
 * @return bool true si le privilège de l'utilisateur vaut 1
 */
function RightCheck(int $ledroit, mysqli $mysqli): bool {
	/* A déplacer dans lib x-Prim : code spécifique à une appli */
	$login = $_SESSION['login'] ?? "";
	if ($login === "") { return false; }

	$sql = "SELECT t2.valeurprivilege "
	     . "FROM `".TB_RH_PERSONNEL2."` t1 "
	     . "INNER JOIN `".TB_RH_PERS_PRIV."` t2 ON t1.id_ndvd = t2.rhnp_id_ndvd "
	     . "WHERE t1.login = ? AND t2.rhnp_privilege_id = ?";

	$stmt = $mysqli->prepare($sql);
	if (!$stmt) { return false; }
	$stmt->bind_param("si", $login, $ledroit);
	$stmt->execute();
	$res = $stmt->get_result();
	$valtest = $res->fetch_assoc();
	$stmt->close();

	return ($valtest !== null && (int)$valtest['valeurprivilege'] === 1);
}

/**
 * Supprime tous les caractères non alphanumériques d'une chaîne.
 *
 * @param string $chaine Chaîne à nettoyer
 * @return string Chaîne alphanumérique resultante
 */
function AlphaNumCheck(string $chaine): string {

	$chaine = preg_replace("#[^0-9A-Za-z]#", "", $chaine);
	return $chaine;
}

/**
 * Génère un mot de passe aléatoire (minuscules, sans caractères ambigus).
 * Remplace la fonction creemotdepasse() de lib-dv.php.
 * Utilise random_int() (cryptography safe) au lieu de srand()/rand().
 *
 * @param int $nbcaracteres Nombre de caractères à générer
 * @return string Mot de passe généré
 */
function PwdGet(int $nbcaracteres): string {

	$pwd="";
	$caracterespossibles = "abcdefghjkmnpqrstuvwxyz123456789";
	$nbmax = strlen($caracterespossibles);
	for($i=0; $i<$nbcaracteres; $i++) {
		$pwd .= $caracterespossibles[random_int(0, $nbmax-1)];
	}
	return $pwd;
}

/**
 * Insère des sauts de ligne et de l'espacement pour l'affichage d'une requête SQL.
 *
 * @param string $req Requête SQL à formater
 * @return string Requête annotée en HTML
 */
function AfficheRequete(string $req): string {

	$req = str_replace("SELECT","SELECT<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("FROM","<br/>FROM<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("LEFT JOIN","<br/>LEFT JOIN<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("WHERE","<br/>WHERE<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("GROUP BY","<br/>GROUP BY<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	$req = str_replace("ORDER BY","<br/>ORDER BY<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$req);
	return $req;
}