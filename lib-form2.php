<?Php
declare(strict_types=1);
/**
 * Génère et affiche (ou retourne) la balise d'ouverture d'un formulaire (<form>).
 *
 * @param array $options {
 *     @var string $action      URL de destination du formulaire (défaut: #).
 *     @var string $method      Méthode d'envoi (GET ou POST, défaut: POST).
 *     @var bool   $multipart   Si true, ajoute l'enctype pour l'upload de fichiers.
 *     @var string $id          L'attribut HTML 'id'.
 *     @var string $class       L'attribut HTML 'class'.
 *     @var string $style       Styles CSS inline.
 *     @var array  $data        Tableau associatif pour les attributs 'data-*'.
 *     @var bool   $retour      Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise <form> ou null.
 */
function FRM2_form(array $options = []): ?string {

    $defaults = [
        'action'    => '#',
        'method'    => 'POST',
        'multipart' => false,
        'id'        => '',
        'class'     => '',
        'style'     => '',
        'data'      => [],
        'retour'    => false
    ];

    $opt = array_merge($defaults, $options);
    $encoding = 'ISO-8859-1';

    // Préparation de la balise de base
    $actionSafe = htmlspecialchars($opt['action'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
    $methodSafe = strtoupper(trim((string)$opt['method']));
    
    $out = "<form action=\"$actionSafe\" method=\"$methodSafe\" accept-charset=\"$encoding\"";

    // Gestion de l'enctype pour les fichiers
    if ($opt['multipart'] === true) {
        $out .= ' enctype="multipart/form-data"';
    }

    // Itération sur les attributs standards
    foreach ($opt as $key => $val) {
        // On ignore les clés déjà traitées ou internes
        if (in_array($key, ['action', 'method', 'multipart', 'retour'])) continue;

        // Gestion du tableau 'data'
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans FRM2_form : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Attributs classiques (id, class, style...)
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
            $out .= " $key=\"$safeVal\"";
        }
    }

    $out .= ">";

    // Sortie ou Retour
    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}

/**
 * Fermeture du formulaire.
 */
function FRM2_form_fin(): void {
    echo "</form>\n";
}
/**
 * Génère et affiche (ou retourne) la balise d'ouverture d'une liste déroulante (<select>).
 *
 * @param string $name    L'attribut HTML 'name' (obligatoire).
 * @param array  $options {
 *     @var string $id      L'attribut HTML 'id'.
 *     @var string $class   L'attribut HTML 'class'.
 *     @var string $style   Styles CSS inline.
 *     @var bool   $multiple Si true, permet la sélection multiple.
 *     @var bool   $disabled Si true, désactive le champ.
 *     @var array  $data     Tableau associatif pour les attributs 'data-*'.
 *     @var bool   $retour   Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise <select> ou null.
 */
function FRM2_se(string $name, array $options = []): ?string {

    $defaults = [
        'id'       => '',
        'class'    => 'seflat',
        'style'    => '',
        'multiple' => false,
        'disabled' => false,
        'data'     => [],
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);
    $encoding = 'ISO-8859-1';

    // On commence avec l'attribut name qui est obligatoire
    $out = "<select name=\"" . htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, $encoding) . "\"";

    foreach ($opt as $key => $val) {
        // On ignore les clés internes
        if (in_array($key, ['retour'])) continue;

        // Gestion du tableau 'data'
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans FRM2_se : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Cas des attributs booléens (multiple, disabled)
        if (is_bool($val)) {
            if ($val === true) {
                $out .= " $key";
            }
            continue;
        }

        // Attributs classiques (id, class, style...)
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
            $out .= " $key=\"$safeVal\"";
        }
    }

    $out .= ">";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}

/**
 * Fermeture de la liste déroulante.
 */
function FRM2_se_fin(): void {
    echo "</select>\n";
}
/**
 * Génère et affiche (ou retourne) une option de liste déroulante (<option>).
 *
 * @param array $options {
 *     @var string $value    La valeur de l'option (attribut value).
 *     @var string $label    Le texte affiché pour l'option.
 *     @var bool   $selected Si true, l'option sera sélectionnée.
 *     @var string $id       L'attribut HTML 'id'.
 *     @var string $class    L'attribut HTML 'class'.
 *     @var string $style    Styles CSS inline.
 *     @var array  $data     Tableau associatif pour les attributs 'data-*'.
 *     @var bool   $retour   Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise <option> complète ou null.
 */
function FRM2_opt(array $options = []): ?string {

    $defaults = [
        'value' => '', 'label' => '', 'selected' => false, 'id' => '',
        'class' => 'optflat', 'style' => '', 'data' => [], 'retour' => false
    ];

    $opt = array_merge($defaults, $options);
    $encoding = 'ISO-8859-1';

    // Sécurisation de la valeur et du label
    $safeValue = htmlspecialchars((string)$opt['value'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
    $safeLabel = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);

    $out = "<option value=\"$safeValue\"";

    foreach ($opt as $key => $val) {
        // On ignore les clés qui ne sont pas des attributs HTML directs 
        // ou qui ont déjà été traitées (value, label, selected, retour)
        if (in_array($key, ['value', 'label', 'selected', 'retour'])) continue;

        // Gestion du tableau 'data'
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans FRM2_opt : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Attributs classiques
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
            $out .= " $key=\"$safeVal\"";
        }
    }

    // Ajout de l'attribut selected
    if ($opt['selected'] === true) {
        $out .= ' selected="selected"';
    }

    $out .= ">$safeLabel</option>";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
/**
 * Génère et affiche (ou retourne) un bouton radio HTML (<input type="radio">).
 *
 * @param array $options {
 *     @var string $name    Le nom du bouton radio (attribut name).
 *     @var string $value   La valeur envoyée (attribut value).
 *     @var string $label   Le texte affiché à côté du bouton.
 *     @var bool   $checked Si true, le bouton est coché.
 *     @var string $id      L'attribut HTML 'id'.
 *     @var string $class   L'attribut HTML 'class'.
 *     @var string $style   Styles CSS inline.
 *     @var array  $data    Tableau associatif pour les attributs 'data-*'.
 *     @var string $extra   Attributs bruts (ex: onClick, onchange...).
 *     @var bool   $retour  Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null Le code HTML du bouton radio ou null.
 */
function FRM2_ir(array $options = []): ?string {

    $defaults = [
        'name'    => '', // Le nom est obligatoire pour associer les boutons radio
        'value'   => '',
        'label'   => '',
        'checked' => false,
        'id'      => '',
        'class'   => '',
        'style'   => '',
        'data'    => [],
        'extra'   => '',
        'retour'  => false
    ];

    $opt = array_merge($defaults, $options);
    $encoding = 'ISO-8859-1';

    // Début de l'élément
    $out = '<input type="radio"';

    foreach ($opt as $key => $val) {
        // On ignore les clés qui ne sont pas des attributs directs ou traitées après
        if (in_array($key, ['label', 'checked', 'retour', 'extra'])) continue;

        // Gestion du tableau 'data'
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans FRM2_ir : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Attributs classiques (name, value, id, class, style)
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
            $out .= " $key=\"$safeVal\"";
        }
    }

    // Gestion de l'état coché
    if ($opt['checked'] === true || $opt['checked'] === 1) {
        $out .= ' checked';
    }

    // Ajout des attributs extra (actions JS)
    if (trim((string)$opt['extra']) !== '') {
        $out .= ' ' . trim($opt['extra']);
    }

    // Fermeture de la balise et ajout du label (texte)
    $safeLabel = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
    $out .= '> ' . $safeLabel;

    // Sortie
    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
?>