<?Php
/** 
* Objet qui représente une connexion à la base de données
* 
* @param string $bdd_requete        Requete SQL la chaine ;bdd_field_set; est remplacée par Une construction à partir de $ar_bdd_fields dans GetDataSelect
* @param array  $ar_bdd_fields      Tableau d'objet OBJDataField
* @param array  $bdd_retour         Tableau qui contient des informations sur la requete 
*
*/ 
class OBJDataSet {
    
    public $bdd_requete, $ar_bdd_fields = array(), $bdd_retour;

    function __construct() {
        // Fixe des parametres par défaut les variable sont ="" par défaut
        global $mysqli;
        $this->mysqli=&$mysqli;
    }

    public function GetDataSelect(){
        $ar_tampon = array();
        foreach ($this->ar_bdd_fields as $field) {
            $bloc = $field->name;
            if ( $field->alias != "" ) { $bloc .=  " AS ".$field->alias; }
            array_push($ar_tampon, $bloc);
        }
        $champs = implode(", ",$ar_tampon);
        $this->bdd_requete = str_replace(";bdd_field_set;", $champs, $this->bdd_requete);
        $this->bdd_retour = DTBS_sqlbrut($this->bdd_requete,$this->mysqli);
    }

}
/** 
* Objet qui représente un champ de base de données 
* 
* @param string $name           Nom du champ comme dans la base de données ou avec un fonction (ex : DATE_FORMAT, IF...)
* @param string $alias          Alias du champ pour le manipuler
* @param string $display        Texte à afficher dans le backend
* @param string $display_format Fonction pour formater l'affichage du texte dans le backend (ex : ucfirst,ucwords...)
* @param string $carac          En fonction du contexte permet de dstinguer des champs (ex : Id, val...)
*
*/ 
class OBJDataField {

    public $name, $alias, $display, $display_format, $carac;

    function __construct($name,$alias,$display,$display_format,$carac) {
        $this->name = $name;
        $this->alias = $alias;
        if ($this->alias == "") {
            if (strpos($this->name,".")>0){
                $this->alias = substr($this->name, strpos($this->name,".")+1);
            } else {
                $this->alias = $this->name;
            }
        }
        $this->display = $display;
        $this->display_format = $display_format;
        $this->carac = $carac;
    }
}


/** 
* Objet qui représente une liste de selection issue d'une requete à la base de données
* 
* @param string $ods                    Objet dy type OBJDataSet
* @param string $se_name                Nom de la liste select
* @param string $se_classe              Nom de la classe de la liste
* @param string $se_size                Taille de la liste
* @param string $se_multiple            Type de liste : dropdown ou multiligne
* @param string $se_style               Style pour surcharger la classe
* @param string $opt_classe             Classe des options de la liste
* @param string $opt_encours            Id en cours qui sera selectionné dans la liste
* @param string $var_session_name_id    Nom de la variable de session quicontient l'Id en cours
* @param string $html                   Contient le code généré à exploiter
*
*/ 
class OBJDataListe {
    
    public $ods;
	public $se_name, $se_classe, $se_size, $se_multiple, $se_style;
	public $opt_classe, $opt_encours;
    public $var_session_name_id;
	public $html;
	
    function __construct() {
    	// Fixe des parametres par défaut les variable sont ="" par défaut
        $this->se_classe = 'seflat';
    	$this->opt_classe = 'optflat';
    	$this->opt_encours = -1;
        $this->ods = new OBJDataSet();
    }

    public function OBJGetDataListe(){
        
        // Récupère les valeurs des champs "id" et "affiche" parmi les champs
        $Id=""; $Affiche = ""; $FuncFormate = "";
        foreach ($this->ods->ar_bdd_fields as $lobj){
            if ($lobj->carac == "id") { $Id = $lobj->alias; }
            if ($lobj->carac == "affiche") { $Affiche = $lobj->alias; $FuncFormate = $lobj->display_format; }
        }
        $this->html = FRM_se($this->se_name, $this->se_classe, $this->se_size, $this->se_multiple, "", 1, $this->se_style);
        while($rec=mysqli_fetch_assoc($this->ods->bdd_retour['resultat'])){
            if ( $this->var_session_name_id!="" AND !isset($_SESSION[$this->var_session_name_id]) ){
                $_SESSION[$this->var_session_name_id] = $rec[$Id];
                $this->opt_encours = $rec[$Id];
            } else {
                $this->opt_encours = $_SESSION[$this->var_session_name_id];
            }
            if ($FuncFormate!=""){ $rec[$Affiche] = $FuncFormate($rec[$Affiche]); }
            $this->html .= FRM_opt($this->opt_classe, $rec[$Id], $rec[$Id] == $this->opt_encours, $rec[$Affiche], 1, "");
        }
        $this->html .= "</select>";
        
    }

}
/** 
* Objet qui représente un fiche issue d'une requete à la base de données
* 
* @param string $name           Nom du champ comme dans la base de données ou avec un fonction (ex : DATE_FORMAT, IF...)
* @param string $alias          Alias du champ pour le manipuler
* @param string $display        Texte à afficher dans le backend
* @param string $display_format Fonction pour formater l'affichage du texte dans le backend (ex : ucfirst,ucwords...)
* @param string $carac          En fonction du contexte permet de dstinguer des champs (ex : Id, val...)
*
*/ 
class OBJDataFiche {

    public $ods, $html, $IdTableDataFiche, $tdSTtitre, $tdSTval, $tableId, $BarreAction;

     function __construct() {
        // Fixe des parametres par défaut les variable sont ="" par défaut
        $this->ods = new OBJDataSet();
    }

    public function OBJGetDataFiche(){

        
        $this->html = TB_table($this->tableId,"","",1);
        if ($this->BarreAction!=""){
            $this->html .= TB_ligne("","",1,"","");
            $this->html .= TB_cellule("",$this->tdSTval,"",2,"",1,"");
            $ar_param = explode(";", $this->BarreAction); $i=0;
            foreach ($ar_param as $prm) {
                if ($i==0){
                    $prefixe = $prm; 
                } else {
                    $this->html .= FRM_bt("btflat", "button", $prefixe.$prm, $prm, "", 1, "", "");
                }
                $i++;
            }
            $this->html .= "</td></tr>";
        }

        $ligne = 0; $cellule = 0;
        $rec=mysqli_fetch_assoc($this->ods->bdd_retour['resultat']);
        foreach ($this->ods->ar_bdd_fields as $field) {
            if ($field->display_format != ""){
                $FuncFormate = $field->display_format;
                $rec[$field->alias] = $FuncFormate($rec[$field->alias]);
            }
            $this->html .= TB_ligne("","",1,"","");
            $this->html .= TB_cellule($this->tableId."_l".$ligne."c".$cellule,$this->tdSTtitre,"","","",1,"");
            $cellule++;
            $this->html .= $field->display."</td>";
            $this->html .= TB_cellule($this->tableId."_l".$ligne."c".$cellule,$this->tdSTval,"","","",1,"");
            $this->html .= $rec[$field->alias]."</td></tr>";
            $ligne++; $cellule=0;
        }
        $this->html .= "</table>";

    }

}

?>