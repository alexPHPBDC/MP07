<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Formularis</title>
</head>

<body>
    <div style="margin: 30px 10%;">
    
    <?php


$dadesOK = true;
if ($_POST) {
    
    if($dadesOK){
        header( "Location: http://localhost/2daw/m07%20-%20PHP/UF1/A2.%20Formularis/A2.5%20-%20Autenticacio%20PRG/p1.php",false,200);
        exit();
    }else{
        header( "Location: http://localhost/2daw/m07%20-%20PHP/UF1/A2.%20Formularis/A2.5%20-%20Autenticacio%20PRG/treballAmbPost.php?dades=etc",false,403);
        exit();
    }
   
 }
            ?>

            <h3>My form</h3>
            <form action="" method="post" id="myform" name="myform">
        
                <label>Text</label> <input type="text" value="" size="30" maxlength="100" name="mytext" id="" /><br /><br />
      
                <input type="radio" name="myradio" value="1" /> First radio
                <input type="radio" checked="checked" name="myradio" value="2" /> Second radio<br /><br />
            
                <input type="checkbox" name="mycheckbox[]" value="1" /> First checkbox
                <input type="checkbox" checked="checked" name="mycheckbox[]" value="2" /> Second checkbox<br /><br />
            
                <label>Select ... </label>
                <select name="myselect" id="">
                    <optgroup label="group 1">
                        <option value="1" selected="selected">item one</option>
                    </optgroup>
                    <optgroup label="group 2" >
                        <option value="2">item two</option>
                    </optgroup>
                </select><br /><br />
            
                <textarea name="mytextarea" id="" rows="3" cols="30">
                    Text area
                </textarea> <br /><br />
            
                <button id="mysubmit" type="submit">Submit</button><br /><br />
        
            </form>
            

        
    
    
</div>
           
</body>
</html>