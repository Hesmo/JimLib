<?Php
function FILESYS_filtre_dir_link($var){ return !is_link($var); }
function FILESYS_filtre_dir_file($var){ return !is_file($var); }
function FILESYS_filtre_dir_dir($var) { return !is_dir($var);  }
function FILESYS_filtre_dir_hide($var) {  if (substr($var,0,1)==".") { return false; } else { return true; } }
function FILESYS_filtre_eaDir($var) {  if (substr($var,-6)=="@eaDir") { return false; } else { return true; } }

function FILESYS_lit_repertoire($path,$file,$link,$dir,$hidden){

	$ar_retour['dispo']=false;
	$ar_retour['fichiers'] = array();
	$ar_retour['pointeur'] = "";
	$ar_retour['txterreur'] = "";

	// Test si le path est un repertoire
	if (!is_dir($path)){ $ar_retour['txterreur'] = "Ce n'est pas un r�pertoire"; return $ar_retour; }
	// Ouvre le repertoire 
	if (!$d=opendir($path)){  $ar_retour['txterreur'] = "Lecture du r�pertoire impossible"; return $ar_retour; }	
	// Charge la liste des fichiers
	while ( $fichier = readdir($d) ) { 
		$clef = fileinode ($path.$fichier);
		if (!$clef){ $ar_retour['txterreur'] = "Lecture de l'inode impossible"; return $ar_retour; }
		$ar_retour['fichiers'][$clef]=$path.$fichier;
		//array_push($ar_retour['fichiers'],$path.$fichier); 
	}

	// Supprime les repertoires eaDir
	$ar_retour['fichiers'] = array_filter($ar_retour['fichiers'],'FILESYS_filtre_eaDir');
	// Supprime les liens symboliques
	if (!$link){  $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'],'FILESYS_filtre_dir_link'); }
	// Supprime les fichiers r�guliers
	if (!$file){ $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'],'FILESYS_filtre_dir_file'); }
	// Supprime les r�pertoire
	if (!$dir){ $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'],'FILESYS_filtre_dir_dir'); }
	// Supprime le repertoire dans les noms de fichiers
	foreach($ar_retour['fichiers'] as $clef=>$valeur){ // For cpt impossible car la clef n'est pas increment�
		$ar_retour['fichiers'][$clef] = str_replace($path,"", $valeur);
	}

	// Supprime les fichiers cach�s (a faire apr�s la suppression du pr�fixe path)
	if (!$hidden){  $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'],'FILESYS_filtre_dir_hide'); }

	$ar_retour['dispo'] = true;
	return $ar_retour;
}
?>