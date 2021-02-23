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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var cid = 0;
    var plrek = '';
    var infox = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
         $( "#dialog-modal_t" ).dialog({
            height: 500,
            width: 800,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal_cetak" ).dialog({
            height: 200,
            width: 400,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal_edit" ).dialog({
            height: 200,
            width: 700,
            modal: true,
            autoOpen:false
        });
    get_nourut();		
        });    
     
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_sts_ppkd',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_kas',
    		title:'Nomor Kas',
    		width:50},
            {field:'tgl_kas',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"left"},
			{field:'total',
    		title:'Nilai',
    		width:50,
			align:"right"},
            {field:'keterangan',
    		title:'Uraian',
    		width:50,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomorkas = rowData.no_kas;
          tglkas   = rowData.tgl_sts;
          nomor = rowData.no_sts;
          tgl   = rowData.tgl_sts;
          kode  = rowData.kd_skpd;
          lckdbank = rowData.kd_bank;
          lckdgiat = rowData.kd_kegiatan;
          lcket = rowData.keterangan;
          lcjnskeg = rowData.jns_trans;
          lcrekbank = rowData.rek_bank;
          lctotal = rowData.total;
          lcinfo = rowData.infoin;
          //alert(lcinfo)
          get(nomorkas,tglkas,nomor,tgl,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,lcinfo);   
          load_detail(nomorkas);  
		  lcstatus='edit';		  
        },
        onDblClickRow:function(rowIndex,rowData){
           //alert(rowData.no_sts);   
            section2();   
        }
        });
        
        $('#dg_tetap').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_tetap_sts/'+kode+'/'+plrek,
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"false",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'ck',
    		title:'Pilih',
    		width:5,
            align:"center",
            checkbox:true                
            },
    	    {field:'no_tetap',
    		title:'Nomor Tetap',
    		width:10,
            align:"center"},
            {field:'tgl_tetap',
    		title:'Tanggal',
    		width:5,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:5,
            align:"center"}
        ]]
        });
        
        
        $('#dg1').edatagrid({  
            toolbar:'#toolbar',
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    id = rowIndex;
                    lnnilai = rowData.rupiah;
            },                                                     
            columns:[[
                {field:'id',
          		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
              	title:'No STS',    		
                hidden:"true"},                
                {field:'kd_rek5',
          		title:'Nomor Rekening',
                width:150},
                {field:'nm_rek',
          		title:'Nama Rekening',
                width:400},                
                {field:'rupiah',
          		title:'Rupiah',
                align:'right',
                width:200},
                {field:'no_terima',
                title:'terima',
                hidden:'true',
                width:200},
                {field:'kd_rek6',
          		title:'Nomor Rekening',
                width:150,hidden:true}                 
            ]],
            
           onDblClickRow:function(rowIndex,rowData){
           id = rowIndex; 
           lcrekedt = rowData.kd_rek5;
           lcnmrekedt = rowData.nm_rek;
           lcnilaiedt = rowData.rupiah; 
           get_edt(lcrekedt,lcnmrekedt,lcnilaiedt); 
           
        }
            
            
        });      
    

        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        
        $('#tgl_kas').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
    
        $('#rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode,             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek',title:'Uraian',width:700},
              ]],
               onSelect:function(rowIndex,rowData){
                plrek = rowData.kd_rek5;
               $("#nmrek1").attr("value",rowData.nm_rek.toUpperCase());
               //alert(kode+'-'+plrek);
               $("#dg_tetap").edatagrid({url: '<?php echo base_url(); ?>/index.php/tukd/load_tetap_sts/'+kode+'/'+plrek});
              }    
            });
            
          $('#cmb_sts').combogrid({  
           panelWidth:700,  
           idField:'no_sts',  
           textField:'no_sts',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_sts_ppkd',  
           columns:[[  
               {field:'no_kas',title:'Nomor STS',width:100},
			   {field:'no_sts',title:'Nomor STS',width:100},			   
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               nomor = rowData.no_sts;               
           } 
       });
       
        $('#nomor').combogrid({  
           panelWidth:700,  
           idField:'no_sts',  
           textField:'no_sts',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_sts_retribusi_cms/'+kode,  
           columns:[[  
               {field:'no_sts',title:'No Bukti',width:100},  
               {field:'total',title:'Nilai',width:150},
			   {field:'nm_pengirim',title:'Pengirim',width:150},
               {field:'keterangan',title:'Ket',width:400},    
           ]],  
           onSelect:function(rowIndex,rowData){
               nos = rowData.no_sts;
               kode = rowData.kd_skpd;
               tglsts = rowData.tgl_sts;
               ket =rowData.keterangan;
               giat =rowData.kd_kegiatan;
               nilai =rowData.total;
               jnst =rowData.jns_trans;
               sumber =rowData.sumber;
               infox = rowData.infoin;
               
               $("#tanggal").datebox("setValue",tglsts);
               $("#ket").attr("value",ket);
               $("#info").attr("value",infox);
               $("#giat").combogrid("setValue",giat);
               $("#jumlahtotal").attr("value",nilai);               
               $("#jns_trans").combobox("setValue",jnst);
               $("#sumber").attr("value",sumber);
               detail(nos,kode,infox); 
           }  
       });
    
        $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/skpduser',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;
               tgl_awal = $('#tgl_kas').datebox('getValue');               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               $('#nomor').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_sts_retribusi_cms/'+kode+'/'+tgl_awal});                 
           }  
       });
       
        $('#jns_trans').combobox({  
        url:'<?php echo base_url(); ?>index.php/tukd/load_jns_rek',  
        valueField:'id',  
        textField:'text',
        onSelect:function(record){
               lcskpd=$('#skpd').combogrid('getValue');
               lckode = record.id;
                //alert('<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd);  
               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd});
                                
           }    
         });  
         
      
       
       // $('#bank').combogrid({  
//           panelWidth:300,  
//           idField:'kode',  
//           textField:'nama',  
//           mode:'remote',
//           url:'<?php echo base_url(); ?>index.php/tukd/bank',  
//           columns:[[  
//               {field:'kode',title:'Kode ',width:100},  
//               {field:'nama',title:'Nama ',width:700}    
//           ]]
//       });
  
          $('#cmb_rek').combogrid({  
           panelWidth:700,  
           idField:'kd_rek5',  
           textField:'kd_rek5',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek/'+kode+'/'+giat,             
           columns:[[  
               {field:'kd_rek5',title:'Kode Rekening',width:140},  
               {field:'nm_rek',title:'Uraian',width:700},
              ]],
              
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek);
              }    
            });
  
                     
        $('#giat').combogrid({
           panelWidth:700,  
           idField:'kd_kegiatan',  
           textField:'kd_kegiatan',  
           mode:'remote',
           //url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lckode+'/'+lcskpd,
           url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
           columns:[[  
               {field:'kd_kegiatan',title:'Kode Kegiatan',width:140},  
               {field:'nm_kegiatan',title:'Nama Kegiatan',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               giat = rowData.kd_kegiatan;
               $("#nmgiat").attr("value",rowData.nm_kegiatan);                                      
           }
              
        });
        
        
    });   
    
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut_ppkdx',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        		$("#no_kas").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
		
	function ambil_nomor(){
			  $('#nomor').combogrid("setValue",'');
		$('#nomor').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_sts_retribusi_cms/'+kode
                                   });
	}
    function openWindow( url )
        {
        var no =nomor.split("/").join("123456789");
        window.open(url+'/'+no, '_blank');
        window.focus();
        }     

    function loadgiat(){
        var lcjnsrek=document.getElementById("jns_trans").value;
        alert(lcjnsrek);
         $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lcjnsrek});  
    }
    
    function load_detail(kk){        
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/load_dsts_ppkd',
                data: ({no:kk}),
                dataType:"json",
                success:function(data){                                   
                                $.each(data,function(i,n){
                                id = n['id'];    
                                kdrek = n['kd_rek5']; 
                                kdrek6 = n['kd_rek6'];                                                                   
                                lnrp = n['rupiah'];    
                                lcnmrek = n['nm_rek'];
                                lcnosts = n['no_sts'];
                                $('#dg1').datagrid('appendRow',{id:id,no_sts:lcnosts,kd_rek5:kdrek,rupiah:lnrp,nm_rek:lcnmrek,kd_rek6:kdrek6});                         
                                });   
                                 
                }
            });
           });  
  
         set_grid();
                           
    }
   

     function detail(nosts,kode,infox){
       // alert(nosts);
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/load_dsts_cms',
                queryParams:({no:nosts,kd_skpd:kode,info:infox}),
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,                                			 				 
                 columns:[[
                            {field:'id',
                    		title:'ID',    		
                            hidden:"true"},
                            {field:'no_sts',
                    		title:'No STS',    		
                            hidden:"true"},                
                    	    {field:'kd_rek5',
                    		title:'Nomor Rekening',
                            width:150},
                            {field:'nm_rek',
                    		title:'Nama Rekening',
                            width:400},                
                            {field:'rupiah',
                    		title:'Rupiah',
                            align:'right',
                            width:200},
                            {field:'no_terima',
        		            title:'terima',
                            hidden:'true',
                            width:200},
                            {field:'kd_rek6',
                    		title:'Nomor Rekening',
                            width:150,hidden:true}               
                        ]]	
			});
		});
        }
 
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
         set_grid();      
     }
       
     
    function get(nomorkas,tglkas,nomor,tgl,kode,lckdbank,lckdgiat,lcket,lcjnskeg,lcrekbank,lctotal,lcinfo){        
        $("#no_kas").attr("value",nomorkas);
        $("#no_simpan").attr("value",nomorkas);
        $("#tgl_kas").datebox("setValue",tglkas);
        $("#nomor").combogrid("setValue",nomor);//attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#skpd").combogrid("setValue",kode);
        $("#info").attr("value",lcinfo);
        //$("#bank").combogrid("setValue",lckdbank);
        $("#ket").attr("value",lcket)
        $("#jns_trans").combobox("setValue",lcjnskeg)
        $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_trskpd1/'+lcjnskeg+'/'+kode}); 
        $("#giat").combogrid("setValue",lckdgiat);
        //$("#rek_bank").attr("value",lcrekbank);        
        $("#jumlahtotal").attr("value",lctotal);
    }
    
    function get_edt(lcrekedt,lcnmrekedt,lcnilaiedt){
        $("#rek_edt").attr("value",lcrekedt);
        $("#nmrek_edt").attr("value",lcnmrekedt);
        $("#nilai_edt").attr("value",lcnilaiedt);
        $("#nilai_edth").attr("value",lcnilaiedt);
        $("#dialog-modal_edit").dialog('open');
    } 
    
    function kosong(){        
        $("#nomor").combogrid("setValue",'');   
        $("#tgl_kas").datebox("setValue",'');     
        $("#no_simpan").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#skpd").combogrid("setValue",'');
        $("#nmskpd").attr("value",'');
        $("#jns_trans").combobox("setValue",'');
        $("#bank").combogrid("setValue",'');
        $("#ket").attr("value",'');
        $("#nmgiat").attr("value",'');
        $("#jumlahtotal").attr("value",0);
        var kode = '';
        var nomor = '';
		get_nourut();
        $('#giat').combogrid('setValue','');
        //$('#dg1').edatagrid('unselectAll');         
        //$('#dg1').datagrid('loadData', {"total":0,"rows":[]});  
        set_grid();
        lcstatus='tambah';
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_sts_ppkd',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
   function append_save(){        
        
            //set_grid();
            var ckdrek = $('#cmb_rek').combogrid('getValue');
            var lcno = document.getElementById('nomor').value;
            var lcnm = document.getElementById('nmrek').value;
            var lcnl = angka(document.getElementById('nilai').value);
            var lstotal = angka(document.getElementById('jumlahtotal').value);
            var lcnl1 = number_format(lcnl,0,'.',',');
            

                                     
            if (ckdrek != '' && lcnl != 0 ) {
                //alert(lstotal);
                total = number_format(lstotal+lcnl,0,'.',',');
                cid = cid + 1;            
                $('#dg1').datagrid('appendRow',{id:cid,no_sts:lcno,kd_rek5:ckdrek,rupiah:lcnl1,nm_rek:lcnm});    
                $('#jumlahtotal').attr('value',total);    
                rek_filter(); 
            }
             
            $('#cmb_rek').combogrid('setValue','');
            $('#nilai').attr('value','0');
            $('#nmrek').attr('value','');
            
           // $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek/'+kode+'/'+giat});
         
        
    }     
    
    
    function rek_filter(){
        var crek='';
         $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');           
			for(var i=0;i<rows.length;i++){
				crek   = crek+"A"+rows[i].kd_rek5+"A";
                if (i<rows.length && i!=rows.length-1){
                    crek = crek+'B';
                }
            }
               $('#dg1').datagrid('unselectAll');
          $('#cmb_rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek/'+kode+'/'+giat+'/'+crek});  
    }
    
    function set_grid(){
        $('#dg1').edatagrid({  
            columns:[[
                {field:'id',
          		title:'ID',    		
                hidden:"true"},
                {field:'no_sts',
          		title:'No STS',    		
                hidden:"true"},                
           	    {field:'kd_rek5',
          		title:'Nomor Rekening',
                width:150},
                {field:'nm_rek',
          		title:'Nama Rekening',
                width:400},                
                {field:'rupiah',
          		title:'Rupiah',
                align:'right',
                width:200},
                {field:'no_terima',
                title:'terima',
                hidden:'true',
                width:200},
                {field:'kd_rek6',
          		title:'Nomor Rekening',
                width:150,hidden:true},                 
                
            ]]
        });    
    }
    
    function tambah(){
        var lcno = document.getElementById('nomor').value;
        //var cjnstetap = document.getElementById('jns_tetap').checked;
        //alert(cjnstetap);
        
        //if(cjnstetap==true){
//            $("#dialog-modal_t").dialog('open');
//        } else {
            if(lcno !=''){
               
            $("#dialog-modal").dialog('open');
            $('#nilai').attr('value','0');
            $('#nmrek').attr('value','');
            //$('#jumlahtotal').attr('value','0');
            var kode = $('#skpd').combogrid('getValue');
            var giat = $('#giat').combogrid('getValue');
            
            
            } else {
                alert('Nomor Sts Tidak Boleh kosong')
                document.getElementById('nomor').focus();
                exit();
            }
            
            rek_filter();
        //}
                
    }
    
    function cetak(){
        $("#dialog-modal_cetak").dialog('open');             
    }
    
    function keluar(){
        $("#dialog-modal").dialog('close');
        $("#dialog-modal_t").dialog('close');
        $("#dialog-modal_cetak").dialog('close');
        $("#dialog-modal_edit").dialog('close');
    }    
    
    
    function hapus_rek(){
        var lckurang = angka(lnnilai);
        var lstotal = angka(document.getElementById('jumlahtotal').value);
        lntotal =  number_format(lstotal - lckurang,0,'.',',');
        
        $("#jumlahtotal").attr("value",lntotal);
                
        $('#dg1').datagrid('deleteRow',id);     
    }
    
     function hapus(){
        var cnomor = nomorkas;
        var info = "Inputan CMS";//infox;       

        var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_sts_ppkd';
        $(document).ready(function(){
         $.post(urll,({no:cnomor,info:info}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
        });    
    }
    
    function simpan_sts(){
        var cnokas = document.getElementById('no_kas').value;
        var cnosimpan = document.getElementById('no_simpan').value;
        var ctglkas = $('#tgl_kas').datebox('getValue');
        var cno = $('#nomor').combogrid('getValue');//document.getElementById('nomor').value;
        var cbank = '';//$('#bank').combogrid('getValue'); 
        var ctgl = $('#tanggal').datebox('getValue');
        var cskpd = $('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var sumber = document.getElementById('sumber').value;
        var cjnsrek = $('#jns_trans').combobox('getValue');
        var cgiat = $('#giat').combogrid('getValue');
        var csubgiat = cgiat + ".01"; 
        var lcrekbank ='';// document.getElementById('rek_bank').value;
        var lntotal = angka(document.getElementById('jumlahtotal').value);
        var cinfo = document.getElementById('info').value;
        //var cstatus = document.getElementById('jns_tetap').checked;
//        if (cstatus==false){
//           cstatus=0;
//        }else{
//            cstatus=1;
//        }
         //alert(cno);       
         
        if(cinfo=='Inputan CMS'){
            var init_info = '1';
        }else{
            var init_info = '0';
        } 
         
        if (cno==''){
            alert('Nomor STS Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal STS Tidak Boleh Kosong');
            exit();
        }
        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }
        
		//if(lcstatus == 'tambah'){
		  
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnokas,tabel:'trhkasin_ppkd',field:'no_kas'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
		
		//---------		
        $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');           
		lcval_det = '';
        lcval_det_pkd = '';
        for(var i=0;i<rows.length;i++){
            //alert(i);
            //alert (ckegi);
			cnosts   = rows[i].no_sts;
            ckdrek  = rows[i].kd_rek5; 
            ckdrek6  = rows[i].kd_rek6;             
            cnilai  = angka(rows[i].rupiah);  
            cnoterima  = rows[i].no_terima; 
            //alert(cnoterima);
            //alert(cnosts+'/'+ckdrek+'/'+cnilai); 
            if(i>0){
				lcval_det = lcval_det+",('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+ckdrek6+"')";
                lcval_det_pkd = lcval_det_pkd+",('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnoterima+"','"+cgiat+"','"+ckdrek6+"')";
			}else{
				lcval_det = lcval_det+"('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+ckdrek6+"')";
                lcval_det_pkd = lcval_det_pkd+"('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnoterima+"','"+cgiat+"','"+ckdrek6+"')";
			}    
                        
		}
        
        $('#dg1').datagrid('unselectAll'); 
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/simpan_sts_ppkd_cms',
                data: ({tabel:'trhkasin_ppkd',nokas:cnokas,tglkas:ctglkas,no:cno,bank:cbank,tgl:ctgl,skpd:cskpd,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,value_det_pkd:lcval_det_pkd,sumber:sumber,init_info:init_info}),
                dataType:"json",
                success:function(data){
                    status = data.pesan;
                    if(status=='0'){
                          alert('gagal');
                    } else{
							$("#no_simpan").attr("value",cnokas);
							alert("Data Berhasil disimpan");
							$('#dg').edatagrid('reload');
							lcstatus = 'edit';
							section1();
					}
                }
            });
        });
         ///
		 
		 //----------
		
		}
		}
		});
		});
		
        
        /*    
        } else {
//alert(z);
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnokas,tabel:'trhkasin_ppkd',field:'no_kas'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cnokas!=cnosimpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cnokas==cnosimpan){
						alert("Nomor Bisa dipakai");
	//---
	$('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections');           
		lcval_det = '';
        for(var i=0;i<rows.length;i++){
            //alert(i);
            //alert (ckegi);
			cnosts  = rows[i].no_sts;
            ckdrek  = rows[i].kd_rek5;   
            ckdrek6 = rows[i].kd_rek6;           
            cnilai  = angka(rows[i].rupiah);  
            cnoterima  = rows[i].no_terima; 
            //alert(cnosts+'/'+ckdrek+'/'+cnilai); 
            if(i>0){
				lcval_det = lcval_det+",('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+ckdrek6+"')";
                lcval_det_pkd = lcval_det_pkd+",('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnoterima+"','"+cgiat+"','"+ckdrek6+"')";
			}else{
				lcval_det = lcval_det+"('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnokas+"','"+cgiat+"','"+ckdrek6+"')";
                lcval_det_pkd = lcval_det_pkd+"('"+cskpd+"','"+cnosts+"','"+ckdrek+"','"+cnilai+"','"+cnoterima+"','"+cgiat+"','"+ckdrek6+"')";
			}
                      
		}
        
        $('#dg1').datagrid('unselectAll'); 
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/tukd/update_sts_ppkd_cms',
                data: ({tabel:'trhkasin_ppkd',nosimpan:cnosimpan,nokas:cnokas,tglkas:ctglkas,no:cno,bank:cbank,tgl:ctgl,skpd:cskpd,ket:lcket,jnsrek:cjnsrek,giat:cgiat,rekbank:lcrekbank,total:lntotal,value_det:lcval_det,value_det_pkd:lcval_det_pkd,init_info:init_info}),
                dataType:"json",
                success:function(data){
                    status = data.pesan;
                    if(status=='0'){
                          alert('gagal');
                    } else{
							$("#no_simpan").attr("value",cnokas);
							alert("Data Berhasil disimpan");
							$('#dg').edatagrid('reload');
							lcstatus = 'edit';
							section1();
							}
						}
					});
				});
         ///
			}
			}
		});
     });
		
        }*/
					
	}			


	
    
    
    function jumlah(){
        var lcno = document.getElementById('nomor').value;
        var lcnm = document.getElementById('nmrek1').value;
        ckdrek = $('#rek').combogrid('getValue'); 
        var rows = $('#dg_tetap').datagrid('getChecked');
        cid = cid + 1;      
        
        var lstotal = angka(document.getElementById('jumlahtotal').value);
        
        
        var lnjm = 0;    
        	for(var i=0;i<rows.length;i++){
        	   ltmb = angka(rows[i].nilai);
               lnjm = lnjm + ltmb;
                               
        	   }
  
            total = number_format(lstotal+lnjm,0,'.',',');
            $('#jumlahtotal').attr('value',total);    
            lcjm = number_format(lnjm,0,'.',',')               

            $('#dg1').datagrid('appendRow',{id:cid,no_sts:lcno,kd_rek5:ckdrek,rupiah:lcjm,nm_rek:lcnm});
             
          keluar();
    }
  
    function delCommas(nStr)
    {
        var no =nStr.split(",").join("");
        return no1 = eval(no);
    }
    
    function edit_detail(){
    
         var lnnilai = angka(document.getElementById('nilai_edt').value);
         var lnnilai_sb = angka(document.getElementById('nilai_edth').value);
         var lstotal = angka(document.getElementById('jumlahtotal').value);
         
         lcnilai = number_format(lnnilai,0,'.',',');
         total = lstotal - lnnilai_sb + lnnilai; 
         ftotal = number_format(total,0,'.',',');
         $('#dg1').datagrid('updateRow',{
            	index: id,
            	row: {
            		rupiah: lcnilai                    
            	}
         });
         $('#jumlahtotal').attr('value',ftotal);  
         keluar();
    }
    
    </script>

</head>
<body>



<div id="content"> 
   
<div id="accordion">
<h3><a href="#" id="section1">List STS</a></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="List STS" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>   

<h3><a href="#" id="section2" onclick="javascript:set_grid();">Surat Tanda Setoran</a></h3>
   <div  style="height: 350px;">
   <p>       
        <table align="center" style="width:100%;" border="0">
            <tr>
                <td>No. Kas</td>
                <td><input type="text" id="no_kas" style="width: 200px;"/> <input type="hidden" id="no_simpan" style="width: 50px;"/></td>
                <td>Tanggal Kas</td>
                <td><input type="text" id="tgl_kas" name="tgl_kas" style="width: 140px;"/></td>     
            </tr> 
            <tr>
                <td>S K P D</td>
                <td><input id="skpd" name="skpd" style="width: 140px;" /></td>
                <td colspan="2" align="left"><input type="text" id="nmskpd" style="border:0;width: 450px;" readonly="true"/></td>
                                
            </tr>
            <tr>
                <td>No. Bukti</td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>
                <td>Tanggal Bukti</td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>   
            </tr>            
            <!--<tr>
                <td>BANK</td>
                <td><input type="text" id="bank" name="bank" style="border:0;width: 150px;" readonly="true" /></td>  
                <td>Rekening Bank</td>
                <td><input type="text" id="rek_bank" style="width: 300px;"/></td>
            </tr>-->
            <tr>
                <td>Uraian</td>
                <td colspan="3"><input type="text" id="ket" style="width: 700px;"/><input type="hidden" name="sumber" id="sumber"/></td>                
            </tr> 
            <tr>
                <td>Jenis Transaksi</td>
                <td colspan="3">
                <input  id="jns_trans" name="jns_trans" style="border:0;width: 150px;"/>                 
                </td> 
            </tr>
            <tr>
            <td>Kegiatan</td>
            <td colspan="3"><input id="giat" name="skpd" style="width: 200px;" />&nbsp;
            <input type="text" id="nmgiat" style="border:0;width: 350px;" readonly="true"/></td>
            </tr>
            <tr><td>Ket. Input</td>
            <td colspan="3"><input type="text" id="info" style="border:0;width: 400px;" readonly="true"/></td></tr>
            <!--<tr>
            <td colspan="4">Dengan Penetapan <input id="jns_tetap" type="checkbox"/></td>            
            </tr>-->
            <tr>
                <td colspan="4" align="right"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_sts();">Simpan</a>
		            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a></td>                
            </tr>
        </table>          
        <table id="dg1" title="Detail STS" style="width:870px;height:330px;" >  
        </table>  
        <!--<div id="toolbar">
    		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
    		<a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rek();">Hapus Rekening</a>    		
        </div>
        -->
                
   </p>
   <table border="0" align="right" style="width:100%;"><tr>
   <td style="width:75%;" align="right"><B>JUMLAH</B></td>
   <td align="right"><input type="text" id="jumlahtotal" readonly="true" style="border:0; width: 200px; text-align:right; font-weight:bold;"/></td>
   </tr>
   </table>
   
   </div>
       


</div>

</div>


<div id="dialog-modal" title="Input Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input id="cmb_rek" name="cmb_rek" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>

<div id="dialog-modal_edit" title="Edit Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">Kode Rekening:</td>
            <td><input type="text" id="rek_edt" readonly="true" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td width="110px">Nama Rekening:</td>
            <td><input type="text" id="nmrek_edt" readonly="true" style="border:0;width: 400px;"/></td>
        </tr>
        <tr> 
           <td width="110px">Nilai:</td>
           <td><input type="text" id="nilai_edt" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/>
               <input type="hidden" id="nilai_edth"/> 
           </td>
        </tr>
    </table>  
    </fieldset>
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:edit_detail();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>


<div id="dialog-modal_cetak" title="Input Rekening">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
    <table>
        <tr>
            <td width="110px">No STS:</td>
            <td><input id="cmb_sts" name="cmb_sts" style="width: 200px;" /></td>
        </tr>
    </table>  
    </fieldset>
     <fieldset>
    <table border="0">
        <tr align="center">
            <td></td>
            <td width="100%" align="center"><a  href="<?php echo site_url(); ?>tukd/cetak_sts" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  </td>
        </tr>
    </table>  
    </fieldset>
    
	
</div>


<div id="dialog-modal_t" title="Checkbox Select">
<table border="0">
<tr>
<td>Rekening</td>
<td><input id="rek" name="rek" style="width: 140px;" />  <input type="text" id="nmrek1" style="border:0;width: 400px;" readonly="true"/></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2">
    <table id="dg_tetap" style="width:770px;height:350px;" >  
        </table>
    </td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan="2" align="center">
    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:jumlah();">Simpan</a>
	<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a></td>
</tr>
</table>  
</div>


    

  	
</body>

</html>