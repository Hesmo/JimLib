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
	return( mktime( substr($w,11,2),substr($w,14,2),substr($w,17,2),substr($w,5,2),substr($w,8,2),substr($w,0,4) ) );
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




?>