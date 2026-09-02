<?Php
declare(strict_types=1);
/**
 * Librairie de Génération de Formulaires HTML (Version 2)
 *
 * Centralise la génération sécurisée des composants de formulaires
 * via un passage de paramètres par tableau associatif unique.
 *
 * @package FRM2
 * @version 2.0.0
 */

// Définition de l'encodage cible pour la sécurisation XSS (XHTML / HTML5)
define('FRM_ENCODING', 'ISO-8859-1');

/**
 * Génère et affiche (ou retourne) la balise d'ouverture d'un formulaire (<form>).
 *
 * Synchronise automatiquement les attributs 'id' et 'name' si l'un d'eux est manquant.
 * Déclenche une erreur critique si aucun identifiant n'est fourni.
 *
 * @param array $options {
 * Configuration optionnelle du formulaire.
 *
 * @var string $action    URL de destination du formulaire (défaut: '#').
 * @var string $method    Méthode HTTP d'envoi : GET ou POST (défaut: 'POST').
 * @var bool   $multipart Si true, force l'enctype pour le téléversement de fichiers.
 * @var string $id        L'attribut HTML 'id'. Obligatoire si 'name' est vide.
 * @var string $name      L'attribut HTML 'name'. Obligatoire si 'id' est vide.
 * @var string $class     Classes CSS de l'élément.
 * @var string $style     Styles CSS inline.
 * @var array  $data      Tableau associatif de paires clés/valeurs pour attributs 'data-*'.
 * @var bool   $retour    Si true, retourne la chaîne HTML brute au lieu de l'afficher.
 * }
 * @return string|null La balise HTML <form> ouverte ou null si affichée directement.
 */
function FRM2_form(array $options = []): ?string {

    // 1. Validation et synchronisation de id et name
    $id = trim((string)($options['id'] ?? ''));
    $name = trim((string)($options['name'] ?? ''));

    if ($id === '' && $name === '') {
        trigger_error("Erreur critique dans FRM2_form : Vous devez fournir au moins un attribut 'id' ou 'name'.", E_USER_ERROR);
    }

    if ($id !== '' && $name === '') { $options['name'] = $id; }
    if ($name !== '' && $id === '') { $options['id'] = $name; }

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

    $actionSafe = htmlspecialchars($opt['action'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);
    $methodSafe = strtoupper(trim((string)$opt['method']));
    
    $out = "<form action=\"$actionSafe\" method=\"$methodSafe\" accept-charset=\"" . FRM_ENCODING . "\"";

    if ($opt['multipart'] === true) {
        $out .= ' enctype="multipart/form-data"';
    }

    // Exportation sécurisée des attributs textuels autorisés
    $allowedAttributes = ['id', 'name', 'class', 'style'];
    foreach ($allowedAttributes as $attr) {
        $strVal = trim((string)($opt[$attr] ?? ''));
        if ($strVal !== '') {
            $out .= " $attr=\"" . htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
        }
    }

    // Traitement du tableau 'data'
    if (!is_array($opt['data'])) {
        trigger_error("Erreur critique dans FRM2_form : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }
    foreach ($opt['data'] as $dataKey => $dataVal) {
        $out .= " data-" . htmlspecialchars((string)$dataKey, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "=\"" . htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
    }

    $out .= ">";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
/**
 * Ferme la balise de formulaire ouverte (<form>).
 *
 * @return void
 */
function FRM2_form_fin(): void {
    echo "</form>\n";
}
/**
 * Génère et affiche (ou retourne) la balise d'ouverture d'une liste déroulante (<select>).
 *
 * @param string $name    L'attribut HTML 'name' unique de l'élément (obligatoire).
 * @param array  $options {
 * Configuration optionnelle du menu déroulant.
 *
 * @var string $id        L'attribut HTML 'id'.
 * @var string $class     Classes CSS applicables (défaut: 'seflat').
 * @var string $style     Styles CSS inline.
 * @var int|string $tabindex Index numérique configurant l'ordre séquentiel de tabulation clavier.
 * @var bool   $multiple  Active la sélection multiple d'éléments.
 * @var bool   $disabled  Désactive l'interaction avec le champ.
 * @var array  $data      Tableau associatif pour les attributs dynamiques 'data-*'.
 * @var bool   $retour    Si true, retourne la chaîne HTML brute au lieu de l'afficher.
 * }
 * @return string|null La balise HTML <select> ou null si affichée directement.
 */
function FRM2_se(string $name, array $options = []): ?string {

    $defaults = [
        'id'       => '',
        'class'    => 'seflat',
        'style'    => '',
        'tabindex' => '',
        'multiple' => false,
        'disabled' => false,
        'data'     => [],
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);

    $out = "<select name=\"" . htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";

    foreach (['id', 'class', 'style'] as $attr) {
        $strVal = trim((string)($opt[$attr] ?? ''));
        if ($strVal !== '') {
            $out .= " $attr=\"" . htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
        }
    }

    if (trim((string)$opt['tabindex']) !== '') {
        $out .= ' tabindex="' . (int)$opt['tabindex'] . '"';
    }

    if ($opt['multiple'] === true) { $out .= " multiple"; }
    if ($opt['disabled'] === true) { $out .= " disabled"; }

    if (!is_array($opt['data'])) {
        trigger_error("Erreur critique dans FRM2_se : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }
    foreach ($opt['data'] as $dataKey => $dataVal) {
        $out .= " data-" . htmlspecialchars((string)$dataKey, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "=\"" . htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
    }

    $out .= ">";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
/**
 * Ferme la balise de liste déroulante sélectionnée (</select>).
 *
 * @return void
 */
function FRM2_se_fin(): void {
    echo "</select>\n";
}
/**
 * Génère et affiche (ou retourne) une option unitaire de liste déroulante (<option>).
 *
 * @param array $options {
 * Configuration de la balise option.
 *
 * @var string $value    Valeur interne renvoyée par le formulaire (attribut 'value').
 * @var string $label    Texte d'affichage utilisateur situé entre les balises.
 * @var bool   $selected Indique si l'option est active / pré-sélectionnée.
 * @var string $id       L'attribut HTML 'id'.
 * @var string $class    Classes CSS (défaut: 'optflat').
 * @var string $style    Styles CSS inline.
 * @var array  $data      Tableau associatif pour les attributs 'data-*'.
 * @var bool   $retour    Si true, retourne la chaîne HTML brute au lieu de l'afficher.
 * }
 * @return string|null Code HTML de la balise <option> complète ou null.
 */
function FRM2_opt(array $options = []): ?string {

    $defaults = [
        'value' => '', 'label' => '', 'selected' => false, 'id' => '',
        'class' => 'optflat', 'style' => '', 'data' => [], 'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    $safeValue = htmlspecialchars((string)$opt['value'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);
    $safeLabel = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);

    $out = "<option value=\"$safeValue\"";

    foreach (['id', 'class', 'style'] as $attr) {
        $strVal = trim((string)($opt[$attr] ?? ''));
        if ($strVal !== '') {
            $out .= " $attr=\"" . htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
        }
    }

    if (!is_array($opt['data'])) {
        trigger_error("Erreur critique dans FRM2_opt : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }
    foreach ($opt['data'] as $dataKey => $dataVal) {
        $out .= " data-" . htmlspecialchars((string)$dataKey, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "=\"" . htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
    }

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
 * Configuration du composant radio.
 *
 * @var string $name      Nom partagé d'association du groupe radio.
 * @var string $value     Valeur transmise si l'élément est coché.
 * @var string $label     Libellé textuel affiché immédiatement après l'input.
 * @var bool   $checked   Indique si le bouton est activé par défaut (accepte true/1).
 * @var bool   $disabled  Grise l'élément et bloque la modification native.
 * @var string $id        L'attribut HTML 'id'.
 * @var string $class     Classes CSS applicables.
 * @var string $style     Styles CSS inline.
 * @var int|string $tabindex Index numérique configurant l'ordre séquentiel de tabulation clavier.
 * @var array  $data      Tableau associatif pour les attributs dynamiques 'data-*'.
 * @var string $extra     Attributs bruts injectés en fin de balise (ex: événements JS).
 * @var bool   $retour    Si true, retourne la chaîne HTML brute au lieu de l'afficher.
 * }
 * @return string|null Le code HTML du bouton radio complet ou null.
 */
function FRM2_ir(array $options = []): ?string {

    $defaults = [
        'name'     => '', 
        'value'    => '',
        'label'    => '',
        'checked'  => false,
        'disabled' => false,
        'id'       => '',
        'class'    => '',
        'style'    => '',
        'tabindex' => '',
        'data'     => [],
        'extra'    => '',
        'retour'   => false
    ];

    $opt = array_merge($defaults, $options);

    $out = '<input type="radio"';

    foreach (['name', 'value', 'id', 'class', 'style'] as $attr) {
        $strVal = trim((string)($opt[$attr] ?? ''));
        if ($strVal !== '') {
            $out .= " $attr=\"" . htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
        }
    }

    if (trim((string)$opt['tabindex']) !== '') {
        $out .= ' tabindex="' . (int)$opt['tabindex'] . '"';
    }

    if ($opt['checked'] === true || $opt['checked'] == 1) { $out .= ' checked'; }
    if ($opt['disabled'] === true || $opt['disabled'] == 1) { $out .= ' disabled'; }
    
    if (trim((string)$opt['extra']) !== '') {
        $out .= ' ' . trim($opt['extra']);
    }

    if (!is_array($opt['data'])) {
        trigger_error("Erreur critique dans FRM2_ir : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }
    foreach ($opt['data'] as $dataKey => $dataVal) {
        $out .= " data-" . htmlspecialchars((string)$dataKey, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "=\"" . htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
    }

    $safeLabel = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);
    $out .= '> ' . $safeLabel;

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
/**
 * Génère et affiche (ou retourne) un champ de saisie classique (<input>).
 *
 * Permet l'instanciation rapide de champs textuels, numériques ou secrets (mot de passe).
 *
 * @param array $options {
 * Configuration du composant input.
 *
 * @var string $type         Type d'input HTML standard (text, password, number, email...) (défaut: 'text').
 * @var string $label        Texte d'affichage brut placé immédiatement avant la balise input.
 * @var string $name         Nom de l'élément (attribut 'name').
 * @var string $value        Valeur par défaut pré-remplie.
 * @var string $placeholder  Indicateur textuel d'aide en fond de champ.
 * @var int|null $size       Dimensionnement visuel CSS/HTML natif.
 * @var int|null $maxlength  Nombre maximal de caractères autorisés à la saisie.
 * @var string $id           L'attribut HTML 'id'.
 * @var string $class        Classes CSS appliquées (défaut: 'itflat').
 * @var string $style        Styles CSS inline.
 * @var int|string $tabindex Index numérique configurant l'ordre séquentiel de tabulation clavier.
 * @var bool   $readonly     Passe le champ en mode lecture seule si true.
 * @var bool   $autocomplete Désactive l'historique de saisie si défini sur false ('off').
 * @var array  $data          Tableau associatif pour attributs 'data-*'.
 * @var bool   $retour        Si true, retourne la chaîne HTML au lieu de l'afficher.
 * }
 * @return string|null Le code HTML complet généré (label + input) ou null.
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
        'tabindex'     => '',
        'readonly'     => false,
        'autocomplete' => true,
        'data'         => [],
        'retour'       => false
    ];

    $opt = array_merge($defaults, $options);

    $out = htmlspecialchars((string)$opt['label'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);
    
    $typeSafe = htmlspecialchars($opt['type'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING);
    $out .= "<input type=\"$typeSafe\"";

    foreach (['name', 'value', 'placeholder', 'id', 'class', 'style', 'size'] as $attr) {
        if ($opt[$attr] !== null && trim((string)$opt[$attr]) !== '') {
            $out .= " $attr=\"" . htmlspecialchars(trim((string)$opt[$attr]), ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
        }
    }

    if ($opt['maxlength'] !== null) {
        $out .= ' maxlength="' . (int)$opt['maxlength'] . '"';
    }

    if (trim((string)$opt['tabindex']) !== '') {
        $out .= ' tabindex="' . (int)$opt['tabindex'] . '"';
    }

    if ($opt['readonly'] === true) { $out .= ' readonly'; }
    if ($opt['autocomplete'] === false) { $out .= ' autocomplete="off"'; }

    if (!is_array($opt['data'])) {
        trigger_error("Erreur critique dans FRM2_it : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
    }
    foreach ($opt['data'] as $dataKey => $dataVal) {
        $out .= " data-" . htmlspecialchars((string)$dataKey, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "=\"" . htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\"";
    }

    $out .= ">\n";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère un menu déroulant dynamique (<select>) alimenté par une requête de base de données.
 *
 * Gère automatiquement les tables préfixées par base de données (ex: 'compta.fournisseurs').
 *
 * @param array $options {
 * Configuration SQL et HTML du composant.
 *
 * @var string $name          Nom unique du select (obligatoire).
 * @var string $table         Nom complet de la table SQL à interroger.
 * @var string $val_field     Nom de la colonne SQL utilisée pour le paramètre 'value'.
 * @var string $lbl_field     Nom de la colonne SQL stockant le libellé utilisateur.
 * @var string $selected      Valeur courante devant être cochée par défaut.
 * @var string $order         Instruction optionnelle de tri SQL (ex: "nom_champ ASC").
 * @var string $where         Condition SQL restrictive (par défaut '1' pour tout lire).
 * @var string|null $first_opt Libellé d'une première option neutre (ex: "-- Choisir --").
 * @var string $first_opt_val Valeur associée à l'option neutre (défaut: '-1').
 * @var string|null $format_lbl Fonction PHP de callback pour formater le texte (ex: 'ucfirst').
 * @var array  $se_options    Sous-tableau d'options HTML spécifiques passées à FRM2_se, exemple style.
 * @var bool   $retour        Si true, retourne l'intégralité du code HTML produit.
 * }
 * @return string|null Le code HTML complet de la liste déroulante ou null.
 */
function FRM2_select_from_table(array $options = []): ?string {
    global $mysqli;

    $defaults = [
        'name'           => '',
        'table'          => '',
        'val_field'      => '',
        'lbl_field'      => '',
        'selected'       => '',
        'order'          => '',
        'where'          => '1',
        'first_opt'      => null,
        'first_opt_val'  => '-1',
        'format_lbl'     => null, 
        'se_options'     => [],
        'retour'         => false
    ];

    $opt = array_merge($defaults, $options);
    $html = "";

    $se_params = $opt['se_options'];
    $se_params['retour'] = true;
    $html .= FRM2_se($opt['name'], $se_params);

    if ($opt['first_opt'] !== null) {
        $html .= FRM2_opt([
            'value'  => $opt['first_opt_val'],
            'label'  => $opt['first_opt'],
            'selected' => ((string)$opt['first_opt_val'] === (string)$opt['selected']),
            'retour' => true
        ]);
    }

    $tableParts = explode('.', $opt['table']);
    $fullTableName = (count($tableParts) === 2) 
        ? "`" . $tableParts[0] . "`.`" . $tableParts[1] . "`" 
        : "`" . $opt['table'] . "`";

    $orderBy = ($opt['order'] !== '') ? "ORDER BY " . $opt['order'] : "";
    
    $safeValField = mysqli_real_escape_string($mysqli, $opt['val_field']);
    $safeLblField = mysqli_real_escape_string($mysqli, $opt['lbl_field']);

    $requete = "SELECT `$safeValField`, `$safeLblField` FROM $fullTableName WHERE " . $opt['where'] . " $orderBy";

    $resultat = DTBS2_sqlbrut($mysqli, $requete);

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

    if ($opt['retour'] === true) { return $html; }
    echo $html;
    return null;
}
/**
 * Génère un menu déroulant (<select>) à partir des valeurs restrictives d'un champ SQL de type ENUM.
 *
 * @param array $options {
 * Configuration d'extraction et de rendu du champ ENUM.
 *
 * @var string $name          Nom de l'élément HTML généré.
 * @var string $table         Nom complet de la table hôte (supporte le format 'bdd.table').
 * @var string $field         Nom exact de la colonne structurée en ENUM.
 * @var string $selected      Valeur à pré-sélectionner à l'affichage.
 * @var string|null $first_opt Libellé d'en-tête optionnel (ex: "-- Choisir statut --").
 * @var string $first_opt_val Valeur de l'option d'en-tête (défaut: '-1').
 * @var string|null $format_lbl Callback de formatage linguistique (ex: 'strtoupper').
 * @var int|string $tabindex  Index numérique configurant l'ordre séquentiel de tabulation clavier.
 * @var array  $se_options    Sous-tableau d'options héritées pour FRM2_se.
 * @var bool   $retour        Si true, extrait le HTML au lieu de l'imprimer.
 * }
 * @return string|null La structure HTML générée complète ou null.
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
        'format_lbl'    => null, 
        'tabindex'      => '',
        'se_options'    => [],
        'retour'        => false
    ];

    $opt = array_merge($defaults, $options);
    $html = "";

    // Récupération des paramètres pour le composant <select>
    $se_params = $opt['se_options'];
    
    // Si un tabindex a été fourni au niveau principal, on le transmet à FRM2_se
    if (trim((string)$opt['tabindex']) !== '') {
        $se_params['tabindex'] = $opt['tabindex'];
    }
    
    $se_params['retour'] = true;
    $html .= FRM2_se($opt['name'], $se_params);

    if ($opt['first_opt'] !== null) {
        $html .= FRM2_opt([
            'value'    => $opt['first_opt_val'],
            'label'    => $opt['first_opt'],
            'selected' => ((string)$opt['first_opt_val'] === (string)$opt['selected']),
            'retour'   => true
        ]);
    }

    $tableParts = explode('.', $opt['table']);
    $fullTableName = (count($tableParts) === 2) 
        ? "`" . $tableParts[0] . "`.`" . $tableParts[1] . "`" 
        : "`" . $opt['table'] . "`";

    $safeField = mysqli_real_escape_string($mysqli, $opt['field']);
    $resSQL = DTBS2_sqlbrut($mysqli, "SHOW COLUMNS FROM $fullTableName LIKE '$safeField'");
    
    if ($resSQL['statut'] && $row = mysqli_fetch_assoc($resSQL['resultat'])) {
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

    if ($opt['retour'] === true) { return $html; }
    echo $html;
    return null;
}
/**
 * Génère un bouton d'action ou de validation HTML (<input type="submit|button|reset">).
 *
 * @param array $options {
 * Configuration structurelle du bouton.
 *
 * @var string $class    Classes CSS graphiques à appliquer (défaut: 'btflat').
 * @var string $type     Attribut de comportement natif : 'submit', 'button', 'reset' (défaut: 'submit').
 * @var string $name     Nom de variable transmis lors de la soumission (défaut: 'boutton_soumission').
 * @var string $value    Texte de l'étiquette affiché à l'intérieur du bouton (défaut: 'Ok').
 * @var string $action   Événements ou attributs JS natifs injectés textuellement (ex: 'onclick="..."').
 * @var string $style    Déclarations CSS inline spécifiques.
 * @var string $id       Identifiant HTML 'id' unique.
 * @var int|string $tabindex Index numérique configurant l'ordre séquentiel de tabulation clavier.
 * @var bool|string $disabled Désactive l'action et grise l'affichage si true ou 'disabled'.
 * @var array  $data      Tableau clé/valeur pour l'injection d'attributs HTML5 'data-*'.
 * @var bool   $retour    Si défini sur true, renvoie la chaîne HTML sans l'afficher.
 * }
 * @return string|null Le code HTML du bouton d'action sécurisé ou null.
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

    $dataStr = "";
    if (is_array($opt['data'])) {
        foreach ($opt['data'] as $key => $val) {
            $dataStr .= ' data-' . htmlspecialchars((string)$key, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '="' . htmlspecialchars((string)$val, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"';
        }
    }

    $idStr = (!empty($opt['id'])) ? ' id="' . htmlspecialchars((string)$opt['id'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"' : '';
    $disabledStr = ($opt['disabled'] === true || $opt['disabled'] === 'disabled') ? ' disabled' : '';
    $tabIndexStr = (!empty($opt['tabindex'])) ? ' tabindex="' . (int)$opt['tabindex'] . '"' : '';

    $html = sprintf(
        '<input%s type="%s" class="%s" name="%s" value="%s" %s style="%s"%s%s%s>' . "\n",
        $idStr,
        htmlspecialchars($opt['type'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING),
        htmlspecialchars($opt['class'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING),
        htmlspecialchars($opt['name'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING),
        htmlspecialchars($opt['value'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING), 
        $opt['action'],
        htmlspecialchars($opt['style'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING),
        $disabledStr,
        $tabIndexStr,
        $dataStr
    );

    if ($opt['retour'] === true) { return $html; }
    echo $html;
    return null;
}

/**
 * Génère et affiche (ou retourne) une zone de texte multiligne (<textarea>).
 *
 * @param array $options {
 * Configuration de la zone de texte.
 *
 * @var string $name     Nom unique identifiant l'élément lors du POST/GET.
 * @var string $class    Classes CSS affectées (défaut: 'itflat').
 * @var string $style    Déclarations graphiques CSS inline.
 * @var int    $rows     Nombre maximal de lignes textuelles visibles par défaut (défaut: 4).
 * @var int    $cols     Largeur indicative calculée en caractères (défaut: 20).
 * @var string $value    Contenu textuel par défaut injecté à l'intérieur du champ.
 * @var string $action   Attributs événementiels complémentaires injectés en brut.
 * @var bool   $readonly Empêche la modification textuelle directe si défini sur true.
 * @var bool   $retour    Si true, extrait le HTML au lieu d'exécuter un echo.
 * }
 * @return string|null La structure textuelle complète HTML <textarea> ou null.
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
    
    $element = '<textarea name="' . htmlspecialchars($opt['name'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"';
    
    if ($opt['class'] !== '')    $element .= ' class="' . htmlspecialchars($opt['class'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"';
    if ($opt['style'] !== '')    $element .= ' style="' . htmlspecialchars($opt['style'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"';
    if ($opt['rows'] !== '')     $element .= ' rows="' . (int)$opt['rows'] . '"';
    if ($opt['cols'] !== '')     $element .= ' cols="' . (int)$opt['cols'] . '"';
    if ($opt['readonly'] === true) $element .= ' readonly';
    if ($opt['action'] !== '')   $element .= ' ' . $opt['action'];
    
    $element .= '>' . htmlspecialchars($opt['value'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '</textarea>' . "\n";

    if ($opt['retour'] === true) { return $element; }
    echo $element;
    return null;
}
/**
 * Génère et affiche (ou retourne) une case à cocher unitaire (<input type="checkbox">).
 *
 * Utilise l'interception événementielle 'onclick' pour émuler de manière transparente
 * un état 'readonly' inexistant nativement sur les composants checkboxes HTML.
 *
 * @param array $options {
 * Configuration détaillée de la case à cocher.
 *
 * @var string $name     Nom de variable pour la transmission des tableaux ou variables.
 * @var string $value    Valeur renvoyée au serveur si la case est cochée.
 * @var string $class    Classes CSS applicables.
 * @var string $style    Directives CSS inline spécifiques.
 * @var bool   $checked  Force la pré-activation de la case (accepte true/1).
 * @var string $text     Libellé descriptif de sécurité affiché à droite de la checkbox.
 * @var int|string $tabindex Configuration de l'accessibilité par tabulation séquentielle.
 * @var string $action   Attributs d'écoute JS complémentaires (ex: 'onchange="..."').
 * @var bool   $readonly Si true, neutralise le clic utilisateur via le retour logique de clic.
 * @var array  $data      Tableau clé/valeur gérant l'exportation vers attributs 'data-*'.
 * @var string $extra1   Chaîne d'attributs complémentaires personnalisés de premier niveau.
 * @var string $extra2   Chaîne d'attributs complémentaires personnalisés de second niveau.
 * @var bool   $retour    Si vrai, dévie la sortie vers un retour de fonction.
 * }
 * @return string|null Le code HTML complet de la checkbox accompagnée de son texte ou null.
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
        $html .= ' name="' . htmlspecialchars($opt['name'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"'; 
    }
    if (!empty($opt['class'])) { 
        $html .= ' class="' . htmlspecialchars($opt['class'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"'; 
    }
    if (!empty($opt['style'])) { 
        $html .= ' style="' . htmlspecialchars($opt['style'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"'; 
    }
    if (trim((string)$opt['value']) !== '') { 
        $html .= ' value="' . htmlspecialchars($opt['value'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"'; 
    }
    if (!empty($opt['tabindex'])) { 
        $html .= ' tabindex="' . (int)$opt['tabindex'] . '"'; 
    }

    if ($opt['checked'] === true || $opt['checked'] == 1) { $html .= ' checked'; }
    if (!empty($opt['action'])) { $html .= ' ' . $opt['action']; }

    if ($opt['readonly'] === true || $opt['readonly'] == 1) { 
        $html .= " onclick='return false;'"; 
    }

    if (is_array($opt['data'])) {
        foreach ($opt['data'] as $key => $val) {
            $html .= ' data-' . htmlspecialchars((string)$key, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '="' . htmlspecialchars((string)$val, ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . '"';
        }
    }

    if (!empty($opt['extra1'])) { $html .= ' ' . $opt['extra1']; }
    if (!empty($opt['extra2'])) { $html .= ' ' . $opt['extra2']; }

    $html .= '> ' . htmlspecialchars($opt['text'], ENT_QUOTES | ENT_SUBSTITUTE, FRM_ENCODING) . "\n";

    if ($opt['retour'] === true) { return $html; }
    echo $html;
    return null;
}
?>