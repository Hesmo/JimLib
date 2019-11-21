<?Php

/*include('class-form.php');
$a = new ElementFormulaire('input');*/

class ElementFormulaire {
    
    // déclaration d'une propriété
    public $ElmtType;
    public $ElmtName;

    
     function __construct() {
        $ar_arg = func_get_args();
        $ar_ElmtType=array('form','text','select','option','checkbox','textarea','button','datalist','output','button','file',);
        if (!in_array($ar_arg[0],$ar_ElmtType)){
			trigger_error("class ElementFormulaire : Type d'element non prise en charge", E_USER_ERROR);
        }
        $this->ElmtType = $ar_arg[0];
    }
}

?>