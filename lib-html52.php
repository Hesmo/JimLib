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
 *     @var bool   $retour      Si true, retourne la chaîne au lieu de l'afficher.
 * }
 * 
 * @return string|null La balise HTML si 'retour' est à true, sinon null.
 */
function HTML52_href(array $options = []): ?string {

    $defaults = [
        'href' => '', 'contenu' => '', 'title' => '', 'target' => '_self',
        'id' => '', 'class' => '', 'style' => '', 'download' => '',
        'data' => [], 'extra' => '', 'retour' => false
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
                trigger_error("Erreur critique dans TB2_href : le paramètre 'data' doit être un tableau.", E_USER_ERROR);
            }
            foreach ($val as $dataKey => $dataVal) {
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Cas général
        $strVal = trim((string)$val);
        if ($strVal !== '') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
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
        'data' => [], 'retour' => false // Par défaut, on affiche (echo)
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
                $safeDataVal = htmlspecialchars((string)$dataVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
                $out .= " data-$dataKey=\"$safeDataVal\"";
            }
            continue;
        }

        // Cas général pour les attributs standards
        $strVal = trim((string)$val);
        
        // On affiche l'attribut s'il n'est pas vide, 
        // SAUF pour 'src' et 'alt' qui sont souvent souhaités même vides pour la validation.
        if ($strVal !== '' || $key === 'src' || $key === 'alt') {
            $safeVal = htmlspecialchars($strVal, ENT_QUOTES | ENT_SUBSTITUTE, 'ISO-8859-1');
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
 * Affiche le début d'un document HTML5 standard.
 *
 * La fonction écrit directement (via `echo`) :
 * - le doctype HTML5,
 * - la balise `<html>` avec l'attribut `lang="fr"`,
 * - l'ouverture de la balise `<head>`.
 *
 * ?? Cette fonction n'a pas de valeur de retour : elle produit
 * uniquement une sortie HTML.
 *
 * @return void
 */
/*function HTML5_doctype(): void {
    echo "<!doctype html>\n<html lang='fr'>\n\t<head>\n";
}
function HTML5_head_off() {
    echo "\t</head>\n";
}
function HTML5_title(string $titre): void {
    echo "\t\t<title>{$titre}</title>\n";
}
function HTML5_meta_charset(string $charset = 'windows-1252'): void {
    echo "\t\t<meta charset=\"{$charset}\">\n";
}
function HTML5_headlink(string $rel, string $href): void {
    echo "\t\t<link rel=\"{$rel}\" href=\"{$href}\">\n";
}
function HTML5_script(string $fichier): void {
    echo "\t\t<script src=\"{$fichier}\"></script>\n";
}
function HTML5_body(bool $etat): void {
    if ($etat) {
        echo "<body>\n";
    } else {
        echo "</body>\n";
    }
}





function HTML5_div(
    bool $retour = false,                 // true = return, false = echo
    string $id = '',                    // Ne peut provenir que d'une variable, pas d'un argument de fonction (risque d'injection)
    string $class = '',                 // Ne peut provenir que d'une variable, pas d'un argument de fonction (risque d'injection)
    string $style = '',                 // Ne peut provenir que d'une variable, pas d'un argument de fonction (risque d'injection)
    CssPosition $position = CssPosition::RELATIVE,   // absolute | relative | fixed | static (Si static top et left ne servent a rien)
    ?int $top = null,
    ?int $left = null,
    ?int $width = null,
    CssUnit $widthUnit = CssUnit::PX,
    ?int $height = null,
    CssUnit $heightUnit = CssUnit::PX,
    CssOverflow $overflow = CssOverflow::HIDDEN     // hidden | scroll | auto | visible
): string|bool {
    $attrs = [];

    if ($id !== '') { $attrs[] = 'id="' . $id . '"'; }
    if ($class !== '') { $attrs[] = 'class="' . $class . '"'; }

    // Construction du style CSS
    $css = [];

    $css[] = 'position:' . $position->value;
    if ($top !== null) { $css[] = 'top:' . $top . 'px'; }
    if ($left !== null) { $css[] = 'left:' . $left . 'px'; }
    if ($width !== null) { $css[] = 'width:' . $width . $widthUnit->value; }
    if ($height !== null) { $css[] = 'height:' . $height . $heightUnit->value; }
    $css[] = 'overflow:' . $overflow->value;
    if ($style !== '') { $css[] = $style; }
    if (!empty($css)) { $attrs[] = 'style="' . implode(';', $css) . '"'; }

    $div = '<div ' . implode(' ', $attrs) . '>';

    if ($retour === false) {
        echo $div;
        return true;
    }

    return $div;
}

function HTML5_href_html(
    bool $retour = false,          // true = return, false = echo
    string $href,                  // URL cible
    string $contenu,               // texte ou HTML (img, span?)
    string $title = '',
    string $target = '_self',
    string $id = '',
    string $class = '',
    string $style = '',
    string $download = '',
    string $extraAttrs = ''         // data-*, aria-* (usage interne)
) {
    if ($href === '') {
        return $retour ? '' : true;
    }

    $esc = fn($v) => htmlspecialchars($v, ENT_QUOTES, 'ISO-8859-1');

    $attrs = [];
    $attrs[] = 'href="' . $esc($href) . '"';

    if ($title !== '')   { $attrs[] = 'title="'   . $esc($title)   . '"'; }
    if ($target !== '')  { $attrs[] = 'target="'  . $esc($target)  . '"'; }
    if ($target === '_blank') { $attrs[] = 'rel="noopener noreferrer"'; }

    if ($id !== '')      { $attrs[] = 'id="'      . $esc($id)      . '"'; }
    if ($class !== '')   { $attrs[] = 'class="'   . $esc($class)   . '"'; }
    if ($style !== '')   { $attrs[] = 'style="'   . $esc($style)   . '"'; }
    if ($download !== ''){ $attrs[] = 'download="' . $esc($download) . '"'; }
    if ($extraAttrs !== '') { $attrs[] = $extraAttrs; }

    $html = '<a ' . implode(' ', $attrs) . '>' . $contenu . '</a>';

    if ($retour) {
        return $html;
    }

    echo $html;
    return true;
}
*/
?>