<?Php
function FILESYS_filtre_dir_link(string $var){ return !is_link($var); }
function FILESYS_filtre_dir_file(string $var){ return !is_file($var); }
function FILESYS_filtre_dir_dir(string $var) { return !is_dir($var);  }
function FILESYS_filtre_dir_hide(string $var) {  if (substr($var,0,1)==".") { return false; } else { return true; } }
function FILESYS_filtre_eaDir(string $var) {  if (substr($var,-6)=="@eaDir") { return false; } else { return true; } }

function FILESYS_lit_repertoire(string $path, bool $file, bool $link, bool $dir,bool $hidden){

	$ar_retour = [
		'dispo'=> false,
		'fichiers'=> array(),
		'txterreur'=> ""
	];

	// Nettoyage du path : garantir un slash final
	$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

	// Test si le path est un repertoire
	if (!is_dir($path)) {
        $ar_retour['txterreur'] = "Ce n'est pas un répertoire";
        return $ar_retour;
    }

	// Ouvre le repertoire 
	if (!$d = opendir($path)) {
        $ar_retour['txterreur'] = "Lecture du répertoire impossible";
        return $ar_retour;
    }

	while (($fichier = readdir($d)) !== false) {
        // Ignorer les répertoires système "." et ".."
        if ($fichier === '.' || $fichier === '..') continue;

        $chemin_complet = $path . $fichier;
        $inode = fileinode($chemin_complet);
        
        if (!$inode) {
            $ar_retour['txterreur'] = "Lecture de l'inode impossible";
            closedir($d);
            return $ar_retour;
        }

        // On stocke le nom du fichier brut, sans le path, pour faciliter les filtres
        $ar_retour['fichiers'][$inode] = $fichier;
    }
    closedir($d);

	// Filtres sur les noms de fichiers (plus rapide que sur les chemins complets)
    // 1. Supprime les fichiers cachés (commençant par .)
    if (!$hidden) {
        $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'], function($f) {
            return $f[0] !== '.';
        });
    }

    // 2. Supprime les eaDir (Synology)
    $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'], 'FILESYS_filtre_eaDir');

    // 3. Application des filtres de type (Nécessite de reconstruire le chemin temporairement)
    // Note : On utilise le path ici pour les tests is_file/is_dir/is_link
    foreach ($ar_retour['fichiers'] as $clef => $nom) {
        $full = $path . $nom;
        if (!$link && is_link($full)) { unset($ar_retour['fichiers'][$clef]); continue; }
        if (!$file && is_file($full) && !is_link($full)) { unset($ar_retour['fichiers'][$clef]); continue; }
        if (!$dir && is_dir($full) && !is_link($full)) { unset($ar_retour['fichiers'][$clef]); continue; }
    }

    $ar_retour['dispo'] = true;
    return $ar_retour;

}
?>