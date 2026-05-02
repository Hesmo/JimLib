<?Php
/**
 * Génère et affiche la balise d'ouverture d'un tableau HTML (<table>).
 *
 * @param array $options {
 *     Tableau associatif des attributs HTML.
 *
 *     @var string $id    L'attribut HTML 'id'. Par défaut vide.
 *     @var string $class L'attribut HTML 'class'. Par défaut vide.
 *     @var string $style L'attribut HTML 'style'. Par défaut vide.
 * }
 * 
 * @return void Affiche directement la balise générée.
 *
 */
function TB2_table(array $options = []): void {
    $defaults = ['id' => '', 'class' => '', 'style' => ''];
    $opt = array_merge($defaults, $options);
    $out = "<table";
    foreach ($opt as $key => $val) {
        $val = trim((string)$val);
        if ($val !== '') {
            // ENT_SUBSTITUTE évite que la chaîne soit vide si un caractère invalide est trouvé
            $safeVal = htmlspecialchars($val, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
            $out .= " $key=\"$safeVal\"";
        }
    }
    echo $out . ">\n";
}
/**
 * Affiche la balise de fermeture d'un tableau HTML (</table>).
 *
 * @return void Affiche directement la balise.
 *
 */
function TB2_table_fin(): void {
    echo "</table>\n";
}
/**
 * Génère et affiche la balise d'ouverture d'une ligne de tableau HTML (<tr>).
 *
 * @param array $options {
 *     Tableau associatif des attributs HTML.
 *
 *     @var string $id    L'attribut HTML 'id'. Par défaut vide.
 *     @var string $class L'attribut HTML 'class'. Par défaut vide.
 *     @var string $style L'attribut HTML 'style'. Par défaut vide.
 *     @var array  $data  Tableau associatif pour générer des attributs 'data-*'.
 *                        Doit impérativement être un tableau sous peine d'arrêt du script.
 * }
 * 
 * @return void Affiche directement la balise générée.
 *
 */
function TB2_ligne(array $options = []): void {

    $defaults = ['id' => '', 'class' => '', 'style' => '', 'data' => []];
    $opt = array_merge($defaults, $options);
    $out = "<tr";

    foreach ($opt as $key => $val) {
        if ($key === 'data') {
            // Si la clé data existe mais n'est pas un tableau, on arrête tout
            if (!is_array($val)) {
                trigger_error("Erreur critique dans TB2_ligne : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
                // Note : E_USER_ERROR provoque l'arrêt du script (équivalent à un die/exit)
            }

            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue; 
        }

        // Cas général pour les attributs standards
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
            $out .= " $key=\"$safeVal\"";
        }
    }

    echo $out . ">\n";
}
/**
 * Affiche la balise de fermeture d'une ligne de tableau HTML (</tr>).
 *
 * @return void Affiche directement la balise.
 *
 */
function TB2_ligne_fin(): void {
    echo "</tr>\n";
}
/**
 * Génère et affiche la balise d'ouverture d'une cellule de tableau HTML (<td>).
 *
 * @param array $options {
 *     Tableau associatif des attributs HTML.
 *
 *     @var string $id      L'attribut HTML 'id'. Par défaut vide.
 *     @var string $class   L'attribut HTML 'class'. Par défaut vide.
 *     @var string $style   L'attribut HTML 'style'. Par défaut vide.
 *     @var int    $colspan L'attribut HTML 'colspan'. Par défaut vide.
 *     @var int    $rowspan L'attribut HTML 'rowspan'. Par défaut vide.
 *     @var array  $data    Tableau associatif pour générer des attributs 'data-*'.
 *                          Doit impérativement être un tableau sous peine d'arrêt du script.
 * }
 * 
 * @return void Affiche directement la balise générée.
 *
 */
/**
 * Génère et affiche une cellule de tableau HTML (<td>).
 * Si l'indice 'texte' est fourni, affiche le contenu et ferme la balise automatiquement.
 *
 * @param array $options {
 *     Tableau associatif des attributs HTML.
 *
 *     @var string $id      L'attribut HTML 'id'. Par défaut vide.
 *     @var string $class   L'attribut HTML 'class'. Par défaut vide.
 *     @var string $style   L'attribut HTML 'style'. Par défaut vide.
 *     @var string $texte   Le contenu de la cellule. Si présent, ferme la cellule.
 *     @var int    $colspan L'attribut HTML 'colspan'. Par défaut vide.
 *     @var int    $rowspan L'attribut HTML 'rowspan'. Par défaut vide.
 *     @var array  $data    Tableau associatif pour générer des attributs 'data-*'.
 *                          Doit impérativement être un tableau sous peine d'arrêt du script.
 * }
 * 
 * @return void Affiche directement la balise générée.
 *
 */
function TB2_cellule(array $options = []): void {

    $defaults = [
        'id' => '', 'class' => '', 'style' => '', 'texte' => null, 'colspan' => '', 'rowspan' => '', 'data' => []
    ];
    
    $opt = array_merge($defaults, $options);
    $out = "<td";

    foreach ($opt as $key => $val) {
        // On ignore l'indice 'texte' dans la boucle des attributs HTML
        if ($key === 'texte') continue;

        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans TB2_cellule : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }

            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue; 
        }

        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
            $out .= " $key=\"$safeVal\"";
        }
    }

    $out .= ">";

    // Si 'texte' est défini, on affiche le contenu et on ferme la balise
    if ($opt['texte'] !== null) {
        $out .= $opt['texte'] . "</td>";
    }

    echo $out . "\n";
}

/**
 * Affiche la balise de fermeture d'une cellule de tableau HTML (</td>).
 *
 * @return void Affiche directement la balise.
 *
 */
function TB2_cellule_fin(): void {
    echo "</td>\n";
}
?>