<?Php
declare(strict_types=1);
/**
 * Génère une balise ouvrante <form> avec des attributs sécurisés.
 *
 * @param string $action L'URL de destination du formulaire.
 * @param string $method La méthode HTTP (GET ou POST).
 * @param array<string, string> $attributes Tableau associatif d'attributs supplémentaires (class, id, etc.).
 * @param bool $isMultipart Si true, ajoute l'enctype pour l'upload de fichiers.
 * @return string Le code HTML de la balise <form>.
 */
function FRM2_form(
    string $action = '#',
    string $method = 'POST',
    array $attributes = [],
    bool $isMultipart = false
): string {
    
    // On s'assure que la méthode est en majuscules (standard HTML)
    $method = strtoupper($method);
    
    // Gestion de l'enctype pour les fichiers
    if ($isMultipart) {
        $attributes['enctype'] = 'multipart/form-data';
    }
	
	// On définit l'encodage pour tout le fichier
    $encoding = 'ISO-8859-1';

    // Construction de la chaîne d'attributs
    $htmlAttributes = "";
    foreach ($attributes as $key => $value) {
        // Utilisation explicite de ISO-8859-1
        $htmlAttributes .= sprintf(' %s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, $encoding));
    }

    return sprintf(
        '<form action="%s" method="%s"%s accept-charset="%s">',
        htmlspecialchars($action, ENT_QUOTES, $encoding),
        $method,
        $htmlAttributes,
        $encoding // On indique au navigateur l'encodage attendu
    );
}
/**
 * Génère la balise ouvrante <select> uniquement.
 *
 * @param string $name L'attribut name du select.
 * @param array $attributes Tableau associatif d'attributs (id, class, style, etc.).
 * @return string La balise <select> HTML.
 */
function FRM2_se(string $name, array $attributes = []): string
{
    $encoding = 'ISO-8859-1';
    
    // On force l'attribut name dans le tableau pour centraliser le traitement
    $attributes['name'] = $name;

    $htmlAttributes = "";
    foreach ($attributes as $key => $value) {
        $htmlAttributes .= sprintf(
            ' %s="%s"', 
            $key, 
            htmlspecialchars((string)$value, ENT_QUOTES, $encoding)
        );
    }

    return sprintf('<select%s>', $htmlAttributes);
}
/**
 * Génère une balise <option> pour un élément <select>.
 *
 * @param string $value La valeur technique de l'option (attribut value).
 * @param string $label Le texte affiché dans la liste.
 * @param bool $isSelected Si true, ajoute l'attribut selected.
 * @param array $attributes Attributs additionnels (class, data-*, etc.).
 * @return string La balise <option> complète.
 */
function FRM2_opt(
    string $value,
    string $label,
    bool $isSelected = false,
    array $attributes = []
): string {
    $encoding = 'ISO-8859-1';

    // Préparation de l'attribut selected
    $selectedAttr = $isSelected ? ' selected="selected"' : '';

    // Construction des attributs additionnels
    $htmlAttributes = "";
    foreach ($attributes as $key => $val) {
        $htmlAttributes .= sprintf(
            ' %s="%s"',
            $key,
            htmlspecialchars((string)$val, ENT_QUOTES, $encoding)
        );
    }

    return sprintf(
        '<option value="%s"%s%s>%s</option>',
        htmlspecialchars($value, ENT_QUOTES, $encoding),
        $selectedAttr,
        $htmlAttributes,
        htmlspecialchars($label, ENT_QUOTES, $encoding)
    );
}

?>