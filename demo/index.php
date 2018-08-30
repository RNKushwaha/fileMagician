<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $txt);
$txt = "Jane Doe\n";
fwrite($myfile, $txt);
fclose($myfile);

class ABC extends pqr implements sad {
      protected $name;
      
      public function __construct($name=null){
            $this->name = $name;
      }
      
      public function getName(){
            return $this->name;
      }  
}

$abc = new ABC();

echo $abc->getName();
