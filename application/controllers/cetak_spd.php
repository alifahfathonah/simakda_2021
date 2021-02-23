<?php if (!defined('BASEPATH'))
 exit('No direct script access allowed');

class cetak_spd extends CI_Controller{

    function __construct(){  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    } 

    function cek_regis_spd_bud()
    {
        $tipe=$this->session->userdata('type');
        if($tipe==1){
            $data['page_title']= 'REGISTER SPD';
            $this->template->set('title', 'REGISTER SPD');   
            $this->template->load('template','anggaran/spd/cek_regis_spd',$data) ; 
        }else{
            $data['page_title']= 'REGISTER SPD';
            $this->template->set('title', 'REGISTER SPD');   
            $this->template->load('template','anggaran/spd/spd_bl',$data) ;              
        }

    }    

    function  tanggal_format_indonesia2($tgl){
        $tanggal  =  substr($tgl,7,2);
        $bulan  = $this-> getBulan(substr($tgl,5,2));
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.' '.$bulan.' '.$tahun;
 
    }  
    

    function  tanggal_format_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = $this-> getBulan(substr($tgl,5,2));
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.' '.$bulan.' '.$tahun;

    }
 
    function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
    }
    function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }

    function  dotrek($rek){
                $nrek=strlen($rek);
                switch ($nrek) {
                case 1:
                $rek = $this->left($rek,1);                             
                 break;
                case 2:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1);                                
                 break;
                case 3:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1);                               
                 break;
                 case 4:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2);                               
                 break;
                case 5:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2);                              
                break;
                case 7:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2).'.'.substr($rek,5,2);                             
                break;
                default:
                $rek = "";  
                }
                return $rek;
    }    
    function cetak_lampiran_spd1(){       
        $print = $this->uri->segment(3);
        $tnp_no = $this->uri->segment(4);
        $jns =  $this->uri->segment(8);
        $ttp = $this->input->post('tglttp');
        $nip_ppkd = $this->input->post('nip_ppkd');  
        $nama_ppkd = $this->input->post('nama_ppkd');       
        $jabatan_ppkd = $this->input->post('jabatan_ppkd'); 
        $pangkat_ppkd = $this->input->post('pangkat_ppkd');     
        $lntahunang = $this->session->userdata('pcThang');       
        $lcnospd = $this->input->post('nomor1');
        $lkd_skpd=$this->rka_model->get_nama($lcnospd,'kd_skpd','trhspd','no_spd');
        $ldtgl_spd=$this->rka_model->get_nama($lcnospd,'tgl_spd','trhspd','no_spd');
        $stsubah=$this->rka_model->get_nama($lkd_skpd,'status_ubah','trhrka','kd_skpd');
        //$field = $this->get_status($ldtgl_spd,$lkd_skpd); 

        if($jns=='6'){
            $wherex ="and left(kd_rek6,1)='6'";
            $judulxx='PENGELUARAN PEMBIAYAAN';
        } else{
             $wherex ="and left(kd_rek6,1)='5'";
             $judulxx='BELANJA';
        }         
        
        $csxsql=$this->db->query("SELECT case when statu=1 and status_sempurna=1 and status_ubah=1 then 'nilai_ubah' 
                       when statu=1 and status_sempurna=1 and status_ubah=0 then 'nilai_sempurna' 
                       when statu=1 and status_sempurna=0 and status_ubah=0 then 'nilai'
                       else 'nilai' end as anggaran from trhrka")->row();
        $field = $csxsql->anggaran;               
        
        
        $csql = "SELECT (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd) AS no_dpa,
                (SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan IN(SELECT kd_subkegiatan FROM trdspd WHERE no_spd=a.no_spd)
                 AND left(kd_skpd,17) = left(a.kd_skpd,17) $wherex) jm_ang,
                (SELECT SUM(total_hasil) as total FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
                tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
                (select sum(nilai_final) as nilai from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
                FROM trhspd a WHERE a.no_spd = '$lcnospd'";
                        
        $hasil = $this->db->query($csql);
        $data1 = $hasil->row();
        $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
        $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
        $jnsspd = $data1->jns_beban;
        $lnsisa = $data1->jm_ang - $data1->jm_spdlalu - $data1->jm_spdini;
        $lkd_skpd =$data1->kd_skpd;
        $ljns_beban =$data1->jns_beban;
        
        $skpdd = substr($lkd_skpd,22);
        
        $selaku='';
        if ($nip_ppkd=='19700502 199003 1 005'){
            $selaku="SELAKU KUASA";
        } else {
            $selaku="SELAKU";
        }
        
        if ($ljns_beban=='6')
        {
            $nm_beban="PEMBIAYAAN";
            $satudig='6';
        } else if ($ljns_beban=='5')
        {
            $nm_beban="BELANJA";
            $satudig='5';           
        } else if ($ljns_beban=='5')
        {
            $nm_beban="BELANJA";
            $satudig='5';
        }else
            {
                $nm_beban="-";
            };
            
        $nospd_cetak= $lcnospd;
        $tahun=$this->tukd_model->get_sclient('thn_ang','sclient');
                
        if ($tnp_no=='1'){
        $con_dpn='903/';
        
        $con_blk_btl='/PEMBIAYAAN/BKD/'.$tahun;
        $con_blk_bl='/BELANJA/BKD/'.$tahun;              
    
            ($ljns_beban=='5') ?  $nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_bl;
            }   
        
            
        
        $cRet = '';


        $font = 12;
        $font1 = $font-1;
        

        $cRet .="<table style='border-collapse:collapse;font-weight:bold;font-family:Times New Roman; font-size:12 px;' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>               
                    <tr>
                        <td width='18%' align='left'>LAMPIRAN SPD NOMOR </td>
                        <td width='72%' align='left'>: $nospd_cetak</td>
                    </tr>
                    <tr>
                        <td colspan=2 width='100%' align='left'>$nm_beban<BR></td>
                    </tr>
                    <tr>
                        <td align='left'> PERIODE BULAN </td><td align='left'>: $periode1 s/d $periode2 $tahun</td>
                    </tr>
                    <tr>
                        <td align='left'>TAHUN ANGGARAN </td><td align='left'>: $lntahunang</td>
                    </tr>
                </table>";
        $cRet .="
           <table style='border-collapse:collapse;font-family:Times New Roman; font-size:$font px;' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>               
                <tr>
                    <td width='3%' align='center' style='font-weight:bold;'>No.<br>Urut        </td>
                    <td width='17%' align='center' style='font-weight:bold;'>Nomor DPA-/DPPA-SKPD        </td>
                    <td width='28%' align='center' style='font-weight:bold;'>URAIAN      </td>
                    <td width='11%' align='center' style='font-weight:bold;'>ANGGARAN(Rp.)       </td>
                    <td width='10%' align='center' style='font-weight:bold;'>AKUMULASI PADA SPD SEBELUMNYA (Rp.)    </td>
                    <td width='10%' align='center' style='font-weight:bold;'>JUMLAH PADA SPD PERIODE INI (Rp.)</td>
                    <td width='10%' align='center' style='font-weight:bold;'>JUMLAH DANA s/d SPD INI (Rp.)</td>
                    <td width='11%' align='center' style='font-weight:bold;'>SISA ANGGARAN (Rp.)</td>
                </tr>";
            
            $sql="      SELECT '0' no_urut, kd_skpd kode, (select nm_skpd from ms_skpd where kd_skpd=o.kd_skpd) uraian, sum(anggaran) anggaran, sum(spd_lalu) spd_lalu, sum(nilai) nilai from(
                        SELECT DISTINCT b.kd_skpd, rtrim(a.kd_subkegiatan)kode,c.nm_sub_kegiatan,
                        isnull((SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  inner join trskpd c on a.kd_subkegiatan=c.kd_sub_kegiatan
                        WHERE  a.no_spd = '$lcnospd') o GROUP BY kd_skpd
                        ";
            $isi=$this->db->query($sql)->row();

              $cRet .="<tr>
                            <td align='center'></td>
                            <td >$isi->kode</td>
                            <td >$isi->uraian</td>
                            <td align='right' >".number_format($isi->anggaran,"2",",",".")."&nbsp;</td>
                            <td align='right' >".number_format($isi->spd_lalu,"2",",",".")."&nbsp;</td>
                            <td align='right' >".number_format($isi->nilai,"2",",",".")."&nbsp;</td>
                            <td align='right' >".number_format($isi->spd_lalu+$isi->nilai,"2",",",".")."&nbsp;</td>
                            <td align='right' >".number_format($isi->anggaran - $isi->spd_lalu - $isi->nilai,"2",",",".")."&nbsp;</td>
                        </tr>";                                            
        
                $sql = " SELECT * from (

                        --sub kegiatan
                        ( SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_subkegiatan) uraian,
                        isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  
                                WHERE  a.no_spd = '$lcnospd')                                                                        
                        union all 

                        --kegiatan                               
                        select (ROW_NUMBER() OVER (ORDER BY left(kode,12)))no_urut, left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai) nilai from(
                        SELECT DISTINCT ('')no_urut, rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17) GROUP by left(kd_sub_kegiatan,12)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  inner join trskpd c on a.kd_subkegiatan=c.kd_sub_kegiatan
                        WHERE  a.no_spd = '$lcnospd' -- ORDER BY kode

                        ) kegiatan GROUP BY left(kode,12)

                        union all

                        --program
                        select ('')no_urut, left(kode,7) kode, (select nm_program from ms_program where kd_program=left(kode,7)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai) nilai from(
                        select  left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai) nilai from(
                        SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                        WHERE  a.no_spd = '$lcnospd') kegiatan GROUP BY left(kode,12)) program GROUP BY left(kode,7)

                        union all

                        --urusan
                        select '' urut, left(kode,4) kode, (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=left(kode,4)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai) nilai from(
                        select ('')no_urut, left(kode,7) kode, (select nm_program from ms_program where kd_program=left(kode,7)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai) nilai from(
                        select  left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai) nilai from(
                        SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  
                        WHERE  a.no_spd = '$lcnospd') kegiatan GROUP BY left(kode,12)) program GROUP BY left(kode,7)) urusan GROUP BY left(kode,4)

                                ) zt order by kode, no_urut"; 

   
                    
                    $hasil = $this->db->query($sql);
                    $lcno = 0;
                    $lntotal = 0;
                    $jtotal_spd = 0;
                    foreach ($hasil->result() as $row)
                    {
                       $lcno = $lcno + 1;
                       $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai;
                       $total_spd=$row->spd_lalu + $row->nilai;
                       if ($row->no_urut=='0') {
                        $lcno_urut='';
                       } else {
                           $lcno_urut=$row->no_urut;
                       };
                       $kode=$row->kode;
                       $lenkode = strlen($kode);
                       

                           if ($lenkode == 12){
                                $bold = 'font-weight:bold;';
                                $fontr = $font1;
                           }else{
                                $bold = '';
                                $fontr = $font;
                           }
 
                            if($lenkode==15){
                                $jtotal_spd = $jtotal_spd + $total_spd;
                            }

                        
                $cRet .="<tr>
                            <td align='center' style='$bold font-size:$fontr px'>$lcno_urut</td>
                            <td style='$bold font-size:$fontr px'>$kode</td>
                            <td style='$bold font-size:$fontr px'>$row->uraian</td>
                            <td align='right' style='$bold font-size:$fontr px'>".number_format($row->anggaran,"2",",",".")."&nbsp;</td>
                            <td align='right' style='$bold font-size:$fontr px'>".number_format($row->spd_lalu,"2",",",".")."&nbsp;</td>
                            <td align='right' style='$bold font-size:$fontr px'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                            <td align='right' style='$bold font-size:$fontr px'>".number_format($total_spd,"2",",",".")."&nbsp;</td>
                            <td align='right' style='$bold font-size:$fontr px'>".number_format($lcsisa,"2",",",".")."&nbsp;</td>
                        </tr>";    
                        
                    }
                $cRet .="<tr>
                            <td align='right'  colspan='3'>JUMLAH &nbsp;&nbsp;&nbsp;</td>
                            <td align='right' style='font-size:$font1 px'>".number_format($data1->jm_ang,"2",",",".")."&nbsp;</td>
                            <td align='right' style='font-size:$font1 px'>".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;</td>
                            <td align='right' style='font-size:$font1 px'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;</td>
                            <td align='right' style='font-size:$font1 px'>".number_format($jtotal_spd,"2",",",".")."&nbsp; </td>
                            <td align='right' style='font-size:$font1 px'>".number_format($lnsisa,"2",",",".")."&nbsp;</td>
                        </tr>";         

                $cRet .="</table>";
        

    
        $init_tgl = $this->tanggal_format_indonesia($ldtgl_spd);

            $cRet .=" <table style='border-collapse:collapse;font-weight: bold;font-family: arial; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>
        <tr>
                <td width='50%' align='right' colspan='2'>&nbsp;
                </td>               
                <td width='50%'  align='center'><br>Ditetapkan di Pontianak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </td>
            </tr>
        <tr >
                <td align='right' colspan='2'>&nbsp;
                </td>   
                <td  text-indent: 50px; align='center'>Pada tanggal : $init_tgl &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </td>
            </tr>   
        <tr >
                <td width='40%' align='right'>&nbsp;</td>
                <td width='60%'  align='center' colspan='2'>PEJABAT PENGELOLA KEUANGAN DAERAH<br>$selaku BENDAHARA UMUM DAERAH<BR>&nbsp;<br>&nbsp;<br>&nbsp;</td>
                </td>
            </tr>   
        <tr >
                <td align='right'>&nbsp;</td>
                <td  align='center' colspan='2'><u>$nama_ppkd</u></td>
                </td>
            </tr>
                <!--<tr >
                <td align='right'>&nbsp;</td>
                <td align='center' colspan='2'>$pangkat_ppkd</td>
                </td>
            </tr>-->
        <tr >
                <td  align='right'>&nbsp;</td>
                <td align='center' colspan='2'>NIP. $nip_ppkd</td>
                </td>
            </tr>           

        </table>";
            //echo $cRet;

        $data['prev']= $cRet;  

        $hasil->free_result();
        if ($print==1){
            $this->master_pdf->_mpdf('',$cRet,10,10,10,1,'','','',5);
        } else{
        echo $cRet;
        }
        
        
        
        
    }   

    function get_status2($skpd){
        $n_status = '';
        
        $sql = "SELECT case when statu='1' and status_sempurna='1' and status_ubah='1' then 'nilai_ubah' 
                    when statu='1' and status_sempurna='1' then 'nilai_sempurna' 
                    when statu='1' 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd'";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;   
        //$n_status;                      
    }   

    function cetak_otor_spd(){
        
        $print = $this->uri->segment(3);
        $tnp_no = $this->uri->segment(4);
        $jn_keg = $this->uri->segment(7);
        $tambah = $this->uri->segment(5) == '0' ? '' : $this->uri->segment(5);
        $lcnospd = $this->input->post('nomor1');                
        $nip_ppkd = $this->input->post('nip_ppkd');  
        $nama_ppkd = $this->input->post('nama_ppkd');       
        $jabatan_ppkd = $this->input->post('jabatan_ppkd'); 
        $pangkat_ppkd = $this->input->post('pangkat_ppkd');         
        $csql2 = "SELECT nm_skpd,kd_skpd,tgl_spd,total_hasil as total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar from trhspd where no_spd = '$lcnospd'  ";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;
        $ldtgl_spd2 = $trh1->tgl_spd;
        $jmlspdini = number_format(($trh1->total),2,',','.');
        if($trh1->total==0){
        $biljmlini = 'nol Rupiah';
            }else{
            $biljmlini = $this->tukd_model->terbilang(($trh1->total));     
            }
        $lckdskpd = $trh1->kd_skpd;
        $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
        $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
        $lcnmskpd = $trh1->nm_skpd;
        $ljns_beban =$trh1->jns_beban;
        $lcnipbk = $trh1->kd_bkeluar;
        
        if ($lcnipbk<>''){         
            $sqlttd1="SELECT nama as nm FROM ms_ttd WHERE id_ttd='$lcnipbk' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                }
        }
        else{
                    $nama1= '';
        }
 
        
        $nospd_cetak=$lcnospd;
        if ($tnp_no=='1'){
        $con_dpn='903/';
        $tahun=$this->session->userdata('pcThang');
        $con_blk_btl='/PEMBIAYAAN/BKD/'.$tahun;
        $con_blk_bl='/BELANJA/BKD/'.$tahun;     

    
            ($ljns_beban=='6') ?  $nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_bl;
            }   
        
        // jumlah anggaran
        $n_status = $this->get_status2($lckdskpd);
       /* if($jn_keg=='6'){
        $csql1 = "SELECT SUM($n_status) AS jumlah FROM trdrka WHERE kd_sub_kegiatan IN 
                  (SELECT kd_subkegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_rek6,1)='6'";
        }else{
            $csql1 = "SELECT SUM($n_status) AS jumlah FROM trdrka WHERE kd_sub_kegiatan IN 
                  (SELECT kd_subkegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_rek6,1)='5'";
        }*/                  
        
        $csql1 = "SELECT SUM($n_status) AS jumlah FROM trdrka WHERE kd_sub_kegiatan IN 
                  (SELECT kd_subkegiatan FROM trdspd WHERE no_spd = '$lcnospd') ";

        $hasil1 = $this->db->query($csql1);
        $trh2 = $hasil1->row();
        $jmldpa = number_format(ceil($trh2->jumlah),2,',','.');
        
        
        //spd lalu
        $sql = "SELECT sum(total_hasil) as jm_spd_l from trhspd where no_spd<>'$lcnospd' 
                and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban'";
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();
        $jmlspdlalu = number_format($trh->jm_spd_l,2,',','.');
        
        $csql = "SELECT thn_ang,provinsi,kab_kota,daerah from sclient";
        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $jmlsisa = number_format(($trh2->jumlah - $trh->jm_spd_l),2,',','.');;
        $jmlsisa2 = number_format(($trh2->jumlah - ($trh->jm_spd_l + $trh1->total)),2,',','.');
        $jmlsisa3 = $trh2->jumlah - ($trh->jm_spd_l + $trh1->total);
        $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

            $njns='';
        if($ljns_beban=='6'){
            $njns = 'Pembiayaan';
        }else {
            $njns = 'Belanja';
        }
        
        $xx = 'Bahwa untuk melaksanakan Anggaran '.$njns.' Tahun Anggaran '.$trh3->thn_ang.' berdasarkan Anggaran Kas yang telah
                ditetapkan, perlu disediakan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
            
        $xx2 = '1. Peraturan Daerah Kota Pontianak Nomor. 9 Tahun 2016 tentang APBD Kota Pontianak Tahun Anggaran '.$trh3->thn_ang.'.';
        $xx3 = '2. Peraturan Walikota Pontianak Nomor. 95 Tahun 2016 tentang Penjabaran APBD Kota Pontianak Tahun Anggaran '.$trh3->thn_ang.'.';
        $xx4 = '3. DPA-SKPD '.$lcnmskpd.' Kota Pontianak (Daftar nomor terlampir)';   
        $cRet = '';
        $cRet .="
        
        <table style='border-collapse:collapse;font-weight: bold;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='2'>
            <tr>
                <td style='font-size:14px;' align='center'>PEMERINTAH KOTA PONTIANAK <br> </td>
            <tr>
            <tr>
                <td align='center'>PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH <br> </td>
            <tr>
            <td align='center'>NOMOR : $nospd_cetak  <br></td></tr>
             <tr>
            <td align='center'>TENTANG</td></tr>
             <tr>
            <td align='center'>SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH TAHUN ANGGARAN $trh3->thn_ang</td></tr>
            <tr>
            <td align='center'>PPKD SELAKU BENDAHARA UMUM DAERAH</td></tr>
        </table>";
        
        

        $font=12;
 
        $cRet .="<br/><table style='border-collapse:collapse; font-size:$font px' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>
                <tr>
                    <td width='3%' align='right' valign='top'>&nbsp;</td>
                    <td width='13%' align='left' valign='top' ><strong>Menimbang</strong></td>
                    <td width='5%' align='right' valign='top'>:</td>
                    <td width='70%' align='justify' colspan='2' rowspan='2' valign='top' >$xx</td>
                </tr>               
                <tr>
                    <td align='right' valign='top'>&nbsp;</td>
                    <td align='left' valign='top' >&nbsp;</td>
                    <td align='right' valign='top'>&nbsp;</td>
                </tr>
                <tr>
                    <td width='3%' align='right' valign='top'>&nbsp;</td>
                    <td width='13%' align='left' valign='top' ><strong>Mengingat</strong></td>
                    <td width='5%' align='right' valign='top'>:</td>
                    <td width='70%' align='justify' colspan='2' valign='top' >$xx2</td>
                </tr>
                
                <tr>
                    <td width='3%' align='right' valign='top'>&nbsp;</td>
                    <td width='13%' align='left' valign='top' ><strong></strong></td>
                    <td width='5%' align='right' valign='top'></td>
                    <td width='70%' align='justify' colspan='2' valign='top' >$xx3</td>
                </tr>
                
                <tr>
                    <td width='3%' align='right' valign='top'>&nbsp;</td>
                    <td width='13%' align='left' valign='top' ><strong></strong></td>
                    <td width='5%' align='right' valign='top'></td>
                    <td width='79%' align='justify' colspan='2'  valign='top' >$xx4 </td>
                </tr>
                

        ";
        
        
        
        $selaku='';
        if ($nip_ppkd=='19700502 199003 1 005'){
            $selaku="SELAKU KUASA";
        } else {
            $selaku="SELAKU";
        }
        $kolom1 = '';

            

        $cRet .="</table>";
        
        $cRet .="        
        <table style='border-collapse:collapse;font-family: arial; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
        
            <tr>
                <td colspan='7' align='center' valign='top' width='100%'  style='font-size:12px'> 
                    <strong>M E M U T U S K A N :<strong>&nbsp;
                </td>
            </tr>
            <tr>
                <td width='10%'  style='font-size:12px' align='right'>&nbsp;
                </td>
                <td colspan='6' align='left' valign='top' width='90%'  style='font-size:12px'></td>
            </tr>
            <tr>
                <td width='10%'  style='font-size:12px' align='right'>&nbsp;</td>
                <td width='3%'   style='font-size:12px'>1.</td>
                <td width='35%'  style='font-size:12px'>Ditujukan kepada SKPD</td>
                <td  width='2%' style='font-size:12px'>:</td>
                <td  width='50%' colspan='3'   style='font-size:12px'>$lckdskpd - $lcnmskpd</td>
            </tr>
            <tr>
                <td style='font-size:12px' align='right'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>2.</td>
                <td style='font-size:12px' valign='top'>Bendahara Pengeluaran / Pengeluaran Pembantu </td>
                <td  style='font-size:12px' valign='top'>:</td>
                <td  colspan='3' style='font-size:12px' valign='top'>$nama1</td>
            </tr>
            <tr>
                <td rowspan='2'  style='font-size:12px' valign='top' align='right'>&nbsp;</td>
                <td rowspan='2' style='font-size:12px' valign='top'>3.</td>
                <td rowspan='2' style='font-size:12px' valign='top'>Jumlah Penyediaan dana</td>
                <td rowspan='2' style='font-size:12px' valign='top'>:</td>
                <td width='4%' style='font-size:12px'>Rp. <br></td>
                <td width='20%' align='right' style='font-size:12px'>  $jmlspdini</td>
                <td width='26%'></td>
            </tr>
            <tr>
                <td  colspan='3' style='font-size:12px'><i>(terbilang: $biljmlini)</i></td>
            </tr>
            <tr>
                <td  style='font-size:12px' align='right'>&nbsp;</td>
                <td style='font-size:12px'>4.</td>
                <td style='font-size:12px'>Untuk Kebutuhan / Jenis Beban</td>
                <td  style='font-size:12px'>:</td>
                <td  colspan='3'   style='font-size:12px'>$tambah Bulan $blnini s.d Bulan $blnsd $trh3->thn_ang / $njns</td>
            </tr>
            <tr>
                <td style='font-size:12px' align='right'>&nbsp;</td>
                <td style='font-size:12px'>5.</td>
                <td style='font-size:12px'><u><strong>IKHTISAR PENYEDIAAN DANA : </strong></u></td>
                <td  style='font-size:12px'></td>
                <td  colspan='3'   style='font-size:12px'></td>
            </tr>
            <tr>
                <td style='font-size:12px'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>a. Jumlah dana DPA-SKPD/DPPA-SKPD/DPAL</td>
                <td style='font-size:12px' valign='top'>:</td>
                <td style='font-size:12px'>Rp. <br></td>
                <td align='right' style='font-size:12px'>  $jmldpa</td>
                <td ></td>
                
            </tr>
            <tr>
                <td  style='font-size:12px'>&nbsp;</td>
                <td  style='font-size:12px' valign='top'>&nbsp;</td>
                <td  style='font-size:12px;' valign='top'>b. Akumulasi SPD sebelumnya</td>
                <td  style='font-size:12px' valign='top'>:</td>
                <td style='font-size:12px;border-bottom: solid 1px black;'>Rp. <br></td>
                <td align='right' style='font-size:12px;border-bottom: solid 1px black;'>  $jmlspdlalu</td>
                <td ></td>

                </tr>
            <tr>
                <td style='font-size:12px'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>c. Sisa dana yang belum di-SPD-kan</td>
                <td  style='font-size:12px' valign='top'>:</td>
                <td style='font-size:12px'>Rp. <br></td>
                <td align='right' style='font-size:12px'>  $jmlsisa</td>
                <td ></td>
            </tr>
           <tr>
                <td style='font-size:12px'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>&nbsp;</td>
                <td style='font-size:12px' valign='top'>d. Jumlah dana yang di-SPD-kan saat ini</td>
                <td style='font-size:12px;' valign='top'>:</td>
                <td style='font-size:12px;border-bottom: solid 1px black;'>Rp. <br></td>
                <td align='right' style='font-size:12px;border-bottom: solid 1px black;'>  $jmlspdini</td>
                <td ></td>

            </tr>
            <tr>
                <td rowspan='2'  style='font-size:12px'>&nbsp;</td>
                <td rowspan='2'  style='font-size:12px' valign='top'>&nbsp;</td>
                <td rowspan='2'  style='font-size:12px' valign='top'>e. Sisa jumlah dana DPA yang belum di-SPD-kan</td>
                <td rowspan='2'  style='font-size:12px' valign='top'>:</td>
                <td style='font-size:12px;border-bottom: solid 2px black;'>Rp. <br></td>
                <td align='right' style='font-size:12px;border-bottom: solid 2px black;'>  $jmlsisa2 <br></td>
                <td ></td>
 
            </tr>
            <tr>
                <td  colspan='3' style='font-size:12px'><i>(terbilang: $bilsisa)</i></td>
            </tr>
            <tr> 
                <td style='font-size:12px'>&nbsp;</td>
                <td style='font-size:12px' align='right' valign='top'>6.</td>
                <td style='font-size:12px' valign='top'>Ketentuan-ketentuan lain</td>
                <td style='font-size:12px' valign='top'>:</td>
                <td  colspan='3' align='justify' style='font-size:12px'>Terhadap cara memperoleh, menggunakan dan mempertanggung- jawabkan
uang yang dimaksud tetap berpedoman pada Peraturan Perundang-Undangan
yang berlaku
                </td>
            </tr>           
            </table>";
             // CETAKAN TANDA TANGAN by Tox
             
             $init_tgl = $this->tanggal_format_indonesia($ldtgl_spd);
             $init_tgl2 = $this->tanggal_format_indonesia($ldtgl_spd2);
             
            $cRet .="
    <table style='border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='2'>
        <tr>
            <td width='50%' align='right' colspan='2'>&nbsp;</td>               
            <td width='50%'  align='left'><br>Ditetapkan di Pontianak</td>
        </tr>
        <tr>
            <td align='right' colspan='2'>&nbsp;</td>   
            <td  text-indent: 50px; align='left'><u>Pada tanggal : $init_tgl2 &nbsp;<u></td>
        </tr>   
        <tr>
            <td width='40%' align='right'>&nbsp;</td>
            <td width='60%'  align='center' colspan='2'>PEJABAT PENGELOLA KEUANGAN DAERAH<br>$selaku BENDAHARA UMUM DAERAH<BR>&nbsp;<br>&nbsp;<br>&nbsp;</td>
        </tr>   
        <tr>
            <td align='right'>&nbsp;</td>
            <td  align='center' colspan='2'><u>$nama_ppkd</u></td>
        </tr>
        <tr >
            <td  align='right'>&nbsp;</td>
            <td align='center' colspan='2'>NIP. $nip_ppkd</td>
        </tr>           
        </table>";
        $data['prev']= $cRet;
        
        
        if ($print==1){
            // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
            $this->master_pdf->_mpdf('',$cRet,10,10,10,'0','no','','',30);

        } else{
          echo $cRet;
        }

}

function cetak_lampiran_spd1_refisi(){       
        $print = $this->uri->segment(3);
        $tnp_no = $this->uri->segment(4);
        $cell =  $this->uri->segment(6);
        $jbeban =  $this->uri->segment(7);
        $ttp = $this->input->post('tglttp');
        $nip_ppkd = $this->input->post('nip_ppkd');  
        $nama_ppkd = $this->input->post('nama_ppkd');       
        $jabatan_ppkd = $this->input->post('jabatan_ppkd'); 
        $pangkat_ppkd = $this->input->post('pangkat_ppkd');     
        $lntahunang = $this->session->userdata('pcThang');       
        $lcnospd = $this->input->post('nomor1');
        $lkd_skpd=$this->rka_model->get_nama($lcnospd,'kd_skpd','trhspd','no_spd');
        $ldtgl_spd=$this->rka_model->get_nama($lcnospd,'tgl_refisi1','trhspd','no_spd');
        $stsubah=$this->rka_model->get_nama($lkd_skpd,'status_ubah','trhrka','kd_skpd');
        $field = $this->anggaran_spd_model->get_status2($lkd_skpd);           
        
        
        if($nip_ppkd == '19611019 198412 1 002')           
        {
            $alias="SELAKU BENDAHARA UMUM DAERAH";
        }else{
            $alias="SELAKU KUASA BENDAHARA UMUM DAERAH";
        }
        
        if($jbeban=='6' || $jbeban=='6'){
            $wherebbn = "and left(kd_rek5,1)='6'";
        }else{
            $wherebbn = '';
        }
        
        $csql = "SELECT (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd) AS no_dpa,
                (SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan IN(SELECT kd_subkegiatan FROM trdspd WHERE no_spd=a.no_spd)
                 AND left(kd_skpd,17) = left(a.kd_skpd,17) $wherebbn) jm_ang,
                (SELECT SUM(total_hasil) FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
                tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
                (select sum(nilai_final) from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,
                (select sum(nilai_refisi1) from trdspd f where f.no_spd=a.no_spd) AS jm_refisi,
                (select refisi from trhspd g where g.no_spd=a.no_spd) AS refisike,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
                FROM trhspd a WHERE a.no_spd = '$lcnospd'";
                        
        $hasil = $this->db->query($csql);
        $data1 = $hasil->row();
        $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
        $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
        $jnsspd = $data1->jns_beban;
        $lnsisa = $data1->jm_ang - $data1->jm_spdlalu - $data1->jm_refisi;
        $lkd_skpd =$data1->kd_skpd;
        $ljns_beban =$data1->jns_beban;
        $refisike = $data1->refisike;
        
        $skpdd = substr($lkd_skpd,0,17);
        

        if ($ljns_beban=='6' || $ljns_beban=='6')
        {   
            $kd_bbx='6';
            $nm_bbx="PEMBIAYAAN";
            $nm_beban="PEMBIAYAAN";
            $wherexx='';
        }else if ($ljns_beban=='5')
        {
            $kd_bbx='5';
            $nm_bbx="BELANJA ";
            $nm_beban="BELANJA ";
            $wherexx='';
        }else
            {
                $kd_bbx='-';
                 $nm_bbx="-";
                $nm_beban="-";
                $wherexx='';
            };
            
        $nospd_cetak= $lcnospd;
        $tahun=$this->tukd_model->get_sclient('thn_ang','sclient');
                
        if ($tnp_no=='1'){
        $con_dpn='903/';
        
            //$tahun=$this->session->userdata('pcThang');
        $con_blk_btl='/PEMBIAYAAN/BKD/'.$tahun;
        $con_blk_bl='/BELANJA/BKD/'.$tahun;              
    
            ($ljns_beban=='6' || $ljns_beban=='6') ?  $nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_bl;
            }   
        
            
        
        $cRet = '';
        $font = 12;
        $font1 = $font-1;
        

        $cRet .="<table style='border-collapse:collapse;font-family:Times New Roman; font-size:$font px;' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>               
                    <tr>
                        <td width='18%' align='left'>LAMPIRAN SPD NOMOR </td>
                        <td width='72%' align='left'>: $nospd_cetak (REVISI KE-$refisike)</td>
                    </tr>
                    <tr><td colspan=2 width='100%' align='left'>$nm_beban<BR></td></tr>
                    <tr><td align='left'> PERIODE BULAN </td><td align='left'>: $periode1 s/d $periode2 $tahun</td></tr>
                    <tr><td align='left'>TAHUN ANGGARAN </td><td align='left'>: $lntahunang</td></tr>
                </table>";
        $cRet .="
           <table style='border-collapse:collapse;font-family:Times New Roman; font-size:$font px;' width='100%' align='center' border='1' cellspacing='5' cellpadding='5'>               
                <tr>
                    <td width='3%' align='center' style='font-weight:bold;'>No.<br>Urut        
                    </td>
                    <td width='17%' align='center' style='font-weight:bold;'>Nomor DPA-/DPPA-SKPD        
                    </td>
                    <td width='28%' align='center' style='font-weight:bold;'>URAIAN      
                    </td>
                    <td width='11%' align='center' style='font-weight:bold;'>ANGGARAN(Rp.)       
                    </td>
                    <td width='10%' align='center' style='font-weight:bold;'>AKUMULASI PADA SPD SEBELUMNYA (Rp.)    
                    </td>
                    <td width='10%' align='center' style='font-weight:bold;'>JUMLAH PADA SPD AWAL (Rp.)
                    </td>
                    <td width='10%' align='center' style='font-weight:bold;'>JUMLAH PADA SPD REFISI (Rp.)
                    </td>
                    <td width='10%' align='center' style='font-weight:bold;'>JUMLAH DANA s/d SPD INI (Rp.)
                    </td>
                    <td width='11%' align='center' style='font-weight:bold;'>SISA ANGGARAN (Rp.)
                    </td>
                </tr>";
            
            
               $sql = "select kd_bidang_urusan kd_urusan, nm_bidang_urusan nm_urusan from ms_bidang_urusan where kd_bidang_urusan=left(rtrim('$lkd_skpd'),4)";
        $hasil = $this->db->query($sql);
        $tox = $hasil->row();
        $total_spd = $data1->jm_refisi;
        $kd_urusan = $tox->kd_urusan;
        
              $cRet .="<tr>
                                    <td>&nbsp;</td>
                                    <td style='font-size:$font1 px;font-weight:bold;'> $kd_urusan       
                                    </td>
                                    <td style='font-weight:bold;'> $tox->nm_urusan       
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_ang,"2",",",".")."&nbsp;           
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;        
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_refisi,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($total_spd,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($lnsisa,"2",",",".")."&nbsp;
                                    </td>
                                </tr>";    
            
             $sql = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='$lkd_skpd'";
        $hasil = $this->db->query($sql);
        $tox = $hasil->row();
        $kd_skpd = $tox->kd_skpd;
        $djns_beban = $this->support->dotrek($ljns_beban);
              $cRet .="<tr>
                                    <td>&nbsp;       
                                    </td>
                                    <td style='font-size:$font1 px;font-weight:bold;'> $kd_urusan.$kd_skpd      
                                    </td>
                                    <td style='font-weight:bold;'> $tox->nm_skpd       
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_ang,"2",",",".")."&nbsp;           
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;        
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_refisi,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($total_spd,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($lnsisa,"2",",",".")."&nbsp;
                                    </td>
                                </tr>";

              $cRet .="<tr>
                                    <td>&nbsp;       
                                    </td>
                                    <td style='font-size:$font1 px;font-weight:bold;'> $kd_urusan.$kd_skpd.$kd_bbx  
                                    </td>
                                    <td style='font-weight:bold;'> $nm_bbx   
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_ang,"2",",",".")."&nbsp;           
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;        
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($data1->jm_refisi,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($total_spd,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-size:$font1 px;font-weight:bold;'>".number_format($lnsisa,"2",",",".")."&nbsp;
                                    </td>
                                </tr>";   
                               
        //if ($ljns_beban=='52'){           
                $sql = " SELECT * from (

                       
                        ( SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_subkegiatan) uraian,
                        isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        isnull((SELECT SUM(nilai_refisi1) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS nilai_refisi,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  
                                WHERE  a.no_spd = '$lcnospd')                                                                        
                        union all 

                                                    
                        select (ROW_NUMBER() OVER (ORDER BY left(kode,12)))no_urut, left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai_refisi) nilai_refisi,sum(nilai) nilai from(
                        SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai_refisi1 AS nilai_refisi,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  
                        WHERE  a.no_spd = '$lcnospd') kegiatan GROUP BY left(kode,12)

                        union all

                       
                        select ('')no_urut, left(kode,7) kode, (select nm_program from ms_program where kd_program=left(kode,7)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai_refisi) nilai_refisi, sum(nilai) nilai from(
                        select  left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai_refisi) nilai_refisi, sum(nilai) nilai from(
                        SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai_refisi1 AS nilai_refisi,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  inner join trskpd c on a.kd_subkegiatan=c.kd_sub_kegiatan
                        WHERE  a.no_spd = '$lcnospd') kegiatan GROUP BY left(kode,12)) program GROUP BY left(kode,7)

                        union all

                      
                        select '' urut, left(kode,4) kode, (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=left(kode,4)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai_refisi) nilai_refisi, sum(nilai) nilai from(
                        select ('')no_urut, left(kode,7) kode, (select nm_program from ms_program where kd_program=left(kode,7)) uraian, sum(anggaran) anggaran, sum(lalu) lalu, sum(nilai_refisi) nilai_refisi, sum(nilai) nilai from(
                        select  left(kode,12) kode, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(kode,12)) uraian, sum(anggaran) anggaran, sum(spd_lalu) lalu, sum(nilai_refisi) nilai_refisi, sum(nilai) nilai from(
                        SELECT ('')no_urut,rtrim(a.kd_subkegiatan)kode,
                        isnull((SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = a.kd_subkegiatan AND left(kd_skpd,17)=left(b.kd_skpd,17)),0) AS anggaran,
                        isnull((SELECT SUM(nilai_final) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd
                        WHERE c.kd_kegiatan = a.kd_kegiatan AND left(d.kd_skpd,17)=left(b.kd_skpd,17) AND c.no_spd <> a.no_spd 
                        AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                        a.nilai_refisi1 AS nilai_refisi,
                        a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd  inner join trskpd c on a.kd_subkegiatan=c.kd_sub_kegiatan
                        WHERE  a.no_spd = '$lcnospd') kegiatan GROUP BY left(kode,12)) program GROUP BY left(kode,7)) urusan GROUP BY left(kode,4)

                                ) zt order by kode, no_urut";


                    $hasil = $this->db->query($sql);
                    $lcno = 0;
                    $lntotal = 0;
                    $jtotal_spd = 0;
                    foreach ($hasil->result() as $row)
                    {
                       $lcno = $lcno + 1;
                       //$lntotal = $lntotal + $row->nilai;
                       $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai_refisi;
                       $total_spd=$row->nilai_refisi;
                       //echo $row->no_dpa;
                       if ($row->no_urut=='0') {
                        $lcno_urut='';
                       } else {
                           $lcno_urut=$row->no_urut;
                       };
                       $kode=$row->kode;
                       $lenkode = strlen($kode);
                       
                
                           if ($lenkode <= 18){
                                $bold = 'font-weight:bold;';
                                $fontr = $font1;
                           }else{
                                $bold = '';
                                $fontr = $font;
                           }
 
                            if($lenkode==18){
                                $jtotal_spd = $jtotal_spd + $total_spd;
                            }
           
                        
                       $cRet .="<tr>
                                    <td align='center' style='$bold font-size:$fontr px'>$lcno_urut    
                                    </td>
                                    <td style='$bold font-size:$fontr px'>$kode 
                                    
                                    </td>
                                    <td style='$bold font-size:$fontr px'>$row->uraian       
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($row->anggaran,"2",",",".")."&nbsp;     
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($row->spd_lalu,"2",",",".")."&nbsp;    
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($row->nilai,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($row->nilai_refisi,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($total_spd,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='$bold font-size:$fontr px'>".number_format($lcsisa,"2",",",".")."&nbsp;
                                    </td>
                                </tr>";    
                        
                    }
                 //perbaiki $total_spd <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                 $cRet .="<tr>
                                    <td align='right' style='font-weight:bold;' colspan=3>JUMLAH &nbsp;&nbsp;&nbsp;       
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($data1->jm_ang,"2",",",".")."&nbsp;           
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;        
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($data1->jm_refisi,"2",",",".")."&nbsp;
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($jtotal_spd,"2",",",".")."&nbsp; 
                                    </td>
                                    <td align='right' style='font-weight:bold;font-size:$font1 px'>".number_format($lnsisa,"2",",",".")."&nbsp;
                                    </td>
                                </tr>";         

                $cRet .="</table>";
          

    
        $init_tgl = $this->support->tanggal_format_indonesia($ldtgl_spd);

            $cRet .=" <table style='border-collapse:collapse;font-weight: bold;font-family: arial; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='1'>
        <tr>
                <td width='50%' align='right' colspan='2'>&nbsp;
                </td>               
                <td width='50%'  align='center'><br>Ditetapkan di Pontianak &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </td>
            </tr>
        <tr >
                <td align='right' colspan='2'>&nbsp;
                </td>   
                <td  text-indent: 50px; align='center'>Pada tanggal : $init_tgl &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </td>
            </tr>   
        <tr >
                <td width='40%' align='right'>&nbsp;</td>
                <td width='60%'  align='center' colspan='2'>PEJABAT PENGELOLA KEUANGAN DAERAH<br>$alias<BR>&nbsp;<br>&nbsp;<br>&nbsp;</td>
                </td>
            </tr>   
        <tr >
                <td align='right'>&nbsp;</td>
                <td  align='center' colspan='2'><u>$nama_ppkd</u></td>
                </td>
            </tr>
                <!--<tr >
                <td align='right'>&nbsp;</td>
                <td align='center' colspan='2'>$pangkat_ppkd</td>
                </td>
            </tr>-->
        <tr >
                <td  align='right'>&nbsp;</td>
                <td align='center' colspan='2'>NIP. $nip_ppkd</td>
                </td>
            </tr>           

        </table>";
            //echo $cRet;
        $cRet .="<table style='border-collapse:collapse;font-family: Times New Roman; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='4'>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$daerah, $tanggal</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'>$jabatan</td></tr>
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                    <tr><td align='center' width='25%'></td>                    
                    <td align='center' width='25%'><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align='center' width='25%'>&nbsp;</td>                    
                    <td align='center' width='25%'>&nbsp;</td></tr>
                  </table>";
        $data['prev']= $cRet; 
        //echo $cRet; 
        //echo $data['prev'];  
//        $this->rka_model->_mpdf('',$cRet,'10','10',5,'1');
        $hasil->free_result();
        if ($print==1){
          //  $this->_mpdf('',$cRet,10,10,10,1,'','','',5);
        $this->master_pdf->_mpdf('',$cRet,10,10,10,'1'); 
        } else{
          echo $cRet;
        }
         
    } 


    function cetak_otor_spd_refisi(){
        
        $print = $this->uri->segment(3);
        $tnp_no = $this->uri->segment(4);
        $jbbn = $this->uri->segment(7);
        $tambah = $this->uri->segment(5) == '0' ? '' : $this->uri->segment(5);
        $lcnospd = $this->input->post('nomor1');                
        $nip_ppkd = $this->input->post('nip_ppkd');  
        $nama_ppkd = $this->input->post('nama_ppkd');       
        $jabatan_ppkd = $this->input->post('jabatan_ppkd'); 
        $pangkat_ppkd = $this->input->post('pangkat_ppkd');         
        $csql2 = "SELECT nm_skpd,kd_skpd,tgl_spd,total_hasil as total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,refisi,tgl_refisi1 from trhspd where no_spd = '$lcnospd'  ";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;
        $jmlspdini = number_format(($trh1->total),2,',','.');//number_format(ceil($trh1->total),2,',','.');;
        $biljmlini = $this->tukd_model->terbilang(($trh1->total));
        $lckdskpd = $trh1->kd_skpd;
        $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
        $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
        $lcnmskpd = $trh1->nm_skpd;
        $ljns_beban =$trh1->jns_beban;
        $refisi =$trh1->refisi;
        $lcnipbk = $trh1->kd_bkeluar;
        $tglref = $trh1->tgl_refisi1;
        
        if($nip_ppkd == '19611019 198412 1 002')          
        {
            $alias="SELAKU BENDAHARA UMUM DAERAH";
        }else{
            $alias="SELAKU KUASA BENDAHARA UMUM DAERAH";
        }
        
        
        
        if ($lcnipbk<>''){         
            $sqlttd1="SELECT nama as nm FROM ms_ttd WHERE id_ttd='$lcnipbk' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                }
        }
        else{
                    $nama1= '';
        }
 
        $refisi1 = "Revisi ke-".$refisi;
        
        $nospd_cetak=$lcnospd;
        if ($tnp_no=='1'){
        $con_dpn='903/';
        $tahun=$this->session->userdata('pcThang');
        $con_blk_btl='/PEMBIAYAAN/BKD/'.$tahun;
        $con_blk_bl='/BELANJA/BKD/'.$tahun;     

        
        $kolom1 = '';
        
            ($ljns_beban=='6' || $ljns_beban=='6') ?  $nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&emsp;&emsp;&emsp;".$con_blk_bl;
            }   
        if($jbbn=='5'){
          $wherebbn="and left(kd_rek5,1)='5'";
        }else{
          $wherebbn='';
        }
        
        // jumlah anggaran
        $n_status = $this->get_status2($lckdskpd);
        $csql1 = "SELECT SUM($n_status) AS jumlah FROM trdrka WHERE kd_sub_kegiatan IN 
                  (SELECT kd_subkegiatan FROM trdspd WHERE no_spd = '$lcnospd') $wherebbn";
                  
                  
        $hasil1 = $this->db->query($csql1);
        $trh2 = $hasil1->row();
        $jmldpa = number_format($trh2->jumlah,2,',','.');
        
        
        //spd lalu
        $sql = "SELECT sum(total_hasil) as jm_spd_l from trhspd where no_spd<>'$lcnospd' 
                and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban'";
        $hasil = $this->db->query($sql);
        $trh = $hasil->row();
        $jmlspdlalu = number_format(ceil($trh->jm_spd_l),2,',','.');
        
        
        
        $csql = "SELECT thn_ang,provinsi,kab_kota,daerah from sclient";
        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
       $jmlsisa = number_format(($trh2->jumlah - $trh->jm_spd_l),2,',','.');
      
        $jmlsisa2 = number_format(($trh2->jumlah - ($trh->jm_spd_l + $trh1->total)),2,',','.'); //number_format(ceil($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.');
        $jmlsisa3 = ($trh2->jumlah - $trh->jm_spd_l) - $trh1->total;
        if($jmlsisa2=='0,00'){
        $bilsisa = 'nol rupiah';
    }else{
        $bilsisa = $this->tukd_model->terbilang($jmlsisa3);
    }
      
        if($ljns_beban=='6' || $ljns_beban=='6'){
            $njns = 'Pembiayaan';
        }else if ($ljns_beban=='5'){
            $njns = 'Belanja';
        }else{
          $njns = '-';
        }
        
        $xx = 'Bahwa untuk melaksanakan Anggaran '.$njns.' Tahun Anggaran '.$trh3->thn_ang.' berdasarkan Anggaran Kas yang telah
                ditetapkan, perlu disediakan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
            
        $xx2 = '1. Peraturan Daerah Kota Pontianak Nomor. 9 Tahun 2016 tentang APBD Kota Pontianak Tahun Anggaran '.$trh3->thn_ang.'.';
        $xx3 = '2. Peraturan Walikota Pontianak Nomor. 95 Tahun 2016 tentang Penjabaran APBD Kota Pontianak Tahun Anggaran '.$trh3->thn_ang.'.';
        $xx4 = '3. DPA-SKPD '.$lcnmskpd.' Kota Pontianak (Daftar nomor terlampir)';
            
        
        $cRet = '';

//              style=\"font-size:12px\"
//  <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">

        $cRet .="
        
        <table style=\"border-collapse:collapse;font-weight: bold;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
            <tr>
                <td style=\"font-size:14px;\" align=\"center\">PEMERINTAH KOTA PONTIANAK <br> </td>
            <tr>
            <tr>
                <td align=\"center\">PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH <br> </td>
            <tr>
            <td align=\"center\">NOMOR : $nospd_cetak (".strtoupper($refisi1).")<br></td></tr>
             <tr>
            <td align=\"center\">TENTANG</td></tr>
             <tr>
            <td align=\"center\">SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH TAHUN ANGGARAN $trh3->thn_ang</td></tr>
            <tr>
            <td align=\"center\">PPKD SELAKU BENDAHARA UMUM DAERAH</td></tr>
        </table>";
        
        
       /* $sql = "otori_spd '$ldtgl_spd'";
        $hasil = $this->db->query($sql);
        $num_row = $hasil->num_rows();
        $font=10;
        if($num_row>10){
            $font = $font-2;
        }*/
        $font="12";
        $cRet .="<br/><table style=\"border-collapse:collapse;font-family: Times New Roman;; font-size:$font px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                <tr>
                    <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                    <td width=\"13%\" align=\"left\" valign=\"top\" ><strong>Menimbang </strong></td>
                    <td width=\"5%\" align=\"right\" valign=\"top\">:</td>
                    <td width=\"70%\" align=\"justify\" colspan=\"2\" rowspan=\"2\" valign=\"top\" >$xx</td>
                </tr>               
                <tr>
                    <td align=\"right\" valign=\"top\">&nbsp;</td>
                    <td align=\"left\" valign=\"top\" >&nbsp;</td>
                    <td align=\"right\" valign=\"top\">&nbsp;</td>
                </tr>
                <tr>
                    <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                    <td width=\"13%\" align=\"left\" valign=\"top\" ><strong>Mengingat</strong></td>
                    <td width=\"5%\" align=\"right\" valign=\"top\">:</td>
                    <td width=\"70%\" align=\"justify\" colspan=\"2\" valign=\"top\" >$xx2</td>
                </tr>
                
                <tr>
                    <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                    <td width=\"13%\" align=\"left\" valign=\"top\" ><strong></strong></td>
                    <td width=\"5%\" align=\"right\" valign=\"top\"></td>
                    <td width=\"70%\" align=\"justify\" colspan=\"2\" valign=\"top\" >$xx3</td>
                </tr>
                
                <tr>
                    <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                    <td width=\"13%\" align=\"left\" valign=\"top\" ><strong></strong></td>
                    <td width=\"5%\" align=\"right\" valign=\"top\"></td>
                    <td width=\"79%\" align=\"justify\" colspan=\"2\"  valign=\"top\" >$xx4</td>
                </tr>
                

        ";
        
        $kolom1 = '';
        //$sql = "otori_spd '$ldtgl_spd'";
        //$hasil = $this->db->query($sql);
        
        $cRet .="</table>";
        
        $cRet .="        
        <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
        
            <tr>
                <td colspan=\"7\" align=\"center\" valign=\"top\" width=\"100%\"  style=\"font-size:12px\"> 
                    <strong>M E M U T U S K A N :<strong>&nbsp;
                </td>
            </tr>
            <tr>
                 <td width=\"10%\"  style=\"font-size:12px\" align=\"right\">&nbsp;
                </td>
                <td colspan=\"6\" align=\"left\" valign=\"top\" width=\"90%\"  style=\"font-size:12px\">
                  
                </td>
            </tr>
            <tr>
                <td width=\"10%\"  style=\"font-size:12px\" align=\"right\">&nbsp;
                </td>
                <td width=\"3%\"   style=\"font-size:12px\">1.
                </td>
                <td width=\"35%\"  style=\"font-size:12px\">Ditujukan kepada SKPD
                </td>
                <td  width=\"2%\" style=\"font-size:12px\">:
                </td>
                <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$lckdskpd - $lcnmskpd
                </td>
            </tr>
            <tr>
                <td style=\"font-size:12px\" align=\"right\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">2.
                </td>
                <td style=\"font-size:12px\" valign=\"top\">Bendahara Pengeluaran / Pengeluaran Pembantu 
                </td>
                <td  style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">$nama1
                </td>
            </tr>
            <tr>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\" align=\"right\">&nbsp;
                </td>
                <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">3.
                </td>
                <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">Jumlah Penyediaan dana
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td width=\"4%\" style=\"font-size:12px\">Rp. <br></td>
                <td width=\"20%\" align=\"right\" style=\"font-size:12px\">  $jmlspdini</td>
                <td width=\"26%\"></td>
            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>(terbilang: $biljmlini)</i></td>
            </tr>
            <tr>
                <td  style=\"font-size:12px\" align=\"right\">&nbsp;
                </td>
                <td style=\"font-size:12px\">4.
                </td>
                <td style=\"font-size:12px\">Untuk Kebutuhan / Jenis Beban
                </td>
                <td  style=\"font-size:12px\">:
                </td>
                <td  colspan=\"3\"   style=\"font-size:12px\">$tambah Bulan $blnini s.d Bulan $blnsd $trh3->thn_ang / $njns
                </td>
            </tr>
            <tr>
                <td style=\"font-size:12px\" align=\"right\">&nbsp;
                </td>
                <td style=\"font-size:12px\">5.
                </td>
                <td style=\"font-size:12px\"><u><strong>IKHTISAR PENYEDIAAN DANA : </strong></u>
                </td>
                <td  style=\"font-size:12px\">
                </td>
                <td  colspan=\"3\"   style=\"font-size:12px\">
                </td>
            </tr>
            <tr>
                <td style=\"font-size:12px\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">a. Jumlah dana DPA-SKPD/DPPA-SKPD/DPAL
                </td>
                <td style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td style=\"font-size:12px\">Rp. <br></td>
                <td align=\"right\" style=\"font-size:12px\">  $jmldpa</td>
                <td ></td>
                
            </tr>
            <tr>
                <td  style=\"font-size:12px\">&nbsp;
                </td>
                <td  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td  style=\"font-size:12px;\" valign=\"top\">b. Akumulasi SPD sebelumnya
                </td>
                <td  style=\"font-size:12px\" valign=\"top\">:
                </td>
               <td style=\"font-size:12px;border-bottom: solid 1px black;\">Rp. <br></td>
                <td align=\"right\" style=\"font-size:12px;border-bottom: solid 1px black;\">  $jmlspdlalu</td>
                <td ></td>

                </tr>
            <tr>
                <td style=\"font-size:12px\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">c. Sisa dana yang belum di-SPD-kan
                </td>
                <td  style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td style=\"font-size:12px\">Rp. <br></td>
                <td align=\"right\" style=\"font-size:12px\">  $jmlsisa</td>
                <td ></td>
            </tr>
           <tr>
                <td style=\"font-size:12px\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td style=\"font-size:12px\" valign=\"top\">d. Jumlah dana yang di-SPD-kan saat ini
                </td>
                <td style=\"font-size:12px;\" valign=\"top\">:
                </td>
               <td style=\"font-size:12px;border-bottom: solid 1px black;\">Rp. <br></td>
                <td align=\"right\" style=\"font-size:12px;border-bottom: solid 1px black;\">  $jmlspdini</td>
                <td ></td>

            </tr>
            <tr>
                <td rowspan=\"2\"  style=\"font-size:12px\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">e. Sisa jumlah dana DPA yang belum di-SPD-kan
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td style=\"font-size:12px;border-bottom: solid 2px black;\">Rp. <br></td>
                <td align=\"right\" style=\"font-size:12px;border-bottom: solid 2px black;\">  $jmlsisa2 <br>
                </td>
                <td ></td>
 
            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>(terbilang: $bilsisa)</i></td>
            </tr>
            <tr> 
                <td style=\"font-size:12px\">&nbsp;
                </td>
                <td style=\"font-size:12px\" align=\"right\" valign=\"top\">6.
                </td>
                <td style=\"font-size:12px\" valign=\"top\">Ketentuan-ketentuan lain
                </td>
                <td style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" align=\"justify\" style=\"font-size:12px\">Terhadap cara memperoleh, menggunakan dan mempertanggung- jawabkan
uang yang dimaksud tetap berpedoman pada Peraturan Perundang-Undangan
yang berlaku
                </td>
            </tr>           
            </table>";
             // CETAKAN TANDA TANGAN by Tox
             
             $init_tgl = $this->support->tanggal_format_indonesia($tglref);
             
            $cRet .="
             <table style=\"border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
        <tr>
                <td width=\"50%\" align=\"right\" colspan=\"2\">&nbsp;
                </td>               
                <td width=\"50%\"  align=\"left\"><br>Ditetapkan di Pontianak</td>
                </td>
            </tr>
        <tr >
                <td align=\"right\" colspan=\"2\">&nbsp;
                </td>   
                <td  text-indent: 50px; align=\"left\"><u>Pada tanggal : $init_tgl &nbsp;<u></td>
                </td>
            </tr>   
        <tr >
                <td width=\"40%\" align=\"right\">&nbsp;</td>
                <td width=\"60%\"  align=\"center\" colspan=\"2\">PEJABAT PENGELOLA KEUANGAN DAERAH<br>$alias<BR>&nbsp;<br>&nbsp;<br>&nbsp;</td>
                </td>
            </tr>   
        <tr >
                <td align=\"right\">&nbsp;</td>
                <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                </td>
            </tr>
                <!--<tr >
                <td align=\"right\">&nbsp;</td>
                <td align=\"center\" colspan=\"2\">$pangkat_ppkd</td>
                </td>
            </tr>-->
        <tr >
                <td  align=\"right\">&nbsp;</td>
                <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                </td>
            </tr>            
        </table>";
        $data['prev']= $cRet;
         
        
        if ($print==1){
            // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'0','no','','',30);

        } else{
          echo $cRet;
        }

    }



function preview_cetak_spd_bud(){


        $cetak = $this->uri->segment(4);
        $data_triw = $this->uri->segment(5);
        $ttd1x = $this->uri->segment(6);
        $ttd1 = str_replace('x',' ',$ttd1x);
        $tgl2 = $this->uri->segment(7);
        $ttd_tgl = $this->support->tanggal_format_indonesia($tgl2);

         $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    //$jdlnip1 = 'Menyetujui,';                    
                    $nip1=empty($rowttd->nip) ? '' : 'NIP.'.$rowttd->nip ;
                    $pangkat1=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                    $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                    $jabatan1  = empty($rowttd->jab) ? '': $rowttd->jab;
                }

        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

        $awl='';
        $jdl='';

        if($data_triw=='1'){
            $awl='1';
            $jdl='TRIWULAN I';
        }else if($data_triw=='2'){
            $awl='4';
            $jdl='TRIWULAN II';
        }else if($data_triw=='3'){
            $awl='7';
            $jdl='TRIWULAN III';
        }else{
            $awl='10';
            $jdl='TRIWULAN IV';
        }

        $cRet='';
       $Xret1 = '';
       $Xret1.="<table style=\"font-size:30px;border-left:solid 0px black;border-top:solid 0px black;border-right:solid 0px black;\" width=\"100%\" border=\"0\">
                    <tr>
                        <td align=\"center\" colspan=\"5\" style=\"font-size:22px;border: solid 0px white;\"><b>LAPORAN REGISTER SPD<br>$jdl</b></td>                     
                    </tr>
                    <tr>
                        <td align=\"center\" colspan=\"5\" style=\"font-size:22px;border: solid 0px white;\"><b>&nbsp;</b></td>                     
                    </tr>
                 </table>";

       $Xret2 = '';
       $Xret3 = ''; 
       
       $Xret2.="<table style=\"border-collapse:collapse;font-size:14px;border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;\" width=\"100%\" border=\"0\">
                    ";
       $Xret3.= " 
                 </table>";
            $cRet .= $Xret1.$Xret2.$Xret3; 
        
        
        $font = 11;
        $font1 = $font - 1;
        
        $cRet .= "<table style=\"border-collapse:collapse;vertical-align:midle;font-size:15 px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">

                     <thead >                       
                        <tr>
                            <td bgcolor=\"#A9A9A9\" width=\"5%\" align=\"center \"><b>No</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"14%\" align=\"center \"><b>Kode SKPD</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"30%\" align=\"center\"><b>Nama SKPD</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Belanja<br>(Rp)</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Pembiayaan<br>(Rp)</b></td>
                             <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Total<br>(Rp)</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";

                $sql1="SELECT kd_skpd,nm_skpd,sum(BTL) as BTL,sum(BL) as BL from
                        (
                        select kd_skpd,nm_skpd,total_hasil as BTL,0 as BL from trhspd
                        where bulan_awal ='$awl' and jns_beban ='5'
                        union
                        select kd_skpd,nm_skpd,0 as BTL,total_hasil as BL from trhspd
                        where bulan_awal ='$awl' and jns_beban ='62'
                        )x
                        group by kd_skpd,nm_skpd
                        order by kd_skpd 
  
                        ";
  

                
                $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
                $ii=0;              
                foreach ($query->result() as $row)
                {
                    $skpd=rtrim($row->kd_skpd);
                    $nama=rtrim($row->nm_skpd);
                    $BL=($row->BL);
                    $nilai_BL = number_format($BL,2,',','.');
                    $BTL=($row->BTL);
                    $nilai_BTL = number_format($BTL,2,',','.');
                    $totalx=$BL+$BTL;
                     $total = number_format($totalx,2,',','.');
                    $ii++;
                   


                      $cRet    .= " <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" > $ii</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$skpd</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >$nama</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BTL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$total</td>
                                        
 
                                    </tr> 
                                   
                                    ";
    
                }

                $sql2="SELECT sum(BTL) as btlx,sum(bl) as blx from
                        (
                        select sum(total_hasil) as BTL,0 as BL from trhspd where jns_beban ='5' and bulan_awal ='$awl'
                        union
                        select 0 BTL,sum(total_hasil) as  BL from trhspd where jns_beban ='62' and bulan_awal ='$awl'
                        )x
                        ";
  

                
                $query = $this->db->query($sql2);
                 //$query = $this->skpd_model->getAllc();              
                foreach ($query->result() as $rows)
                {
                    $BLx=($rows->blx);
                    $nilai_BLx = number_format($BLx,2,',','.');
                    $BTLx=($rows->btlx);
                    $nilai_BTLx = number_format($BTLx,2,',','.');
                    $totalx=$BLx+$BTLx;
                     $totalxx = number_format($totalx,2,',','.');
                    $ii++;
                   


                      $cRet    .= " <tr>
                                        <td align=\"right\" colspan=\"3\" style=\"vertical-align:middle; \" >TOTAL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BTLx</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BLx</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$totalxx</td>
                                        
 
                                    </tr>
                                    ";
    
                }

 
        $cRet .="

                                    </table>
<table style=\"border-collapse:collapse;vertical-align:midle;font-size:15 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$daerah, $ttd_tgl</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$jabatan1</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" ><b><u>$nama1</u></b></td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$nip1</td>
                                    </tr>
                                    
                             
       </table>
        ";
 
        $data['prev']= $cRet;    
        //$this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        switch($cetak) {
        case 0;
               echo ("<title>Lap Regis SPD</title>");
                echo($cRet);
  
 //           $this->template->load('template','anggaran/rka/perkadaII',$data);
        break;
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= cek_anggaran.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        
        }    
    }   
function preview_cetak_spd_bud_2(){



        $cetak = $this->uri->segment(4);
        $data_triw = $this->uri->segment(5);
        $ttd1x = $this->uri->segment(6);
        $ttd1 = str_replace('x',' ',$ttd1x);
        $tgl2 = $this->uri->segment(7);
        $ttd_tgl = $this->tukd_model->tanggal_format_indonesia($tgl2);

        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    //$jdlnip1 = 'Menyetujui,';                    
                    $nip1=empty($rowttd->nip) ? '' : 'NIP.'.$rowttd->nip ;
                    $pangkat1=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                    $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                    $jabatan1  = empty($rowttd->jab) ? '': $rowttd->jab;
                }

        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

        $awl='';
        $jdl='';

        if($data_triw=='1'){
            $awl='1';
            $jdl='TRIWULAN I';
        }else if($data_triw=='2'){
            $awl='4';
            $jdl='TRIWULAN II';
        }else if($data_triw=='3'){
            $awl='7';
            $jdl='TRIWULAN III';
        }else{
            $awl='10';
            $jdl='TRIWULAN IV';
        }

        $cRet='';
       $Xret1 = '';
       $Xret1.="<table style=\"font-size:30px;border-left:solid 0px black;border-top:solid 0px black;border-right:solid 0px black;\" width=\"100%\" border=\"0\">
                    <tr>
                        <td align=\"center\" colspan=\"5\" style=\"font-size:22px;border: solid 0px white;\"><b>LAPORAN REGISTER REVISI SPD<br>$jdl</b></td>                     
                    </tr>
                    <tr>
                        <td align=\"center\" colspan=\"5\" style=\"font-size:22px;border: solid 0px white;\"><b>&nbsp;</b></td>                     
                    </tr>
                 </table>";

       $Xret2 = '';
       $Xret3 = ''; 
       
       $Xret2.="<table style=\"border-collapse:collapse;font-size:14px;border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black;\" width=\"100%\" border=\"0\">
                    ";
       $Xret3.= " 
                 </table>";
            $cRet .= $Xret1.$Xret2.$Xret3; 
        
        
        $font = 11;
        $font1 = $font - 1;
        
        $cRet .= "<table style=\"border-collapse:collapse;vertical-align:midle;font-size:15 px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">

                     <thead >                       
                        <tr>
                            <td rowspan=\"2\" bgcolor=\"#A9A9A9\" width=\"5%\" align=\"center \"><b>No</b></td>
                            <td rowspan=\"2\" bgcolor=\"#A9A9A9\" width=\"14%\" align=\"center \"><b>Kode SKPD</b></td>
                            <td rowspan=\"2\" bgcolor=\"#A9A9A9\" width=\"30%\" align=\"center\"><b>Nama SKPD</b></td>
                            <td colspan=\"2\" bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Belanja<br>(Rp)</b></td>
                            <td colspan=\"2\" bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Pembiayaan<br>(Rp)</b></td>
                            <td colspan=\"2\" bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Total<br>(Rp)</b></td>                            
                         </tr>
                         <tr>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Awal</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Revisi</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Awal</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Revisi</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Awal</b></td>
                            <td bgcolor=\"#A9A9A9\" width=\"20%\" align=\"center\"><b>Revis</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";

                $sql1="SELECT kd_skpd,nm_skpd,sum(awal1) as awal1,sum(awal2) as awal2,sum(BTL) as BTL,sum(BL) as BL from
                        (
                        select kd_skpd,nm_skpd,total as awal1,0 awal2,total_hasil as BTL,0 as BL from trhspd
                        where bulan_awal ='$awl' and jns_beban ='5'
                        union
                        select kd_skpd,nm_skpd,0 awal1, total as awal2,0 as BTL,total_hasil as BL from trhspd
                        where bulan_awal ='$awl' and jns_beban ='62'
                        )x
                        group by kd_skpd,nm_skpd
                        order by kd_skpd 
  
                        ";
  

                
                $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
                $ii=0;              
                foreach ($query->result() as $row)
                {
                    $skpd=rtrim($row->kd_skpd);
                    $nama=rtrim($row->nm_skpd);

                    $awal1=($row->awal1);
                    $nilai_awal1 = number_format($awal1,2,',','.');
                    $awal2=($row->awal2);
                    $nilai_awal2 = number_format($awal2,2,',','.');

                    $BL=($row->BL);
                    $nilai_BL = number_format($BL,2,',','.');
                    $BTL=($row->BTL);
                    $nilai_BTL = number_format($BTL,2,',','.');
                    
                    $totalz=$awal1+$awal2;
                    $totalz = number_format($totalz,2,',','.');

                    $totalx=$BL+$BTL;
                    $total = number_format($totalx,2,',','.');
                    $ii++;
                   
                    $cRet    .= " <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$ii</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >$skpd</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >$nama</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_awal1</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BTL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_awal2</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$totalz</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$total</td>
 
                                    </tr> 
                                   
                                    ";
    
                }

                $sql2="SELECT sum(awal1) as awal1,sum(awal2) as awal2,sum(BTL) as btlx,sum(bl) as blx from
                        (
                        select sum(total) as awal1,0 as awal2,sum(total_hasil) as BTL,0 as BL from trhspd where jns_beban ='5' and bulan_awal ='$awl'
                        union
                        select 0 as awal1,sum(total) as awal2,0 BTL,sum(total_hasil) as  BL from trhspd where jns_beban ='62' and bulan_awal ='$awl'
                        )x
                        ";
  

                
                $query = $this->db->query($sql2);
                 //$query = $this->skpd_model->getAllc();              
                foreach ($query->result() as $rows)
                {
                    $awal1=($rows->awal1);
                    $nilai_awal1 = number_format($awal1,2,',','.');
                    $awal2=($rows->awal2);
                    $nilai_awal2 = number_format($awal2,2,',','.');

                    $BLx=($rows->blx);
                    $nilai_BLx = number_format($BLx,2,',','.');
                    $BTLx=($rows->btlx);
                    $nilai_BTLx = number_format($BTLx,2,',','.');
                    $totalx=$BLx+$BTLx;
                    $totalxx = number_format($totalx,2,',','.');
                    $totalz=$awal1+$awal2;
                    $totalzz = number_format($totalz,2,',','.');
                    $ii++;
                   


                      $cRet    .= " <tr>
                                        <td align=\"right\" colspan=\"3\" style=\"vertical-align:middle; \" >TOTAL</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_awal1</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BTLx</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_awal2</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$nilai_BLx</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$totalzz</td>
                                        <td align=\"right\" style=\"vertical-align:middle; \" >$totalxx</td>
                                        
                                    </tr>
                                    ";
    
                }

 
        $cRet .="

                                    </table>
<table style=\"border-collapse:collapse;vertical-align:midle;font-size:15 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$daerah, $ttd_tgl</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$jabatan1</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" ><b><u>$nama1</u></b></td>
                                    </tr>
                                    <tr>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>                                
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"left\" style=\"vertical-align:middle; \" >&nbsp;</td>
                                        <td align=\"center\" colspan=\"2\" style=\"vertical-align:middle; \" >$nip1</td>
                                    </tr>
                                    
                             
       </table>
        ";
 
        $data['prev']= $cRet;    
        //$this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        switch($cetak) {
        case 0;
               echo ("<title>Lap Regis SPD</title>");
                echo($cRet);
  
 //           $this->template->load('template','anggaran/rka/perkadaII',$data);
        break;
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= cek_anggaran.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        
        }    
    }  

}

