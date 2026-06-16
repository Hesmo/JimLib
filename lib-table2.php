<?Php
/**
 * Fonction interne pour sécuriser les attributs HTML.
 * Basculée en ISO-8859-1 pour éviter les conflits de caractères.
 */
function _TB2_escape(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
}
/**
 * Génère la balise d'ouverture d'un tableau HTML (<table>).
 *
 * @param array $options {
 * Tableau associatif des attributs HTML.
 *
 * @var string $id      L'attribut HTML 'id'.
 * @var string $class   L'attribut HTML 'class'.
 * @var string $style   L'attribut HTML 'style'.
 * @var bool   $retour  Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * }
 * * @return string|null Retourne le HTML si 'retour' est à true, sinon null.
 */
function TB2_table(array $options = []): ?string {
    $defaults = ['id' => '', 'class' => '', 'style' => '', 'retour' => false];
    
    // On fusionne les options reçues avec les valeurs par défaut
    $opt = array_merge($defaults, $options);

    $out = "<table";
    foreach ($opt as $key => $val) {
        // CRITIQUE : On ignore l'option 'retour' pour ne pas polluer le HTML
        if ($key === 'retour' || is_array($val)) {
            continue;
        }

        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $out .= " $key=\"" . _TB2_escape($strVal) . "\"";
        }
    }
    $out .= ">\n";

    // Gestion du retour ou de l'affichage direct
    if ($opt['retour']) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère la balise de fermeture d'un tableau HTML (</table>).
 *
 * @param bool $retour Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * @return string|null
 */
function TB2_table_fin(bool $retour = false): ?string {
    $out = "</table>\n";
    
    if ($retour) {
        return $out;
    }
    
    echo $out;
    return null;
}
/**
 * Génère la balise d'ouverture d'une ligne de tableau HTML (<tr>).
 *
 * @param array $options {
 * Tableau associatif des attributs HTML.
 *
 * @var string $id      L'attribut HTML 'id'.
 * @var string $class   L'attribut HTML 'class'.
 * @var string $style   L'attribut HTML 'style'.
 * @var array  $data    Tableau associatif pour générer des attributs 'data-*'.
 * @var bool   $retour  Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * }
 * @return string|null Retourne le HTML si 'retour' est à true, sinon null.
 */
function TB2_ligne(array $options = []): ?string {
    $defaults = ['id' => '', 'class' => '', 'style' => '', 'data' => [], 'retour' => false];
    
    // Validation stricte du type de 'data' avant la fusion
    if (isset($options['data']) && !is_array($options['data'])) {
        trigger_error("Erreur critique dans TB2_ligne : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }

    $opt = array_merge($defaults, $options);
    $out = "<tr";

    foreach ($opt as $key => $val) {
        // CRITIQUE : On ignore 'retour' pour ne pas générer un attribut retour="1"
        if ($key === 'retour') {
            continue;
        }

        if ($key === 'data') {
            foreach ($val as $dataKey => $dataVal) {
                $out .= " data-$dataKey=\"" . _TB2_escape((string)$dataVal) . "\"";
            }
            continue; 
        }

        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $out .= " $key=\"" . _TB2_escape($strVal) . "\"";
        }
    }

    $out .= ">\n";

    if ($opt['retour']) {
        return $out;
    }

    echo $out;
    return null;
}

/**
 * Génère la balise de fermeture d'une ligne de tableau HTML (</tr>).
 *
 * @param bool $retour Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * @return string|null
 */
function TB2_ligne_fin(bool $retour = false): ?string {
    $out = "</tr>\n";
    
    if ($retour) {
        return $out;
    }
    
    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) une cellule de tableau HTML (<td>).
 * Si l'indice 'texte' est fourni, affiche le contenu et ferme la balise automatiquement.
 *
 * @param array $options {
 * Tableau associatif des attributs HTML.
 *
 * @var string $id       L'attribut HTML 'id'.
 * @var string $class    L'attribut HTML 'class'.
 * @var string $style    L'attribut HTML 'style'.
 * @var string $texte    Le contenu de la cellule. Si présent, ferme la cellule automatiquement.
 * @var int    $colspan  L'attribut HTML 'colspan'.
 * @var int    $rowspan  L'attribut HTML 'rowspan'.
 * @var array  $data     Tableau associatif pour générer des attributs 'data-*'.
 * @var bool   $retour   Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * }
 * @return string|null Retourne le HTML généré si 'retour' est true, sinon null.
 */
function TB2_cellule(array $options = []): ?string {

    $defaults = [
        'id' => '', 'class' => '', 'style' => '', 'texte' => null, 
        'colspan' => '', 'rowspan' => '', 'data' => [], 'retour' => false
    ];
    
    if (isset($options['data']) && !is_array($options['data'])) {
        trigger_error("Erreur critique dans TB2_cellule : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }

    $opt = array_merge($defaults, $options);
    $out = "<td";

    foreach ($opt as $key => $val) {
        // CRITIQUE : On ignore 'texte' et 'retour' pour ne pas polluer la balise HTML
        if ($key === 'texte' || $key === 'retour') {
            continue;
        }

        if ($key === 'data') {
            foreach ($val as $dataKey => $dataVal) {
                $out .= " data-$dataKey=\"" . _TB2_escape((string)$dataVal) . "\"";
            }
            continue; 
        }

        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $out .= " $key=\"" . _TB2_escape($strVal) . "\"";
        }
    }

    $out .= ">";

    // Si 'texte' est défini, on injecte le contenu et on ferme le <td>
    if ($opt['texte'] !== null) {
        $out .= $opt['texte'] . "</td>";
    }

    // Gestion du retour de la chaîne ou de l'affichage direct
    if ($opt['retour']) {
        return $out;
    }

    // Si on affiche directement, on ajoute un saut de ligne si la cellule est fermée
    echo $out . ($opt['texte'] !== null ? "\n" : "");
    return null;
}

/**
 * Génère la balise de fermeture d'une cellule de tableau HTML (</td>).
 *
 * @param bool $retour Si true, retourne le HTML au lieu de l'afficher. Par défaut false.
 * @return string|null
 */
function TB2_cellule_fin(bool $retour = false): ?string {
    $out = "</td>\n";
    
    if ($retour) {
        return $out;
    }
    
    echo $out;
    return null;
}
?>