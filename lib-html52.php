<?Php
declare(strict_types=1);
enum CssPosition: string {
    case ABSOLUTE = 'absolute';
    case RELATIVE = 'relative';
    case FIXED   = 'fixed';
    case STATIC  = 'static';
}
enum CssOverflow: string {
    case HIDDEN  = 'hidden';
    case SCROLL  = 'scroll';
    case AUTO    = 'auto';
    case VISIBLE = 'visible';
}
enum CssUnit: string {
    case PX = 'px';
    case PERCENT = '%';
}
/**
 * Génère et affiche (ou retourne) le début d'un document HTML5 standard (doctype, html, head).
 *
 * @param array $options {
 *     Tableau associatif des options de configuration du document.
 *
 *     @var string $lang Code de la langue du document HTML (défaut: 'fr').
 *     @var bool $retour Si true, retourne la chaîne HTML au lieu de l'afficher. Par défaut false.
 *     @var string $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 * }
 * 
 * @return string|null La structure HTML de démarrage si 'retour' est true, sinon null.
 */
function HTML52_doctype(array $options = []): ?string {

    $defaults = [
        'lang' => 'fr',
        'encodage' => 'ISO-8859-1',
        'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    $safeLang = htmlspecialchars(trim((string)$opt['lang']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);

    $out = "<!doctype html>\n<html lang=\"$safeLang\">\n<head>\n";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) une balise HTML meta (<meta>).
 *
 * @param array $options {
 *     Tableau associatif des paramètres de la balise meta.
 *
 *     @var string $name Attribut HTML 'name' (ex: 'description', 'viewport').
 *     @var string $content Attribut HTML 'content'.
 *     @var string $httpEquiv Attribut HTML 'http-equiv' (ex: 'X-UA-Compatible').
 *     @var string $charset Attribut HTML 'charset' (ex: 'ISO-8859-1').
 *     @var string $extra Attributs bruts complémentaires (ex: property="og:title").
 *     @var string $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 *     @var bool $retour Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * @return string|null La balise <meta> si 'retour' est true, sinon null.
 */
function HTML52_meta(array $options = []): ?string {
    $defaults = [
        'name' => '',
        'content' => '',
        'httpEquiv' => '',
        'charset' => '',
        'extra' => '',
        'encodage' => 'ISO-8859-1',
        'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    $out = "<meta";

    // Attribut charset direct (ex: <meta charset="ISO-8859-1">)
    if (trim((string)$opt['charset']) !== '') {
        $safeCharset = htmlspecialchars(trim((string)$opt['charset']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
        $out .= " charset=\"$safeCharset\"";
    }

    // Attribut http-equiv (ex: <meta http-equiv="X-UA-Compatible" content="...">)
    if (trim((string)$opt['httpEquiv']) !== '') {
        $safeHttpEquiv = htmlspecialchars(trim((string)$opt['httpEquiv']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
        $out .= " http-equiv=\"$safeHttpEquiv\"";
    }

    // Attribut name (ex: <meta name="description" content="...">)
    if (trim((string)$opt['name']) !== '') {
        $safeName = htmlspecialchars(trim((string)$opt['name']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
        $out .= " name=\"$safeName\"";
    }

    // Attribut content
    if (trim((string)$opt['content']) !== '') {
        $safeContent = htmlspecialchars(trim((string)$opt['content']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
        $out .= " content=\"$safeContent\"";
    }

    // Injection d'attributs supplémentaires (OpenGraph, etc.)
    if (trim((string)$opt['extra']) !== '') {
        $out .= " " . trim((string)$opt['extra']);
    }

    $out .= ">\n";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) une balise HTML <title>.
 *
 * @param array $options {
 *     Tableau associatif des paramètres du titre.
 *
 *     @var string $titre Le texte du titre de la page (défaut: '').
 *     @var bool $retour Si true, retourne la chaîne HTML au lieu de l'afficher. Par défaut false.
 *     @var string $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 * }
 * 
 * @return string|null La balise HTML <title> si 'retour' est true, sinon null.
 */
function HTML52_title(array $options = []): ?string {

    $defaults = [
        'titre' => '',
        'encodage' => 'ISO-8859-1',
        'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    $safeTitre = htmlspecialchars(trim((string)$opt['titre']), ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);

    $out = "<title>$safeTitre</title>\n";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) une balise HTML <link> (feuille de style, favicon, etc.).
 *
 * @param array $options {
 *     Tableau associatif des paramètres de la balise link.
 *
 *     @var string $rel Relation de la balise link (ex: 'stylesheet', 'icon') (défaut: 'stylesheet').
 *     @var string $href URL de destination du fichier (défaut: '').
 *     @var string $type Type MIME de la ressource (ex: 'text/css').
 *     @var string $media Média cible (ex: 'screen', 'print').
 *     @var string $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 *     @var bool $retour Si true, retourne la chaîne HTML au lieu de l'afficher. Par défaut false.
 * }
 * 
 * @return string|null La balise HTML <link> si 'retour' est true, sinon null.
 */
function HTML52_headlink(array $options = []): ?string {

    $defaults = [
        'rel' => 'stylesheet',
        'href' => '',
        'type' => '',
        'media' => '',
        'encodage' => 'ISO-8859-1',
        'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    // Si le lien est vide, on n'affiche rien
    if (trim((string)$opt['href']) === '') {
        return $opt['retour'] ? '' : null;
    }

    $out = "<link";

    foreach (['rel', 'href', 'type', 'media'] as $attr) {
        $strVal = trim((string)($opt[$attr] ?? ''));
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
            $out .= " $attr=\"$safeVal\"";
        }
    }

    $out .= ">\n";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) une balise <script> HTML5 pour l'inclusion d'un fichier JavaScript.
 *
 * @param array $options {
 *     Tableau associatif des paramètres de la balise <script>.
 *
 *     @var string $src Chemin ou URL vers le fichier JavaScript (obligatoire).
 *     @var bool $async Active le chargement asynchrone (async).
 *     @var bool $defer Active le chargement différé (defer).
 *     @var string $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 *     @var bool $retour Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * @return string|null La balise <script> si 'retour' est true, sinon null.
 */
function HTML52_script(array $options = []): ?string {
    $defaults = [
        'src' => '',
        'async' => false,
        'defer' => false,
        'encodage' => 'ISO-8859-1',
        'retour' => false
    ];

    $opt = array_merge($defaults, $options);
    if (trim((string)$opt['src']) === '') { return $opt['retour'] ? '' : null; }

    $out = '<script src="' . htmlspecialchars((string)$opt['src'], ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']) . '"';
    if ($opt['async'] === true) { $out .= ' async'; }
    if ($opt['defer'] === true) { $out .= ' defer'; }
    $out .= "></script>\n";
    if ($opt['retour'] === true) { return $out; }
    echo $out;
    
    return null;
}
/**
 * Ferme la balise de fermeture de l'en-tête HTML (</head>).
 *
 * @param bool $retour Si true, retourne la chaîne au lieu de l'afficher.
 * @return string|null
 */
function HTML52_head_off(bool $retour = false): ?string {
    $out = "</head>\n";

    if ($retour) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Affiche ou retourne la balise d'ouverture ou de fermeture <body> HTML.
 *
 * @param bool $etat True pour l'ouverture (<body>), false pour la fermeture (</body>).
 * @param bool $retour Si true, retourne la chaîne au lieu de l'afficher.
 * @return string|null
 */
function HTML52_body(bool $etat, bool $retour = false): ?string {
    $out = $etat ? "<body>\n" : "</body>\n";

    if ($retour) {
        return $out;
    }

    echo $out;
    return null;
}
/**
 * Génère et affiche (ou retourne) un lien HTML (<a>).
 *
 * @param array $options {
 *     Tableau associatif des paramètres du lien.
 *
 *     @var string $href        L'URL cible (obligatoire).
 *     @var string $contenu     Le texte ou HTML à l'intérieur du lien.
 *     @var string $title       L'attribut HTML 'title'.
 *     @var string $target      L'attribut HTML 'target' (défaut: _self).
 *     @var string $id          L'attribut HTML 'id'.
 *     @var string $class       L'attribut HTML 'class'.
 *     @var string $style       L'attribut HTML 'style'.
 *     @var string $download    L'attribut HTML 'download'.
 *     @var array  $data        Tableau associatif pour les attributs 'data-*'.
 *     @var string $extra       Attributs bruts (aria-*, etc.).
 *     @var string $encodage    Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 *     @var bool   $retour      Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise HTML si 'retour' est à true, sinon null.
 */
function HTML52_href(array $options = []): ?string {

    $defaults = [
        'href' => '', 'contenu' => '', 'title' => '', 'target' => '_self',
        'id' => '', 'class' => '', 'style' => '', 'download' => '',
        'data' => [], 'extra' => '', 'encodage' => 'ISO-8859-1', 'retour' => false
    ];

    $opt = array_merge($defaults, $options);

    // Si le lien est vide, on n'affiche rien
    if (trim((string)$opt['href']) === '') {
        return $opt['retour'] ? '' : null;
    }

    $out = "<a";

    foreach ($opt as $key => $val) {
        // On ignore les clés qui ne sont pas des attributs directs de la balise <a>
        if (in_array($key, ['contenu', 'retour', 'extra'])) continue;

        // Gestion des data-attributes
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans HTML52_href : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Cas général
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
            $out .= " $key=\"$safeVal\"";
            
            // Sécurité automatique pour le target _blank
            if ($key === 'target' && $strVal === '_blank') {
                $out .= ' rel="noopener noreferrer"';
            }
        }
    }

    // Ajout des attributs extra (non échappés car destinés aux aria-* ou autres)
    if ($opt['extra'] !== '') {
        $out .= " " . $opt['extra'];
    }

    // Construction finale
    $out .= ">" . $opt['contenu'] . "</a>";

    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}
/**
 * Génère et affiche (ou retourne) une balise image HTML5 (<img />).
 *
 * @param array $options {
 *     Tableau associatif des attributs de l'image.
 *
 *     @var string     $src      URL de l'image.
 *     @var string     $alt      Texte alternatif.
 *     @var string     $id       Identifiant unique.
 *     @var string     $class    Classes CSS.
 *     @var string     $style    Styles inline.
 *     @var string|int $width    Largeur.
 *     @var string|int $height   Hauteur.
 *     @var string     $title    Bulle d'aide.
 *     @var array      $data     Tableau associatif pour attributs 'data-*'.
 *     @var string     $encodage Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 *     @var bool       $retour   Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise HTML si 'retour' est à true, sinon rien.
 */
function HTML52_img(array $options = []): string|null {

    // Définition des valeurs par défaut pour l'image
    $defaults = [
        'src' => '', 'alt' => '', 'id' => '', 'class' => '', 'style' => '',
        'width' => '', 'height' => '', 'title' => '',
        'data' => [], 'encodage' => 'ISO-8859-1', 'retour' => false // Par défaut, on affiche (echo)
    ];

    $opt = array_merge($defaults, $options);
    $out = "<img";

    foreach ($opt as $key => $val) {
        // Gestion sécurisée des data-attributes
        if ($key === 'data') {
            if (!is_array($val)) {
                trigger_error("Erreur critique dans HTML52_img : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }

            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Cas général pour les attributs standards
        $strVal = trim((string)$val);
        
        // On affiche l'attribut s'il n'est pas vide, 
        // SAUF pour 'src' et 'alt' qui sont souvent souhaités même vides pour la validation.
        if ($strVal !== '' || $key === 'src' || $key === 'alt') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
            $out .= " $key=\"$safeVal\"";
        }
    }
    $out .= " />\n";

    // Logique de retour ou d'affichage
    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out;
    return null; // Optionnel, mais propre pour correspondre au prototype
}
/**
 * Génère et affiche (ou retourne) la balise d'ouverture d'un bloc (<div>).
 *
 * @param array $options {
 *     @var bool        $retour     Si true, retourne la chaîne au lieu de l'afficher.
 *     @var string      $id         L'attribut HTML 'id'.
 *     @var string      $class      L'attribut HTML 'class'.
 *     @var string      $style      Styles CSS additionnels (chaîne brute).
 *     @var CssPosition $position   Enum: ABSOLUTE, RELATIVE, FIXED, STATIC.
 *     @var int|null    $top        Position haute (px).
 *     @var int|null    $left       Position gauche (px).
 *     @var int|null    $width      Largeur.
 *     @var CssUnit     $widthUnit  Enum: PX, PERCENT.
 *     @var int|null    $height     Hauteur.
 *     @var CssUnit     $heightUnit Enum: PX, PERCENT.
 *     @var CssOverflow $overflow   Enum: HIDDEN, SCROLL, AUTO, VISIBLE.
 *     @var array       $data       Tableau pour attributs 'data-*'.
 *     @var string      $encodage   Jeu de caractères pour l'encodage HTML (défaut: 'ISO-8859-1').
 * }
 * 
 * @return string|null La balise <div> ou null selon l'option 'retour'.
 */
function HTML52_div(array $options = []): ?string {

    $defaults = [
        'retour'     => false,
        'id'         => '',
        'class'      => '',
        'style'      => '',
        'position'   => CssPosition::RELATIVE,
        'top'        => null,
        'left'       => null,
        'width'      => null,
        'widthUnit'  => CssUnit::PX,
        'height'     => null,
        'heightUnit' => CssUnit::PX,
        'overflow'   => CssOverflow::HIDDEN,
        'data'       => [],
        'encodage' => 'ISO-8859-1'
    ];

    $opt = array_merge($defaults, $options);
    
    // --- 1. Construction du style CSS via les Enums ---
    $css = [];
    $css[] = 'position:' . $opt['position']->value;
    
    if ($opt['top']    !== null) $css[] = 'top:' . $opt['top'] . 'px';
    if ($opt['left']   !== null) $css[] = 'left:' . $opt['left'] . 'px';
    if ($opt['width']  !== null) $css[] = 'width:' . $opt['width'] . $opt['widthUnit']->value;
    if ($opt['height'] !== null) $css[] = 'height:' . $opt['height'] . $opt['heightUnit']->value;
    
    $css[] = 'overflow:' . $opt['overflow']->value;

    // Fusion avec le style manuel (on nettoie les points-virgules superflus)
    if (trim((string)$opt['style']) !== '') {
        $css[] = trim((string)$opt['style'], '; ');
    }

    $finalStyle = implode(';', $css);

    // --- 2. Construction de la balise HTML ---
    $out = "<div";

    // Sécurisation ID et Class (encodage spécifié)
    if (trim((string)$opt['id']) !== '') {
        $out .= ' id="' . htmlspecialchars($opt['id'], ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']) . '"';
    }
    if (trim((string)$opt['class']) !== '') {
        $out .= ' class="' . htmlspecialchars($opt['class'], ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']) . '"';
    }

    // Le style est déjà sécurisé par les Enums et le trim, mais on l'échappe par précaution
    $out .= ' style="' . htmlspecialchars($finalStyle, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']) . '"';

    // --- 3. Gestion du tableau 'data' (Style TB2) ---
    if (!empty($opt['data'])) {
        if (!is_array($opt['data'])) {
            trigger_error("Erreur critique dans HTML52_div : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
        }
        foreach ($opt['data'] as $dataKey => $dataVal) {
            $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, $opt['encodage']);
            $out .= " data-$dataKey=\"$safeDataVal\"";
        }
    }

    $out .= ">";

    // --- 4. Sortie ou Retour ---
    if ($opt['retour'] === true) {
        return $out;
    }

    echo $out . "\n";
    return null;
}

/**
 * Fermeture du bloc div.
 */
function HTML52_div_fin(): void {
    echo "</div>\n";
}



?>