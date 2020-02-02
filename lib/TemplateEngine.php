<?php
namespace Library;

class TemplateEngine{
    private $str;
    private $copy;
    private $lista=array();
    public function __construct($te){
        $this->str=file_get_contents($te);
        $this->copy=$this->str;
    }
    public function addVariable($buscar,$reemplazar){
    $this->lista[$buscar]=$reemplazar;
    }
    public function render(){
        $replace="";
        $dentro=False;
        foreach($this->lista as $buscar=>$reemplazar){
            $this->copy= str_replace("{{".$buscar."}}",$reemplazar,$this->copy);
        }
        for($i=0;$i<strlen($this->copy);$i++){
            if ($this->copy[$i]=="{" and $this->copy[$i+1]=="{"){
                $dentro=True;
            }
            if ($this->copy[$i-2]=="}" and $this->copy[$i-1]=="}"){
                $dentro=False;
            }
            if (!$dentro){
                $replace.=$this->copy[$i];
            }
        }
        $this->copy=$replace;
        return $this->copy;
    }
    public function buscarKey(){
        $cantidad=0;
        $tipo="";
        $var="";
        for($i=0;$i<strlen($this->copy);$i++){
            if ($this->copy[$i]=="{" and $this->copy[$i+1]=="{"){
                $dentro=True;  
            }
            if ($this->copy[$i-2]=="}" and $this->copy[$i-1]=="}"){
                $dentro=False;
                $cantidad++;
            
            }
        }
        return $cantidad.$var;
    }
}
// $te=new TE("index.template");
// $te->addVariable("titulo","Grande Lean");
// // $te->addVariable("parrafo","2020");
// echo $te-> buscarKey()."\n";

// echo $te-> render();

