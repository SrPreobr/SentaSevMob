<?php

$db = 0; // глобальный дискриптор БД
$perpage  = 20; // Количество строк отображаемых данных из БД на странице

function OpenDB() {
   global $db; 
                                        try {     
                                          $db = new PDO('sqlite:Lit.s3db');
                                        }
                                        catch(PDOException $e) {  
                                            echo $e->getMessage();  
                                        }
                                           // echo ('База данных Test.s3db открыта<br>');
}

                                    function PrSt($id) { 
    global $db;                           $qr='SELECT Name FROM Liter WHERE _id='.$id;
                                          //$qr='SELECT * FROM Liter';
                                          $res = $db->query($qr);
                                          if (!$res) {                                               
                                                 echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
                                            } else {
                                                $src=$res->fetch();
                                                if ($src) echo('№ '.$src['_id'].'=>'.$src['Name'].'<br>');
                                                
                                            }
                                    } 
                                    
    function PrPage() { 
    global $db;                                          
                                          $qr='SELECT * FROM Liter';
                                        //$qr='SELECT * FROM Liter LIMIT 1 10';
                                          $res = $db->query($qr);
                                          if (!$res) {                                               
                                                 echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
                                            } else {
                                                echo ("<table border='1'>");
                                                while ($src=$res->fetch()) {
                                                 echo ('<tr><td>'.$src['_id'].'</td>   <td>'.$src['Name'].'</td></tr>');
                                                }
                                                echo ('</table>');
                                            }
    }

    function PrPageLimit($start_pos, $perpage, $filtr ) { 
    global $db;                                          
     
     if ($filtr=='')
      $qr='SELECT * FROM Liter WHERE (_id>'.$start_pos.') LIMIT '.$perpage ;
     else 
      $qr="SELECT * FROM Liter WHERE (_id>".$start_pos.")". 
         " AND  (Name LIKE '%".$filtr."%')"
         ." LIMIT ".$perpage ;
        
     $res = $db->query($qr);
     if (!$res) {                                               
        echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
     } else {
                echo ("<table border='1'>");
                while ($src=$res->fetch()) {
                    echo ('<tr><td>'.$src['_id'].'</td>   <td>'.$src['Name'].'</td></tr>');
                }
                echo ('</table>');
            }
    }

    function link_bar($page, $pages_count, $filtr)
    {
        for ($j = 1; $j <= $pages_count; $j++)
        {
            // Вывод ссылки
            if ($j == $page) {
                echo ' <a style="color: #808000;" ><b>'.$j.'</b></a> ';
            } else {
                echo ' <a style="color: #808000;" href='.$_SERVER['php_self'].'?page='.$j.
                    '&filtr='.$filtr.'>'.$j.'</a> ';
            }
            // Выводим разделитель после ссылки, кроме последней
            // например, вставить "|" между ссылками
            if ($j != $pages_count) echo ' ';
        }
        return true;
    } // Конец функции

//
//echo ' test1 <br> ';
?>

<table>
 <form action="index.php" method="get" NAME="frm">
  <tr><td colspan=2 height=10></td></tr>
  <tr><td colspan=2><b> Вывести строки, в которых есть текст</b></td></tr>
  <tr><td align=center><INPUT TYPE=text SIZE=50 name=filtr value="<?php echo $_GET['filtr'];?>"></td>
      <td><INPUT id=Button type=submit value=Фильтровать></td></tr>
  </form>
</table>

<?php
OpenDB();
// PrSt(4);
//PrPageLimit(30,20);

// Подготовка к постраничному выводу
if (empty(@$_GET['page']) || ($_GET['page'] <= 0)) {
$page = 1;
} else {
$page = (int) $_GET['page']; // Считывание текущей страницы
}

// Общее количество строк
$qr='SELECT COUNT(*) as count FROM Liter';
$res=$db->query($qr);
$src=$res->fetch();

$count = $src['count'];
echo ('Строк в таблице всего='.$count);

// Обработка фильтра
$filtr='';
if (!empty(@$_GET['filtr']))  {
    $filtr=$_GET['filtr'];
    
    
    $qr="SELECT COUNT(*) as count FROM Liter"." WHERE Name LIKE '%".$filtr."%'";
    //echo ' Запрос='.$qr.'<br> ';
    $res=$db->query($qr);
    if (!$res) {                                               
        echo ('Строк содержащих '.$filtr.' не найдено<br>');
        $filtr='';
     } 
    else {
      $src=$res->fetch();
      $countFiltr = $src['count'];
    }
} 

// htmlspecialchars()
if ($filtr!='') echo (' Строк, соответствующих фильтру='.$countFiltr);

if ($countFiltr==0)  { $filtr=''; } else { $count=$countFiltr; }

//echo (' Строк на странице='.$perpage.'<br> ');
echo ('<br> ');

$pages_count = ceil($count / $perpage); // Количество страниц

// Если номер страницы оказался больше количества страниц
if ($page > $pages_count) $page = 1; //$pages_count
$start_pos = ($page - 1) * $perpage; // Начальная позиция, для запроса к БД

// Вызов функции, для вывода ссылок на экран
link_bar($page, $pages_count, $filtr);

PrPageLimit($start_pos,$perpage, $filtr);
?>