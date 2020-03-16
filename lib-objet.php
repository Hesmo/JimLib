<?Php
class OBJDataListe {
    
    public $bdd_resultat, $bdd_field_id, $bdd_field_val;
	public $se_name, $se_classe, $se_size, $se_multiple;
	public $opt_classe, $opt_encours;
	public $laliste;
	
    function __construct() {
    	// Fixe des parametres par défaut
    	$this->se_classe = 'seflat';
    	$this->se_size = '';
    	$this->se_multiple = "";
    	$this->opt_classe = 'optflat';
    	$this->opt_encours = -1;
    }

    function OBJGetDataListe(){
    	// TODO : Ajouter ici des controles sur les parametres
		$this->laliste = FRM_se($this->se_name, $this->se_classe, $this->se_size, $this->se_multiple, "", 1, "");
		    while($rec=mysqli_fetch_assoc($this->bdd_resultat)){
		    	$this->laliste .= FRM_opt($this->opt_classe, $rec[$this->bdd_field_id], $rec[$this->bdd_field_id] == $this->opt_encours, $rec[$this->bdd_field_val], 1, "");
		    }
		$this->laliste .= "</select>";
    }

    

}

?>