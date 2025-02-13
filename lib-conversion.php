<?Php
function CV_date_mysqltohuman($datesql){
	
	$ar_retour['jour'] =  substr($datesql,8,2);
	$ar_retour['mois'] =  substr($datesql,5,2);
	$ar_retour['annee'] = substr($datesql,0,4);
	$ar_retour['anneecourt'] = substr($datesql,2,2);
	$ar_retour['heure'] =  substr($datesql,11,2);
	$ar_retour['minute'] =  substr($datesql,14,2);
	$ar_retour['seconde'] = substr($datesql,17,2);

	$ar_retour['datelong'] = $ar_retour['jour']."/".$ar_retour['mois']."/".$ar_retour['annee'];
	$ar_retour['datecourt'] = $ar_retour['jour']."/".$ar_retour['mois']."/".$ar_retour['anneecourt'];
	$ar_retour['heure'] = $ar_retour['heure'].":".$ar_retour['minute'];

	$ar_retour['dateheurelong'] = $ar_retour['datelong']." ".$ar_retour['heure'];
	$ar_retour['dateheurecourt'] = $ar_retour['datecourt']." ".$ar_retour['heure'];
	
	
	$ar_retour['tmstp'] = mktime((int) $ar_retour['heure'],(int) $ar_retour['minute'],(int) $ar_retour['seconde'],(int) $ar_retour['mois'],(int) $ar_retour['jour'],(int) $ar_retour['annee']);
	
	return $ar_retour;
	
}
/**
* RETOURNE UN TIMESTAMP A PARTIR D'UNE DATE AU FORMAT AAAA-MM-JJ HH:MM:SS
*
* @param string $w
*/
function CV_datesql_TStamp($w) {
	$ladate = date_create_from_format("Y-m-d H:i:s",$w);
	//return( mktime( substr($w,11,2),substr($w,14,2),substr($w,17,2),substr($w,5,2),substr($w,8,2),substr($w,0,4) ) );
}
/**
* RETOURNE UN TABLEAU AVEC LE NOMBRE DE heure,seconde à partir d'un nombre de seconde
*
* @param string $dureesec
*/
function CV_dureesec_tohuman($dureesec){
	if ($dureesec<3600){ $ar_retour['heure'] = 0; } else {$ar_retour['heure']= floor($dureesec/3600); $dureesec = $dureesec - ($ar_retour['heure']*3600); }
	if ($dureesec<60){ $ar_retour['minute'] = 0; } else {$ar_retour['minute']= floor($dureesec/60); $dureesec = $dureesec - ($ar_retour['minute']*60); }
	$ar_retour['seconde']= $dureesec;

	return $ar_retour;	
}
/**
* RENVOIE LA DIFFERENCE (toujours positif) EN SECONDE ENTRE DEUX DATES
*
* @param string $a // Format AAAA-MM-JJ HH:MM:SS ou AAAA-MM-JJ
* @param string $b // Format AAAA-MM-JJ HH:MM:SS ou AAAA-MM-JJ
*/
function CV_DifferenceDate($a,$b) {

	if (strlen($a)==10){ $a = $a." 00:00:00"; }
	if (strlen($b)==10){ $b = $b." 00:00:00"; }

	$a2 = mktime (substr($a,11,2),substr($a,14,2),substr($a,17,2),substr($a,5,2),substr($a,8,2),substr($a,0,4));
	$b2 = mktime (substr($b,11,2),substr($b,14,2),substr($b,17,2),substr($b,5,2),substr($b,8,2),substr($b,0,4));
	
	return(abs($b2-$a2));
}
function CV_boleentostring(){
	$ar_arg = func_get_args();
	if ($ar_arg[0]==-1){ $valret = "non"; } else { $valret = "oui"; }
	if (!isset($ar_arg[1]) OR !$ar_arg[1]){ echo $valret; } else { return $valret; }
}
function CV_calculsumean13($digit12){
	if (strlen($digit12)!=12){return false;}
	// Fait un tableau de caractère
	$c = str_split($digit12);

	$paire = $c[0] + $c[2] + $c[4] + $c[6] + $c[8] + $c[10];
  $impaire = $c[1] + $c[3] + $c[5] + $c[7] + $c[9] + $c[11];
	
  	$checksum = (10-(((3*$impaire)+$paire)%10))%10;

//	define_syslog_variables();
//	syslog(LOG_WARNING, "SPYVAL : ".$checksum);

	return $checksum;
}
function CV_Calcul_Sum_Ean14($d13){

	if (strlen($d13)!=13){return false;}
	$total = ($d13[0]*3) + ($d13[1]*1) + ($d13[2]*3) + ($d13[3]*1) + ($d13[4]*3) + ($d13[5]*1) + ($d13[6]*3) + ($d13[7]*1) + ($d13[8]*3) + ($d13[9]*1) + ($d13[10]*3) + ($d13[11]*1) + ($d13[12]*3);
	$multiple = floor($total/10);
	if (($multiple*10)<$total) { $multiple++;}
	$sdc = ($multiple*10) - $total;
	return $sdc;
	
}
/**
* RETOURNE UN TABLEAU A PARTIR D'UNE CHAINE FOURNIT PAR UN BADGE
*
* @param string $lu la chaine lu par le lecteur rfid
* ar_retour[0] la chaine fourni en argumant (nombre de 10 digit ou chaine de caractère symbole sous les chiffres sans shifts)
* ar_retour[1] la chaine fourni en argumant converti en un nombre de 10 digit
* ar_retour[2] 1ere partie du code hexadecimal
* ar_retour[3] 2eme partie du code hexadecimal
*/
function CV_nbadge($lu){

	//    1 2 3 4 5 6 7 8 9 0
	//    & é " ' ( § è ! ç à   // Clavier MAC
	// 	  & é " ' ( - è _ ç à   // Clavier PC
	// La ligne suivantes transforme une saisie mac en saisie pc
	$lu = str_replace("§","-", $lu); $lu = str_replace("!","_", $lu);

	$patterns[1] = "/&/";
	$patterns[2] = "/é/";
	$patterns[3] = "/\"/";
	$patterns[4] = "/'/";
	$patterns[5] = "/\(/";
	$patterns[6] = "/-/";
	$patterns[7] = "/è/";
	$patterns[8] = "/_/";
	$patterns[9] = "/ç/";
	$patterns[10] = "/à/";

	$replacements = array(1=>"1","2","3","4","5","6","7","8","9","0");


	return(preg_replace($patterns,$replacements,$lu));

/*	
	Tentaive de conversion 10d8h pas résolu
	$ar_retour[2] = hexdec(substr(dechex($ar_retour[1]),0,2));
	$ar_retour[3] = hexdec(substr(dechex(substr($ar_retour[1],-7)),-4));
*/
	
	return($ar_retour);

}
/**********************************************************
* CALCUL UNE ECHEANCE (POUR LA COMPTA)
*
* @param int $lestamp - TimeStamp de la date de depart
* @param string $lecheance - Code de l'echeance (propre a xprim)
* @param string $lecalcul - Formule de calcul de l'échéance
*/
function CV_echeance_mdr($lestamp,$lecheance,$lecalcul){
	
	$ar_dureemois = array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30,12=>31);
	$ar_retour = array('statut'=>false,'humain'=>'','mysql'=>'','msgerreur'=>'');
	//$lejour=date("j",$lestamp); $lemois=date("n",$lestamp); $lannee=date("Y",$lestamp); 

	if (trim($lecalcul)==""){ // Si lecalcul n'est pas fournit on sort
		$ar_retour['msgerreur']='Mode de reglement non calculable';
		return $ar_retour;
	}

	$ar_ac=explode(";",$lecalcul);

	// Ajoute le nombre de jour à l'échéance
	$echeance = $lestamp + (preg_replace("/[^0-9]/", "", $ar_ac[0]) * 24 * 60 * 60);

	if (isset($ar_ac[1])){ 
		// Fixe la fin du mois (Au 20/01/2018 ar_ac[1] est toujours égal à fm si il existe)
		if ($ar_ac[1]=='fm'){  
			$lannee = date("Y",$echeance);
			$lemois = date("n",$echeance);
			$lejour = $ar_dureemois[$lemois];
			// SI Annee bisextile ajoute un jour au mois de février
			if ( (floor($lannee/400) == ($lannee/400)) AND $lemois=2 ){ $lejour++; }
			$echeance = mktime(0,0,1,$lemois,$lejour,$lannee);
		}
	}

	if (isset($ar_ac[2])){ 
		// Pas de test sur la chaine de carac car il n'y a que des dm (Au 21/01/2020 ar_ac[2] a toujours dm en suffixe)
		$lejour = preg_replace("/[^0-9]/", "", $ar_ac[2]);
		$lannee = date("Y",$echeance);
		$lemois = date("n",$echeance) + 1;
		if ($lemois==13){ $lemois=1; $lannee++; }
		$echeance = mktime(0,0,1,$lemois,$lejour,$lannee);
		
	}

	$ar_retour['humain'] = date("d/m/y",$echeance);
	$ar_retour['mysql'] = date("Y-m-d",$echeance);
	$ar_retour['sage'] = date("Ymd",$echeance);
	$ar_retour['statut'] = true;
	return $ar_retour;
	
}
/**
* FORMATE UNE DATE POUR UN COURRIER
* @param string $date
* @param string $typed ('humain','mysql')
* @param integer $avecheure (true,false)
*/
function CV_datecourrier($date,$typed,$avecheure){
	$ma=array(1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	
	$date = preg_replace("/[^0-9]/", "", $date);
	if ($avecheure){ $avecheure = substr($date,0,8); }
	
	$j=0; $m=0; $a=0;
	if ($typed=='humain'){
		$j = substr($date,0,2); $m = substr($date,2,2); $a = substr($date,-4);
	} else {
		$j = substr($date,-2);  $m = substr($date,4,2); $a = substr($date,0,4);
	}
	
	$lachaine  = date("j",mktime(13,52,0,$m,$j,$a))." ";
	$lachaine .= $ma[date("n",mktime(13,52,0,$m,$j,$a))]." ";
	$lachaine .= date("Y",mktime(13,52,0,$m,$j,$a));

	return $lachaine;
	
}

/**********************************************************
* Met en forme un tableau de taille de colonne
*
* @param string $valeur - Valeur du tableau a mettre en forme
* @param string $key - Cle du tableau
*/
function prefixe_width(&$valeur,$key) { $valeur = "width: ".$valeur."px;"; }

/**
* RENVOI UN TABLEAU AVEC LES TROIS ELEMENTS D'UNE DATE AU FORMAT JJ/MM/YYYY
* LES MOIS ET JOURS N'ONT PAS DE ZERO DEVANT
* @param string $a
*/
function CV_ExplodeDate($a) {
	$ar_ed = array('jour'=>'', 'mois'=>'', 'annee'=>'');
	$ar_ed['jour']  = substr($a,0,2) + 0; // Le + 0 a la fin permet de transformer de facon implicite la chaine en entier
	$ar_ed['mois']  = substr($a,3,2) + 0;
	$ar_ed['annee'] = substr($a,6,4);
	return $ar_ed;
}

?>