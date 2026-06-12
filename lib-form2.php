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
 *     @var string $name        L'attribut HTML 'name'.
 *     @var string $class       L'attribut HTML 'class'.
 *     @var string $style       Styles CSS inline.
 *     @var array  $data        Tableau associatif pour les attributs 'data-*'.
 *     @var bool   $retour      Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise <form> ou null.
 */
function FRM2_form(array $options = []): ?string {

    // 1. Validation et synchronisation de id et name
    $hasId = isset($options['id']) && trim((string)$options['id']) !== '';
    $hasName = isset($options['name']) && trim((string)$options['name']) !== '';

    if (!$hasId && !$hasName) {
        trigger_error("Erreur critique dans FRM2_form : Vous devez fournir au moins un attribut 'id' ou 'name'.", E_USER_ERROR);
    }

    if ($hasId && !$hasName) {
        $options['name'] = $options['id'];
    } elseif ($hasName && !$hasId) {
        $options['id'] = $options['name'];
    }

    $defaults = [
        'action'    => '#',
        'method'    => 'POST',
        'multipart' => false,
        'id'        => '',
        'name'      => '',
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
 * Remplace FRM_se
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
*   @var string $name    Le nom du bouton radio (attribut name).
*   @var string $value   La valeur envoyée (attribut value).
*   @var string $label   Le texte affiché à côté du bouton.
*   @var bool   $checked Si true, le bouton est coché.
*   @var bool   $disabled Si true, le bouton est grisé / désactivé.
*   @var string $id      L'attribut HTML 'id'.
*   @var string $class   L'attribut HTML 'class'.
*   @var string $style   Styles CSS inline.
*   @var array  $data    Tableau associatif pour les attributs 'data-*'.
*   @var string $extra   Attributs bruts (ex: onClick, onchange...).
*   @var bool   $retour  Si true, retourne la chaîne au lieu de l'afficher.
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
        'disabled' => false,
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

    // Gestion de l'état désactivé (HTML5 standard : juste 'disabled')
    if ($opt['disabled'] === true || $opt['disabled'] == 1) {
        $out .= ' disabled';
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
/**
 * Génère et affiche (ou retourne) un champ de saisie HTML (<input>).
 *
 * @param array $options {
 *     @var string $type         Type d'input (text, password, email, etc. - défaut: text).
 *     @var string $label        Texte affiché devant l'input.
 *     @var string $name         Nom de l'élément (attribut name).
 *     @var string $value        Valeur par défaut.
 *     @var string $placeholder  Texte d'aide en fond de champ.
 *     @var int    $size         Taille visuelle du champ.
 *     @var int    $maxlength    Nombre de caractères maximum.
 *     @var string $id           L'attribut HTML 'id'.
 *     @var string $class        L'attribut HTML 'class'.
 *     @var string $style        Styles CSS inline.
 *     @var bool   $readonly     Si true, le champ est en lecture seule.
 *     @var bool   $autocomplete Si false, ajoute autocomplete="off".
 *     @var array  $data         Tableau associatif pour les attributs 'data-*'.
 *     @var bool   $retour       Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null Le code HTML de l'input ou null.
 */
function FRM2_it(array $options = []): ?string {

    $defaults = [
        'type'         => 'text',
        'label'        => '',
        'name'         => '',
        'value'        => '',
        'placeholder'  => '',
        'size'         => null,
        'maxlength'    => null,
        'id'           => '',
        'class'        => 'itflat',
        'style'        => '',
        'readonly'     => false,
        'autocomplete' => true,
        'data'         => [],
        'retour'       => false
    ];

    $opt = array_merge($defaults, $options);
    $encoding = 'ISO-8859-1';

    // Début de la sortie avec le label
    $out = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
    
    // Construction de la balise input
    $typeSafe = htmlspecialchars($opt['type'], ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
    $out .= "<input type=\"$typeSafe\"";

    foreach ($opt as $key => $val) {
        // On ignore les clés déjà traitées ou internes
        if (in_array($key, ['type', 'label', 'readonly', 'autocomplete', 'retour', 'maxlength'])) continue;

        // Gestion du tableau 'data'
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans FRM2_it : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Attributs classiques (name, value, id, class, style, size, placeholder)
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
            $out .= " $key=\"$safeVal\"";
        }
    }

    // Gestion de maxlength (séparé car souvent un entier)
    if ($opt['maxlength'] !== null) {
        $out .= ' maxlength="' . (int)$opt['maxlength'] . '"';
    }

    // Attributs booléens
    if ($opt['readonly'] === true) {
        $out .= ' readonly';
    }
    if ($opt['autocomplete'] === false) {
        $out .= ' autocomplete="off"';
    }

    // Ajout des attributs extra (scripts, etc.)
    $out .= ">\n";

    // Sortie
    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère un menu déroulant complet à partir d'une table SQL (format bdd.table supporté).
 * 
 * @param array $options {
 *     @var string $name        Nom du select (obligatoire).
 *     @var string $table       Nom complet de la table (ex: "compta.fournisseurs").
 *     @var string $val_field   Nom du champ SQL pour la 'value'.
 *     @var string $lbl_field   Nom du champ SQL pour le libellé.
 *     @var string $selected    Valeur à sélectionner.
 *     @var string $order       Ordre de tri (ex: "nom ASC").
 *     @var string $where       Condition WHERE.
 *     @var string $first_opt   Option vide (ex: "-- Choisir --").
 *     @var string $first_opt_val   // Valeur par défaut -1
 *     @var string $format_lbl  Fonction de formatage du label (ex: 'ucfirst', 'strtoupper', 'ucwords'....).
 *     @var array  $se_options  Options pour FRM2_se (class, id, data...).
 *     @var bool   $retour      Si true, retourne le HTML.
 * }
 */
function FRM2_select_from_table(array $options = []): ?string {
    global $mysqli;

    $defaults = [
        'name'           => '',
        'table'          => '',
        'val_field'     => '',
        'lbl_field'     => '',
        'selected'      => '',
        'order'         => '',
        'where'         => '1',
        'first_opt'     => null,
        'first_opt_val' => '-1',
        'format_lbl'    => null, // Nouvelle option
        'se_options'    => [],
        'retour'        => false
    ];

    $opt = array_merge($defaults, $options);
    $html = "";

    // 1. Initialisation du SELECT
    $se_params = $opt['se_options'];
    $se_params['retour'] = true;
    $html .= FRM2_se($opt['name'], $se_params);

    // 2. Première option (vide)
    if ($opt['first_opt'] !== null) {
        $html .= FRM2_opt([
            'value'  => $opt['first_opt_val'],
            'label'  => $opt['first_opt'],
            'selected' => ((string)$opt['first_opt_val'] === (string)$opt['selected']),
            'retour' => true
        ]);
    }

    // 3. Construction de la requête
    // On split le nom de la table pour protéger bdd et table séparément
    $tableParts = explode('.', $opt['table']);
    if (count($tableParts) === 2) {
        $fullTableName = "`" . $tableParts[0] . "`.`" . $tableParts[1] . "`";
    } else {
        $fullTableName = "`" . $opt['table'] . "`";
    }

    $orderBy = ($opt['order'] !== '') ? "ORDER BY " . $opt['order'] : "";
    
    $requete = "SELECT `" . $opt['val_field'] . "`, `" . $opt['lbl_field'] . "` 
                FROM $fullTableName 
                WHERE " . $opt['where'] . " 
                $orderBy";

    $resultat = DTBS_sqlbrut($requete, $mysqli);

    // 4. Génération des options
    if ($resultat['statut'] && $resultat['nbrec'] > 0) {
        while ($row = mysqli_fetch_assoc($resultat['resultat'])) {
            $val = $row[$opt['val_field']];
            $lbl = $row[$opt['lbl_field']];
            
            if (!empty($opt['format_lbl']) && function_exists($opt['format_lbl'])) {
                $lbl = $opt['format_lbl']($lbl);
            }

            $html .= FRM2_opt([
                'value'    => $val,
                'label'    => $lbl, 
                'selected' => ((string)$val === (string)$opt['selected']),
                'retour'   => true
            ]);
        }
    }

    $html .= "</select>\n";

    if ($opt['retour'] === true) {
        return $html;
    }

    echo $html;
    return null;
}
/**
 * Génère un menu déroulant complet à partir des valeurs d'un champ ENUM.
 * 
 * @param array $options {
 *     @var string $name        Nom du select (obligatoire).
 *     @var string $table       Nom de la table (format bdd.table supporté).
 *     @var string $field       Nom du champ ENUM.
 *     @var string $selected    Valeur à sélectionner par défaut.
 *     @var string $first_opt   Libellé d'une première option (ex: "-- Statut --").
 *     @var string $first_opt_val Valeur de la première option (défaut: -1).
 *     @var string $format_lbl  Fonction de formatage du label (ex: 'ucfirst', 'strtoupper').
 *     @var array  $se_options  Options pour FRM2_se (class, id, data...).
 *     @var bool   $retour      Si true, retourne le HTML au lieu de l'afficher.
 * }
 */
function FRM2_select_from_enum(array $options = []): ?string {
    global $mysqli;

    $defaults = [
        'name'          => '',
        'table'         => '',
        'field'         => '',
        'selected'      => '',
        'first_opt'     => null,
        'first_opt_val' => '-1',
        'format_lbl'    => null, // Nouvelle option de formatage
        'se_options'    => [],
        'retour'        => false
    ];

    $opt = array_merge($defaults, $options);
    $html = "";

    // 1. Initialisation du SELECT via ta fonction FRM2_se
    $se_params = $opt['se_options'];
    $se_params['retour'] = true;
    $html .= FRM2_se($opt['name'], $se_params);

    // 2. Première option optionnelle
    if ($opt['first_opt'] !== null) {
        $html .= FRM2_opt([
            'value'    => $opt['first_opt_val'],
            'label'    => $opt['first_opt'],
            'selected' => ((string)$opt['first_opt_val'] === (string)$opt['selected']),
            'retour'   => true
        ]);
    }

    // 3. Récupération de la structure du champ ENUM
    $tableParts = explode('.', $opt['table']);
    $fullTableName = (count($tableParts) === 2) 
        ? "`" . $tableParts[0] . "`.`" . $tableParts[1] . "`" 
        : "`" . $opt['table'] . "`";

    $safeField = mysqli_real_escape_string($mysqli, $opt['field']);
    $resSQL = DTBS2_sqlbrut($mysqli, "SHOW COLUMNS FROM $fullTableName LIKE '$safeField'");
    //$res = mysqli_query($mysqli, "SHOW COLUMNS FROM $fullTableName LIKE '$safeField'");
    
    //if ($res && $row = mysqli_fetch_assoc($res)) {
    if ($resSQL['statut'] && $row = mysqli_fetch_assoc($resSQL['resultat'])) {
        // Extraction des valeurs entre enum('...', '...')
        if (preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches)) {
            $enum_values = explode("','", $matches[1]);
            
            foreach ($enum_values as $val) {
                $lbl = $val;
                if (!empty($opt['format_lbl']) && function_exists($opt['format_lbl'])) {
                    $lbl = $opt['format_lbl']($val);
                }
                $html .= FRM2_opt([
                    'value'    => $val,
                    'label'    => $lbl, 
                    'selected' => ((string)$val === (string)$opt['selected']),
                    'retour'   => true
                ]);
            }
        }
    }

    $html .= "</select>\n";

    if ($opt['retour'] === true) {
        return $html;
    }

    echo $html;
    return null;
}
/**
 * Génère un bouton HTML (input type submit/button/reset).
 * 
 * @param array $options {
 *     @var string $class    Classes CSS.
 *     @var string $type     Type du bouton (default: submit).
 *     @var string $name     Nom de l'élément (default: boutton_soumission).
 *     @var string $value    Texte du bouton (default: Ok).
 *     @var string $action   Attributs JS (ex: onclick="...").
 *     @var string $style    Style CSS en ligne.
 *     @var string $id       ID unique de l'élément.
 *     @var int    $tabindex Attribut tabindex pour l'ordre de tabulation.
 *     @var string $disabled Attribut disabled (si vrai, ajoute 'disabled').
 *     @var array  $data     Tableau associatif pour les attributs data- (ex: ['id' => 1]).
 *     @var bool   $retour   Si true, retourne le HTML au lieu de l'afficher.
 * }
 */
function FRM2_bt(array $options = []): ?string {
    
    $defaults = [
        'class'    => 'btflat',
        'type'     => 'submit',
        'name'     => 'boutton_soumission',
        'value'    => 'Ok',
        'action'   => '',
        'style'    => '',
        'id'       => '',
        'tabindex' => '',
        'disabled' => false,
        'data'     => [],
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);

    // Gestion des attributs data-
    $dataStr = "";
    if (!empty($opt['data'])) {
        foreach ($opt['data'] as $key => $val) {
            $dataStr .= ' data-' . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
        }
    }

    // Préparation de l'ID et du Disabled
    $idStr = (!empty($opt['id'])) ? ' id="' . $opt['id'] . '"' : '';
    $disabledStr = ($opt['disabled'] === true || $opt['disabled'] === 'disabled') ? ' disabled' : '';

    $html = sprintf(
        '<input%s type="%s" class="%s" name="%s" value="%s" %s style="%s"%s%s>' . "\n",
        $idStr,
        $opt['type'],
        $opt['class'],
        $opt['name'],
        htmlspecialchars($opt['value']), // Sécurité sur le libellé
        $opt['action'],
        $opt['style'],
        $disabledStr,
        $dataStr
    );

    if ($opt['retour'] === true) {
        return $html;
    }

    echo $html;
    return null;
}

/**
 * Génère et affiche une zone de texte (textarea).
 *
 * @param array $options {
 * Tableau associatif des paramètres.
 *
 * @var string $name     Nom du champ (défaut: 'textarea_default').
 * @var string $class    Classe CSS (défaut: vide).
 * @var string $style    Style CSS inline (défaut: vide).
 * @var int    $rows     Nombre de lignes (défaut: 4).
 * @var int    $cols     Nombre de colonnes (défaut: 20).
 * @var string $value    Contenu de la zone de texte (défaut: vide).
 * @var string $action   Attributs additionnels bruts (défaut: vide).
 * @var bool   $readonly Indique si le champ est en lecture seule (défaut: false).
 * @var bool   $retour   Si true, retourne la chaîne au lieu de l'afficher (défaut: false).
 * }
 * * @return string|null La balise textarea ou null si affichée directement.
 */
function FRM2_ta(array $options = []): ?string {
    $defaults = [
        'name'     => '',
        'class'    => 'itflat',
        'style'    => '',
        'rows'     => 4,
        'cols'     => 20,
        'value'    => '',
        'action'   => '',
        'readonly' => false,
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);
    
    $element = '<textarea name="' . htmlspecialchars($opt['name']) . '"';
    
    if ($opt['class'] !== '')    $element .= ' class="' . htmlspecialchars($opt['class']) . '"';
    if ($opt['style'] !== '')    $element .= ' style="' . htmlspecialchars($opt['style']) . '"';
    if ($opt['rows'] !== '')     $element .= ' rows="' . (int)$opt['rows'] . '"';
    if ($opt['cols'] !== '')     $element .= ' cols="' . (int)$opt['cols'] . '"';
    if ($opt['readonly'] === true) $element .= ' readonly';
    if ($opt['action'] !== '')   $element .= ' ' . $opt['action'];
    
    $element .= '>' . htmlspecialchars($opt['value']) . '</textarea>' . "\n";

    if ($opt['retour'] === true) {
        return $element;
    }
    echo $element;
    return null;
}
/**
 * Génère une case à cocher (input type="checkbox").
 * 
 * @param array $options {
 * @var string $name        Nom du champ.
 * @var string $value       Valeur soumise si cochée.
 * @var string $class       Classes CSS.
 * @var string $style       Style CSS en ligne.
 * @var bool   $checked     Si vrai, coche la case (accepte true/1).
 * @var string $text        Texte affiché à côté de la checkbox (libellé).
 * @var int    $tabindex Attribut tabindex pour l'ordre de tabulation.
 * @var string $action      Attributs JS (ex: onclick="...", onchange="...").
 * @var bool   $readonly    Si vrai, bloque la modification (via un return false au clic).
 * @var array  $data        Tableau associatif pour les attributs data- (ex: ['id' => 1]).
 * @var string $extra1      Attribut ou chaîne libre supplémentaire.
 * @var string $extra2      Deuxième attribut ou chaîne libre supplémentaire.
 * @var bool   $retour      Si true, retourne le HTML au lieu de l'afficher.
 * }
 */
function FRM2_cb(array $options = []): ?string {
    
    $defaults = [
        'name'     => '',
        'value'    => '',
        'class'    => '',
        'style'    => '',
        'checked'  => false,
        'text'     => '',
        'tabindex' => '',
        'action'   => '',
        'readonly' => false,
        'data'     => [],
        'extra1'   => '',
        'extra2'   => '',
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);

    $html = '<input type="checkbox"';

    if (!empty($opt['name'])) { 
        $html .= ' name="' . $opt['name'] . '"'; 
    }
    if (!empty($opt['class'])) { 
        $html .= ' class="' . $opt['class'] . '"'; 
    }
    if (!empty($opt['style'])) { 
        $html .= ' style="' . $opt['style'] . '"'; 
    }
    if (trim((string)$opt['value']) !== '') { 
        $html .= ' value="' . htmlspecialchars($opt['value']) . '"'; 
    }

    // Gestion du checked (souple : accepte true, 1 ou "1")
    if ($opt['checked'] === true || $opt['checked'] == 1) { 
        $html .= ' checked'; 
    }

    // Gestion des attributs JS d'action
    if (!empty($opt['action'])) { 
        $html .= ' ' . $opt['action']; 
    }

    // Ton astuce métier readonly pour jQuery
    if ($opt['readonly'] === true || $opt['readonly'] == 1) { 
        $html .= " onclick='return false;'"; 
    }

    // Gestion des attributs data- (standardisés TB2)
    if (!empty($opt['data'])) {
        foreach ($opt['data'] as $key => $val) {
            $html .= ' data-' . htmlspecialchars($key) . '="' . htmlspecialchars($val) . '"';
        }
    }

    // Conservation de tes extras
    if (!empty($opt['extra1'])) { $html .= ' ' . $opt['extra1']; }
    if (!empty($opt['extra2'])) { $html .= ' ' . $opt['extra2']; }

    // On ferme le input et on ajoute le texte d'accompagnement protégé
    $html .= '> ' . htmlspecialchars($opt['text']) . "\n";

    if ($opt['retour'] === true) {
        return $html;
    }

    echo $html;
    return null;
}
?>