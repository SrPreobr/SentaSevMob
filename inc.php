<?php 
                                    function Zena($id) { 
                                          
                                        try {     
                                          $db = new PDO('sqlite:Base/Test.s3db');
                                        }
                                        catch(PDOException $e) {  
                                            echo $e->getMessage();  
                                        }
                                          // echo ('База данных Test.s3db открыта<br>');
                                          $res = $db->query('SELECT Fasovka,ZenaRozn FROM Tovar WHERE _id='.$id);
                                          if (!$res) {                                               
                                                 echo ('Запрос цены товара выполнен с ошибкой'); 
                                            } else {
                                                $src=$res->fetch();
                                                if ($src) echo(' Price: '.$src['Fasovka'].'='.$src['ZenaRozn']);
                                                
                                            }
                                    } 
                                    
                                    function ZenaFromName($name) { 
                                        try {     
                                          $db = new PDO('sqlite:Base/Test.s3db');
                                        }
                                        catch(PDOException $e) {  
                                            echo $e->getMessage();  
                                        }
                                          // echo ('База данных Test.s3db открыта<br>');
                                          
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