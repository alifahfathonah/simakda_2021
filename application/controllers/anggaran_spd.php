<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
class anggaran_spd extends CI_Controller {

public $ppkd = "4.02.01"; 
public $ppkd1 = "4.02.01.00";
 
    function __construct(){  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    } 
 
    function spd_belanja(){
        $data['jenis']="belanja";
        $data['page_title']= 'INPUT SPD BELANJA';
        $this->template->set('title', 'INPUT SPD BELANJA');   
        $this->template->load('template','anggaran/spd/spd_belanja',$data) ; 
    }

    function spd_pembiayaan(){
        $data['jenis']="pembiayaan";
        $data['page_title']= 'INPUT SPD PEMBIAYAAN';
        $this->template->set('title', 'INPUT SPD PEMBIAYAAN');   
        $this->template->load('template','anggaran/spd/spd_belanja',$data) ; 
    } 

    function spd_belanja_revisi(){
        $data['page_title']= 'INPUT SPD BELANJA REVISI';
        $this->template->set('title', 'INPUT SPD BELANJA REVISI');   
        $this->template->load('template','anggaran/spd/spd_belanja_revisi',$data) ; 
    }    

    function spd_pembiayaan_revisi(){
        $data['page_title']= 'INPUT SPD PEMBIAYAAN REVISI';
        $this->template->set('title', 'INPUT SPD PEMBIAYAAN REVISI');   
        $this->template->load('template','anggaran/spd/spd_pembiayaan_revisi',$data) ; 
    } 

    function skpduser_bp() {
        $lccr = $this->input->post('q');
        $result=$this->master_model->skpduser_induk($lccr);
           
        echo json_encode($result);
    }

    function bln_spdakhir(){
        $kdskpd = $this->input->post('skpd');
        $jns = $this->input->post('jenis');
        $result=$this->anggaran_spd_model->bln_spdakhir($kdskpd,$jns);
        echo ($result);
    }
   
    function load_spd_bl($kriteria='') {
        $id      = $this->session->userdata('pcUser');
        $kd_skpd = $this->session->userdata('kdskpd');
        $beban = $this->input->post('jenis'); 
        $result  =$this->anggaran_spd_model->load_spd_bl($kriteria,$kd_skpd,$id,$beban);                  
        echo ($result);    
    }

    function load_ttd_bud(){
        echo $this->master_ttd->load_ttd_bud();                
    }
    
    function load_skpd_bp(){
        $lccr=$this->input->post('q');          
        echo $this->master_ttd->load_skpd_bp($lccr);

    }

    function jumlah_detail_angkas_spd_baru(){ /*cek selisih angkas*/
        $skp      = $this->input->post('skp');
        $jn       = $this->input->post('jn');
        echo $this->anggaran_spd_model->jumlah_detail_angkas_spd_baru($skp,$jn);
    }  

    function config_spd_nomor(){
        echo $this->anggaran_spd_model->config_spd_nomor();
    }

    function load_tot_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$tgl1=''){
        echo $this->anggaran_spd_model->load_tot_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$tgl1);
    }

    function load_bendahara_p(){
        $kdskpd = $this->input->post('kode');
        $cari = $this->input->post('q');
        echo $this->master_ttd->load_bendahara_p($kdskpd,$cari);
    }
    
    function load_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$cbln1=''){
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = $this->input->post('cari');
        echo $this->anggaran_spd_model->load_dspd_bl($jenis,$skpd,$awal,$ahir,$nospd,$cbln1,$tgl,$page,$rows,$offset,$kriteria); 
    }

    function cek_simpan_spd(){
        $nomor   = $this->input->post('no');
        $skpd    = $this->input->post('skpd');
        $awal    = $this->input->post('awal');   
        $akhir    = $this->input->post('akhir');
        echo $this->anggaran_spd_model->cek_simpan_spd($nomor,$skpd,$awal,$akhir);
    } 

       function simpan_spd(){
        $tabel  = $this->input->post('tabel');
        $idx    = $this->input->post('cidx');
        $nomor  = $this->input->post('no');
        $cno_u  = $this->input->post('cno_u');
        $nomor2  = $this->input->post('no2');
        $mode_tox= $this->input->post('mode_tox');
        $tgl    = $this->input->post('tgl');
        $skpd   = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $bend   = $this->input->post('bend');
        $bln1   = $this->input->post('bln1');
        $bln2   = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $jenis_spp  = $this->input->post('jns_spp');
        $total      = $this->input->post('total');
        $csql       = $this->input->post('sql');        
        $usernm     = $this->session->userdata('pcNama');    
        $update     = date('Y-m-d H:i:s');    
        $msg = array();                
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox=='tambah'){

                $sql = "INSERT into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate,jns_spp,total_hasil,urut) 
                        values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$total', rtrim('$ketentuan'),'$bend','$usernm','$update','$jenis_spp','$total','$cno_u')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          

            } else if($mode_tox=='edit'){
                $sql = "UPDATE $tabel set 
                    no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                    jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',total_hasil='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update',jns_spp='$jenis_spp'
                    where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          
                
            }
            
        } else if ($tabel == 'trdspd') {
            
            // Simpan Detail //                       
                $sql = "delete from  $tabel where no_spd='$nomor2'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg= array('pesan'=>'0');
                   echo json_encode($msg);
                   exit();
                } else {
                    $sql = "INSERT into  $tabel(no_spd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,kd_program,nm_program,nilai,nilai_final,kd_subkegiatan,nm_subkegiatan)";                        
                    $asg = $this->db->query($sql.$csql);
                    $updt=$this->db->query("UPDATE a SET
                                            a.total=b.nilai,
                                            a.total_hasil=b.final
                                            from trhspd a inner join 
                                            (select sum(nilai_final) final, sum(nilai) nilai, no_spd from trdspd where no_spd='$nomor' 
                                            GROUP BY no_spd) b on a.no_spd=b.no_spd where a.no_spd='$nomor'");
                    if (!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                             
        }        
    }

    function hapus_spd(){
        $nomor = $this->input->post('no');
        echo $this->anggaran_spd_model->hapus_spd($nomor);           
    } 

    function load_dspd_ag_bl() {            
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $dskpd = substr($skpd,0,22);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $rows='';
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = $this->input->post('cari');
        echo $this->anggaran_spd_model->load_dspd_ag_bl($no,$jenis,$skpd,$dskpd,$tgl,$cbln1,$page,$rows,$offset,$kriteria);
    } 

    function load_spd_bl_angkas() {
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $beban = $this->input->post('beban');  
        $id  = $this->session->userdata('pcUser');  
        echo $this->anggaran_spd_model->load_spd_bl_angkas($kd_skpd,$page, $rows,$offset,$kriteria,$id,$beban);    
    }

    function update_sts_spd(){
        $no_spd      = $this->input->post('no');
        $ckd_skpd     = $this->input->post('kd_skpd');
        $csts        = $this->input->post('status_spd');
        echo $this->anggaran_spd_model->update_sts_spd($no_spd, $ckd_skpd,$csts);            
        
    }

    function jumlah_detail_spd(){
        
        $no_spd = $this->input->post('cno_spd');
        $rek = $this->input->post('jns');
      
        $sql = "SELECT SUM(a.nilai) as nilai,SUM(a.nilai_refisi1) as nilai_refisi FROM trdspd a 
        inner join trhspd b on b.no_spd = a.no_spd
        WHERE b.no_spd = '$no_spd'";
                
        $query1 = $this->db->query($sql);  
        $test   = $query1->num_rows();
        $ii     = 0;
        
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'total' => $resulte['nilai'],
                        'total_refisi' => $resulte['nilai_refisi']
                        );
                        $ii++;
        }
        
        if ($test===0){
            $result = array(
                        'total' => ''
                        );
                        $ii++;
        }       
        echo json_encode($result);
        $query1->free_result();   
    }

    function cek_simpan(){ /*untuk cek appakah ada spd di tabel trhspp*/
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field'); /*trhspp*/
        echo $this->anggaran_spd_model->cek_simpan($nomor,$tabel,$field);       
    }

    function load_spd_bl_angkas_revisi() {
        $kd_skpd = $this->session->userdata('kdskpd'); 
        
        //$kd_skpd = '1.20.08.10'; 
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari'); 
        $id  = $this->session->userdata('pcUser');  

        if ($kriteria <> ''){                               
            $where="where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper( left(a.jns_beban,1) )='5' 
                     ) ";            
        }
        
        $sql = "SELECT count(*) as total from trhspd a " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        //$sql = "SELECT *,IF(jns_beban='52','Belanja Langsung','Belanja Tidak Langsung') AS nm_beban from trhspd $where order by tgl_Spd,kd_skpd limit $offset,$rows";
        
        $sql = "SELECT  a.*,
        (select nama from ms_ttd x where x.nip = a.kd_bkeluar and x.kd_skpd = a.kd_skpd) nama
        ,'BELANJA' AS nm_beban from trhspd a  order by urut ";
        $query1 = $this->db->query($sql);       
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
           

             if($resulte['refisi']==""){
                $cekrefisi=0;
                $ketrefisi='Belum Revisi';
            }else{
                $cekrefisi=$resulte['refisi'];
                $ketrefisi='Revisi Ke '.$resulte['refisi'];
            }
            
            $opd = $resulte['kd_skpd'];
            $n_status = $this->anggaran_spd_model->get_status3($opd);

            $row[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'tgl_spd' => $resulte['tgl_spd'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'ketentuan' => $resulte['klain'],
                        'nama_bend' => $resulte['nama'],
                        'nip' => $resulte['kd_bkeluar'],                        
                        'jns_beban' => $resulte['jns_beban'],
                        'nm_beban' => $resulte['nm_beban'],
                        'bulan_awal' => $resulte['bulan_awal'],
                        'bulan_akhir' => $resulte['bulan_akhir'],
                        'total' => $resulte['total_hasil'],                                                                      
                        'status' => $resulte['status'],
                        'st' => $resulte['status'],
                        'refisi' => $cekrefisi,
                        'ket_refisi' => $ketrefisi,
                        'tgl_refisi' => $resulte['tgl_refisi1'],
                        'status_ang' => $n_status                                                                            
                        );
                        $ii++;
        }
        $result["rows"] = $row;           
        echo json_encode($result);
        $query1->free_result();        
    }

     function load_dspd_ag_bl_angkas_revisi() {            
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $dskpd = substr($skpd,0,17); 
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        //echo($jenis);
        //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
        //echo($sql);
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $n_status = $this->anggaran_spd_model->get_status($tgl,$skpd);        
        
        //$spd = str_replace('123456789','/',$nospd); 
        $field=$n_status;
          $sql = "SELECT a.* ,kd_rek5, nm_rek5,
                (SELECT SUM(nilai_final) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_subkegiatan=a.kd_subkegiatan  AND m.no_spd <> '$no' and month(m.tgl_spd)<'$cbln1') AS lalu,
                (select sum($field) from trdrka where kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND left(b.kd_skpd,17)='$dskpd' 
                AND a.kd_subkegiatan NOT IN (SELECT TOP $offset kd_subkegiatan FROM trdspd where no_spd = '$no')order by b.no_spd,a.kd_subkegiatan,a.kd_rek5 "; //echo($sql);
        
        $query1 = $this->db->query($sql);  
        $resultw = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $resultw[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],
                        'kd_kegiatan' => $resulte['kd_subkegiatan'],
                        'nm_kegiatan' => $resulte['nm_subkegiatan'],
                        'kd_program'  => $resulte['kd_program'],
                        'nm_program'  => $resulte['nm_program'],
                        'kd_rekening' => $resulte['kd_rek5'],
                        'nm_rekening' => $resulte['nm_rek5'],
                        'nilai'       => number_format($resulte['nilai'],"2",".",","),
                        'nilairefisi'       => number_format($resulte['nilai_refisi1'],"2",".",","),
                        'lalu'        => number_format($resulte['lalu'],"2",".",","),
                        'anggaran'    => number_format($resulte['anggaran'],"2",".",",")                               
                        );
                        $ii++;
        }
           
/*        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result(); */  
        echo json_encode($resultw);
    }

    function load_dspd_bl_angkas($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$cbln1=''){
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $dskpd = substr($skpd,0,17);                
        if ($kriteria <> ''){                               
            $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }

        //$sql = "SELECT count(*) as tot FROM trskpd a WHERE left(a.kd_skpd,7)='$dskpd' and a.jns_kegiatan ='52'";
        $sql = "SELECT count(*) as tot FROM trskpd a WHERE left(a.kd_skpd,17)='$dskpd'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        //echo '<script type="text/javascript">alert("' . $skpd. '"); </script>';
        
        $n_status = $this->anggaran_spd_model->get_status2($skpd);
        
        $spd = str_replace('123456789','/',$nospd);
        $field= 'a.'.$n_status;
        $fieldb= $n_status;
        
        $spd = str_replace('123456789','/',$nospd);
        $sql = "SELECT  a.kd_kegiatan, a.nm_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                a.total_ubah as anggaran, nilai,refisi,lalu FROM(

                select a.kd_sub_kegiatan kd_kegiatan, b.nm_sub_kegiatan nm_kegiatan, b.kd_program, b.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                sum($field) as total_ubah, left(a.kd_skpd,17) kd_skpd
                FROM trdrka a inner join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
                 WHERE left(a.kd_skpd,17)=LEFT ('$dskpd', 17) and left(a.kd_rek6,1) ='5' group by a.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_program, b.nm_program,LEFT (a.kd_skpd, 17)
                    

                ) a LEFT JOIN (

                   SELECT kd_subkegiatan kd_kegiatan,LEFT (kd_skpd, 17)+'.0000' kd_skpd, SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                    WHERE left(b.kd_skpd,17)=LEFT ('$dskpd', 17) and bulan_awal='$awal' and bulan_akhir='$ahir'
                    GROUP BY kd_subkegiatan,LEFT (kd_skpd, 17)

                )b ON a.kd_kegiatan=b.kd_kegiatan AND left(a.kd_skpd,7)=left(b.kd_skpd,7) 
                LEFT JOIN (

                    SELECT kd_subkegiatan kd_kegiatan, LEFT (kd_skpd, 17)+'.0000' kd_skpd, SUM ($fieldb) AS refisi FROM trdskpd_ro a inner join (select kd_sub_kegiatan oke from trdrka group by kd_sub_kegiatan) b on a.kd_subkegiatan=b.oke
                   WHERE LEFT (kd_skpd, 17) = LEFT ('$dskpd', 17) and left(kd_rek6,1)='5' AND bulan BETWEEN '$awal' AND '$ahir' GROUP BY kd_subkegiatan, LEFT (kd_skpd, 17)

                )e ON a.kd_kegiatan=e.kd_kegiatan AND left(a.kd_skpd,17)=left(e.kd_skpd,17)
                LEFT JOIN (
                    SELECT kd_subkegiatan kd_kegiatan,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                    WHERE left(b.kd_skpd,17)=LEFT ('$dskpd', 17) and a.no_spd != '$spd' and month(b.tgl_spd)<'$cbln1'
                    GROUP BY kd_subkegiatan
                ) c ON a.kd_kegiatan=c.kd_kegiatan              
                ORDER BY a.kd_kegiatan 
                ";//echo($sql);


             //and a.jns_kegiatan ='52'    
            //WHERE a.kd_subkegiatan NOT IN (SELECT TOP $offset kd_subkegiatan FROM  trskpd a WHERE left(a.kd_skpd,7)='$dskpd' and a.jns_kegiatan ='51' ORDER BY a.kd_kegiatan)
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id'          => $ii,        
                        'no_spd'      => '',                        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program'  => $resulte['kd_program'],
                        'nm_program'  => $resulte['nm_program'],
                        'kd_rekening' => $resulte['kd_rek5'],
                        'nm_rekening' => $resulte['nm_rek5'],
                        'nilai'       => number_format($resulte['nilai'],"2",".",","),
                        'nilairefisi'       => number_format($resulte['refisi'],"2",".",","),
                        'lalu'        => number_format($resulte['lalu'],"2",".",","),
                        'anggaran'    => number_format($resulte['anggaran'],"2",".",",")
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

    function simpan_spd_refisi(){
        $tabel  = $this->input->post('tabel');
        $idx    = $this->input->post('cidx');
        $nomor  = $this->input->post('no');
        $nomor2  = $this->input->post('no2'); 
        $mode_tox= $this->input->post('mode_tox');
        $tgl    = $this->input->post('tgl');
        $skpd   = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $bend   = $this->input->post('bend');
        $bln1   = $this->input->post('bln1');
        $bln2   = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $jenis_spp  = $this->input->post('jns_spp');
        $total      = $this->input->post('total');
        $xrefisi      = $this->input->post('xrefisi');
        $tglrefisi   = $this->input->post('tglrefisi');
        $totalrefisi = $this->input->post('totalrefisi');        
        $csql       = $this->input->post('sql');        
        $usernm     = $this->session->userdata('pcNama');    
        $update     = date('Y-m-d H:i:s');    

        $msg = array();                
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox=='tambah'){
            
                $sql = "INSERT into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate,jns_spp,refisi,tgl_refisi1,total_refisi,total_hasil) 
                        values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$total', rtrim('$ketentuan'),'$bend','$usernm','$update','$jenis_spp','$xrefisi','$tglrefisi','$totalrefisi','$totalrefisi')";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          

            } else {
                $sql = "UPDATE $tabel set 
                    no_spd='$nomor',tgl_spd='$tgl',nm_skpd=rtrim('$nmskpd'),
                    jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update',jns_spp='$jenis_spp',refisi='$xrefisi',tgl_refisi1='$tglrefisi',total_refisi='$totalrefisi',total_hasil='$totalrefisi'
                    where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                } else {
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                }          
                
            }
            
        } else if ($tabel == 'trdspd') {
            
            // Simpan Detail //                       
                $sql = "DELETE from  $tabel where no_spd='$nomor2'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg= array('pesan'=>'0');
                   echo json_encode($msg);
                   exit();
                } else {
                    $sql = "INSERT into  $tabel(no_spd,kd_subkegiatan,nm_subkegiatan,kd_rek5,nm_rek5,kd_program,nm_program,nilai,nilai_refisi1,nilai_final,kd_kegiatan,nm_kegiatan)";                        
               
                    $asg = $this->db->query($sql.$csql);

                            $upat = "UPDATE a SET
                            a.total=b.nilai,
                            a.total_refisi=b.refisi,
                            a.total_hasil=b.jum
                            from trhspd a inner join 
                            (select no_spd, sum(nilai) nilai, sum(nilai_final) jum, sum(nilai_refisi1) refisi from trdspd GROUP BY no_spd) b on a.no_spd=b.no_spd
                            where a.no_spd=b.no_spd";
                              $this->db->query($upat);
                    if (!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {









                        
                        $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                             
        }        
    }













}
