<?Php
/* Liste des codes erreurs et traduction en langage courant */
$ar_errmysql[1451] = "Suppression impossible, enregistrement utilisé dans une autre table";
$ar_errmysql[1062] = "Ajout impossible, enregistrement déjà présent";
$ar_errmysql[1452] = "Action impossible, en raison d'une contrainte de clé etrangère";

/**
 * Exécute une requête SELECT générique sur une base de données MySQL / MariaDB.
 *
 * Cette fonction permet de construire dynamiquement une requête SELECT
 * à partir d'un tableau d'options et d'exécuter cette requête via MySQLi.
 * Les clauses WHERE, GROUP BY et ORDER BY sont optionnelles.
 *
 * Les paramètres sont fournis sous forme de tableau associatif afin de
 * permettre un appel de la fonction dans n'importe quel ordre.
 *
 * ?? Sécurité :
 * Les paramètres SQL (champ, condition, groupby, tri) ne doivent pas
 * être alimentés directement par des données utilisateur non filtrées,
 * sous peine d?injection SQL.
 *
 * @param mysqli $pointeur
 * Connexion MySQLi valide et active.
 *
 * @param array $options
 * Tableau associatif des options de la requête :
 *  - table     : (string) Nom de la table à interroger (obligatoire)
 *  - champ     : (string) Champs à sélectionner (par défaut "*")
 *  - condition : (string) Clause WHERE sans le mot-clé WHERE
 *  - groupby   : (string) Clause GROUP BY sans le mot-clé GROUP BY
 *  - tri       : (string) Clause ORDER BY sans le mot-clé ORDER BY
 *
 * @return array
 * Tableau de retour structuré contenant :
 *  - statut   : (bool) Succès ou échec de l?exécution
 *  - erreur   : (string) Message d?erreur MySQL en cas d?échec
 *  - requete  : (string) Requête SQL générée
 *  - nbrec    : (int) Nombre d?enregistrements retournés
 *  - resultat : (mysqli_result|int) Résultat MySQLi ou 0 en cas d?erreur
 */
function DTBS2_select(mysqli $pointeur, array $options = []) {
	
	// Déclaration du tableau de retour
	$ar_retour = array( 'statut'=>true, 'erreur'=>"", 'requete'=>"", 'nbrec'=>0, 'resultat'=>0 );
	// Parametres par défaut
	$defaults = [
        'table' => '', 'champ' => '*', 'condition' => '', 'groupby' => '', 'tri' => ''
	];
	// Fusion des parametres par défaut et des parametres fournis (les param fournis ecrase les param par défaut)
    $opt = array_merge($defaults, $options);

	// Test si au moint une table est fournie
	if (trim($opt['table'])===""){ $ar_retour['statut']=false; $ar_retour['erreur'] = "Table non fourni"; return $ar_retour; }

    // Construction de la requete
	$sql = "SELECT {$opt['champ']} FROM {$opt['table']}";
    if ($opt['condition']) $sql .= " WHERE {$opt['condition']}";
    if ($opt['groupby'])   $sql .= " GROUP BY {$opt['groupby']}";
    if ($opt['tri'])       $sql .= " ORDER BY {$opt['tri']}";

	$ar_retour['requete'] = $sql;

	// Exécution de la requete
	$ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete'],MYSQLI_STORE_RESULT);
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_num_rows($ar_retour['resultat']);
	}
	return $ar_retour;
    
}
/**
 * Supprime un ou plusieurs enregistrements dans une table MySQL
 * en utilisant une clause WHERE fournie en texte libre.
 *
 * Le nom de la table est sécurisé en étant entouré de backticks, afin
 * d'éviter les conflits ou injections via un nom de table manipulé.
 *
 * @param mysqli $pointeur
 *        Ressource de connexion MySQLi valide.
 *
 * @param string $table
 *        Nom de la table dans laquelle effectuer la suppression.
 *        Le nom est automatiquement protégé par des backticks.
 *
 * @param string $clause
 *        Clause WHERE (sans le mot-clé WHERE), telle que :
 *        "id = 5" ou "etat = 'A' AND actif = 1".
 *        ? Attention : cette clause n'est PAS sécurisée automatiquement.
 *
 * @return array{
 *     statut: bool,
 *     erreur: string,
 *     requete: string,
 *     nbrec: int,
 *     resultat: mixed
 * }
 *     Retourne un tableau contenant :
 *     - statut   : true si la suppression a réussi, sinon false
 *     - erreur   : message d'erreur MySQL en cas d'échec
 *     - requete  : la requête SQL exécutée
 *     - nbrec    : nombre de lignes supprimées
 *     - resultat : résultat brut de mysqli_query()
 *
 * Exemple d'utilisation :
 *
 *     $ret = DTBS2_efface_rec($db, "clients", "id = 42");
 *     if ($ret['statut']) {
 *         echo $ret['nbrec'] . " ligne(s) supprimée(s)";
 *     } else {
 *         echo "Erreur : " . $ret['erreur'];
 *     }
 *
 */
function DTBS2_efface_rec(mysqli $pointeur, string $table, string $clause) {
	
	// Déclaration du tableau de retour
	$ar_efface = array( 'statut'=>true, 'erreur'=>"", 'requete'=>"", 'nbrec'=>0, 'resultat'=>0 );

	// Ajout de backticks pour protéger le nom de table
	//$table_safe = "`" . str_replace("`", "``", $table) . "`";

	$ar_efface['requete'] = "DELETE FROM $table WHERE $clause";
	$ar_efface['resultat'] = mysqli_query($pointeur, $ar_efface['requete']);
	if (!$ar_efface['resultat']) {
		$ar_efface['statut']= false;
		$ar_efface['erreur']= mysqli_error($pointeur);
	} else {
		$ar_efface['nbrec'] = mysqli_affected_rows($pointeur);
	}
	return $ar_efface;
		
}

/**
 * Modifie un ou plusieurs champs d'un enregistrement dans une table MySQL.
 *
 * Cette fonction construit dynamiquement une requête UPDATE avec une clause
 * WHERE fournie en texte libre. Elle sécurise le nom de la table, les noms
 * des colonnes et les valeurs, afin de réduire les risques d'injection SQL.
 *
 * ? Attention :
 * - La clause WHERE est insérée telle quelle et n'est PAS sécurisée.
 *   L'appelant doit s'assurer qu'elle ne contient aucun contenu dangereux.
 * - Pour une sécurité totale, utiliser une version basée sur des requêtes
 *   préparées (prepared statements).
 *
 * @param mysqli $pointeur
 *        Ressource MySQLi valide représentant la connexion à la base.
 *
 * @param string $table
 *        Nom de la table à modifier. Le nom est protégé automatiquement
 *        par des backticks et l'échappement des backticks internes.
 *
 * @param string $clause
 *        Clause WHERE (sans contrôle automatique). Exemple :
 *        "id = 5" ou "id = 3 AND actif = 1".
 *        ? La fonction n'ajoute pas de LIMIT.
 *
 * @param array<string,string> $ar_field
 *        Tableau associatif des champs à modifier :
 *        - clé   : nom du champ (alphanumérique + underscore)
 *        - valeur : nouvelle valeur
 *
 *        Exemple :
 *        [
 *            "nom" => "Dupont",
 *            "date_modif" => "NOW()"
 *        ]
 *
 *        Si la valeur est EXACTEMENT "NOW()", elle sera insérée comme fonction SQL
 *        sans guillemets.
 *
 * @return array{
 *     statut: bool,
 *     erreur: string,
 *     requete: string,
 *     resultat: mixed,
 *     nbrec: int
 * }
 *     Tableau de retour comprenant :
 *     - statut   : true si la requête a réussi, sinon false
 *     - erreur   : texte de l?erreur MySQL en cas d?échec
 *     - requete  : la requête SQL générée
 *     - resultat : retour brut de mysqli_query()
 *     - nbrec    : nombre de lignes affectées
 *
 * @example
 *     $modif = DTBS2_modif_rec(
 *         $db,
 *         "clients",
 *         "id = 42",
 *         [
 *             "nom"        => "Martin",
 *             "date_modif" => "NOW()"
 *         ]
 *     );
 *
 *     if ($modif['statut']) {
 *         echo "Ligne modifiée : " . $modif['nbrec'];
 *     } else {
 *         echo "Erreur SQL : " . $modif['erreur'];
 *     }
 */

function DTBS2_modif_rec(mysqli $pointeur, string $table, string $clause, array $ar_field) {

	$ar_retour = array(
		'statut'=>true,
		'erreur'=>"",
		'requete'=>"",
		'resultat'=>0,
		'nbrec'=>0
	);
	$ar_stock = [];

	// Ajout de backticks pour protéger le nom de table
	//$table_safe = "`" . str_replace("`", "``", $table) . "`";

	// Constition de la requete
	$ar_retour['requete'] = "UPDATE ".$table." SET ";
	foreach ($ar_field as $clef=>$valeur){ 
		// Sécurisation du nom de champ
		if (!preg_match("/^[a-zA-Z0-9_]+$/", $clef)) {
			$ar_retour['statut'] = false;
			$ar_retour['erreur'] = "Nom de champ invalide : $clef";
			return $ar_retour;
		}
		// NOW() ou valeur normale ?
		if ($valeur === "NOW()") {
            $ar_stock[] = "`$clef` = NOW()";
        } else {
            $valeur_safe = "'" . mysqli_real_escape_string($pointeur, $valeur) . "'";
            $ar_stock[] = "`$clef` = $valeur_safe";
        }
	}
	$ar_retour['requete'] .= implode(", ", $ar_stock);
	// Clause WHERE brute (non sécurisée)
    $ar_retour['requete'] .= " WHERE $clause";

	// Exécution
    $ar_retour['resultat'] = mysqli_query($pointeur, $ar_retour['requete']);
	if (!$ar_retour['resultat']) {
		$ar_retour['statut']= false;
		$ar_retour['erreur']= mysqli_error($pointeur);
	} else {
		$ar_retour['nbrec'] = mysqli_affected_rows($pointeur);
	}
	return $ar_retour;

}
/**
 * Ajoute un enregistrement dans une table MySQL via l'extension mysqli.
 *  
 * @param mysqli $mysqli    L'instance de connexion active
 * @param string $table     Le nom de la table
 * @param array $ar_nval    Tableau associatif [nom_champ => valeur]
 * @return array            Tableau de retour avec les clés :
 *                          - statut (bool) : true si succès, false sinon
 *                          - erreur (string) : message d'erreur MySQL en cas d'échec
 *                          - requete (string) : la requête SQL générée
 *                          - resultat (mixed) : résultat brut de mysqli_query()
 *                          - insert_id (int) : ID de l'enregistrement inséré ou -1 en cas d'échec
 */

function DTBS2_add_rec(mysqli $mysqli, string $table, array $ar_nval): array {
    $ar_retour = [
        'statut'    => true,
        'erreur'    => "",
        'requete'   => "",
        'resultat'  => 0,
        'insert_id' => -1
    ];
	
	if (empty($ar_nval)) {
        $ar_retour['statut'] = false;
        $ar_retour['erreur'] = "Le tableau de données est vide.";
        return $ar_retour;
    }

	// 1. Préparation des éléments de la requête
    $columns = implode(", ", array_keys($ar_nval));
    $placeholders = implode(", ", array_fill(0, count($ar_nval), "?"));

	// On stocke la requête SQL "template" pour le retour
	$table_protected = str_replace('.', '`.`', $table);
    $ar_retour['requete'] = "INSERT INTO `$table_protected` ($columns) VALUES ($placeholders)";

    // 2. Préparation du Statement
    $stmt = $mysqli->prepare($ar_retour['requete']);
	if (!$stmt) {
        $ar_retour['statut'] = false;
        $ar_retour['erreur'] = "Erreur de préparation : " . $mysqli->error;
        return $ar_retour;
    }

	// 3. Typage dynamique (i = int, d = double, s = string)
    $types = "";
    foreach ($ar_nval as $value) {
        if (is_int($value)) $types .= "i";
        elseif (is_double($value)) $types .= "d";
        else $types .= "s";
    }

	// 4. Liaison et exécution
	$params = array_values($ar_nval); 
	$stmt->bind_param($types, ...$params); // Plus de souligné ici car $params est une variable

    if ($stmt->execute()) {
        $ar_retour['resultat'] = $stmt->affected_rows;
        $ar_retour['insert_id'] = $mysqli->insert_id;
    } else {
        $ar_retour['statut'] = false;
        $ar_retour['erreur'] = "Erreur d'exécution : " . $stmt->error;
    }

    $stmt->close();
    return $ar_retour;
}
/**
 * Extrait les valeurs possibles d'un champ ENUM d'une table MariaDB.
 * Pour faire un select utiliiser plutot FRM2_select_from_enum
 * 
 * @param mysqli $mysqli  Lien de connexion.
 * @param string $table   Nom de la table (format bdd.table supporté).
 * @param string $field   Nom du champ ENUM.
 * @return array          Tableau contenant les valeurs de l'énumération.
 * 
 */
function DTBS2_get_choice_enum($mysqli, $table, $field): array {
    
	

    // Protection du nom de la table pour le format bdd.table
    $tableParts = explode('.', $table);
    $fullTableName = (count($tableParts) === 2) 
        ? "`" . $tableParts[0] . "`.`" . $tableParts[1] . "`" 
        : "`" . $table . "`";

    $requete = "SHOW COLUMNS FROM $fullTableName LIKE '" . mysqli_real_escape_string($mysqli, $field) . "'";
    $res = mysqli_query($mysqli, $requete);
    
    if (!$res) {
        return [];
    }

    $row = mysqli_fetch_assoc($res);
    
    // Le type ressemble à : enum('Valeur 1','Valeur 2','Valeur 3')
    if (preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches)) {
        // On sépare par ',' (les valeurs sont entourées de quotes dans la chaîne retournée par SQL)
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    return [];
}
/**
 * Exécute une requête SQL brute (V2) et retourne un compte-rendu complet.
 * 
 * @param mysqli $pointeur  Le lien de connexion mysqli. 
 * @param string $requete   La requête SQL à exécuter.
 * @return array {
 *     @var bool   $statut   True si succès, False si erreur SQL.
 *     @var string $erreur   Message d'erreur mysqli_error.
 *     @var string $requete  La requête telle qu'envoyée.
 *     @var int    $nbrec    Lignes retournées (lecture) ou affectées (écriture).
 *     @var mixed  $resultat Ressource mysqli_result ou boolean.
 * }
 */
function DTBS2_sqlbrut(mysqli $pointeur, string $requete): array {
    
    $ar_retour = [
        'statut'   => true,
        'erreur'   => "",
        'requete'  => $requete,
        'nbrec'    => 0,
        'resultat' => null
    ];

    $ar_retour['resultat'] = mysqli_query($pointeur, $requete);

    if (!$ar_retour['resultat']) {
        $ar_retour['statut'] = false;
        $ar_retour['erreur'] = mysqli_error($pointeur);
    } else {
        // Nettoyage et passage en majuscules du début de la requête
        $start = strtoupper(ltrim($requete));

        // Liste des commandes qui retournent un jeu de résultats (lecture)
        if (str_starts_with($start, 'SELECT') || 
            str_starts_with($start, 'SHOW')   || 
            str_starts_with($start, 'DESCRIBE')) {
            $ar_retour['nbrec'] = mysqli_num_rows($ar_retour['resultat']);
        } else {
            // Commandes d'écriture (INSERT, UPDATE, DELETE, etc.)
            $ar_retour['nbrec'] = mysqli_affected_rows($pointeur);
        }
    }

    return $ar_retour;
}
?>