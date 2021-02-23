<div id="content">
<?php ini_set('memory_limit',"-1"); ?>
<?php $att=array('autocomplete'=>'off');?>
<?php echo form_open('',$att)?>
<?php echo isset($pesan) ? $pesan : ''?>
<table cellpadding="2px" 
cellspacing="1px"  width="400px" class="tableBorder" align="center">

    
     <tr>
        <td align="center" colspan="3">
            <img src=" <?php echo base_url();?>image/gembok.png" border="0" align="absbottom"/>&nbsp;
            <span class="message">Silahkan Login Dahulu  </span>
        </td>
    </tr>
   

<tr>


<td class="label" align="right" >Username:</td>
<td>
:
</td>
<td>

<?php echo form_input('username')?>
</td>
</tr><tr>
 <td class="label" align="right">Password:</td><td>
:
</td><td>
<?php echo form_password('password')?>
</td>
</tr><tr>

<tr>


<td class="label" align="right" >Tahun Anggaran</td>
<td>
:
</td>
<td>


   <?php $thang =  '2021'; 
          $th = '2021'; 
        /*$thang_maks = $thang + 5 ;
        $thang_min = $thang - 5 ;
        echo '<select name ="pcthang">';
        
        for ($th=$thang_min ; $th<=$thang_maks ; $th++)
        {
            if ($th==$thang) {
                echo "<option selected value=$th>$thang</option>";
                }
            else {  
            echo "<option value=$th>$th</option>";
            }
        }
        echo '</select>';   
        */
        echo '<select name ="pcthang">';
        echo "<option selected value=$th>$thang</option>";
        echo '</select>';   
    
    
?>      <?php echo form_submit('submit', 'Login', "class='button'")?>
</td>
</tr>


</tr>




</table>
<?php echo form_close()?>
</div>
