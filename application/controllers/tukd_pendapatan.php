<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tukd_pendapatan extends CI_Controller {

	public $ppkd1 = "4.02.02.01";
	public $ppkd2 = "4.02.02.02";

	function __construct(){
		parent::__construct();		
	}
	
	function buku_kas(){
        $data['page_title']= 'Buku Kas Penerimaan';
        $this->template->set('title', 'Buku Kas Penerimaan');   
        $this->template->load('template','tukd/pendapatan/kasda/kas_harian',$data) ; 
    }

     function buku_kas_arus()
    {
        $data['page_title']= 'Buku Arus Kas';
        $this->template->set('title', 'Buku Arus Kas');   
        $this->template->load('template','tukd/pendapatan/kasda/kas_arus',$data) ; 
    }

    function cetak_kas_harian()
    {   
        
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
           
         $tgl= $_REQUEST['tgl1'];
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $csql="SELECT a.jabatan,a.pangkat,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' and nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $jab = $trh2->jabatan;
         $pangkat = $trh2->pangkat;
         $lcNipBP = $trh2->nip;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"8\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN BUKU KAS UMUM</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">TANGGAL</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;\">: ".$this->tukd_model->tanggal_format_indonesia($tgl)."</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" rowspan=\"2\" width=\"4%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >No.</td>
				<td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >KASDA</td>
                <td align=\"center\" colspan=\"3\" width=\"30%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Transaksi</td>
                <td align=\"center\" rowspan=\"2\" width=\"29%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan<br>(Rp)</td> 
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran<br>(Rp)</td>             
            </tr> 
            <tr>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\" >SP2D</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">STS</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">Lain - Lain</td>
                       
            </tr>           
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">4</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">5</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">6</td>               
            </tr>";
                      
                $csql= "
                select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl'
union all
select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti < '$tgl' 
) x";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='5.02.0.00.0.00.01.0000'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd 
 WHERE  a.tgl_kas < '$tgl'";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu;                                            
                
                     /*$sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_sp2d AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE STATUS='1'
                            UNION
                            SELECT '' as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_pkd 
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd) z WHERE z.tgl='$tgl' ORDER BY z.tgl";
					 */
				 $sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE status_bud='1'
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_kas AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd
                            union 
                            select no_bukti as kasda,no_bukti AS nomor,tgl_bukti AS tgl,KET as uraian,nilai AS nilai,'5' AS jns from trhinlain_ppkd) z WHERE z.tgl='$tgl' ORDER BY z.tgl";
					
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lckeluar_ini=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       $no      =$no+1;
                       $nomor   =$row->nomor;
					   $kasda   =$row->kasda;
                       $tgl     =$row->tgl;
                       $uraian  =$row->uraian; 
                       $nilai   =$row->nilai;
                       $jns     =$row->jns;                   
                       if ($jns==5){ 
                        $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$no</td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$kasda</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nomor</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";                     
                                                                    
                                $lckeluar_ini=$lckeluar_ini+$nilai;
                       }else{
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$no</td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$kasda</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nomor</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>"; 
                                   $lcterima_ini=$lcterima_ini+$nilai;
                       }           
                    }
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;
                 

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Jumlah </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                        </tr>";
        }else {
                 $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Posisi Kas Lalu (H-1)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu,2)."</td>
                </tr>
                        
                <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\"></td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
                    
        /* $sqlsaldo = "SELECT sld_awal as nilai from ms_skpd where kd_skpd='4.02.01.00'";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank</td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";                    
                }*/
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas</b></td>
                    <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, tanggal ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$jab</td>                    
                </tr>
                 <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>
      
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>                
                <tr>
                    <td align=\"center\" colspan=\"5\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
               
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Harian";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }        
	
	function cetak_kas_harian_bln()
    {   
        
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
           
         $tgl= $_REQUEST['tgl1'];
         $tgl2= $_REQUEST['ttd7'];
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $csql="SELECT a.jabatan,a.pangkat,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' and nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $jab = $trh2->jabatan;
         $pangkat = $trh2->pangkat;
         $lcNipBP = $trh2->nip;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"8\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN BUKU KAS UMUM</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">TANGGAL</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;\">: ".$this->tukd_model->tanggal_format_indonesia($tgl)." s/d ".$this->tukd_model->tanggal_format_indonesia($tgl2)."</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" rowspan=\"2\" width=\"4%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >No.</td>
				<td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >KASDA</td>
                <td align=\"center\" colspan=\"3\" width=\"30%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Transaksi</td>
                <td align=\"center\" rowspan=\"2\" width=\"29%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan<br>(Rp)</td> 
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran<br>(Rp)</td>             
            </tr> 
            <tr>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\" >SP2D</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">STS</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">Lain - Lain</td>
                       
            </tr>           
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">4</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">5</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">6</td>               
            </tr>";
                      
                $csql= "
                select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl'
union all
select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti < '$tgl' 
) x";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='5.02.0.00.0.00.01.0000'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd 
 WHERE  a.tgl_kas < '$tgl'";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu;                                            
                
                     /*$sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_sp2d AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE STATUS='1'
                            UNION
                            SELECT '' as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_pkd 
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd) z WHERE z.tgl='$tgl' ORDER BY z.tgl";
					 */
				 $sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE status_bud='1'
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_kas AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd
                            union 
                            select no_bukti as kasda,no_bukti AS nomor,tgl_bukti AS tgl,KET as uraian,nilai AS nilai,'5' AS jns from trhinlain_ppkd) z WHERE z.tgl >= '$tgl' and z.tgl <='$tgl2' ORDER BY z.tgl";
					
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lckeluar_ini=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       $no      =$no+1;
                       $nomor   =$row->nomor;
					   $kasda   =$row->kasda;
                       $tgl     =$row->tgl;
                       $uraian  =$row->uraian; 
                       $nilai   =$row->nilai;
                       $jns     =$row->jns;                   
                       if ($jns==5){ 
                        $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$no</td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$kasda</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nomor</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";                     
                                                                    
                                $lckeluar_ini=$lckeluar_ini+$nilai;
                       }else{
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$no</td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$kasda</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nomor</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>"; 
                                   $lcterima_ini=$lcterima_ini+$nilai;
                       }           
                    }
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;
                 

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Jumlah </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                        </tr>";
        }else {
                 $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Posisi Kas Lalu (H-1)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu,2)."</td>
                </tr>
                        
                <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\"></td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
                    
        /* $sqlsaldo = "SELECT sld_awal as nilai from ms_skpd where kd_skpd='4.02.01.00'";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank</td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";                    
                }*/
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas</b></td>
                    <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, tanggal ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$jab</td>                    
                </tr>
                 <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>
      
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>                
                <tr>
                    <td align=\"center\" colspan=\"5\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
               
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Harian";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }        
    
    function cetak_kas_harian_rinci()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         
         
         $tgl= $_REQUEST['tgl1'];
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $csql="SELECT a.jabatan,a.pangkat,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' AND nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $lcNipBP = $trh2->nip;
         $jabatan = $trh2->jabatan;
         $pangkat= $trh2->pangkat;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"7\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN BUKU KAS UMUM</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">TANGGAL</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;\">: ".$this->tukd_model->tanggal_format_indonesia($tgl)."</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" rowspan=\"2\" width=\"4%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >No.</td>
				<td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >KASDA</td>
                <td align=\"center\" colspan=\"3\" width=\"30%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Transaksi</td>
                <td align=\"center\" rowspan=\"2\" width=\"29%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan<br>(Rp)</td> 
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran<br>(Rp)</td>             
            </tr> 
            <tr>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\" >SP2D</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">STS</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">Lain - Lain</td>
                       
            </tr>           
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">4</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">5</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">6</td>               
            </tr>";
               
                $csql= "select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl'
union all
select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti < '$tgl' 
) x  ";      
                //$csql= "select SUM(nilai) AS nilai FROM trhsp2d  WHERE  tgl_kas_bud < '$tgl' ";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu2 = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='5.02.0.00.0.00.01.0000'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd 
 WHERE  a.tgl_kas < '$tgl' ";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu2;                                            
                //echo $lckasinppkd."</br>";
                //echo $lcsp2d_lalu2."</br>";
                //echo $posisi_1."</br>";                  
				 /*$sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE STATUS='1'
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd) z WHERE z.tgl='$tgl' ORDER BY z.tgl,z.kasda";
	               */
                   
                   $sql = "SELECT * FROM
(
SELECT '1' as urut,no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE status_bud='1'
UNION ALL
SELECT '2' as urut,a.no_kas_bud as kasda,a.no_sp2d AS nomor,a.tgl_kas_bud AS tgl,b.kd_rek6+'-'+b.nm_rek6 AS uraian,b.nilai AS nilai,'5' AS jns FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1'
UNION ALL
SELECT '1' as urut,no_kas as kasda,no_sts AS nomor,tgl_kas AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd
UNION ALL
SELECT '2' as urut,a.no_kas as kasda,a.no_sts AS nomor,a.tgl_kas AS tgl,b.kd_rek6+'- '+(select top 1 nm_rek6 from ms_rek6 where kd_rek6=b.kd_rek6) AS uraian,b.rupiah AS nilai,'4' AS jns FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
UNION ALL
select '1' as urut,NO_BUKTI as kasda,NO_BUKTI as nomor,TGL_BUKTI as tgl, KET AS uraian,nilai AS nilai,'5' jns from trhinlain_ppkd
UNION ALL
select '2' as urut,a.NO_BUKTI as kasda,a.NO_BUKTI as nomor,a.TGL_BUKTI as tgl, b.kd_rek5 AS uraian,sum(b.nilai) AS nilai,'5' jns 
from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
group by a.NO_BUKTI,a.TGL_BUKTI,b.kd_rek5
) 
z WHERE z.tgl='$tgl' 
ORDER BY z.tgl,z.kasda,z.urut";			
                        
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lckeluar_ini=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       if($row->urut=='1'){
                            $no =$no+1;                        
                       }
                       $no =$no+0;
                       
                       $urut   =$row->urut;
                       $nomor   =$row->nomor;
					   $kasda   =$row->kasda;
                       $tgl     =$row->tgl;
                       $uraian  =$row->uraian; 
                       $nilai   =$row->nilai;
                       $jns     =$row->jns;                   
                       if ($jns==5){
                            
                            if($urut==1){
                                $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$no</b></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$kasda</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nomor</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$uraian</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                     
                                                                    
                                $lckeluar_ini=$lckeluar_ini+$nilai;
                            }else{
                                 $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";                     
                                         
                            }
                                                
                       }else{
                            if($urut==1){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$no</b></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$kasda</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nomor</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$uraian</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>"; 
                                   $lcterima_ini=$lcterima_ini+$nilai;
                            }else{
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";   
                            }                                   
                       }           
                    }
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;
                

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Jumlah </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                        </tr>";
        }else {
                 $cRet .="<tr>
                            <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Posisi Kas Lalu (H-1)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu2,2)."</td>
                </tr>
                        
                <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\"></td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
         /*           
         $sqlsaldo = "SELECT nm_bank as nama,saldo as nilai from saldo_bank order by kd_bank";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    $nmbank = $roww->nama;
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank $nmbank </td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";
                    $totsaldo=$totsaldo+$rp;
               }*/
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas</b></td>
                    <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, tanggal ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$jabatan</td>                    
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>                 
                <tr>
                    <td align=\"center\" colspan=\"5\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Harian";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }


    function cetak_kas_harian_arusrinci()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
           
         $tgl= $_REQUEST['tgl1'];
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $csql="SELECT a.jabatan,a.pangkat,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' and nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $lcNipBP = $trh2->nip;
         $pangkat = $trh2->pangkat;
         $jabatan = $trh2->jabatan;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"5\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN RINCIAN ARUS KAS</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">TANGGAL : ".$this->tukd_model->tanggal_format_indonesia($tgl)."</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >REK.</td>
				<td align=\"center\" width=\"45%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >URAIAN</td>
                <td align=\"center\" colspan=\"3\" width=\"45%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">NILAI</td>
            </tr>                        
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>                             
            </tr>";
                      
                $csql= "select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl'
                union all
                select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
                on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
                where a.tgl_bukti < '$tgl' 
                ) x";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='5.02.0.00.0.00.01.0000'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd  
WHERE  a.tgl_kas < '$tgl' ";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu; 
               // echo $kasin_1;                                                           

                 $sql4="SELECT a.seq,a.nor,a.kode,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,a.cetak,a.bold FROM map_arus_kas a 
				   GROUP BY a.seq,a.nor,a.kode,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),a.cetak,a.bold ORDER BY a.seq";
                       
                  $query4 = $this->db->query($sql4);
                  $no     = 0;                                  
               
                foreach ($query4->result() as $row4)
                {
                    $nseq      = $row4->seq; 
                    $nkode     = $row4->kode;                      
                    $nama      = $row4->uraian;   
                    $n         = $row4->kode_1;
					$n		   = ($n=="-"?"'-'":$n);
					$n2        = $row4->kode_2;
					$n2		   = ($n2=="-"?"'-'":$n2);
					$n3        = $row4->kode_3;
					$n3		   = ($n3=="-"?"'-'":$n3);
                    $ncetak    = $row4->cetak;
                    $nbold     = $row4->bold;
                    
                    $digit = strlen($n)-2;
                    
                    if($n3==5){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and left(b.kd_rek6,$digit) in ($n) ";				       
                    }else if($n3==61){
                        $sql = "SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,$digit) in ($n) ";			       
                    }else if($n3==62){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and left(b.kd_rek6,$digit) in ($n) ";				       
                    }else if($n3==51){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and left(b.kd_rek6,1) in ($n) ";				       
                    }else if($n3==41){
                        $sql = " select sum(x.nilai) AS nilai from (SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,2) in ($n)
union all
select sum(b.nilai)*-1 AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti ='$tgl' and left(b.kd_rek5,2) in ($n) ) x  ";				                               
                    }else{
                        $sql = "select sum(x.nilai) AS nilai from (SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,$digit) in ($n)
union all
select sum(b.nilai)*-1 AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti ='$tgl' and left(b.kd_rek5,$digit) in ($n)) x ";				                               
                    }
                                            				 	
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lcterima_ini1=0;
                    $lcterima_ini2=0;
                    $lckeluar_ini=0;
                    $lcnilai_up=0;
                    $lcnilai_gu=0;
                    $lcnilai_tu=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       $no      =$no+1;
                       $nilai   =$row->nilai;
                        
                       if($nkode=='4'){
                        $lcterima_ini=$lcterima_ini+$nilai;
                       } 
  
                       if($n3==4 || $n3==41){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                        
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }//batas 4
                    
                    $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";  
                    
                    if($n3==51){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            //up
                            if($nseq==675){
                                $sqlup = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and left(b.kd_rek6,1)='1' and c.jns_spp = '1' ")->row();
                                $lcnilai_up = $sqlup->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_up,2)."</td>
                                  </tr>";    
                            }
                            }
                            //GU                            
                            if($nseq==680){
                                $sqlgu = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and c.jns_spp = '2' ")->row();
                                $lcnilai_gu = $sqlgu->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_gu,2)."</td>
                                  </tr>";    
                            }
                            }   
                            //TU                            
                            if($nseq==685){
                                $sqltu = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and c.jns_spp = '3' ")->row();
                                $lcnilai_tu = $sqltu->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_tu,2)."</td>
                                  </tr>";    
                            }
                            } 
                            //batas                           
                       }                                  
                    }//batas51
                    if($n3==5){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }//batas 5
                    
                     $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";  
                     
                     if($n3==6){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }
                        
                     if($n3==61 || $n3==62){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }//batas 6
                    
                    }
                    
                    }
                    
                     $sql_4cp = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,1) in ('5','1')")->row();
                    $lcterima_cp = $sql_4cp->nilai;
                    
                    $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"><b>Contra Pos</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\">".number_format($lcterima_cp,2)."</td>
                                  </tr>";
                    
                    
                    $sql_4 = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,1) in ('4','5','1')")->row();
                    $lcterima_ini1 = $sql_4->nilai;
                    
                    $sql_61 = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas='$tgl' and left(b.kd_rek6,2) in ('61')")->row();                    
                    $lcterima_ini2 = $sql_61->nilai;
                    $lcterima_ini = $lcterima_ini1 + $lcterima_ini2;
                    
                    $sql_5 = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud = '$tgl' and left(b.kd_rek6,2) in ('51','52','62','11')")->row(); 
                    $lckeluarxy_ini = $sql_5->nilai;
                    
                    $sql_7 = $this->db->query("select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti= '$tgl' ")->row(); 
                    $lckeluarx_ini = $sql_7->nilai;
                    
                    $lckeluar_ini=$lckeluarxy_ini+$lckeluarx_ini;
                    
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;                 

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">1. Jumlah Penerimaan (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>                    
                </tr>";
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">2. Jumlah Pengeluaran (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";        
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">3. Perubahan Posisi Kas (1 - 2)</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>                            
                        </tr>";
        }else {
                 $cRet .="<tr>
                        <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">3. Perubahan Posisi Kas (1 - 2)</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>                            
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">4. Penerimaan Kas Lalu (H - 1) </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">5. Pengeluaran Kas Lalu (H - 1) </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu,2)."</td>
                </tr>                         
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">6. Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
                    /*
         $sqlsaldo = "SELECT nm_bank as nama,saldo as nilai from saldo_bank order by kd_bank";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    $nmbank = $roww->nama;
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank $nmbank </td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";
                    $totsaldo=$totsaldo+$rp;
               }*/
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas </b></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$jabatan</td>                    
                </tr>
               <tr>                
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"1\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>
                </tr>                
                <tr>
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
                
                <tr>
                    <td align=\"center\" colspan=\"1\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Harian";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }
   
    function cetak_kas_harian_arusrinci_bln()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
           
           
         $tgl= $_REQUEST['tgl1'];
         $tgl7= $_REQUEST['ttd7'];
          //$lcperiode = $this->tukd_model->getBulan($tgl);
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         //$lcperiode = $this->tukd_model->getBulan($tgl);
         //$lcperiode7 = $this->tukd_model->getBulan($tgl7);
            //$ttd= $_REQUEST['ttd'];
           // $lcperiode = $this->tukd_model->getBulan($bulan);
            //$ttd= $_REQUEST['ttd'];
            $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $csql="SELECT a.jabatan,a.pangkat,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' and nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $lcNipBP = $trh2->nip;
         $pangkat = $trh2->pangkat;
         $jabatan = $trh2->jabatan;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"5\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN RINCIAN ARUS KAS</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">PERIODE : ".$this->tukd_model->tanggal_format_indonesia($tgl)."  s/d  ".$this->tukd_model->tanggal_format_indonesia($tgl7)."</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >REK.</td>
				<td align=\"center\" width=\"45%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >URAIAN</td>
                <td align=\"center\" colspan=\"3\" width=\"45%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">NILAI</td>
            </tr>                        
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>                             
            </tr>";
                      
                $csql= "select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl'
union all
select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti < '$tgl' 
) x";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='4.02.01.00'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and 
b.kd_skpd = a.kd_skpd  WHERE  a.tgl_kas < '$tgl'                
                ";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu;                                                            

                 $sql4="SELECT a.seq,a.nor,a.kode,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,a.cetak,a.bold FROM map_arus_kas a 
				   GROUP BY a.seq,a.nor,a.kode,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),a.cetak,a.bold ORDER BY a.seq";
                       
                  $query4 = $this->db->query($sql4);
                  $no     = 0;                                  
               
                foreach ($query4->result() as $row4)
                {
                    $nseq      = $row4->seq; 
                    $nkode     = $row4->kode;                      
                    $nama      = $row4->uraian;   
                    $n         = $row4->kode_1;
					$n		   = ($n=="-"?"'-'":$n);
					$n2        = $row4->kode_2;
					$n2		   = ($n2=="-"?"'-'":$n2);
					$n3        = $row4->kode_3;
					$n3		   = ($n3=="-"?"'-'":$n3);
                    $ncetak    = $row4->cetak;
                    $nbold     = $row4->bold;
                    
                    $digit = strlen($n)-2;
                    
                    if($n3==5){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and left(b.kd_rek5,$digit) in ($n) ";				       
                    }else if($n3==51){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and left(b.kd_rek5,1) in ($n) ";				       
                    }else if($n3==61){
                        $sql = "SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,$digit) in ($n) ";
                    }else if($n3==62){
                        $sql = "SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and left(b.kd_rek5,$digit) in ($n) ";				       
                    }else if($n3==41){
                        $sql = "select sum(x.nilai) AS nilai from (SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,2) in ($n)
union all
select sum(b.nilai)*-1 AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti >='$tgl' and a.tgl_bukti <='$tgl7' and left(b.kd_rek5,2) in ($n)) x  ";				                               
                    }else{
                        $sql = "select sum(x.nilai) AS nilai from (SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,$digit) in ($n)
union all
select sum(b.nilai)*-1 AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti >='$tgl' and a.tgl_bukti <='$tgl7' and left(b.kd_rek5,$digit) in ($n)) x ";				                               
                    }
                                            				 	
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lcterima_ini1=0;
                    $lcterima_ini2=0;
                    $lckeluar_ini=0;
                    $lcnilai_up=0;
                    $lcnilai_gu=0;
                    $lcnilai_tu=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       $no      =$no+1;
                       $nilai   =$row->nilai;
                        
                       if($nkode=='4'){
                        $lcterima_ini=$lcterima_ini+$nilai;
                       } 
  
                       if($n3==4 or $n3==41){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                        
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }//batas 4
                    
                    $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";  
                    
                    if($n3==51){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            //up
                            if($nseq==675){
                                $sqlup = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and left(b.kd_rek5,1)='1' and c.jns_spp = '1' ")->row();
                                $lcnilai_up = $sqlup->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_up,2)."</td>
                                  </tr>";    
                            }
                            }
                            //GU                            
                            if($nseq==680){
                                $sqlgu = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and c.jns_spp = '2' ")->row();
                                $lcnilai_gu = $sqlgu->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_gu,2)."</td>
                                  </tr>";    
                            }
                            }   
                            //TU                            
                            if($nseq==685){
                                $sqltu = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
                                left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
                                left join trhspp c on b.no_spp = c.no_spp and c.kd_skpd = b.kd_skpd                                
                                WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and c.jns_spp = '3' ")->row();
                                $lcnilai_tu = $sqltu->nilai;
                          
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($lcnilai_tu,2)."</td>
                                  </tr>";    
                            }
                            } 
                            //batas                           
                       }                                  
                    }//batas51
                    if($n3==5){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }//batas 5
                    
                     $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";  
                     
                     
                     if($n3==6){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }
                    
                    if($n3==61 || $n3==62){ 
                       if ($nbold==2){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else if ($nbold==3){                                                                                            
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nkode</b></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                                                                         
                       }else{
                            if($ncetak=='p1'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";    
                        }else if($ncetak=='p2'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }else if($ncetak=='p3'){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$nkode</td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";    
                        }                               
                       }                                  
                    }
                    //batas 6
                    
                    }
                    
                    }
                    
                    $sql_4cp = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,1) in ('5','1')")->row();
                    $lcterima_cp = $sql_4cp->nilai;
                    
                    $cRet .="<tr>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"></td>
								  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"><b>Contra Pos</b></td>
                                  <td valign=\"top\" colspan=\"2\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\"></td>                                 
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:solid 1px black\">".number_format($lcterima_cp,2)."</td>
                                  </tr>";
                    
                    $sql_4 = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,1) in ('4','5','1')")->row();
                    $lcterima_ini1 = $sql_4->nilai;
                    
                    $sql_61 = $this->db->query("SELECT sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
where a.tgl_kas >= '$tgl' and a.tgl_kas <= '$tgl7' and left(b.kd_rek5,2) in ('61')")->row();                    
                    $lcterima_ini2 = $sql_61->nilai;
                    $lcterima_ini = $lcterima_ini1 + $lcterima_ini2;
                    
                    $sql_5 = $this->db->query("SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud >= '$tgl' and a.tgl_kas_bud <= '$tgl7' and left(b.kd_rek5,2) in ('51','52','62','11')")->row(); 
                    
                    $lckeluarxy_ini = $sql_5->nilai;
                    
                    $sql_7 = $this->db->query("select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti>= '$tgl' and a.tgl_bukti<= '$tgl7' and left(b.kd_rek5,1) in ('4','5','1') ")->row(); 
                    $lckeluarx_ini = $sql_7->nilai;
                    
                    $lckeluar_ini=$lckeluarxy_ini+$lckeluarx_ini;
                    
                    
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;                 

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">1. Jumlah Penerimaan (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>                    
                </tr>";                
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">2. Jumlah Pengeluaran (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";        
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">3. Perubahan Posisi Kas (1 - 2)</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>                            
                        </tr>";
        }else {
                 $cRet .="<tr>
                        <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">3. Perubahan Posisi Kas (1 - 2)</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>                            
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">4. Penerimaan Kas Lalu (H - 1) </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black;\">5. Pengeluaran Kas Lalu (H - 1) </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu,2)."</td>
                </tr>                         
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">6. Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
                    
         $sqlsaldo = "SELECT nm_bank as nama,saldo as nilai from saldo_bank order by kd_bank";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    $nmbank = $roww->nama;
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank $nmbank </td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";
                    $totsaldo=$totsaldo+$rp;
               }
               
              // $tgl_ttd2 = date('YYYY-m-d');
               // ".$this->tanggal_format_indonesia($tgl_ttd2)."
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas </b></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"1\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$jabatan</td>                    
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>                
                <tr>
                    <td align=\"center\" colspan=\"2\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"1\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>     
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Harian";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }


    function cetak_kas_harian_rincibln()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $jnscetak = $this->uri->segment(3);
            $bulan =''; 
            
            $lcperiode = $this->tukd_model->getBulan($bulan);
            $ttd= $_REQUEST['ttd'];
         $lcttd        = str_replace('a',' ',$ttd);
         $tgl6= $_REQUEST['tgl1'];
         $tgl7= $_REQUEST['ttd7'];            
         $tgl_ttd= $_REQUEST['tgl_ttd'];
         
         //$lcperiode = $this->tukd_model->getBulan($tgl6);
         //$lcperiode7 = $this->tukd_model->getBulan($tgl7);
         
         $csql="SELECT a.pangkat,a.jabatan,a.nama, a.nip FROM ms_ttd a WHERE kode = 'BUD' AND nip='$lcttd'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $lcNipBP = $trh2->nip;
         $jabatan = $trh2->jabatan;
         $pangkat = $trh2->pangkat;                         
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"9\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KOTA PONTIANAK <br>LAPORAN BUKU KAS UMUM</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"1\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>                
                <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">PERIODE</td>
                <td align=\"left\" colspan=\"7\" style=\"font-size:12px;border: solid 1px white;\">: ".$this->tukd_model->tanggal_format_indonesia($tgl6)."  s/d  ".$this->tukd_model->tanggal_format_indonesia($tgl7)."</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"7\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
            
            <tr>
                <td align=\"center\" rowspan=\"2\" width=\"4%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >No.</td>
				<td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >KASDA</td>                
				<td align=\"center\" rowspan=\"2\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >TGL KAS</td>
                <td align=\"center\" colspan=\"3\" width=\"30%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Transaksi</td>
                <td align=\"center\" rowspan=\"2\" width=\"29%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan<br>(Rp)</td> 
                <td align=\"center\" rowspan=\"2\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran<br>(Rp)</td>             
            </tr> 
            <tr>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\" >SP2D</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">STS</td>
                <td align=\"center\"  width=\"12%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">Lain - Lain</td>
                       
            </tr>           
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>                
				<td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>
                <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">4</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">5</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">6</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">7</td>               
            </tr>";
               
                $csql= "select sum(x.nilai) AS nilai from (
                SELECT sum(b.nilai) AS nilai FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1' and a.tgl_kas_bud < '$tgl6'
union all
select sum(b.nilai) AS nilai from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
where a.tgl_bukti < '$tgl6' 
) x";      
                //$csql= "select SUM(nilai) AS nilai FROM trhsp2d  WHERE  tgl_kas_bud < '$tgl' ";
                $hasil2 = $this->db->query($csql);
                $trhsp2d2 = $hasil2->row();          
                $lcsp2d_lalu2 = $trhsp2d2->nilai;
                
                $csqlkasin1= "SELECT sld_awal as nilai from ms_skpd where kd_skpd='5.02.0.00.0.00.01.0000'";
                $hasil3 = $this->db->query($csqlkasin1);
                $trhkasinpkd = $hasil3->row();          
                $lckasinpkd = $trhkasinpkd->nilai;
                
                $csqlkasin2= "select sum(b.rupiah) AS nilai FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and 
b.kd_skpd = a.kd_skpd  WHERE  a.tgl_kas < '$tgl6' ";
                $hasil4 = $this->db->query($csqlkasin2);
                $trhkasinppkd = $hasil4->row();          
                $lckasinppkd = $trhkasinppkd->nilai;
                
                $kasin_1= $lckasinpkd+$lckasinppkd;
				//$kasin_1= $lckasinppkd;
                $posisi_1= $kasin_1-$lcsp2d_lalu2;                                            
                //echo $lckasinppkd."</br>";
                //echo $lcsp2d_lalu2."</br>";
                //echo $posisi_1."</br>";                  
				 /*$sql = "SELECT * FROM
						   (SELECT no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE STATUS='1'
                            UNION
                            SELECT no_kas as kasda,no_sts AS nomor,tgl_sts AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd) z WHERE z.tgl='$tgl' ORDER BY z.tgl,z.kasda";
	               */
                   
                   $sql = "SELECT * FROM
(
SELECT '1' as urut,no_kas_bud as kasda,no_sp2d AS nomor,tgl_kas_bud AS tgl,keperluan AS uraian,nilai AS nilai,'5' AS jns FROM trhsp2d WHERE status_bud='1'
UNION ALL
SELECT '2' as urut,a.no_kas_bud as kasda,a.no_sp2d AS nomor,a.tgl_kas_bud AS tgl,b.kd_rek6+' -'+b.nm_rek6 AS uraian,b.nilai AS nilai,'5' AS jns FROM trhsp2d a
left join trdspp b on b.no_spp = a.no_spp and a.kd_skpd = b.kd_skpd
WHERE a.status_bud='1'
UNION ALL
SELECT '1' as urut,no_kas as kasda,no_sts AS nomor,tgl_kas AS tgl,keterangan AS uraian,total AS nilai,'4' AS jns FROM trhkasin_ppkd
UNION ALL
SELECT '2' as urut,a.no_kas as kasda,a.no_sts AS nomor,a.tgl_kas AS tgl,b.kd_rek6+' -'+(select top 1 nm_rek6 from ms_rek6 where kd_rek6=b.kd_rek6) AS uraian,b.rupiah AS nilai,'4' AS jns FROM trhkasin_ppkd a
left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and b.kd_skpd = a.kd_skpd
union ALL
select '1' as urut,NO_BUKTI as kasda,NO_BUKTI as nomor,TGL_BUKTI as tgl, KET AS uraian,nilai AS nilai,'5' jns from trhinlain_ppkd
union ALL
select '2' as urut,a.NO_BUKTI as kasda,a.NO_BUKTI as nomor,a.TGL_BUKTI as tgl, b.kd_rek5 AS uraian,sum(b.nilai) AS nilai,'5' jns 
from trhinlain_ppkd a inner join trdinlain_ppkd b
on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti
group by a.NO_BUKTI,a.TGL_BUKTI,b.kd_rek5
) 
z WHERE z.tgl >='$tgl6' and z.tgl<='$tgl7'
ORDER BY z.tgl,z.kasda,z.urut";			
                        
                	
                    $hasil = $this->db->query($sql);       
                    $lcterima_ini=0;
                    $lckeluar_ini=0;
                    $no=0;
                    foreach ($hasil->result() as $row)
                    {
                       
                       if($row->urut=='1'){
                            $no =$no+1;                        
                       }
                       $no =$no+0;
                       $urut   =$row->urut;
                       $nomor   =$row->nomor;
					   $kasda   =$row->kasda;
                       $tgl     =$row->tgl;
                       $uraian  =$row->uraian; 
                       $nilai   =$row->nilai;
                       $jns     =$row->jns;      
                       
                       $tglv = explode('-',$tgl);
                       $tglv1 = $tglv[0];
                       $tglv2 = $tglv[1];
                       $tglv3 = $tglv[2];
                       
                       $tgll = $tglv3.'-'.$tglv2.'-'.$tglv1; 
                                                            
                       if ($jns==5){
                            
                            if($urut==1){
                                $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$no</b></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$kasda</b></td>                                  
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$tgll</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nomor</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$uraian</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  </tr>";                     
                                                                    
                                $lckeluar_ini=$lckeluar_ini+$nilai;
                            }else{
                                 $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>                                  
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  </tr>";                     
                                         
                            }
                                                
                       }else{
                            if($urut==1){
                            $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$no</b></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$kasda</b></td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$tgll</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$nomor</b></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>$uraian</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"><b>".number_format($nilai,2)."</b></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>"; 
                                   
                            }else{
                              $cRet .="<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>                                  
								  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\">".number_format($nilai,2)."</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:none;border-top:none\"></td>
                                  </tr>";   
                                  //$lcterima_ini=$lcterima_ini+$nilai;
                            }                                   
                       }           
                    }
                    
                    $csqlkasin25= "select sum(nilai) nilai,sum(koreksi) koreksi
					from(select sum(b.rupiah) AS nilai,0 as koreksi FROM trhkasin_ppkd a
                    left join trdkasin_ppkd b on b.no_kas = a.no_kas and b.no_sts = a.no_sts and 
                    b.kd_skpd = a.kd_skpd  WHERE  a.tgl_kas >= '$tgl6' and a.tgl_kas <= '$tgl7'
						UNION
			SELECT
				0 AS nilai,
				SUM (b.nilai) AS koreksi
			FROM
				trhinlain_ppkd a
			LEFT JOIN trdinlain_ppkd b ON b.no_bukti = a.no_bukti
			AND b.kd_skpd = a.kd_skpd
			WHERE
				a.tgl_bukti >= '$tgl6'
			AND a.tgl_bukti <= '$tgl7'
	) x";
                $hasil45 = $this->db->query($csqlkasin25);
                $trhkasinppkd5 = $hasil45->row();          
                $lcterima_ini = $trhkasinppkd5->nilai;
				$lckoreksi_ini = $trhkasinppkd5->koreksi;
                    
                    
                    $posisi_ini=$lcterima_ini-$lckeluar_ini;
                    $posisi=$posisi_1+$posisi_ini;
					$posisi_baru =$posisi-$lckoreksi_ini;
                 

        $cRet .="<tr>
                    <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;border-top:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Jumlah </td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcterima_ini,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lckeluar_ini,2)."</td>
                </tr>";
       if ($posisi_ini<0){
                
                $cRet .="<tr>
                            <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                        </tr>";
        }else {
                 $cRet .="<tr>
                            <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Perubahan Posisi Kas</td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\"></td>
                            <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($posisi_ini,2)."</td>
                        </tr>";
        }       
        $cRet .="<tr>
                    <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\"> &nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">Posisi Kas Lalu (H-1)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($kasin_1,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black;\">".number_format($lcsp2d_lalu2,2)."</td>
                </tr>
                        
                <tr>
                    <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;border-right:solid 1px black;\">&nbsp;</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">Posisi Kas (H)</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\">".number_format($posisi,2)."</td>
                    <td align=\"right\" style=\"font-size:12px;border: solid 1px black; border-bottom:solid 1px black;\"></td>
                </tr>";
        
         $cRet .="
                 <tr>&nbsp;
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white; border-top:solid 1px black;\">&nbsp;</td>                                                                                                                                                                                
                </tr>
                 <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:15px;border: solid 1px white;\">Rekapitulasi Posisi kas di BUD (Rp)</td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>
                    <td align=\"left\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>";
                    /*
         $sqlsaldo = "SELECT nm_bank as nama,saldo as nilai from saldo_bank order by kd_bank";
                $saldo = $this->db->query($sqlsaldo);
                $totsaldo=0;
                foreach ($saldo->result() as $roww)
                {
                    $nmbank = $roww->nama;
                    $rp = $roww->nilai;
                
                    $cRet .="<tr>
                                <td align=\"left\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">Saldo di Bank $nmbank </td>
                                <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"> ".number_format($rp)."</td>
                                <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                            </tr>";
                    $totsaldo=$totsaldo+$rp;
               }*/
         $cRet .="<tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\"><b>Total Saldo Kas</b></td>
                    <td align=\"right\" colspan=\"2\" style=\"font-size:15px;border: solid 1px white;\"><b>".number_format($posisi,2)."</b></td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:15px;border: solid 1px white;\"></td>                                                                                                                                                                                
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">Pontianak, tanggal ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">$jabatan</td>                    
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">$pangkat</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
				<tr>
                    <td align=\"left\" colspan=\"5\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>                
                <tr>
                    <td align=\"center\" colspan=\"5\" style=\"font-size:12px; border: solid 1px white;\"></td>                    
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px; border: solid 1px white;\"><b><u>$lcNmBP</u></b></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> &nbsp;</td>
                    <td align=\"center\"  style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:15px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  
                
        $cRet .='</table>';
                 
                 $juduls = "Kas Bulanan";
                 
         $data['prev']= $cRet;    
         if($jnscetak=="0"){
                echo $cRet;
         }else if($jnscetak=="1"){
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
         }else if($jnscetak=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $juduls.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
     
    }

}    