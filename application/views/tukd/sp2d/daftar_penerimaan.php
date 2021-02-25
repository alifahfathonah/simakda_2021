<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
    var nip='';
	var kdskpd='';
	var kdrek5='';
    var chek = 0;
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });
			$("#div_tgl").hide();
			$("#div_periode").hide();
             get_skpd();               
        });   
    
	$(function(){
		$('#id_pengirim').combogrid({
			   panelWidth:480,  
			   idField:'kd_pengirim',  
			   textField:'kd_pengirim',  
			   mode:'remote',
			   url:'<?php echo base_url(); ?>index.php/tukd/load_pengirim',             
			   columns:[[  				   
				   {field:'kd_skpd',title:'SKPD',width:70},
                   {field:'nm_pengirim',title:'Nama Pengirim',width:380}
				   
			   ]],  
			   onSelect:function(rowIndex,rowData){
				   kd_pengirim = rowData.kd_pengirim;
				   $("#nm_pengirim").attr("value",rowData.nm_pengirim);   
					cskpd(rowData.kd_skpd);
			   }
			}); 
	});
	$(function(){  
            $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BUD',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama").attr("value",rowData.nama);
           } 
            });          
         });
    
		$(function(){  
            $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
			
			$('#tgl_ttd1').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});
            
            $('#tgl_ttd2').datebox({  
				required:true,
				formatter :function(date){
					var y = date.getFullYear();
					var m = date.getMonth()+1;
					var d = date.getDate();
					return y+'-'+m+'-'+d;
				}
			});            
         });
		  
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
	
	function opt(val){        
        pilih = val; 
        if (pilih=='2'){
            $("#div_tgl").show();
			$("#div_periode").hide();
        }else if (pilih=='3'){
            $("#div_tgl").hide();
			$("#div_periode").show();
        }else{
			exit();
		}                 
    }
    
	function cskpd(skpd){
         $(function(){
	$('#srek').combogrid({  	   
		panelWidth:630,  
		idField:'kd_rek5',  
		textField:'kd_rek5',  
		mode:'remote',        
		url:"<?php echo base_url(); ?>index.php/tukd/rek5_skpd/"+skpd,  
		columns:[[  
			{field:'kd_rek5',title:'Kode Rekening',width:100},  
			{field:'nm_rek5',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdrek5 = rowData.kd_rek5;
			$("#nmrek5").attr("value",rowData.nm_rek5);			
		}  
		}); 
	});

    }
	
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                       // $("#skpd").attr("value",rowData.kd_skpd);
        								kdskpd = data.kd_skpd;
                                        
        							  }                                     
        	});
             
        }
    
		
        function cetak(ctk)
        {	
									
			var cetk       = pilih;
			var no_halaman = document.getElementById('no_halaman').value;
			var pengirim   = $('#id_pengirim').combogrid('getValue'); 
			var spasi  = document.getElementById('spasi').value; 
			var  ttd = $('#ttd').combogrid('getValue');
		         ttd = ttd.split(" ").join("123456789");
			var blnawal   = document.getElementById('bulan').value; 
			var blnakhir  = document.getElementById('bulan2').value; 
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
			var ctglttd2   = $('#tgl_ttd2').datebox('getValue');
            
			if($('#q_kasda2').attr('checked')){	
				//alert("Cetakkan 1");
			var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan";  
	
			if(cetk=='3'){
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2, '_blank');
				window.focus();	
			}
			}else if($('#q_kasda').attr('checked')){		
				//alert("Cetakkan 2");
				var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan2";  
	
			if(cetk=='3'){
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2, '_blank');
				window.focus();	
			}
			}
        }
        
        
        function cetak_rincian(ctk)
        {	
									
			var cetk       = pilih;
			var no_halaman = document.getElementById('no_halaman').value;
			var pengirim   = $('#id_pengirim').combogrid('getValue'); 
			var spasi  = document.getElementById('spasi').value; 
			var  ttd = $('#ttd').combogrid('getValue');
		         ttd = ttd.split(" ").join("123456789");
			var blnawal   = document.getElementById('bulan').value; 
			var blnakhir  = document.getElementById('bulan2').value; 
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
            var ctglttd2   = $('#tgl_ttd2').datebox('getValue');
			
			if($('#q_kasda2').attr('checked')){	
				//alert("Cetakkan 1");
			var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan_2";  
	
			if(cetk=='3'){
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2, '_blank');
				window.focus();	
			}
			}else if($('#q_kasda').attr('checked')){		
				//alert("Cetakkan 2");
				var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan_2";  
	
			if(cetk=='3'){
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2, '_blank');
				window.focus();	
			}
			}
        }
        
		function cetakrek(ctk)
        {	
									
			var cetk       = pilih;
			var no_halaman = document.getElementById('no_halaman').value;
			var pengirim   = $('#id_pengirim').combogrid('getValue'); 
			var spasi  = document.getElementById('spasi').value; 
			var  ttd = $('#ttd').combogrid('getValue');
		         ttd = ttd.split(" ").join("123456789");
			var blnawal   = document.getElementById('bulan').value; 
			var blnakhir  = document.getElementById('bulan2').value; 
			var ctglttd    = $('#tgl_ttd').datebox('getValue');
			var ctglttd1   = $('#tgl_ttd1').datebox('getValue');
			var ctglttd2   = $('#tgl_ttd2').datebox('getValue');
            var ctglttd2 = ctglttd2.replace(' ','');
            var kd_rek = $('#srek').combogrid('getValue');
            
			if($('#q_kasda2').attr('checked')){	
				//alert("Cetakkan 1");
			var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan_rek";  
	
			if(cetk=='3'){
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2+'/'+kd_rek, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+pengirim+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2+'/'+kd_rek, '_blank');
				window.focus();	
			}
			}else if($('#q_kasda').attr('checked')){		
				//alert("Cetakkan 2");
				var url    = "<?php echo site_url(); ?>tukd/ctk_daftar_penerimaan2";  
	
			if(cetk=='3'){
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+blnawal+'/'+blnakhir+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+'-'+'/'+'-'+'/'+ctglttd2+'/'+kd_rek, '_blank');
				window.focus();
			}else{
				window.open(url+'/'+'-'+'/'+ctk+'/'+ttd+'/'+'-'+'/'+'-'+'/'+no_halaman+'/'+spasi+'/'+cetk+'/'+ctglttd+'/'+ctglttd1+'/'+ctglttd2+'/'+kd_rek, '_blank');
				window.focus();	
			}
			}
        }
		
		function runEffect() {        
			$("#id_pengirim").combogrid('disable');
			$('#q_kasda2')._propAttr('checked',false);
			$("#nm_pengirim").attr("value","");
			$('#id_pengirim').combogrid('setValue',""); 
			
		};  
		
		function runEffect2() {        
			$("#id_pengirim").combogrid('enable');
			$('#q_kasda')._propAttr('checked',false);
		};  
		
    </script>

    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
	</STYLE> 

</head>
<body>

<div id="content">



<h3>DAFTAR PENERIMAAN</h3>
<div id="accordion">
    
    <p align="right">         
        <table border="0" id="sp2d" title="Cetak Daftar Penerimaan" style="width:922px;height:200px;" >
		<tr>
			<td colspan="4">
					<div id="div_bend">
							<table style="width:100%;" border="0">
								<td width="30%"><input id="q_kasda" name="q_kasda" type="checkbox" value="1" onclick="javascript:runEffect();"/> Keseluruhan KASDA</td>
								<td width="30%"><input id="q_kasda2" name="q_kasda2" type="checkbox" value="2" onclick="javascript:runEffect2();"/> Pengirim STS</td> 
							</table>
					</div>
			</td> 
		</tr>
		<tr>
			<td colspan="4">
					<div id="div_bend">
							<table style="width:100%;" border="0">
								<td width="20%">Nama Pengirim</td>
								<td><input type="text" id="id_pengirim" name="id_pengirim" style="width: 200px;" /> &nbsp;&nbsp;
									<input type="nama" id="nm_pengirim" name="nm_pengirim" readonly="true" style="width: 450px;border:0" /> 
								</td> 
							</table>
					</div>
			</td> 
		</tr>
		<!--Radio Button-->
		<tr >
			<td colspan="4">
				<table style="width:100%;" border="0">
					<tr>
						<td><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Tanggal</b>
							<div id="div_tgl">
									<table>
										<tr>								
											<td><b>Tanggal</b></td>
											<td><input type="text" id="tgl_ttd" style="width: 100px;" /></td>
											<td >s/d</td>
											 <td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>
										</tr>
									</table>
							</div>
						   </td> 
						</tr>
						<tr>
						<td><input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" /><b>Per Periode</b>
							<div id="div_periode">
									<table>
										<tr>								
											<td><b>Bulan</b></td>
											<td id="bln" width="100px;">
												<select name="bulan" id="bulan">    
												 <option value="1"> Januari </option> 
												 <option value="2"> Februari </option>
												 <option value="3"> Maret </option>
												 <option value="4"> April </option> 
												 <option value="5"> Mei </option>
												 <option value="6"> Juni </option>
												 <option value="7"> Juli </option> 
												 <option value="8"> Agustus </option>
												 <option value="9"> September </option>
												 <option value="10"> Oktober </option> 
												 <option value="11"> November </option>
												 <option value="12"> Desember </option> 
											</td>
											<td >s/d</td>
											<td id="bln2" width="100px;">
												<select name="bulan2" id="bulan2">    
												 <option value="1"> Januari </option> 
												 <option value="2"> Februari </option>
												 <option value="3"> Maret </option>
												 <option value="4"> April </option> 
												 <option value="5"> Mei </option>
												 <option value="6"> Juni </option>
												 <option value="7"> Juli </option> 
												 <option value="8"> Agustus </option>
												 <option value="9"> September </option>
												 <option value="10"> Oktober </option> 
												 <option value="11"> November </option>
												 <option value="12"> Desember </option> 
											</td>
										</tr>
									</table>
							</div>
						   </td> 
						</tr>
		</table>
		</td>
		</tr>
        <tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd2" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>	
		<tr>
			<td colspan="4">
					<div id="div_bend">
							<table style="width:100%;" border="0">
								<td width="20%">Kuasa BUD</td>
								<td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
								<input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
								
								</td> 
							</table>
					</div>
			</td> 
		</tr>
		
		<tr>
			<td>
				<table style="width:100%;" border="0">
					<td width="20%">No. Halaman</td>
					<td><input type="number" id="no_halaman" style="width: 100px;" value="1"/></td>                       
                </table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table style="width:100%;" border="0">
					<td width="20%">Spasi</td>
					<td><input type="number" id="spasi" style="width: 100px;" value="1"/></td>                       
                </table>
			</td>
		</tr>
		
		<tr >
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak Pdf</a>
			</td>
		</tr>
        
        <tr >
			<td colspan="2" align="center">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_rincian(0);">Cetak Rincian</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_rincian(1);">Cetak Rincian Pdf</a>
			</td>
		</tr>
        <tr >
			<td colspan="2" align="center">
			<br />
			</td>
		</tr>
        
        <tr >
			<td colspan="2" align="center">
            REKENING : <input id="srek" name="srek" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 450px; border:0;" />
            <br />
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakrek(0);">Cetak Rek</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetakrek(1);">Cetak Rek Pdf</a>
			</td>
		</tr>     		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>