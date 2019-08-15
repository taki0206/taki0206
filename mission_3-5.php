<!DOCTYPE html>
<html lang = "ja">
<html>
<head>

    <meta charset = "utf-8">
    <title>投稿フォーム</title>

<?php
echo '<p style = "font-size : 25px;">好きなスポーツ選手は誰ですか？<hr/></p>';
?>
</head>

<?php

//空でないときに動くという条件にする
$comment = (isset($_POST["comment"]) == true)?$_POST["comment"]:"";         //三項演算子
$name = (isset($_POST["name"]) == true)?$_POST["name"]:"";

$password = (isset($_POST["password"]) == true)?$_POST["password"]:"";      //パスワード
$delpass = (isset ($_POST["delpass"]) == true)?$_POST["delpass"]:"";
$editpass = (isset ($_POST["editpass"]) == true)?$_POST["editpass"]:"";

$edit_num = (isset ($_POST["edit_num"]) == true)?$_POST["edit_num"]:"";     //編集対象番号（投稿フォームに持ってくるためのもの）
$editNo = (isset ($_POST["editNo"]) == true)?$_POST["editNo"]:"";                 //実際に編集するためのフォーム

date_default_timezone_set("Asia/Tokyo");                //日本時間に設定
$time = date("Y/m/d H:i:s");                            //投稿時間の変数

$filename = "mission_3-5.txt";                          //ファイルを代入
if(file_exists($filename)){                             //もしファイルが存在したら
    $num = count(file("mission_3-5.txt")) + 1;              //最終投稿番号に＋1
    }else{
    $num = 1;                                               //無ければ1
    }

$filedata = "mission_3-5.txt";

$three = "<>".$name."<>".$comment."<>".$time."<br>"."<>".$password."<>"."\r\n";
$three = nl2br($three);

$editdata1 = "";
$editdata2 = "";
$editdata4 = "";

/*新規投稿フォーム*/      

    if(isset($_POST["submit"]) == true){                                     //送信ボタンを押したら作動

        if($comment == ""){                                   //コメントか名前のどっちかが空欄だったら教える
          echo '<p style = "font-size : 18px;">未入力の項目があるか、Enterで実行しようとしています。ボタンを押して処理を実行してください。<br/></p>';

        }elseif($name == ""){
         echo '<p style = "font-size : 18px;">未入力の項目があるか、Enterで実行しようとしています。ボタンを押して処理を実行してください。<br/></p>';

//表示されたものを実際に編集する(旧)

        }elseif(!empty($_POST["editNo"]) == true){

            $filedata = file("mission_3-5.txt");                             //ファイルを読み込む
            $fp = fopen($filename,"w");                           //ファイルの中身を空にする
            
            foreach($filedata as $editdata){                         //配列の内容をすべてループ処理でブラウザに表示
            $editdata = explode("<>",$editdata);                      //分割して投稿番号を表示

                if($editdata[0] == $editNo) {                       //投稿番号と編集フォームに入ってる番号が一致したら実行
                fwrite($fp,$editNo.$three);                        //ファイルには空白行を記入
                
                }else{                                        //それ以外は
                fwrite($fp,implode("<>",$editdata));         //元の情報を書き込む（パスワードが合わなかったから編集しない）
                }
            }
            fclose( $fp );                                         //忘れてた
            echo '<p style = "font-size : 18px;">編集が完了しました！<br/></p>';
    


//新規投稿を送信    送信された内容をテキストファイルに保存
            }else{
            $fp = fopen($filename,"a");
            fwrite($fp,nl2br($num."<>".$name."<>".$comment."<>".$time."<br>"."<>".$password."<>"."\r\n"));
            fclose($fp);
            echo '<p style = "font-size : 18px;">投稿を受け付けました！<br/></p>';         
            }
    }


/*削除フォーム*/
$delnumber = (isset ($_POST["delnumber"]) == true)?$_POST["delnumber"]:"";  //削除対象番号
$filedata = "mission_3-5.txt";

 //削除対象番号のフォームがあることを確認(旧)

    if(isset($_POST["delete_btn"]) == true){        /*削除ボタンが押されたら作動*/

         if($delnumber == ""){             //フォームが空だったら知らせる
         echo '<p style = "font-size : 18px;">未入力の項目があるか、Enterで実行しようとしています。ボタンを押して処理を実行してください。<br/></p>';

         }elseif($delpass == ""){
         echo '<p style = "font-size : 18px;">パスワードが入力されていません。パスワードの無い投稿は削除できません。<br/></p>';

         }else{
         $filedata = file("mission_3-5.txt");                                              /*空じゃなかったらファイルを読み込む*/
         
            $fp = fopen($filename,"w");                                                     /*ファイルの中身を空にする*/
       
            foreach ($filedata as $deldata) {                                                  /*配列の内容をすべてループ処理でブラウザに表示*/
            $deldata = explode("<>",$deldata);                                              /*分割して投稿番号を取得*/

                      if ($deldata[0] !== $delnumber) {                                      /*投稿番号と削除対象番号が違ったら*/
                      fwrite($fp,implode("<>",$deldata));                                  //元の内容を書き込む
                      

                      }elseif($deldata[0] == $delnumber && $deldata[4] !== $delpass){      //パスワードが間違ってたら
                      fwrite($fp,implode("<>",$deldata));                                  //元の内容を書き込む
                      echo '<p style = "font-size : 18px;">パスワードが違います。削除できません。<br/></p>';


                      }elseif($deldata[0] == $delnumber && $deldata[4] == $delpass){       //投稿番号と削除対象番号が同じで、かつパスワードも同じだったら

                      fwrite($fp,nl2br(""."<>".""."<>".""."<>".""."<>"."\r\n"));                                                //$blankを入れて投稿番号が狂わないようにする
                      echo '<p style = "font-size : 18px;">削除しました！<br/></p>';

                      
                      }
            }
            fclose( $fp );
         }  
    
    }



/*編集したいものをフォームに表示させる（実際に編集するコードは新規投稿の中にある）*/

    if (isset ($_POST["edit_btn"]) == true){    /*編集ボタンを押したら開始*/ 

        if($edit_num == ""){                    //編集対象番号のフォームが空だったら知らせる
        echo '<p style = "font-size : 18px;">未入力の項目があるか、Enterで実行しようとしています。ボタンを押して処理を実行してください。<br/></p>';

        }elseif($editpass == ""){
        $edit_num = "";
        echo '<p style = "font-size : 18px;">パスワードが入力されていません。パスワードの無い投稿は編集できません。<br/></p>';

        }else{
        $filedata = file("mission_3-5.txt");               //空じゃなかったらファイルを読み込む
        
            foreach ($filedata as $editdata){               //配列の内容をすべてループ処理でブラウザに表示
            $editdata = explode("<>",$editdata);    //分割して投稿番号取得

              if($editdata[0] == $edit_num && $editdata[4] !== $editpass){
              echo '<p style = "font-size : 18px;">パスワードが違います。編集できません。<br/></p>';

              }elseif ($edit_num == $editdata[0] && $editpass == $editdata[4]) {         /*編集対象番号と投稿番号が一致したら上のフォームに戻す（フォームに書き入れる）*/
                       $editdata1 = $editdata[1];
                       $editdata2 = $editdata[2];
                       $editdata4 = $editdata[4];
                       echo '<p style = "font-size : 18px;">内容を編集した後、送信ボタンを押し編集を完了して下さい！<br/></p>';
              }
            }
        
        }
    }

?>


<html>

<!-- 各種フォームの作成 -->
<body>
<form action = "mission_3-5.php" method = "post">

<!-- 新規投稿フォーム -->
<?php echo '<p style = "font-size : 18px;"><新規投稿></p>','<p style = "color : red;">※パスワードが無いと後から投稿を削除・編集することができません!!</p>';?>
<input type = "text" name = "name" placeholder = "名前" value = "<?php echo $editdata1;?>">
<input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $editdata2;?>">
<input type = "password" name = "password" value = "<?php echo $editdata4;?>" placeholder = "パスワード">
<input type = "submit" name = "submit" value = "送信"><br/>
<br/>

<!-- 削除フォーム -->
<?php echo '<p style = "font-size : 18px;"><投稿を削除する></p>';?>
<input type = "number"  name = "delnumber" placeholder = "削除対象番号">
<input type = "password" name = "delpass" placeholder = "パスワード">
<input type = "submit" name = "delete_btn" value = "削除"><br/>
<br/>

<!-- 編集フォーム -->
<?php echo '<p style = "font-size : 18px;"><投稿を編集する></p>';?>
<input type = "number" name = "edit_num" placeholder = "編集対象番号">
<input type = "password" name = "editpass" placeholder = "パスワード">
<input type = "submit" name = "edit_btn" value = "編集">
<input type = "hidden" name = "editNo" value = "<?php echo $edit_num ;?>">
</form>
</body>
</html>

<?php

//ブラウザにファイルの内容を表示する

echo '<p style = "font-size : 19px;">・コメント一覧</p>';

$file = "mission_3-5.txt";

if(file_exists($file)){
 
$file = file("mission_3-5.txt");
    
    foreach($file as $lines){
    $lines = explode("<>",$lines);

        if($lines[0] == ""){
        echo $lines[0];

        }else{
        echo $lines[0]." ".$lines[1]." "."「".$lines[2]."」"." ".$lines[3];
        }
    }
}
?>

