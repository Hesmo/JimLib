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

?>