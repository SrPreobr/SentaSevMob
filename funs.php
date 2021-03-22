<?php

$db = 0; // глобальный дискриптор БД

function OpenDB() {
   global $db; 
                                        try {     
                                          $db = new PDO('sqlite:Base/Test.s3db');
                                        }
                                        catch(PDOException $e) {  
                                            echo $e->getMessage();  
                                        }
                                           // echo ('База данных Test.s3db открыта<br>');
}

                                    function Zena($id) { 
    global $db;                                         
                                          $res = $db->query('SELECT Fasovka,ZenaRozn FROM Tovar WHERE _id='.$id);
                                          if (!$res) {                                               
                                                 echo ('Запрос цены товара выполнен с ошибкой'); 
                                            } else {
                                                $src=$res->fetch();
                                                if ($src) echo(' Price: '.$src['Fasovka'].'='.$src['ZenaRozn']);
                                                
                                            }
                                    } 
                                    
                                    function ZenaFromName($name) { 
    global $db;                                          
                                          $qurySt='SELECT Fasovka,ZenaRozn FROM Tovar WHERE TovarName="'.$name.'"';
                                          $res = $db->query($qurySt);
                                          if (!$res) {                                               
                                                 echo ('Запрос '.$qurySt.' выполнен с ошибкой'); 
                                            } else {
                                                echo ("<table border='1'>");
                                                while ($src=$res->fetch()) {
                                                 echo ('<tr><td>'.$src['Fasovka'].'</td>   <td>'.$src['ZenaRozn'].'</td></tr>');
                                                }
                                                echo ('</table>');
                                            }
                                    }

?>