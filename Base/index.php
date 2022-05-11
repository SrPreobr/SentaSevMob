<?php

$db = 0; // глобальный дискриптор БД
$perpage  = 20; // Количество строк отображаемых данных из БД на странице
$temid = 0; // номер выбранной темы

  function OpenDB() {
   global $db; 
                                        try {     
                                          $db = new PDO('sqlite:Katalog.s3db');
                                        }
                                        catch(PDOException $e) {  
                                            echo $e->getMessage();  
                                        }
                                           // echo ('База данных Test.s3db открыта<br>');
  }

  function PrSt($id) {
    // печать строки из таблицы Liter где _id= 
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
    // печать всех строк из таблицы  Liter
    global $db;                                          
                                          $qr='SELECT * FROM Liter';
                                        //$qr='SELECT * FROM Liter LIMIT 1 10';
                                          $res = $db->query($qr);
                                          if (!$res) {                                               
                                                 echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
                                            } else {
                                                echo ("<table border='1'>
                                                <tr>
                                                  <th>№</th>
                                                  <th>Содержание</th>
    
                                                </tr>");
                                                while ($src=$res->fetch()) {
                                                 echo ('<tr><td>'.$src['_id'].'</td>   <td>'.$src['Name'].'</td></tr>');
                                                }
                                                echo ('</table>');
                                            }
  }

function PrSelect() {
  // вывод выпадающего списка SELECT из таблицы Tems
        global $db;                                          
        global $temid;
      
      // определение выбранной Темы
      if (empty(@$_GET['temid']) || ($_GET['temid'] <= 0)) {  
        $temid = 0;
      } else {
      $temid = (int) $_GET['temid']; // Считывание текущей Темы
      } 

      $qr='SELECT * FROM Tems';
      $res = $db->query($qr);
      if (!$res) {                                               
        echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
      } else  {
        echo "<select name='temid'>";
        // value="<?php echo $_GET['filtr'];
        while ($src=$res->fetch()) {
          echo ("<option");
          if ($src['_id']==$temid) echo (" selected");
          echo (" value='".$src['_id']."'>".$src['Name']."</option>");
        }
        echo "</select>";
      } 
}
    
function RetunWhereSql ($filtr, $temid) {
  if (($filtr!='') OR ($temid!=0)) {    
    $noFirst = false;

    $qr= " WHERE";
    if ($filtr!='') {
      $qr.= " (Name LIKE '%".$filtr."%')"; //
      $noFirst = true;
    }  
    if ($temid!=0) { 
      if ($noFirst)  $qr.= " AND";
      $qr.= " (TemId= '".$temid."')";
      $noFirst = true;
    }
  }
  return $qr;
}

function PrPageLimit($start_pos, $perpage, $filtr, $temid ) {
    // печать строк из таблицы  Liter начиная с $start_pos порциями по $perpage строк учитывая фильтр $filtr
    global $db;

    //echo ('Стртовая позиция= '.$filtr.'Фильтр= '.$filtr.'Темы №= '.$temid);
    $qr='SELECT * FROM Liter';
    $qr.= RetunWhereSql ($filtr, $temid);
    

    $qr.=" LIMIT ".$perpage." OFFSET ".$start_pos;

     $res = $db->query($qr);
     if (!$res) {                                               
        echo ('Запрос '.$qr.' выполнен с ошибкой<br>'); 
     } else {
                echo ("<table border='1'><tr>
                                                  <th>№</th>
                                                  <th>Автор</th>
                                                  <th>Содержание</th>".
                                                  //<th>Тема</th>
                                                  "<th>Ссылка</th>  
                                                </tr>");
                while ($src=$res->fetch()) {
                    echo ('<tr>'. 
                            '<td>'.$src['_id'].'</td>'.
                            '<td>'.$src['Avtor'].'</td>'.
                            '<td>'.$src['Name'].'</td>'.
                            //'<td>'.$src['Tem'].'</td>'.
                            '<td>'.'<a style="color: #808000;" href='.$src['Url'].'>'.$src['Url'].'</a>'.'</td>'.
                        ' </tr>');
                }
                echo ('</table>');
            }
}

function link_bar($page, $pages_count, $filtr, $temid)
  // вывод ссылок на строницы по их №
    {
        echo 'Страницы: ';
        for ($j = 1; $j <= $pages_count; $j++)
        {
            // Вывод ссылки
            
            if ($j == $page) {
                echo ' <a style="color: #808000;" ><b>'.$j.'</b></a> ';
            } else {
                echo ' <a style="color: #808000;" href='.$_SERVER['php_self'].'?page='.$j.
                    '&filtr='.$filtr.'&temid='.$temid.'>'.$j.'</a> '; // '&temid='.$temid.
            }
            // Выводим разделитель после ссылки, кроме последней
            // например, вставить "|" между ссылками
            if ($j != $pages_count) echo ' ';
        }
        return true;
} // Конец функции

//
//echo ' test1 <br> ';

OpenDB();

// Обработка № Темы
if (empty(@$_GET['temid']) || ($_GET['temid'] <= 0)) {
  $temid = 0;
  } else {
  $temid = (int) $_GET['temid']; // Считывание текущей temid
  }

?>

<style type="text/css">
table tr th {
 background-color: #d3DADE;
 padding: 3px;
}
</style>

<table>
 <form action="index.php" method="get" NAME="frm">
  <tr><td colspan=3 height=10></td></tr>
  <tr>
    <td><b> Вывести строки, в содержании которых есть текст</b></td>
    <td><b> Вывести строки, по Теме</b></td>
    <td></td>    
  </tr>
  <tr>
    <td align=center><INPUT TYPE=text SIZE=50 name=filtr value="<?php echo $_GET['filtr'];?>"></td>
    <td><?php PrSelect(); ?></td>
    <td><button>Фильтровать</button></td>  <!--  <INPUT id=Button type=submit value=Фильтровать>  -->
  </tr>
 </form>
</table>

<?php

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

// Обработка фильтра подстройки и темы
$filtr='';
if (!empty(@$_GET['filtr']))  {
    $filtr=$_GET['filtr'];
}    

if (($filtr!='') OR ($temid!=0)) {

    $qr="SELECT COUNT(*) as count FROM Liter";
    $qr.= RetunWhereSql ($filtr, $temid);

    // echo ' Запрос='.$qr.'<br> ';

    $res=$db->query($qr);
    if (!$res) {                                               
        echo (' Строк содержащих '.$filtr.' и Тему='.$temid.' не найдено<br>');
        $filtr='';
     } 
    else {
      $src=$res->fetch();
      $countFiltr = $src['count'];
    }
} 

// htmlspecialchars()
if (($filtr!='') OR ($temid!=0)) echo (' Строк, соответствующих фильтру='.$countFiltr);

if ($countFiltr==0)  { $filtr=''; } else { $count=$countFiltr; }

//echo (' Строк на странице='.$perpage.'<br> ');
echo ('<br> ');

$pages_count = ceil($count / $perpage); // Количество страниц

// Если номер страницы оказался больше количества страниц
if ($page > $pages_count) $page = 1; //$pages_count
$start_pos = ($page - 1) * $perpage; // Начальная позиция, для запроса к БД


// Вызов функции, для вывода ссылок Номеров Страниц на экран
link_bar($page, $pages_count, $filtr, $temid);

PrPageLimit($start_pos,$perpage, $filtr, $temid);
?>