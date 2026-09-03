<?php
declare(strict_types=1);
/**
 * Filtre : Exclut les liens symboliques.
 */
function FILESYS2_filtre_dir_link(string $var): bool { return !is_link($var); }

/**
 * Filtre : Exclut les fichiers simples.
 */
function FILESYS2_filtre_dir_file(string $var): bool {return !is_file($var); }

/**
 * Filtre : Exclut les répertoires.
 */
function FILESYS2_filtre_dir_dir(string $var): bool { return !is_dir($var); }

/**
 * Filtre : Exclut les éléments cachés (commençant par un point).
 */
function FILESYS2_filtre_dir_hide(string $var): bool { return !str_starts_with($var, '.'); }

/**
 * Filtre : Exclut les répertoires d'indexation Synology (@eaDir).
 */
function FILESYS2_filtre_eaDir(string $var): bool { return !str_ends_with($var, '@eaDir'); }

/**
 * Lit le contenu d'un répertoire sur le disque et applique des filtres typés.
 *
 * @param string $path Chemin cible du répertoire
 * @param bool $file Conserver les fichiers
 * @param bool $link Conserver les liens symboliques
 * @param bool $dir Conserver les dossiers
 * @param bool $hidden Conserver les fichiers cachés
 *
 * @return array{dispo: bool, fichiers: array<int, string>, txterreur: string}
 */
function FILESYS2_lit_repertoire(string $path, bool $file, bool $link, bool $dir, bool $hidden): array {

    $ar_retour = [
        'dispo' => false,
        'fichiers' => [],
        'txterreur' => ""
    ];

    // Nettoyage du path : garantir un slash final
    $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    // Test si le path est un répertoire valide
    if (!is_dir($path)) {
        $ar_retour['txterreur'] = "Ce n'est pas un répertoire";
        return $ar_retour;
    }

    // Ouvre le répertoire 
    $d = opendir($path);
    if (!$d) {
        $ar_retour['txterreur'] = "Lecture du répertoire impossible";
        return $ar_retour;
    }

    while (($fichier = readdir($d)) !== false) {
        // Ignorer les répertoires système "." et ".."
        if ($fichier === '.' || $fichier === '..') {
            continue;
        }

        $chemin_complet = $path . $fichier;
        $inode = fileinode($chemin_complet);
        
        if ($inode === false) {
            $ar_retour['txterreur'] = "Lecture de l'inode impossible";
            closedir($d);
            return $ar_retour;
        }

        // On stocke le nom du fichier brut indexé par son inode
        $ar_retour['fichiers'][$inode] = $fichier;
    }
    closedir($d);

    // 1. Suppression des fichiers cachés via la fonction de filtre dédiée
    if (!$hidden) {
        $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'], 'FILESYS2_filtre_dir_hide');
    }

    // 2. Suppression des répertoires système Synology (@eaDir)
    $ar_retour['fichiers'] = array_filter($ar_retour['fichiers'], 'FILESYS2_filtre_eaDir');

    // 3. Application des filtres sur le type d'élément (link, file, dir)
    foreach ($ar_retour['fichiers'] as $clef => $nom) {
        $full = $path . $nom;

        if (!$link && !FILESYS2_filtre_dir_link($full)) {
            unset($ar_retour['fichiers'][$clef]);
            continue;
        }
        if (!$file && !FILESYS2_filtre_dir_file($full) && FILESYS2_filtre_dir_link($full)) {
            unset($ar_retour['fichiers'][$clef]);
            continue;
        }
        if (!$dir && !FILESYS2_filtre_dir_dir($full) && FILESYS2_filtre_dir_link($full)) {
            unset($ar_retour['fichiers'][$clef]);
            continue;
        }
    }

    $ar_retour['dispo'] = true;
    return $ar_retour;
}