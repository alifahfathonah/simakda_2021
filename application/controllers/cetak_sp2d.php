<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_sp2d extends CI_Controller { 

	function __construct(){	 
		parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }    
	} 
  
  	function reg_efisiensi_sp2d(){
        $data['page_title']= 'REGISTER EFISIENSI SP2D';
        $this->template->set('title', 'REGISTER EFISIENSI SP2D');   
        $this->template->load('template','tukd/register/reg_efisiensi_sp2d',$data) ; 
    }

	function cetak_daftar_penguji($no_uji='',$ttd='',$dcetak='',$cetak='',$atas='',$bawah='',$kiri='',$kanan=''){
		$print = $cetak;
	
			$no_uji = str_replace('123456789','/',$this->uri->segment(3));
			$lcttd = str_replace('abcdefg',' ',$this->uri->segment(4));
		
		    $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
			$sqlcount="SELECT COUNT(a.no_sp2d) as jumlah FROM trduji a INNER JOIN trhuji b ON a.no_uji=b.no_uji WHERE a.no_uji='$no_uji'";
                 $sql123=$this->db->query($sqlcount);
                 foreach ($sql123->result() as $rowcount)
                {
                    $jumlah=$rowcount->jumlah;                    
                   
                }
			$PageCount='$page';	
			$cRet ='';
			$cRet .="<table style='border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:12px' border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>
            <tr >
                <td width='100%' align='center' colspan=4 style='font-size:18px'>DAFTAR PENGUJI / PENGANTAR<br>SURAT PERINTAH PENCAIRAN DANA</td></tr>
					 <TR >
						<TD align='left' width='10%'>Tanggal </TD>
						<TD align='left' width='70%'>: ".$this->support->tanggal_format_indonesia($dcetak)."</TD>
						<TD align='left' width='10%'></TD>
						<TD align='right'  width='20%'>Lembaran ke 1</TD>
					 </TR>					 
					 <TR>
						<TD align='left'> Nomor</TD>
						<TD align='left'  >: ".$no_uji."</TD>
						<TD align='left' > </TD>
						<TD align='right' >Terdiri dari ".$jumlah." lembar </TD>
					 </TR>
					 </TABLE>";

			$cRet .=" <table style='border-collapse:collapse;font-family:Tahoma; font-size:11px' width='100%' align='center' border='1' cellspacing='0' cellpadding='1'>               
				<thead>
			   <tr style='font-size:12px;font-weight:bold;'>
                    <td width='5%' align='center'><b>NO</b></td>
                    <td width='10%' align='center' ><b>TANGGAL DAN<br>NOMOR SP2D</b></td>
                     <td  width='28%' align='center'><b>ATAS NAMA<br>( YANG BERHAK )</b>
					 </td>
					 <td width='20%' align='center' ><b>SKPD</b>        
                    </td>
					<td width='7%' align='center' ><b>NOMOR<br/>REKENING</b>        
                    </td>
                    <td  width='10%' align='center'><b>JUMLAH KOTOR<br>(Rp)</b>
					 </td>					 
                    <td width='10%' align='center' ><b>JUMLAH<br>POTONGAN</b>
                    </td>
                    <td width='10%' align='center'><b>JUMLAH<br>BERSIH</b>
                    </td>
                    <td  width='10%' align='center'><b>TANGGAL<br>TRANSFER</b>
                    </td>
                   
                </tr>
				<tr style='font-size:11px;font-weight:bold;'>	
					<td align='center' >1
                    </td>
					<td align='center' >2
                    </td>
					<td align='center' >3
                    </td>
					<td align='center' >4
                    </td>
					<td align='center' >5
                    </td>
					<td align='center' >6
                    </td>
					<td align='center' >7
                    </td>
					<td align='center' >8
                    </td>
					<td align='center' >9
                    </td>
				</tr>
				</thead>
				";
			
			  $sql = "SELECT b.no_sp2d,c.tgl_sp2d,c.nmrekan,c.pimpinan,c.alamat,c.kd_skpd,c.nm_skpd
,c.jns_spp,c.jenis_beban,c.kotor,c.pot,c.no_rek FROM TRHUJI a inner join TRDUJI b on a.no_uji=b.no_uji 
LEFT join (
SELECT a.*,ISNULL(SUM(b.nilai),0)pot FROM (select no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban, isnull(SUM(z.nilai),0)kotor,a.no_rek
from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
INNER JOIN trdspp z ON b.no_spp=z.no_spp AND b.kd_skpd=z.kd_skpd
INNER JOIN ms_skpd d on a.kd_skpd=d.kd_skpd
GROUP BY no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban,a.no_rek)a 
LEFT JOIN 
trspmpot b ON a.no_spm=b.no_spm and a.kd_skpd=b.kd_skpd
GROUP BY no_sp2d,a.no_spm,tgl_sp2d,a.nmrekan,a.alamat,a.pimpinan,
a.kd_skpd,a.nm_skpd,a.jns_spp,a.jenis_beban,a.kotor,a.no_rek) c on b.no_sp2d=c.no_sp2d WHERE a.no_uji='$no_uji'";
			 $hasil = $this->db->query($sql);
                    $lcno = 0;
                     $total_kotor=0;
                     $total_pot=0;
					 
					 foreach ($hasil->result() as $row)
                    {
                       $lcno = $lcno + 1;
					   $no_sp2d=empty($row->no_sp2d) || $row->no_sp2d == '' ? ' ' :$row->no_sp2d;
					   //$tgl_sp2d=$row->tgl_sp2d;
					   $nmrekan=empty($row->nmrekan) || $row->nmrekan == '' ? ' ' :$row->nmrekan;
					   $pimpinan=empty($row->pimpinan) || $row->pimpinan == '' ? ' ' :$row->pimpinan;
					   $alamat=empty($row->alamat) || $row->alamat == '' ? ' ' :$row->alamat;
					   $kd_skpd=empty($row->kd_skpd) || $row->kd_skpd == '' ? ' ' :$row->kd_skpd;
					   $nm_skpd=empty($row->nm_skpd) || $row->nm_skpd == '' ? ' ' :$row->nm_skpd;
					   $jns=empty($row->jns_spp) || $row->jns_spp == '' ? ' ' :$row->jns_spp;
					   $jns_bbn=empty($row->jenis_beban) || $row->jenis_beban == '' ? ' ' :$row->jenis_beban;
					   $kotor=empty($row->kotor) || $row->kotor == '' ? 0 :$row->kotor;
					   $pot=empty($row->pot) || $row->pot == '' ? 0 :$row->pot;
					   $no_rek2=empty($row->no_rek) || $row->no_rek == '' ? 0 :$row->no_rek;
					   
					   $total_kotor=$kotor+$total_kotor;
					   $total_pot=$pot+$total_pot;
					   //$total_bersih=$total_kotor-$total_pot;
					   $tgl_sp2d=empty($row->tgl_sp2d) || $row->tgl_sp2d == '' ? ' ' :$this->tukd_model->tanggal_ind($row->tgl_sp2d);
			             $cekbp = substr($kd_skpd,18,4);
                         if($cekbp=='0000'){
                        $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$kd_skpd' AND kode='BK'";
                        }else{
                        $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$kd_skpd' AND kode='BPP'";   
                        }
							 $sqlnam=$this->db->query($sqlnam);
							 foreach ($sqlnam->result() as $rownam)
							{
								$nama_ben=$rownam->nama;                    
								$jabat_ben=$rownam->jabatan;                    
							}
						$nama_ben = empty($nama_ben) || $nama_ben == 'NULL' ? 'Belum Ada data Bendahara' :$nama_ben;
						$jabat_ben = empty($jabat_ben) || $jabat_ben == 0 ? ' ' :$jabat_ben;
						
						
		if(($jns==6) && ($jns_bbn==3) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd 
					</td>	
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		} else if (($jns==6) && ($jns_bbn==2) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>					
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		
		}else if (($jns==4) && ($jns_bbn==9) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan, $pimpinan <br>$alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>			
					<td valign='top' align='center' >$no_rek2 
					</td>	
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		
		} else if(($jns==5) ){
					                       					
       $cRet .=" <tr >
                    <td valign='top' align='center'>$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left'>$nmrekan <br> $alamat
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
		}  else{
		  $cRet .=" <tr >
                    <td valign='top' align='center' >$lcno  
                    </td>
                    <td valign='top' align='center' >$no_sp2d <br> $tgl_sp2d
					</td>					
                    <td valign='top' align='left' >$nama_ben <br>$jabat_ben $nm_skpd
					</td>					
                    <td valign='top' align='left' >$kd_skpd<br>$nm_skpd
					</td>
					<td valign='top' align='center' >$no_rek2 
					</td>
                    <td valign='top' align='right' >".number_format($kotor,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='right' >".number_format($pot,"2",",",".")."&nbsp; 
                    </td>
                    <td valign='top' align='right' >".number_format($kotor-$pot,"2",",",".")."&nbsp; 
					 </td>
					<td valign='top' align='center' >&nbsp; 
					</td>
									 
                </tr>
				";
			
		}
				};
				
			 $cRet .=" <tr style='font-size:11px;font-weight:bold;'>
                    <td colspan='5' align='center' >TOTAL
                    </td>
                    <td  align='right' >".number_format($total_kotor,"2",",",".")."&nbsp; 
					</td>
					<td  align='right' >".number_format($total_pot,"2",",",".")."&nbsp; 
					</td>
					<td  align='right' >".number_format($total_kotor-$total_pot,"2",",",".")."&nbsp;
					</td>
					<td  align='center' >&nbsp; 
					</td>
                </tr>
				";
			$cRet .='</table>';
			
			$cRet .=" <table style='border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:11px;' border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>
			
			<tr >
				<td align='left' width='70%' style='height: 30px;' >&nbsp;&nbsp;Diterima oleh : ................................................</td>
				<td align='center' width='30%' >$jabatan</td>
				
				</tr>
			<tr>
				<td>&nbsp;&nbsp;.....................................................</td>
				<td align='center'>$pangkat</td>
				</tr>
			<tr>
				<td colspan='2' ><br>&nbsp;&nbsp;Petugas Bank / Pos</td>
				</tr>
			<tr >
				<td width='100%' colspan='2' style='height: 50px;' >&nbsp;</td>
				</tr>
			<tr>
				<td>&nbsp;</td>
				<td align='center'><u>$nama</u></td>
				</tr>
			<tr>
				<td>&nbsp;</td>
				<td align='center'>NIP. $nip</td>
				</tr>
			<tr>
				<td><align='left' style='width: 250px;'>__________________________________</td>
				<td align='center'></td>
				</tr>
				</table>";

			$data['prev']= 'Kartu Kendali';
			if ($print==1){

	    $this->master_pdf->_mpdf('',$cRet,10,10,10,1,'','','',5);

				

		} else{
		  $cRet = str_replace('$page', '', $cRet);
		  echo $cRet;
		}
	
	}

   	function antrian_sp2d_cair(){
		
		$n = date("Y-m-d");
		$tanggalsbl = $this->support->tanggal_format_indonesia($n);
		$thn_ang = $this->session->userdata('pcThang');
		$cRet="";
		$cRet .="<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
			<tr>
                <td align='center' colspan='16' style='font-size:14px;border: solid 1px white;'><b>PEMERINTAH KOTA PONTIANAK<br>DAFTAR ANTRIAN SP2D TERBIT DAN STATUS ADVICE</b><br> <b>TAHUN ANGGARAN $thn_ang</b></td>
            </tr>
            
			</table>
			<table style='border-collapse:collapse; border-color: black;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1' >
            <thead> 
			<tr>
                <td align='center' bgcolor='#CCCCCC' width='5%' style='font-weight:bold;'>NOMOR</td>                 
                <td align='center' bgcolor='#CCCCCC' width='30%' style='font-weight:bold'>SKPD</td>
                <td align='center' bgcolor='#CCCCCC' width='15%' style='font-weight:bold'>SP2D</td>
				<td align='center' bgcolor='#CCCCCC' width='10%' style='font-weight:bold'>TANGGAL TERBIT</td>
                <td align='center' bgcolor='#CCCCCC' width='10%' style='font-weight:bold'>TANGGAL ADVICE</td>
                <td align='center' bgcolor='#CCCCCC' width='30%' style='font-weight:bold'>STATUS ANTRIAN</td>
            </tr>
            <tr>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>1</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>2</td>
				<td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>3</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>4</td>
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>5</td>                
                <td align='center' bgcolor='#CCCCCC' style='border-top:solid 1px black'>6</td>
            </tr>
			<tr>
				<td></td>
                <td>Tanggal : $tanggalsbl</td>
				<td></td>
                <td></td>                
                <td></td>
                <td></td>
			</tr>
            <tr>
				<td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
				<td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
                <td style='border-top:hidden;'>&nbsp;</td>
			</tr>
			</thead>";
		
        
		$sql2 = "
                SELECT c.nm_skpd,c.no_sp2d,convert(varchar(20), c.tgl_sp2d,105) tgl_sp2d,c.tgl_uji,case when c.uji='1' then 'SP2D Telah Advice, Belum Cair' else 'SP2D Terbit, Belum Advice' end as hasil from(
                select a.nm_skpd,a.no_sp2d,a.tgl_sp2d,
                (select convert(varchar(20), tgl_uji,105) from TRDUJI where no_sp2d=a.no_sp2d) tgl_uji, 
                (select COUNT(no_sp2d) from TRDUJI where no_sp2d=a.no_sp2d) uji 
                from trhsp2d a where a.no_kas_bud is null and a.sp2d_batal is null
                )c order by c.uji,c.tgl_sp2d";
		$hasil2 = $this->db->query($sql2);
		$i=0;
		foreach ($hasil2->result() as $row){
			
            $i=$i+1;
            $init1 = $row->nm_skpd;
			$init2 = $row->no_sp2d;			
            $init3 = $row->tgl_sp2d;            
			$init4 = $row->tgl_uji;
			$init5 = $row->hasil;		
			
			$cRet .= '
			
			<tr>
				<TD style="border-top:hidden;" align="center">'.$i.'</TD>
                <TD style="border-top:hidden;" align="left">'.$init1.'</TD>				
                <TD style="border-top:hidden;" align="left">'.$init2.'</TD>
				<TD style="border-top:hidden;" align="center">'.$init3.'</TD>                
				<TD style="border-top:hidden;" align="center">'.$init4.'</TD>
				<TD style="border-top:hidden;" align="left">'.$init5.'</TD>				                
			</tr>';
		}	
			
			$cRet .='</table>';	
		
		
			 $data['prev']= $cRet;    
			 echo ("<title>DAFTAR ANTRIAN SP2D CAIR</title>");
			 echo $cRet;
			
	}

   function sp2d(){
		$lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789','/',$this->uri->segment(3));
        $jud = str_replace('123456789','_',$this->uri->segment(3));
        $lcttd = str_replace('abc',' ',$this->uri->segment(5));
	    $banyak = $this->uri->segment(6);
		$jns_cetak = $this->uri->segment(7);
        $a ='*'.$lcnosp2d.'*';
        $csql = "SELECT a.*,
				(SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
				(SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
				(SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
		$lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $n          = $trh->nilai;
		$pimpinan	= $trh->pimpinan;
        $nmrekan	=$trh->nmrekan;
		$jns_bbn	=$trh->jenis_beban;
		$jns		=$trh->jns_spp;
        $bank=$trh->bank;
		$banyak_kar = strlen($lcperlu);
        $tanggal    = $this->support->tanggal_format_indonesia($tgl);
		//$banyak = $banyak_kar > 400 ? 14 :23;                     

        
		$sqlrek="SELECT bank,rekening, npwp FROM ms_skpd WHERE kd_skpd = '$lckd_skpd' ";
                 $sqlrek=$this->db->query($sqlrek);
                 foreach ($sqlrek->result() as $rowrek)
                {
                    $bank_ben=$rowrek->bank;                    
                    $rekben=$rowrek->rekening;                    
                    $npwp_ben= $rowrek->npwp;
                }
			$rek_ben = empty($rekben) || $rekben == 0 ? '' :$rekben;
			$npwp_ben = empty($npwp_ben) || $npwp_ben == 0 ? '' :$npwp_ben;
			$nama_bank = empty($bank) ? 'Belum Pilih Bank' :$this->rka_model->get_nama($bank,'nama','ms_bank','kode');		
			$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
                
                $cekbp = substr($lckdskpd,18,4);
                if($cekbp=='0000'){
          $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BK' ";
        }else{
          $sqlnam="SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BPP' ";
        }
                 $sqlnam=$this->db->query($sqlnam);
                 foreach ($sqlnam->result() as $rownam)
                {
                    $nama_ben=$rownam->nama;                    
                    $jabat_ben=$rownam->jabatan;                    
                }
		$nama_ben = empty($nama_ben) ? 'Belum Ada data Bendahara' :$nama_ben;
		$jabat_ben = empty($jabat_ben) ? ' ' :$jabat_ben;
        
		if (($jns == '1') or ($jns == '2')  or ($jns == '4') or ($jns == '5') or ($jns == '7')){
		$kd_kegi = '';                    
		$nm_kegi = ''; 
		$kd_prog = ''; 
		$nm_prog = '';	
		}
		else {
		$sql12="SELECT kd_sub_kegiatan FROM trdspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd 
				WHERE b.kd_skpd = '$lckdskpd' AND no_sp2d='$lcnosp2d' group by kd_sub_kegiatan ";
                 $sqlrek12=$this->db->query($sql12);
                 foreach ($sqlrek12->result() as $rowrek)
                {
                    $kd_kegi=$rowrek->kd_sub_kegiatan;                    
                }
		$nm_kegi = " - ".$this->rka_model->get_nama($kd_kegi,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan') ; 
		$kd_prog = $this->support->left($kd_kegi,7); 
		$nm_prog = " - ".$this->rka_model->get_nama($kd_prog,'nm_program','trskpd','kd_program'); 
		}
		
		if($jns_cetak=='2'){
			$tinggi='150px';
			//$banyak=9;
			$banyak=10;
		} else 
		if($jns_cetak=='1'){
			$tinggi='80px';
			//$banyak=15;
			$banyak=16;
		}else{
			$tinggi='10px';
			$banyak=$banyak;
		}		
		
		$cRet = '';
		$cRet .= "
		<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px;'  width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>";
        $cRet .="
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:16px'> PEMERINTAH KOTA PONTIANAK
            </td> 
        </tr>
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:18px'> BADAN KEUANGAN DAERAH (BKD)
            </td> 
        </tr>		
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:11px'> Jalan Letnan Jendral Sutoyo. Telp / Fax (0561) 732509 / 741641 <br/> Kota Pontianak - 81147
            </td> 
        </tr>
		</table><br/>
		
		";
        $cRet .= "
		<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px;'  width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>";
        $cRet .="
		<tr>
            <td align='center' width='50%' style='border-collapse:collapse;font-weight:bold; font-size:12px'> PEMERINTAH KOTA PONTIANAK
            </td>
            <td align='center' width='50%'>
                <table style='border-collapse:collapse;font-size:12px; font-weight: bold;' width='100%' align='center' cellspacing='4' cellpadding='0'>
                    <tr>
                        <td align='right'>
                            <b>Nomor : $lcnosp2d</b>
                        </td>
                    </tr>
                    <tr>
                        <td align='center'>
                            SURAT PERINTAH PENCAIRAN DANA<br>(SP2D)
                        </td>
                    </tr>
                </table>
            </td>
        </tr>   
        <tr>
            <td style='border-left:solid 1px black;' >
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' valign='top' border='1' cellspacing='4' cellpadding='0'>
      					<tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='30%' align='left' valign='top'>&nbsp;Nomor SPM</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='2%' valign='top'>:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' width='69%' valign='top'>$lcnospm</td>
                    </tr>
                    <tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>&nbsp;Tanggal</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top' >:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>".$this->support->tanggal_format_indonesia($ldtglspm)."</td>
                    </tr>
                    <tr>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>&nbsp;SKPD</td>
                        <td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top'>:</td>
						<td style='border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;' valign='top' height='35px'>$lckd_skpd $lcnmskpd</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%'  valign='top' border='0' cellspacing='4' cellpadding='0'>
                    <tr>
                        <td valign='top'>&nbsp;Dari &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Bendahara Umum Daerah (BUD)</td>
                    </tr>
					 <tr>
                        <td valign='top' >&nbsp;Tahun Anggaran : &nbsp;$lntahunang</td>
                    </tr>
					<tr>
                        <td valign='top' >&nbsp;</td>
                    </tr>
					<tr>
                        <td valign='top' >&nbsp;</td>
                    </tr>					
                </table>
            </td>
        </tr>
			<tr>
		<td colspan='2'>
			<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='1' cellspacing='4' cellpadding='0'>
			<tr>
				<td style='border-bottom: hidden; border-right: hidden;' width='120px'>&nbsp;Bank/Pos</td>
                <td style='border-bottom: hidden; border-right: hidden;' width='10px' align='left'>:</td>
				<td style='border-bottom: hidden;' >PT. Bank Kalbar Cabang Utama Pontianak</td>
			</tr>
			<tr>
				<td style='border-bottom: hidden;' colspan='3' >&nbsp;Hendaklah mencairkan / memindahbukukan dari baki Rekening Nomor 100.100.283.0</td>
			</tr>
			<tr>
				<td style='border-bottom: hidden; border-right: hidden;' >&nbsp;Uang sebesar Rp</td>
                <td width='1' style='border-bottom: hidden; border-right: hidden;' width='10px' align='left'>:</td>
				<td style='border-bottom: hidden;' >".number_format($n,'2',',','.')."  , (".$this->tukd_model->terbilang($n).") </td>
			</tr>
			</table>
        </td>
		</tr>	
        <tr>
            <td colspan='2'>";
		if(($jns==6) && ($jns_bbn==3)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$kd_prog."$nm_prog
					<br>".$this->support->right($kd_kegi,3)."$nm_kegi
					</td>

				</tr>
                </table> ";
		}
		else
		if(($jns==6) && ($jns_bbn==2)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$kd_prog."$nm_prog
					<br>".$kd_kegi."$nm_kegi
					</td>

				</tr>
                </table> ";
		}else
		if(($jns==4) && ($jns_bbn==9)){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$kd_prog."$nm_prog
					<br>".$kd_kegi."$nm_kegi
					</td>

				</tr>
                </table> ";
		}	
		else if($jns==5){

             $cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada</td>
					<td valign='top' width='10px' >:</td>
                    <td valign='top' >$pimpinan $nmrekan ($lcnmskpd)</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                    <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top'>&nbsp;Bank/Pos</td>
					<td valign='top'>:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$kd_prog."$nm_prog
					<br>".$kd_kegi."$nm_kegi
					</td>

				</tr>
                </table> ";
		}else{
			
			$cRet .="<table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			   <tr>
                    <td valign='top' width='120px'>&nbsp;Kepada </td>
					<td valign='top' width='10px'>:&nbsp;</td>
                    <td valign='top' font-family: Arial; >$nama_ben - $jabat_ben ($lcnmskpd)</td>
					</tr>
                <tr>
                    <td valign='top' >&nbsp;NPWP</td>
					<td valign='top' >:</td>
                    <td valign='top' >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;No.Rekening Bank</td>
					<td valign='top' >:</td>
                     <td valign='top' >$rekbank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Bank/Pos</td>
					<td valign='top' >:</td>
                    <td valign='top'>$nama_bank</td>
                </tr>
                <tr>
                    <td valign='top' >&nbsp;Untuk Keperluan</td>
					<td valign='top' >:</td>
                    <td height='$tinggi' valign='top' style='border-collapse:collapse;font-family: Tahoma;font-size:12px' >$lcperlu
					<br>".$kd_prog."$nm_prog
					<br>".$kd_kegi."$nm_kegi
					</td>
				</tr>
                </table> ";
			
		}
         $cRet	.="  </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:11px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr >
                        <td width='5%' align='center'><b>NO</b></td>
                        <td width='28%' align='center'><b>KODE REKENING</b></td>
                        <td align='center'><b>URAIAN</b></td>
                        <td width='15%' align='center'><b>JUMLAH (Rp)</b></td>
                    </tr>
					<tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                    </tr>";
			$sql_total="SELECT sum(nilai)total FROM trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
                 $sql_x=$this->db->query($sql_total);
                 foreach ($sql_x->result() as $row_x)
                {
                    $lntotal=$row_x->total;                    
                }
				if(($jns==1) || ($jns==2) || ($jns==7)){
                                        $sql = "SELECT SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
                                        $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {
                                           $lcno = $lcno + 1;
                                           $lntotal = $lntotal + $row->nilai;
										    $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;1</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $lckd_skpd  </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $lcnmskpd</td>
                                                        <td style='border-bottom: hidden;' align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                                    </tr>"; 
                                          
										}  
											if($lcno<=$banyak)
											   {
												 for ($i = $lcno; $i <= $banyak; $i++) 
												  {
													 $cRet .="<tr>
                                                        <td style='border-top: hidden;' align='center'>&nbsp;</td>
                                                        <td style='border-top: hidden;' ></td>
                                                        <td style='border-top: hidden;'></td>
                                                        <td style='border-top: hidden;' align='right'></td>
                                                    </tr>";    
												  }                                                   
											   } 
										   }
				else{
				
				
				$sql1 = "SELECT COUNT(*) as jumlah from 
							(select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' 
							group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,8)kd_rek,nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek5 b on left(kd_rek6,8)=kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,8),nm_rek5,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(a.kd_rek6,12)kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek6 b on left(a.kd_rek6,12)=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(a.kd_rek6,12),b.nm_rek6,kd_sub_kegiatan
							) tox";
						$hasil1 = $this->db->query($sql1);
						$row1 = $hasil1->row();
						$jumlahbaris = $row1->jumlah;  
						if($jumlahbaris<$banyak){
							$sql = "SELECT * from (select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(kd_rek6,8)kd_rek,nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek5 b on left(kd_rek6,8)=kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,8),nm_rek5,kd_sub_kegiatan
							union all
							select kd_sub_kegiatan,left(a.kd_rek6,12)kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek6 b on left(a.kd_rek6,12)=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(a.kd_rek6,12),b.nm_rek6,kd_sub_kegiatan
							) tox order by kd_sub_kegiatan, kd_rek";
						}else{
							$sql = "SELECT * from 
							(select '1' urut, kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
							union all
							select '2' urut, kd_sub_kegiatan,left(kd_rek6,3)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
							ms_rek3 b on left(kd_rek6,3)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
							group by left(kd_rek6,3),nm_rek3,kd_sub_kegiatan
							union all
							select '3' as urut, '' as kd_sub_kegiatan, '' kd_rek, '(Rincian Terlampir)' as nm_rek, 0 as nilai
							) tox order by urut,kd_rek";	
						}
                                        $hasil = $this->db->query($sql);
                                        $lcno = 0;
										$lcno_baris = 0;
                                       // $lntotal = 0;
                                        foreach ($hasil->result() as $row)
                                        {	
											$lcno_baris = $lcno_baris + 1;										
											if (strlen($row->kd_rek)>=12){
											$lcno = $lcno + 1;
											$lcno_x = $lcno;
											}
											else {
												$lcno_x ='';
											}
//                                           $lntotal = $lntotal + $row->nilai;                     
                                            $ceknama = $row->nm_rek;                      
                                            if($ceknama!='(Rincian Terlampir)'){                                                
                                           $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;$lcno_x</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->kd_sub_kegiatan.".$this->support->dotrek($row->kd_rek)." </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->nm_rek</td>
                                                        <td style='border-bottom: hidden;' align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                                    </tr>";
                                            }else{
                                                
                                           $cRet .="<tr>
                                                        <td style='border-bottom: hidden;' align='center'>&nbsp;$lcno_x</td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->kd_kegiatan.".$this->tukd_model->dotrek($row->kd_rek)." </td>
                                                        <td style='border-bottom: hidden;'>&nbsp; $row->nm_rek</td>
                                                        <td style='border-bottom: hidden;' align='right'></td>
                                                    </tr>";   
                                            }    
                                            
                                        }
                                        if($lcno_baris<=$banyak)
                                       {
                                       	if($banyak>=8){
                                       		$banyak=$banyak-5;
                                       	}
                                         for ($i = $lcno_baris; $i <= $banyak; $i++) 
                                          {
                                            $cRet .="<tr>
                                                        <td style='border-top: hidden;' align='center'>&nbsp;</td>
                                                        <td style='border-top: hidden;' ></td>
                                                        <td style='border-top: hidden;'></td>
                                                        <td style='border-top: hidden;' align='right'></td>
                                                    </tr>";    
                                          }                                                   
                                       }
                                       
				}     
             $cRet .="<tr>
                        <td align='right' colspan='3'>&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align='right'><b>".number_format($lntotal,"2",",",".")."</b>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='4'>&nbsp;Potongan-potongan</td>
                    </tr>
                    <tr>
                        <td  align='center'><b>NO</b></td>
                        <td  align='center'><b>Uraian (No.Rekening)</b></td>
                        <td  align='center'><b>Jumlah(Rp)</b></td>
                        <td  align='center'><b>Keterangan</b></td>
                    </tr>";
                    
                    $sql = "select * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('4140611','2110501','2110701','2110801','2110901','4140612')";
                            $hasil = $this->db->query($sql);
                            $lcno = 0;
                            $lntotalpot = 0;
                            foreach ($hasil->result() as $row){
                               $lcno = $lcno + 1;
                               $lntotalpot = $lntotalpot + $row->nilai;
                                $cRet .="<tr>
                                            <td align='center'>&nbsp;$lcno</td>
                                            <td>&nbsp; $row->nm_rek5</td>
                                            <td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";    
                            }
							if($lcno<=3)
                                       {
                                         for ($i = $lcno; $i < 3; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
                                        
                    $cRet .="
                    <tr>
                        <td>&nbsp;</td>
                        <td align='right'><b>Jumlah</b>&nbsp;</td>
                        <td align='right'><b>".number_format($lntotalpot,"2",",",".")."</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     <tr>
                        <td colspan='4'>&nbsp;Informasi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(tidak mengurangi jumlah pembayaran SP2D)</i></td>
                    </tr>
            
                    <tr>
                        <td align='center'><b>NO</b></td>
                        <td align='center'><b>Uraian (No.Rekening)</b></td>
                        <td align='center'><b>Jumlah(Rp)</b></td>
                        <td align='center'><b>Keterangan</b></td>
                    </tr>";
                     $sql = "SELECT 1 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('2130301')
							UNION ALL
							select 2 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 NOT IN('4140611','2130301','2110501','2110701','2110801','2110901','4140612')
							ORDER BY urut,kd_rek6";
                            $hasil = $this->db->query($sql);
                            $lcno = 0;
                            $lntotalpott = 0;
                            foreach ($hasil->result() as $row)
                            {
                               $lcno = $lcno + 1;
                               $lntotalpott = $lntotalpott + $row->nilai;
                               $kode_rek=$row->kd_rek6;
							   if($kode_rek=='2130101'){
								   $nama_rek='PPh 21';
							   } else if ($kode_rek=='2130201'){
								   $nama_rek='PPh 22';
							   } else if($kode_rek=='2130301'){
								   $nama_rek='PPN';
							   } else if($kode_rek=='2130401'){
								   $nama_rek='PPh 23';
							   } else if($kode_rek=='2130501'){
								   $nama_rek='PPh Pasal 4 ayat 2';
							   } else{
								    $nama_rek=$row->nm_rek6;
							   }
							   $cRet .="<tr>
                                            <td align='center'>&nbsp;$lcno</td>
                                            <td> &nbsp; $nama_rek</td>
                                            <td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";    
                            }
							if($lcno<=4)
                                       {
                                         for ($i = $lcno; $i < 4; $i++) 
                                          {
                                            $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";    
                                          }                                                   
                                       }
							
							
							
							$jum_bayar=strval($lntotal-$lntotalpot);
							$bil_bayar = strval($lntotal-($lntotalpot+$lntotalpott));
                    $cRet .="
                    <tr>
                        <td>&nbsp;</td>
                        <td align='right'><b>Jumlah</b>&nbsp;</td>
                        <td align='right'><b>".number_format($lntotalpott,"2",",",".")."</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     
                </table>  
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:12px' width='100%' align='center' border='1' cellspacing='-1' cellpadding='-1'>
                   <tr>
                        <td colspan='4' valign='bottom' style='font-weight: bold;'>&nbsp;SP2D yang Dibayarkan</td>
                    </tr>
				   <tr>
				   
                        <td width='28%' align='left'>&nbsp;<b>Jumlah yang Diterima</b></td>
                        <td style='border-left: hidden;' width='4%' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden; font-size:12px;' width='50%' align='right'><b>&nbsp;".number_format($lntotal,"2",",",".")."</b></td>
						<td style='border-left: hidden;' width='20%' align='center'>&nbsp;</td>
						</tr>
                    <tr > 
                        <td align='left'>&nbsp;<b>Jumlah Potongan</b></td>
                        <td style='border-left: hidden;' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden; font-size:12px;' align='right' ><b>&nbsp;".number_format($lntotalpot+$lntotalpott,"2",",",".")."</b></td>
						<td style='border-left: hidden;' >&nbsp;</td>
                    </tr>
                    <tr style='font-weight: bold;'>
                        <td align='left'>&nbsp;<b>Jumlah yang Dibayarkan</b></td>
                        <td style='border-left: hidden;' align='left'><b>&nbsp;RP</b></td>
                        <td style='border-left: hidden;font-size:12px;' align='right'><b>&nbsp;".number_format($lntotal-($lntotalpot+$lntotalpott),"2",",",".")."</b></td>
						<td style='border-left: hidden;' >&nbsp;</td>
                    </tr>                    
                </table>  
            </td>
        </tr>
        
        <tr>
            <td colspan='2'>
                <table style='border-collapse:collapse;font-weight: bold;font-family: Tahoma;font-size:12px' width='100%' align='center' border='0' cellspacing='-1' cellpadding='-1'>
			<tr>
                        <td colspan='2' align='left' >&nbsp;Uang Sejumlah :
                        (&nbsp;".$this->tukd_model->terbilang($bil_bayar)."&nbsp;)</td>
						
	
                    </tr>
				<tr>
                        <td width='65%' align='left' style='font-size:10px' valign='top'>
                        <br>&nbsp;Lembar 1 : Bank Yang Ditunjukan<br>
                        &nbsp;Lembar 2 : Pengguna Anggaran/Kuasa Pengguna Anggaran<br>
                        &nbsp;Lembar 3 : Arsip Bendahara Umum Daerah (BUD)<br>
                        &nbsp;Lembar 4 : Pihak Ketiga<br>
                               
                        </td>
                        <td width='35%' align='center'>
                        <br>
                        Pontianak, $tanggal<br>
                        $jabatan
						<br>Kepala Bidang Perbendaharaan
                        <br>$pangkat
                        <br>
                        <br>
                        <br>
                        <u></u><br>
                        $nama
						<br>
                        NIP. $nip                
                        </td>
                   </tr>
                </table>  
            </td>
        </tr>
        
        
        </table>
        ";
        $data['prev']= $cRet;
        $this->master_pdf->_mpdf('',$cRet,10,5,5,'0');
    }

	function cetak_lamp_sp2d(){
		$lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789','/',$this->uri->segment(3));
        $lcttd = str_replace('abc',' ',$this->uri->segment(5));
	    $banyak = $this->uri->segment(6);
		$jns_cetak = $this->uri->segment(7);
        $a ='*'.$lcnosp2d.'*';
         $csql = "SELECT a.*,
				(SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
				(SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
				(SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
		$lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $n          = $trh->nilai;
		$pimpinan	= $trh->pimpinan;
        $nmrekan	=$trh->nmrekan;
		$jns_bbn	=$trh->jenis_beban;
		$jns		=$trh->jns_spp;
        $bank=$trh->bank;
		$banyak_kar = strlen($lcperlu);
        $tanggal    = $this->support->tanggal_format_indonesia($tgl);
		//$banyak = $banyak_kar > 400 ? 14 :23;

		$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
					$pangkat=$rowttd->pangkat;
                }
				
		 $cRet ="";
		 $cRet	.="<br><br><br><br><br><br><br>  
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:13px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr >
                        <td width='100%' align='center'><b>Daftar Lampiran SP2D Nomor: $lcnosp2d </b></td>
                    </tr>
					<tr>
                        <td align='center'><b>Tanggal : $tanggal</b></td>
                    </tr>
					<tr>
                        <td align='center'>&nbsp;</td>
                    </tr>
				</table>";
         $cRet	.="  
                <table style='border-collapse:collapse;font-family: Tahoma;font-size:11px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr >
                        <td width='5%' align='center'><b>NO</b></td>
                        <td width='28%' align='center'><b>KODE REKENING</b></td>
                        <td align='center'><b>URAIAN</b></td>
                        <td width='15%' align='center'><b>JUMLAH (Rp)</b></td>
                    </tr>
					<tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                    </tr>";
		
			$sql = "SELECT * from 
			(select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
			ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
			group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
			union all
			select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
			ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
			group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
			union all
			select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
			ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
			group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
			union all
			select kd_sub_kegiatan,left(a.kd_rek6,8)kd_rek,b.nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a inner join 
			ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
			group by left(a.kd_rek6,8),b.nm_rek5,kd_sub_kegiatan
			union all
			select kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
			ms_rek6 b on a.kd_rek6 =b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
			group by a.kd_rek6 ,b.nm_rek6,kd_sub_kegiatan
			) tox order by kd_rek";
		
			$hasil = $this->db->query($sql);
			$lcno = 0;
			$lcno_baris = 0;
			$lntotal = 0;
			foreach ($hasil->result() as $row)
		   {	
				$lcno_baris = $lcno_baris + 1;										
				if (strlen($row->kd_rek)>=12){
				$lcno = $lcno + 1;
				$lcno_x = $lcno;
				$lntotal = $lntotal+$row->nilai;
				}
				else {
					$lcno_x ='';
				}
//                                           $lntotal = $lntotal + $row->nilai;                                           
			   $cRet .="<tr>
							<td align='center'>&nbsp;$lcno_x</td>
							<td >&nbsp; $row->kd_sub_kegiatan.".$this->support->dotrek($row->kd_rek)." </td>
							<td >&nbsp; $row->nm_rek</td>
							<td align='right'>".number_format($row->nilai,"2",",",".")."&nbsp;</td>
						</tr>";    
			}
             $cRet .="<tr>
                        <td align='right' colspan='3'>&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align='right'><b>".number_format($lntotal,"2",",",".")."</b>&nbsp;</td>
                    </tr>
        </table>";
		 $cRet .="<table style='border-collapse:collapse; font-family:Tahoma; font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
					</tr>
                    <tr>
					<td width='50%' align='center'></td>
                    <td width='50%' align='center'>Pontianak, $tanggal</td>
					</tr>
                    <tr>
					<td width='50%' align='center'></td>
                    <td width='50%' align='center'>$jabatan<br>Kepala Bidang Perbendaharaan</td>
					</tr>
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>$pangkat</td>
					</tr>                              
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
					</tr>                                       
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>&nbsp;</td>
					</tr>
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'><b><u>$nama</u></b></td>
					</tr>
                    <tr>
					<td width='50%' align='center'>&nbsp;</td>
                    <td width='50%' align='center'>NIP. $nip</td>
					</tr>
                    
                  </table>";
        $data['prev']= $cRet;
				$this->master_pdf->_mpdf('',$cRet,10,5,5,'0');
    }

    function cetak_register_persp2d($dcetak='',$ttd='',$skpd='',$tstatus='',$dcetak2='',$cetak=1,$jenis='',$urut='', $pilihan=''){ //Tox
	$print = $cetak;    
	 $tahun  = $this->session->userdata('pcThang');
    $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
                
        $kd ='';
        $a ='';
        $nama ='';
		$where2='';	
             switch ($tstatus){
                        case '1': //SP2D Keluar
						$where2='';
                        $ket1='BERDASARKAN SP2D TERBIT';
                            break;
                        case '2': //SP2D lunas
							$where2=" and status_bud=1 ";
                            $ket1='BERDASARKAN SP2D LUNAS';
                            break;
                        case '3': //SP2D Advice
                            $where2=" and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) ";
                            $ket1='BERDASARKAN SP2D ADVICE';
                            break;
                        case '4': //SP2D belum Bayar
                           $where2=" and no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) and status_bud <> 1 ";
                           $ket1='BERDASARKAN SP2D BELUM CAIR';
                            break;                       
					    case '5': //Belum Advice
                           $where2=" and no_sp2d NOT IN (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) ";
                           $ket1='BERDASARKAN SP2D BELUM ADVICE';
                            break;     
                        case '6': //SP2D batal
							$where2=" and b.sp2d_batal=1";
                            $ket1='BERDASARKAN SP2D YANG DIBATALKAN';
                            break;                      
                    }       
                    
			 switch ($jenis){
                        case '1': //BL
						$where4="AND b.jns_spp in ('6')";
                        $ket2 = "BELANJA LANGSUNG";
                            break;
                        case '2': //BTL
							$where4=" AND b.jns_spp in ('4','5')";
                            $ket2 = "BELANJA TIDAK LANGSUNG";
                            break;
                        case '3': //gaji
                            $where4=" AND b.jns_spp='4' and b.jenis_beban in ('1','2') and d.kd_rek5 in ('5110101','5110102','5110103','5110104','5110105','5110106','5110107','5110108','5110110','5110111','5110112','5110113','5110114','5110115')";
                            $ket2 = "BELANJA TIDAK LANGSUNG (GAJI)";
                            break;
                        case '4': //non gaji
                            $where4=" AND a.jns_spp in ('4','5') and a.jenis_beban in ('9','2','3') and d.kd_rek5 not in ('5110101','5110102','5110103','5110104','5110105','5110106','5110107','5110108','5110110','5110111','5110112','5110113','5110114','5110115')";
                            $ket2 = "BELANJA TIDAK LANGSUNG (NON GAJI)";
                            break;
                        case '5': //non gaji
                            $where4=" AND b.jns_spp in ('1','2','3','4','5','6')";
                            $ket2 = "KESELURUHAN";
                            break;
                       case '6': //GAJI KHUSUS
                            $where4=" AND b.jns_spp in ('4','5') AND d.kd_rek5 IN ('5110116','5110101','5110102','5110103','5110104','5110105','5110106','5110107','5110108')";
                            $ket2 = "GAJI KHUSUS";
                            break;
                        case '7': //UP
						$where4="AND b.jns_spp in ('1','2')";
                        $ket2 = "UP/GU";
                            break;
                        case '7': //TU
							$where4=" AND b.jns_spp in ('3')";
                            $ket2 = "TU";
                            break;    
                        }
					  
			switch ($pilihan){
                        case '1': //SEMUA
							$where3 ="";
                            $ket3 ="PER JANUARI S/D DESEMBER";                            
                            break;
                        case '2': //BULAN
                           $where3=" and MONTH(tgl_sp2d)='$dcetak' ";
                           $nm_bulanawal = $this->support->getBulan($dcetak);
                           $ket3 ="PER BULAN ".$nm_bulanawal;
                            break;
						case '3': //PERIODE
                           $where3= " and ( tgl_sp2d between '$dcetak' and '$dcetak2') ";
                           $n_tglawal1   = $this->support->tanggal_format_indonesia($dcetak);
		                   $n_tglawal2   = $this->support->tanggal_format_indonesia($dcetak2);                                                      
                           $ket3 ="PER ".$n_tglawal1." S/D ".$n_tglawal2;
                            break;
                      }		
						   
			switch ($urut){
							case '1': //BL
							$order="ORDER BY cast(b.urut as int)";
								break;
							case '2': //BTL
							$order="ORDER BY cast(b.urut as int)";							
								break;
                            case '3': //BL
							$order="ORDER BY cast(b.urut as int)";
								break;
							case '4': //BL
							$order="ORDER BY cast(b.urut as int)";
								break;
							    
						  }
            
            $ket4="";
            $init = $skpd;
            if ($init <> '--'){                               
                $sk = $this->db->query("select nm_skpd from ms_skpd where kd_skpd='$init'")->row();
                $ket4 = $sk->nm_skpd;                        
            }              
                          
		
        $cRet = "<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>";
        $cRet .="<thead>
        <tr>
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px white;' colspan='10'><b>PEMERINTAH ".strtoupper($kab)."</b></td>            
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px white;' colspan='10'><b>REGISTER SP2D TAHUN ANGGARAN $tahun</b></td>
        </tr>
        <tr>            
            <td align='center' style='font-size:14px;border: solid 1px white;border-bottom:solid 1px black;' colspan='10'><b> $ket2 $ket3 <br/> $ket1<br/>$ket4</b></td>
        </tr>
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='3'><b>No.<br>Urut</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='40%' colspan='9'><b>SP2D</td>
        </tr>  
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='2'><b>TANGGAL</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='2'><b>NAMA SKPD</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' rowspan='2'><b>No. SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='10%' rowspan='2'><b>URAIAN</b>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' rowspan='2'><b>UP</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' rowspan='2'><b>GU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' rowspan='2'><b>TU</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='10%' colspan='2'><b>LS</b></td>
        </tr>  
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' ><b>GAJI/ BTL</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='5%' ><b>Barang <br>& Jasa</b></td>           
        </tr>
         
          </thead>
          <tr>
            <td style='font-size:10px' align='center' ><b>1</b></td>
            <td style='font-size:10px' align='center' ><b>2</b></td>
            <td style='font-size:10px' align='center' ><b>3</b></td>
            <td style='font-size:10px' align='center' ><b>4</b></td>
            <td style='font-size:10px' align='center' ><b>5</b></td>
            <td style='font-size:10px' align='center' ><b>6</b></td>
            <td style='font-size:10px' align='center' ><b>7</b></td>
            <td style='font-size:10px' align='center' ><b>8</b></td>
            <td style='font-size:10px' align='center' ><b>9</b></td>
            <td style='font-size:10px' align='center' ><b>10</b></td>
                      </tr>";
        //$skpd = $this->uri->segment(3); 
        $kriteria = '';
        $kriteria = $skpd;
        $where ="where (b.sp2d_batal IS NULL  OR b.sp2d_batal !=1) ";
        if ($kriteria <> '--'){                               
            $where=" where  (b.sp2d_batal IS NULL OR b.sp2d_batal !=1) and left(a.kd_skpd,7) =left('$kriteria',7) ";            
        }       
   /*           
        $sql = "SELECT sum(d.nilai),a.no_spm,a.kd_skpd,a.tgl_spm,b.tgl_sp2d,b.no_sp2d,b.keperluan,
				(case when a.jns_spp=1 then b.nilai else 0  end)up,
        (case when a.jns_spp=2 then b.nilai else 0  end)gu,
				(case when a.jns_spp=3 then b.nilai else 0  end)tu,
				(case when a.jns_spp in (4,5) then b.nilai else 0  end)gaji,
				(case when a.jns_spp=6 then b.nilai else 0  end)ls
				 FROM TRHSPM a inner JOIN TRHSP2D b ON a.no_spm=b.no_spm 
				  inner join TRHSPp c on a.no_spp=c.no_spp
          inner join TRDSPP d on c.no_spp=d.no_spp and a.kd_skpd=b.kd_skpd
          $where $where2 $where4 $where3
          group by a.no_spm,a.kd_skpd,a.tgl_spm,b.tgl_sp2d,b.no_sp2d,b.keperluan,a.jns_spp,b.nilai,b.urut
				  $order";
 */                 
        $sql = "SELECT a.no_spm,a.nm_skpd,left(a.kd_skpd,17) kd_skpd,a.tgl_spm,b.tgl_sp2d,b.no_sp2d,b.keperluan,
				(case when a.jns_spp=1 then b.nilai else 0  end)up,
				(case when a.jns_spp=2 then b.nilai else 0  end)gu,
				(case when a.jns_spp=3 then b.nilai else 0  end)tu,
				(case when a.jns_spp in (4,5) then b.nilai else 0  end)gaji,
				(case when a.jns_spp=6 then b.nilai else 0  end)ls
				 FROM TRHSPM a inner JOIN TRHSP2D b ON a.no_spm=b.no_spm 
				  inner join TRHSPP c on a.no_spp=c.no_spp
          inner join TRDSPP d on c.no_spp=d.no_spp and left(a.kd_skpd,17)=left(b.kd_skpd,17)
          $where $where2 $where4 $where3
          group by a.no_spm,left(a.kd_skpd,17),a.tgl_spm,b.tgl_sp2d,b.no_sp2d,b.keperluan,a.jns_spp,b.nilai,b.urut,a.nm_skpd
          $order ";
          
                $hasil = $this->db->query($sql);
                $lcno = 0;
				 $t_up= 0;
                    $t_gu=0;
                    $t_tu=0;
                    $t_gaji=0;
                    $t_ls=0;
                foreach ($hasil->result() as $row)
                {
                    $spm= $row->no_spm;
                    $tgl_spm=$row->tgl_spm;
                    $sp2d= $row->no_sp2d;
					$nm_spk=$row->nm_skpd;
                    $tgl_sp2d=$row->tgl_sp2d;
                    $kkeperluan= $row->keperluan;
                    $n_up= $row->up;
                    $n_gu= $row->gu;
                    $n_tu= $row->tu;
                    $n_gaji= $row->gaji;
                    $n_ls= $row->ls;
                    $t_up= $t_up+$n_up;
                    $t_gu= $t_gu+$n_gu;
                    $t_tu= $t_tu+$n_tu;
                    $t_gaji=$t_gaji+$n_gaji;
                    $t_ls= $t_ls+$n_ls;
                                        
                    $lcno = $lcno + 1;
                    
                    $cRet .=  "<tr>
                                <td align='center' style='font-size:12px'>$lcno</td>
                                <td align='center' style='font-size:12px'>".$this->support->tanggal_format_indonesia($tgl_sp2d)."</td>
                                <td align='left' style='font-size:12px'>$nm_spk</td>
                                <td align='center' style='font-size:12px'>$sp2d</td>
                                <td align='left' style='font-size:12px'>$kkeperluan</td>
                                <td align='right' style='font-size:12px'>".number_format($n_up,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($n_gu,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($n_tu,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($n_gaji,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($n_ls,"2",",",".")."</td>
                              </tr>  "; 
                   
                }

                    $cRet .=  "<tr  style='font-size:12px;font-weight:bold;'>
                                <td align='center' style='font-size:12px' colspan=5> Jumlah</td>
                                <td align='right' style='font-size:12px'>".number_format($t_up,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($t_gu,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($t_tu,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($t_gaji,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'>".number_format($t_ls,"2",",",".")."</td>
                              </tr>  "; 
                   
  
				
        $cRet .="</table>";
		
		$nip = str_replace('a',' ',$ttd);
		 $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip' AND kode='BUD'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
		$tanggal_ttd = date('d-m-Y');		
		$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$jabatan.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$nip.'</TD>
					</TR>
					</TABLE><br/>';
		
        $data['prev']= $cRet;    
		if ($print==1){
				$this->rka_model->_mpdf_folio('',$cRet,10,10,10,'1'); 

		} else if($print=="2"){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Register_SP2D.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         }else{
		echo ("<title>REGISTER SP2D</title>");
  	    echo $cRet;
		}
		//echo("$cRet");   
    }


	function cetak_reg_efisiensi_sp2d($dcetak='',$ttd='',$skpd='',$tstatus='',$dcetak2='',$cetak=1, $pilihan=''){ //Tox
	$print = $cetak;   
    
    $printx =  $this->uri->segment(8); 
    $tahun  = $this->session->userdata('pcThang');
    $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
                
        $kd ='';
        $a ='';
        $nama ='';
		$where2='';	
               
                    
			 
					  
			switch ($pilihan){
                        case '1': //SEMUA
							$where3 ="";
                            $ket3 ="PER JANUARI S/D DESEMBER";                            
                            break;
                        case '2': //BULAN
                           $where3=" and MONTH(tgl_sp2d)='$dcetak' ";
                           $nm_bulanawal = $this->support->getBulan($dcetak);
                           $ket3 ="PER BULAN ".$nm_bulanawal;
                            break;
						case '3': //PERIODE
                           $where3= " and ( tgl_sp2d between '$dcetak' and '$dcetak2') ";
                           $n_tglawal1   = $this->support->tanggal_format_indonesia($dcetak);
		                   $n_tglawal2   = $this->support->tanggal_format_indonesia($dcetak2);                                                      
                           $ket3 ="PER ".$n_tglawal1." S/D ".$n_tglawal2;
                            break;
                      }		
						   
		
            
            $ket4="";
            $init = $skpd;
            if ($init <> '--'){                               
                $sk = $this->db->query("select nm_skpd from ms_skpd where kd_skpd='$init'")->row();
                $ket4 = $sk->nm_skpd;                        
            }              
                          
		
        $cRet = "
		<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
				<tr>
					<td align='center' colspan='10' style='font-size:16px;border: solid 1px white;'><b>REGISTER EFISIENSI SP2D<br>$ket3<br></b></td>
				</tr>
		</table>
		<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>";
        $cRet .="
		
		<thead>
         
        <tr>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='3%' ><b>NO.</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='7%' ><b>KODE SKPD</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='20%' ><b>NAMA SKPD</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='13%' ><b>NO. SPM</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='8%' ><b>TANGGAL SPM</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='8%' ><b>TANGGAL TERIMA</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='13%' ><b>NO SP2D</b></td>
			<td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='8%' ><b>TANGGAL SP2D</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='10%' ><b>NILAI</b></td>
            <td style='font-size:10px' bgcolor='#CCCCCC' align='center' width='10%' ><b>KETERANGAN</b></td>
        </tr>  
        <tr>
            <td style='font-size:10px' align='center' ><b>1</b></td>
            <td style='font-size:10px' align='center' ><b>2</b></td>
            <td style='font-size:10px' align='center' ><b>3</b></td>
            <td style='font-size:10px' align='center' ><b>4</b></td>
            <td style='font-size:10px' align='center' ><b>5</b></td>
            <td style='font-size:10px' align='center' ><b>6</b></td>
            <td style='font-size:10px' align='center' ><b>7</b></td>
            <td style='font-size:10px' align='center' ><b>8</b></td>
            <td style='font-size:10px' align='center' ><b>9</b></td>
            <td style='font-size:10px' align='center' ><b>10</b></td>
          </tr>
         
          </thead>
          ";
        //$skpd = $this->uri->segment(3); 
        $kriteria = '';
        $kriteria = $skpd;
        $where ="where (sp2d_batal IS NULL  OR sp2d_batal !=1) ";
        if ($kriteria <> '--'){                               
            $where=" where  (sp2d_batal IS NULL  OR sp2d_batal !=1) and left(kd_skpd,17) = left('$kriteria',17) ";            
        }       
              
        $sql = "SELECT *,case when total_hari > 3 then 'TIDAK TEPAT WAKTU' ELSE 'TEPAT WAKTU' END AS ket from(
				select a.kd_skpd,a.nm_skpd,a.no_spm,a.tgl_spm,
				case when b.tgl_terima is null then a.tgl_spm else b.tgl_terima end as tgl_terima,
				a.no_sp2d,a.tgl_sp2d,a.nilai,urut,sp2d_batal,
				DateDiff (Day,b.tgl_terima,a.tgl_sp2d) as Total_Hari from trhsp2d a 
				left join config_valspm b 
				on a.no_spm = b.no_spm
				)x
				$where $where3
				order by urut";
				
                $hasil = $this->db->query($sql);
                $lcno = 0;
				$t_nil= 0;
                   
                foreach ($hasil->result() as $row)
                {
                    $kd_skpd		= $row->kd_skpd;
					          $nm_skpd		= $row->nm_skpd;
                    $no_spm			= $row->no_spm;
					          $tgl_terima		= $row->tgl_terima;
					          $tgl_spm		= $row->tgl_spm;
                    $no_sp2d		= $row->no_sp2d;
                    $tgl_sp2d		= $row->tgl_sp2d;
					          $nil_sp2d		= $row->nilai;
					          $ket			= $row->ket;
					
                    $t_nil			= $t_nil+$nil_sp2d;
                   // echo "$t_nil";
                    if($printx!=2){
                    $nilai_sp2d = $nil_sp2d;
					          $tot_nil = $t_nil;
                    }else{
                        $nilai_sp2d = $nil_sp2d;
                        $tot_nil = $t_nil;
                    }
                                        
                    $lcno = $lcno + 1;
                    
                    $cRet .=  "<tr>
                                <td align='left' style='font-size:12px'>$lcno</td>
                                <td align='center' style='font-size:12px'>$kd_skpd</td>
								<td align='left' style='font-size:12px'>$nm_skpd</td>
								<td align='left' style='font-size:12px'>$no_spm</td>
                                <td align='center' style='font-size:12px'>".$this->support->tanggal_format_indonesia($tgl_spm)."</td>
                                <td align='center' style='font-size:12px'>".$this->support->tanggal_format_indonesia($tgl_terima)."</td>
                                <td align='left' style='font-size:12px'>$no_sp2d</td>
								<td align='center' style='font-size:12px'>".$this->support->tanggal_format_indonesia($tgl_sp2d)."</td>
                                <td align='right' style='font-size:12px'>".number_format($nilai_sp2d,"2",",",".")."</td>
								<td align='center' style='font-size:12px'>$ket</td>
                              </tr>  "; 
                   
                }

                    $cRet .=  "<tr  style='font-size:12px;font-weight:bold;'>
                                <td align='right' style='font-size:12px' colspan=8> Jumlah</td>
                                <td align='right' style='font-size:12px'>".number_format($tot_nil,"2",",",".")."</td>
                                <td align='right' style='font-size:12px'></td>
                              </tr>  "; 
                   
  
				
        $cRet .="</table>";
		
		$nip = str_replace('a',' ',$ttd);
		 $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
		$tanggal_ttd = date('d-m-Y');		
		$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$jabatan.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$nip.'</TD>
					</TR>
					</TABLE><br/>';
		
        $data['prev']= $cRet;    
		if ($print==1){
				$this->master_pdf->_mpdf('',$cRet,10,10,10,'1'); 

		}else if($print==2){
         header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= efisiensi_sp2d.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
         } else{
		echo ("<title>REGISTER SP2D</title>");
  	    echo $cRet;
		}
		//echo("$cRet");   
    }

    function cetak_rth_skpd($nbulan='',$ctk='',$ttd='', $tgl=''){
        $nip = str_replace('123456789',' ',$ttd);
		$tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl);
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$nip'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
       
		
			$cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD align="center" ><b>REKAPITULASI TRANSAKSI HARIAN BELANJA DAERAH (RTH) </TD>
					</TR>
					<tr></tr>
                    <TR>
					<TD align="center" ><b>PEMERINTAH KOTA '.strtoupper($daerah).'	 <br>
					BULAN '.strtoupper($this->support->getBulan($nbulan)).' '.$thn.'</TD>
					</TR>
					</TABLE><br/>';			

			$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="3" align=center>
					 <thead>
					 <TR>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >No.</TD>
                        <TD rowspan="2" bgcolor="#CCCCCC" align="center" >NAMA SKPD / BUD</TD>
						<TD colspan="2" bgcolor="#CCCCCC" align="center" >SPM / SPD </TD>
						<TD colspan="2" bgcolor="#CCCCCC" align="center" >SP2D</TD>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >POTONGAN PAJAK</TD>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >KET</TD>
					 </TR>
					 <TR>
                        <TD bgcolor="#CCCCCC" align="center" >JUMLAH<BR>TOTAL</TD>
						<TD bgcolor="#CCCCCC" align="center" >NILAI BELANJA<BR>TOTAL</TD>						
						<TD bgcolor="#CCCCCC" align="center" >JUMLAH<BR>TOTAL </TD>
						<TD bgcolor="#CCCCCC" align="center" >NILAI BELANJA<BR>TOTAL</TD>
					 </TR>
					 </thead>
					 ';
		
			$query = $this->db->query("
                    SELECT left(e.kd_skpd,17)+'.0000' kd_skpd,
(select nm_skpd from ms_skpd where kd_skpd=left(e.kd_skpd,17)+'.0000') as nm_skpd,
sum(e.byk_sp2d) banyak,sum(e.nilai) nilai_belanja,sum(e.nil_pot) nilai_pot from(
select d.kd_skpd,d.no_sp2d,sum(d.nilai) nilai,sum(d.nil_pot) nil_pot,count(d.no_sp2d) byk_sp2d from(
select c.kd_skpd,c.no_sp2d,sum(c.nilai) nilai,sum(c.nil_pot) nil_pot from(

select a.kd_skpd,b.no_sp2d,0 nil_pot,isnull(sum(a.nilai),0) nilai from trdspp a 
left join trhsp2d b on b.kd_skpd=a.kd_skpd and b.no_spp=a.no_spp
where MONTH(b.tgl_sp2d)='$nbulan'
group by a.kd_skpd,b.no_sp2d
union all
SELECT left(b.kd_skpd,17)+'.0000' as kd_skpd, b.no_sp2d, SUM(a.nilai) nil_pot,0 nilai FROM trdstrpot a INNER JOIN trhstrpot b
ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
WHERE RTRIM(a.kd_rek6) IN ('210105010001','210105020001','210105030001','2130401','2130501') 
and MONTH(b.tgl_bukti)='$nbulan'
GROUP BY left(b.kd_skpd,17),b.no_sp2d

)c group by c.kd_skpd,c.no_sp2d
)d group by d.kd_skpd,d.no_sp2d
)e group by left(e.kd_skpd,17)
order by left(e.kd_skpd,17)");

			$no=0;
			$tot_spm=0;
			$totNilspm=0;
			$tot_sp2d=0;
			$totNilsp2d=0;
			$totNilpot=0;
			   foreach ($query->result() as $row) {
				$no=$no+1;
				$kd_skpd = $row->kd_skpd; 
				$nm_skpd = $row->nm_skpd;                   
				$banyak_spm = $row->banyak;                   
				$nil_spm = $row->nilai_belanja;                   
				$banyak_sp2d = $row->banyak;
				$nil_sp2d =$row->nilai_belanja;
				$nil_pot=$row->nilai_pot;
				$tot_spm=$tot_spm + $banyak_spm;
				$totNilspm=$totNilspm + $nil_spm;
				$tot_sp2d=$tot_sp2d + $banyak_sp2d;
				$totNilsp2d=$totNilsp2d + $nil_sp2d;
				$totNilpot=$totNilpot + $nil_pot;
				$banyak_spm1  = empty($banyak_spm) || $banyak_spm == 0 ? 0 :$banyak_spm;	
				$nil_spm1  = empty($nil_spm) || $nil_spm == 0 ? number_format(0,"2",",",".") :number_format($nil_spm,"2",",",".");	
				$banyak_sp2d1 = empty($banyak_sp2d) || $banyak_sp2d == 0 ? 0 :$banyak_sp2d;	
				$nil_sp2d1  = empty($nil_sp2d) || $nil_sp2d == 0 ? number_format(0,"2",",",".") :number_format($nil_sp2d,"2",",",".");	
				$nil_pot1  = empty($nil_pot) || $nil_pot == 0 ? number_format(0,"2",",",".") :number_format($nil_pot,"2",",",".");
	
                 
                if(substr($kd_skpd,8,2)=='00'){      
				$cRet .="<tr>
						<td valign='top' align='center'> $no </td>
						<td valign='top' align='left'> $nm_skpd </td>
						<td valign='top' align='center'> $banyak_spm1 </td>
						<td valign='top' align='right'> $nil_spm1 &nbsp;</td>
						<td valign='top' align='center'> $banyak_sp2d1 </td>
						<td valign='top' align='right'> $nil_sp2d1 &nbsp;</td>
						<td valign='top' align='right'> $nil_pot1 &nbsp;</td>
						<td valign='top' align='right'> &nbsp;</td>
						</tr>"  ;
                    }    
				}	
				$cRet .="<tr>
						<td valign='top' align='center'><b> TOTAL </b></td>
						<td valign='top' align='center'><b> $no </b></td>
						<td valign='top' align='center'><b> $tot_spm</b> </td>
						<td valign='top' align='right'><b> ".number_format($totNilspm,"2",",",".")." </b>&nbsp;</td>
						<td valign='top' align='center'><b> $tot_sp2d </b></td>
						<td valign='top' align='right'> <b>".number_format($totNilsp2d,"2",",",".")."</b> &nbsp;</td>
						<td valign='top' align='right'><b> ".number_format($totNilpot,"2",",",".")."</b> &nbsp;</td>
						<td valign='top' align='right'> &nbsp;</td>
						</tr>"  ;
				   
			
				
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$jabatan.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$nip.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= $cRet;
             switch ($ctk)
        {
            case 0;
			echo ("<title>RTH (SKPD)</title>");
				echo $cRet;
				break;
            case 1;
				$this->master_pdf->_mpdf('',$cRet,10,10,10,'P',1,'');
               break;
			case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=RTH_$nbulan.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
			break;   
		}
	}

   function cetak_dth_global_skpd($lcskpd='',$nbulan='',$ctk=''){
        $nomor = str_replace('123456789',' ',$this->uri->segment(6));
		$tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(7));
        $atas = $this->uri->segment(8);
        $bawah = $this->uri->segment(9);
        $kiri = $this->uri->segment(10);
        $kanan = $this->uri->segment(11);
		$jns_bp = $this->uri->segment(12);
        
		$lcskpdd = substr($lcskpd,0,7).".00";   
        if($lcskpd!='-'){     
		$skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');}else{		  
		$skpd='Keseluruhan';}
        $sqlsc="SELECT top 1  tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>DAFTAR TRANSAKSI HARIAN BELANJA DAERAH (DTH) <br>
											BULAN '.strtoupper($this->tukd_model->getBulan($nbulan)).' '.$thn.'</TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>';
			
			$cRet .='		 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
					 <thead>
					 <TR>
                        <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >No.</TD>
						<TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" >SKPD</TD>
                        <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" >SPM/SPD</TD>
						<TD colspan="2" width="150"  bgcolor="#CCCCCC" align="center" >SP2D </TD>
						<TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Akun Belanja</TD>
						<TD colspan="3" width="150" bgcolor="#CCCCCC" align="center" >Potongan Pajak</TD>
						<TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >NPWP</TD>
						<TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Nama Rekanan</TD>
						<TD rowspan="2" width="150" bgcolor="#CCCCCC" align="center" >Ket</TD>
						<TD rowspan="2" width="50" bgcolor="#CCCCCC" align="center" >NTPN</TD>
					 </TR>
					 <TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >No. SPM</TD>
						<TD width="150"  bgcolor="#CCCCCC" align="center" >Nilai Belanja(Rp)</TD>						
						<TD width="150"  bgcolor="#CCCCCC" align="center" >No. SP2D </TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" >Nilai Belanja (Rp)</TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" >Akun Potongan</TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" >Jenis</TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" >jumlah (Rp)</TD>
					 </TR>
					 </thead>
					 ';
			
				//$par_rek_pot = "('2110101','2110201','2110301','2110302','2110303','2110304','2110305','2110401','2110501','2110601','2110701','2110702','2110801','2110802')";
				//$par_rek_pot = "('2110901','2110101','2110201','2110301','2110501','2110601','2110701','2110801','2130101','2130201','2130301','2130401','2130501')";
				$par_rek_pot = "('210105010001','210105020001','210106010001','210105030001','2130501')";
        
                if($lcskpd=='-'){ 
                    $sqlskpd = $this->db->query("select kd_skpd from ms_skpd");
                    
                    foreach ($sqlskpd->result() as $rows) {
                    $kdskpd_init = $rows->kd_skpd; 
                    
                    $query = $this->db->query("SELECT z.urut,z.kd_skpd,
(select nm_skpd from ms_skpd where jns_skpd='1' and kd_skpd=z.kd_skpd) as nm_skpd,
z.no_spm,sum(z.nilai_sp2d) as nilai_spm,z.no_sp2d,sum(z.nilai_sp2d) as nilai_sp2d,sum(z.banyak_sp2d) as banyak_spm,sum(z.banyak_sp2d) as banyak_sp2d,sum(z.nil_pot) as nil_pot,z.no_bukti,z.kode_belanja,z.kd_rek6,z.jns_pajak,z.npwp,z.nmrekan,z.ket,z.jns_spp,z.no_nnt FROM(
SELECT  '1' as urut,LEFT(d.kd_skpd,17)+'.0000' AS kd_skpd, '' nm_skpd, d.no_spm, sum(0) nilai_spm, d.no_sp2d, SUM(a.nilai) as nilai_sp2d,0 banyak_spm,count(DISTINCT no_sp2d) as banyak_sp2d, 0 nil_pot,
'' no_bukti, '' kode_belanja,'' kd_rek6,'' jns_pajak,'' npwp,'' nmrekan,'' ket,'' jns_spp,'' no_nnt 
FROM trhsp2d d
WHERE MONTH(d.tgl_sp2d)='$nbulan' and (d.sp2d_batal is null or d.sp2d_batal<>'1')
GROUP BY left(d.kd_skpd,17),d.no_spm,d.no_sp2d
union all
SELECT '2' as urut,left(b.kd_skpd,17)+'.0000' as kd_skpd,'' nm_skpd,'' no_spm,0 nilai_spm, b.no_sp2d, 0 nilai_sp2d, 0 banyak_spm, 0 banyak_sp2d, SUM(a.nilai) nil_pot, 
b.no_bukti, b.kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6) as kd_rek6,'' jns_pajak,b.npwp,'' as nmrekan,'No Set: '+a.no_bukti as ket,'' jns_spp,a.ntpn as no_nnt
FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd   
LEFT JOIN trhsp2d c on c.no_sp2d=b.no_sp2d and LEFT(c.kd_skpd,17)=LEFT(b.kd_skpd,17) 
WHERE MONTH(c.tgl_sp2d)='$nbulan' and (c.sp2d_batal is null or c.sp2d_batal<>'1') AND RTRIM(a.kd_rek6) in $par_rek_pot          
GROUP BY left(b.kd_skpd,17),b.no_sp2d,b.no_bukti,b.kd_sub_kegiatan,a.kd_rek_trans,a.kd_rek6,b.npwp,a.no_bukti, a.ntpn
)z
GROUP BY z.urut,z.kd_skpd,z.no_spm,z.no_sp2d,z.no_bukti,z.kode_belanja,z.kd_rek6,z.jns_pajak,z.npwp,z.nmrekan,z.ket,z.jns_spp,z.no_nnt
ORDER BY z.kd_skpd,z.no_sp2d,z.urut,z.kode_belanja,z.kd_rek6");  
                    }
                }else{
                    $query = $this->db->query("SELECT z.urut,z.kd_skpd,
(select nm_skpd from ms_skpd where kd_skpd=z.kd_skpd) as nm_skpd,
z.no_spm,sum(z.nilai_sp2d) as nilai_spm,z.no_sp2d,sum(z.nilai_sp2d) as nilai_sp2d,sum(z.banyak_sp2d) as banyak_spm,sum(z.banyak_sp2d) as banyak_sp2d,sum(z.nil_pot) as nil_pot,z.no_bukti,z.kode_belanja,z.kd_rek6,z.jns_pajak,z.npwp,z.nmrekan,z.ket,z.jns_spp,z.no_nnt FROM(
SELECT  '1' as urut,LEFT(d.kd_skpd,17)+'.0000' AS kd_skpd, '' nm_skpd, d.no_spm, sum(0) nilai_spm, d.no_sp2d, SUM(a.nilai) as nilai_sp2d,0 banyak_spm,count(DISTINCT no_sp2d) as banyak_sp2d, 0 nil_pot,
'' no_bukti, '' kode_belanja,'' kd_rek6,'' jns_pajak,'' npwp,'' nmrekan,'' ket,'' jns_spp,'' no_nnt 
FROM trdspp a 
INNER JOIN trhsp2d d on a.no_spp = d.no_spp AND a.kd_skpd=d.kd_skpd
WHERE MONTH(d.tgl_sp2d)='$nbulan' and left(d.kd_skpd,17)=left('$lcskpd',17) and (d.sp2d_batal is null or d.sp2d_batal<>'1')
GROUP BY left(d.kd_skpd,17),d.no_spm,d.no_sp2d
union all
SELECT '2' as urut,left(b.kd_skpd,17)+'.0000' as kd_skpd,'' nm_skpd,'' no_spm,0 nilai_spm, b.no_sp2d, 0 nilai_sp2d, 0 banyak_spm, 0 banyak_sp2d, SUM(a.nilai) nil_pot, 
b.no_bukti, b.kd_sub_kegiatan+'.'+a.kd_rek_trans as kode_belanja,RTRIM(a.kd_rek6) as kd_rek6,'' jns_pajak,b.npwp,'' as nmrekan,'No Set: '+a.no_bukti as ket,'' jns_spp,a.ntpn as no_nnt
FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd   
LEFT JOIN trhsp2d c on c.no_sp2d=b.no_sp2d and LEFT(c.kd_skpd,17)=LEFT(b.kd_skpd,17) 
WHERE MONTH(b.tgl_bukti)='$nbulan' and left(a.kd_skpd,17)=left('$lcskpd',17) and (c.sp2d_batal is null or c.sp2d_batal<>'1') AND RTRIM(a.kd_rek6) in $par_rek_pot          
GROUP BY left(b.kd_skpd,17),b.no_sp2d,b.no_bukti,b.kd_sub_kegiatan,a.kd_rek_trans,a.kd_rek6,b.npwp,a.no_bukti,a.ntpn
)z
GROUP BY z.urut,z.kd_skpd,z.no_spm,z.no_sp2d,z.no_bukti,z.kode_belanja,z.kd_rek6,z.jns_pajak,z.npwp,z.nmrekan,z.ket,z.jns_spp,z.no_nnt
ORDER BY z.kd_skpd,z.no_sp2d,z.urut,z.kode_belanja,z.kd_rek6");  
                    }
                                    
				$lcno=0;
				$tot_nilai=0;
				$tot_nilai_belanja=0;
				$tot_nilai_pot=0;
				foreach ($query->result() as $row) {
                    $no_spm = $row->no_spm; 
                    $nilai = $row->nilai_spm;    
					$nilai_belanja =$row->nilai_sp2d;
                    $no_sp2d = $row->no_sp2d;
                    $jns_spp = $row->jns_spp;
					if($jns_spp=='2'){
					$nilai_belanja =$nilai;	
					}
                    $kode_belanja=$row->kode_belanja;
                    $kd_rek5 = $row->kd_rek6;
                    $jenis_pajak = $row->jns_pajak;
                    $nilai_pot = $row->nil_pot;
                    $npwp = $row->npwp;
                    $nmrekan  = $row->nmrekan;
                    $ket  = $row->ket;
                    $kd_skpdd = $row->kd_skpd;
                    $no_nnt  = $row->no_nnt;
					$banyak  = ($row->banyak_sp2d)+1;
					if (($row->urut)==1){
						   $lcno = $lcno + 1;
					   } 
					
					if($kd_rek5=='210106010001'){
						$kd_rek5='210106010001';
						$jenis_pajak='PPn';
					}
					if($kd_rek5=='210105010001'){
						$kd_rek5='210105010001';
						$jenis_pajak='PPh 21';
					}
					if($kd_rek5=='210105020001'){
						$kd_rek5='210105020001';
						$jenis_pajak='PPh 22';
					}
					if($kd_rek5=='210105030001'){
						$kd_rek5='210105030001';
						$jenis_pajak='PPh 23';
					}
					if($kd_rek5=='2130501'){
						$kd_rek5='411128';
						$jenis_pajak='PPh 4';
					}
					if($kd_rek5=='2130601'){
						$kd_rek5='411128';
						$jenis_pajak='PPh 4 Ayat (2)';
					}
					if (($row->urut)==1){
							$cRet.='<TR>
                                <TD width="80" valign="top" align="center">'.$lcno.'</TD>
								<TD width="80" valign="top" align="center">'.$kd_skpdd.'</TD>
								<TD width="90" valign="top" >'.$no_spm.'</TD>
								<TD width="150" valign="top" align="right" >'.number_format($nilai,'2','.',',').'</TD>								
								<TD width="150" valign="top" >'.$no_sp2d.'</TD>
								<TD width="150" valign="top" align="right" >'.number_format($nilai_belanja,'2','.',',').'</TD>
								<TD width="150" align="right" ></TD>
								<TD width="150" align="left" ></TD>
								<TD width="150" align="left" ></TD>
								<TD width="150" align="left" ></TD>
								<TD width="150" align="left" ></TD>
								<TD width="150" align="left" ></TD>
								<TD width="150" align="left" ></TD>			
                                <TD width="150" align="right" ></TD>					
							 </TR>';	
						} else{
							$cRet.='<TR>
                                <TD width="150" align="right" style="border-top:hidden;"></TD>
								<TD width="150" align="left" style="border-top:hidden;"></TD>
                                <TD width="150" align="right" style="border-top:hidden;"></TD>
								<TD width="150" align="left" style="border-top:hidden;"></TD>
								<TD width="150" align="left" style="border-top:hidden;"></TD>
								<TD width="150" align="left" style="border-top:hidden;"></TD>
								<TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$kode_belanja.'</TD>
								<TD width="150" valign="top" align="center"  style="border-top:hidden;">'.$kd_rek5.'</TD>
								<TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$jenis_pajak.'</TD>
								<TD width="150" valign="top" align="right" style="border-top:hidden;" >'.number_format($nilai_pot,'2','.',',').'</TD>
								<TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$npwp.'</TD>
								<TD width="150" valign="top" align="left"  style="border-top:hidden;">'.$nmrekan.'</TD>
								<TD style="border-top:hidden;" width="150" valign="top" align="left" >'.$ket.'</TD>
								<TD style="border-top:hidden;" width="50" valign="top" align="left" >'.$no_nnt.'</TD>
							 </TR>';							
						}
				$tot_nilai=$tot_nilai+$nilai;
				$tot_nilai_belanja=$tot_nilai_belanja+$nilai_belanja;
				$tot_nilai_pot=$tot_nilai_pot+$nilai_pot;
				}
                
                /*$sql_sp2d = $this->db->query("select sum(x.nilai) as nilai_belanja from (
                SELECT b.no_sp2d,(select sum(nilai) from trhsp2d where no_sp2d=b.no_sp2d) as nilai
                FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
                WHERE month(b.tgl_bukti)='$nbulan'
                AND RTRIM(a.kd_rek5) IN $par_rek_pot
                and left(a.kd_skpd,7)=left('$lcskpd',7)
                group by b.no_sp2d)x")->row();
                $nilai_tot_sp2d=$sql_sp2d->nilai_belanja;*/
                
			$cRet .='<TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" >Total</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>                        
                        <TD width="50" bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_belanja,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_belanja,'2','.',',').'</TD>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ></TD>
						<TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>						
						<TD width="150"  bgcolor="#CCCCCC" align="center" ></TD>
						<TD width="150" bgcolor="#CCCCCC" align="right" >'.number_format($tot_nilai_pot,'2','.',',').'</TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ></TD>
						<TD width="50" bgcolor="#CCCCCC" align="center" ></TD>
					 </TR>';
			

			$cRet .='</TABLE>';
			
				$cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					 <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			
			$data['prev']= 'DTH';
             switch ($ctk)
        {
            case 0;
			echo ("<title>DTH (SKPD)</title>");
				echo $cRet;
				break;
            case 1;
				//$this->_mpdf('',$cRet,10,10,10,10,1,'');
				$this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
			   break;
		}
	}

    function cetak_reg_sp2d_count($lcskpd='',$ctk=''){
		
        $nomor = str_replace('123456789',' ',$this->uri->segment(5));
		$tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(6));
        $atas = $this->uri->segment(7);
        $bawah = $this->uri->segment(8);
        $kiri = $this->uri->segment(9);
        $kanan = $this->uri->segment(10);
		$jns_bp = $this->uri->segment(11);
        
		$lcskpdd = substr($lcskpd,0,7).".00";   
        if($lcskpd!='-'){     
		$skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');}else{		  
		$skpd='Keseluruhan';}
        $sqlsc="SELECT top 1 tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd = '$nomor' ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<meta http-equiv="refresh" content="2" /> <TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER SP2D<br>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
					 <thead>
					 <TR>
                        <TD rowspan="2" width="80" bgcolor="#CCCCCC" align="center" ><b>No.</b></TD>
                        <TD colspan="2" width="90"  bgcolor="#CCCCCC" align="center" ><b>SKPD</b></TD>
						<TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" ><b>SPP</b></TD>
						<TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" ><b>SPM</b></TD>
						<TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" ><b>SP2D</b></TD>
						<TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" ><b>SPP - SPM</b></TD>
						<TD colspan="2" width="150" bgcolor="#CCCCCC" align="center" ><b>SPM - SP2D</b></TD>
					 </TR>
					 <TR>
                        <TD width="90"  bgcolor="#CCCCCC" align="center" ><b>Kode SKPD</b></TD>
						<TD width="150"  bgcolor="#CCCCCC" align="center" ><b>Nama SKPD</b></TD>	
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Total SPP</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Jumlah Nilai SPP</br>(Rp)</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Total SPM</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Jumlah Nilai SPM</br>(Rp)</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Total SP2D</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Jumlah Nilai SP2D</br>(Rp)</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Total</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Jumlah Nilai</br>(Rp)</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Total</b></TD>
						<TD width="150" bgcolor="#CCCCCC" align="center" ><b>Jumlah Nilai</br>(Rp)</b></TD>
					 </TR>
					 </thead>
					 ';
			
                    
                    
                    $query = $this->db->query("SELECT x.kd_skpd,a.nm_skpd,sum(x.jm_spp) as spp,sum(x.nil_spp) as n_spp,sum(x.jm_spm) as spm,sum(x.nil_spm) as n_spm,sum(x.jm_sp2d) as sp2d,sum(x.nil_sp2d) as n_sp2d,
					sum(x.jm_spp_spm) as sl_spp_spm,sum(x.nil_sl_spp_spm) as n_spp_spm,sum(x.jm_spm_sp2d) as sl_spm_sp2d,sum(x.nil_sl_spm_sp2d) as n_spm_sp2d from (
					select kd_skpd,sum(jml_spp) jm_spp,sum(q.total_spp) as nil_spp,sum(q.jml_spm) as jm_spm,sum(q.total_spm) as nil_spm,sum(q.jml_sp2d) as jm_sp2d,sum(q.total_sp2d) as nil_sp2d 
					, sum(jml_spp-jml_spm) as jm_spp_spm,sum(q.total_spp)-sum(q.total_spm) nil_sl_spp_spm, sum(jml_spm-jml_sp2d) as jm_spm_sp2d 
					,sum(q.total_spm)-sum(q.total_sp2d) nil_sl_spm_sp2d from (
					select left(a.kd_skpd,17)+'.0000' as kd_skpd,COUNT(a.no_spp) as jml_spp,sum(a.nilai) as total_spp,'' as jml_spm,0 as total_spm,'' as jml_sp2d,0 as total_sp2d from trhspp a 
					where a.kd_skpd+a.no_spp not in (select kd_skpd+no_spp from trhsp2d where sp2d_batal='1')
					and a.kd_skpd+a.no_spp in (select distinct kd_skpd+no_spp from trdspp) 
					group by left(a.kd_skpd,17)
					union all
					select left(kd_skpd,17)+'.0000' as kd_skpd,'' as jml_spp,0 as total_spp,COUNT(no_spm) as jml_spm,sum(nilai) as total_spm,'' as jml_sp2d,0 as total_sp2d 
					from trhspm
					where kd_skpd+no_spp+no_spm not in (select kd_skpd+no_spp+no_spm from trhsp2d where sp2d_batal='1') 
					and kd_skpd+no_spp in (select distinct kd_skpd+no_spp from trdspp) 
					group by LEFT(kd_skpd,17)
					union all
					select left(kd_skpd,17)+'.0000' as kd_skpd,'' as jml_spp,0 as total_spp,'' as jml_spm,0 as total_spm,COUNT(no_sp2d) as jml_sp2d,sum(nilai) as total_sp2d 
					from trhsp2d
					where kd_skpd+no_spp+no_spm not in (select kd_skpd+no_spp+no_spm from trhsp2d where sp2d_batal='1') 
					group by LEFT(kd_skpd,17)
					) q
					group by q.kd_skpd 
					) x left join ms_skpd a
					on x.kd_skpd=a.kd_skpd
					group by x.kd_skpd,a.nm_skpd
					order by x.kd_skpd ");  
                    
				$lcno=0;
				foreach ($query->result() as $row) {
                    $kd_skpd = $row->kd_skpd; 
                    $nm_skpd = $row->nm_skpd; 
                    $spp = $row->spp; 
                    $n_spp = $row->n_spp; 
                    $spm = $row->spm; 
                    $n_spm = $row->n_spm; 
                    $sp2d = $row->sp2d; 
                    $n_sp2d = $row->n_sp2d; 
                    $sl_spp_spm = $row->sl_spp_spm; 
                    $n_spp_spm = $row->n_spp_spm; 
                    $sl_spm_sp2d = $row->sl_spm_sp2d; 
                    $n_spm_sp2d = $row->n_spm_sp2d; 
					$lcno = $lcno + 1;
					 
							$cRet.='<TR>
                                <TD width="80" valign="top" align="center">'.$lcno.'</TD>
								<TD width="80" valign="top" align="center">'.$kd_skpd.'</TD>
								<TD width="90" valign="top"  align="left">'.$nm_skpd.'</TD>
								<TD width="90" valign="top"  align="center">'.$spp.'</TD>
								<TD width="150" valign="top"  align="right">'.number_format($n_spp,'2','.',',').'</TD>	
								<TD width="90" valign="top"  align="center">'.$spm.'</TD>
								<TD width="150" valign="top"  align="right">'.number_format($n_spm,'2','.',',').'</TD>	
								<TD width="90" valign="top"  align="center">'.$sp2d.'</TD>
								<TD width="150" valign="top"  align="right">'.number_format($n_sp2d,'2','.',',').'</TD>	
								<TD width="90" valign="top"  align="center">'.$sl_spp_spm.'</TD>
								<TD width="150" valign="top"  align="right">'.number_format($n_spp_spm,'2','.',',').'</TD>	
								<TD width="90" valign="top"  align="center">'.$sl_spm_sp2d.'</TD>
								<TD width="150" valign="top"  align="right">'.number_format($n_spm_sp2d,'2','.',',').'</TD>	
							 </TR>';	
						}
					$query = $this->db->query("SELECT '' as kd_skpd,'' as nm_skpd,sum(x.jm_spp) as spp,sum(x.nil_spp) as n_spp,sum(x.jm_spm) as spm,sum(x.nil_spm) as n_spm,sum(x.jm_sp2d) as sp2d,sum(x.nil_sp2d) as n_sp2d,
					sum(x.jm_spp_spm) as sl_spp_spm,sum(x.nil_sl_spp_spm) as n_spp_spm,sum(x.jm_spm_sp2d) as sl_spm_sp2d,sum(x.nil_sl_spm_sp2d) as n_spm_sp2d from (
					select sum(jml_spp) jm_spp,sum(q.total_spp) as nil_spp,sum(q.jml_spm) as jm_spm,sum(q.total_spm) as nil_spm,sum(q.jml_sp2d) as jm_sp2d,sum(q.total_sp2d) as nil_sp2d 
					, sum(jml_spp-jml_spm) as jm_spp_spm,sum(q.total_spp)-sum(q.total_spm) nil_sl_spp_spm, sum(jml_spm-jml_sp2d) as jm_spm_sp2d 
					,sum(q.total_spm)-sum(q.total_sp2d) nil_sl_spm_sp2d from (
					select left(a.kd_skpd,17)+'.0000' as kd_skpd,COUNT(a.no_spp) as jml_spp,sum(a.nilai) as total_spp,'' as jml_spm,0 as total_spm,'' as jml_sp2d,0 as total_sp2d from trhspp a 
					where a.kd_skpd+a.no_spp not in (select kd_skpd+no_spp from trhsp2d where sp2d_batal='1')
					and a.kd_skpd+a.no_spp in (select distinct kd_skpd+no_spp from trdspp) 
					group by left(a.kd_skpd,17)
					union all
					select left(kd_skpd,17)+'.0000' as kd_skpd,'' as jml_spp,0 as total_spp,COUNT(no_spm) as jml_spm,sum(nilai) as total_spm,'' as jml_sp2d,0 as total_sp2d 
					from trhspm
					where kd_skpd+no_spp+no_spm not in (select kd_skpd+no_spp+no_spm from trhsp2d where sp2d_batal='1') 
					and kd_skpd+no_spp in (select distinct kd_skpd+no_spp from trdspp) 
					group by LEFT(kd_skpd,17)
					union all
					select left(kd_skpd,17)+'.0000' as kd_skpd,'' as jml_spp,0 as total_spp,'' as jml_spm,0 as total_spm,COUNT(no_sp2d) as jml_sp2d,sum(nilai) as total_sp2d 
					from trhsp2d
					where kd_skpd+no_spp+no_spm not in (select kd_skpd+no_spp+no_spm from trhsp2d where sp2d_batal='1') 
					group by LEFT(kd_skpd,17)
					) q
					) x ");  
                    
				$lcno=0;
				foreach ($query->result() as $row) {
                    $kd_skpd = $row->kd_skpd; 
                    $nm_skpd = $row->nm_skpd; 
                    $spp = $row->spp; 
                    $n_spp = $row->n_spp; 
                    $spm = $row->spm; 
                    $n_spm = $row->n_spm; 
                    $sp2d = $row->sp2d; 
                    $n_sp2d = $row->n_sp2d; 
                    $sl_spp_spm = $row->sl_spp_spm; 
                    $n_spp_spm = $row->n_spp_spm; 
                    $sl_spm_sp2d = $row->sl_spm_sp2d; 
                    $n_spm_sp2d = $row->n_spm_sp2d; 
					$lcno = $lcno + 1;
					 
							$cRet.='<TR>
								<TD colspan="3" width="90" valign="top"  align="center"><b>JUMLAH</b></TD>
								<TD width="90" valign="top"  align="center"><b>'.$spp.'</b></TD>
								<TD width="150" valign="top"  align="right"><b>'.number_format($n_spp,'2','.',',').'</b></TD>	
								<TD width="90" valign="top"  align="center"><b>'.$spm.'</b></TD>
								<TD width="150" valign="top"  align="right"><b>'.number_format($n_spm,'2','.',',').'</b></TD>	
								<TD width="90" valign="top"  align="center"><b>'.$sp2d.'</b></TD>
								<TD width="150" valign="top"  align="right"><b>'.number_format($n_sp2d,'2','.',',').'</b></TD>	
								<TD width="90" valign="top"  align="center"><b>'.$sl_spp_spm.'</b></TD>
								<TD width="150" valign="top"  align="right"><b>'.number_format($n_spp_spm,'2','.',',').'</b></TD>	
								<TD width="90" valign="top"  align="center"><b>'.$sl_spm_sp2d.'</b></TD>
								<TD width="150" valign="top"  align="right"><b>'.number_format($n_spm_sp2d,'2','.',',').'</b></TD>	
							 </TR>';	
						}

			$cRet .='</TABLE>';
			
				$cRet .='<TABLE style="font-size:14px;" width="100%" align="center">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					 <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br>'.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			
			$data['prev']= 'REGISTER SP2D';
             switch ($ctk)
        {
            case 0;
			echo ("<title>REGISTER SP2D</title>");
				echo $cRet;
				break;
            case 1;
				//$this->_mpdf('',$cRet,10,10,10,10,1,'');
				$this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
			   break;
		}
	}

	function cetaklpjup_ag(){
		
		$cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a',' ',$this->uri->segment(3));
        $ttd2   = str_replace('a',' ',$this->uri->segment(6));
		$ctk    =   $this->uri->segment(5);
        $nomor1 = str_replace('abcdefghij','/',$this->uri->segment(7));
        $nomor  = str_replace('123456789',' ',$nomor1);
		$jns    =   $this->uri->segment(8);
		$lctgl1 = $this->rka_model->get_nama2($nomor,'tgl_awal','trhlpj','no_lpj','kd_skpd',$cskpd);
		$lctgl2 = $this->rka_model->get_nama2($nomor,'tgl_akhir','trhlpj','no_lpj','kd_skpd',$cskpd);
		$lctglspp = $this->rka_model->get_nama2($nomor,'tgl_lpj','trhlpj','no_lpj','kd_skpd',$cskpd);

          
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
		$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where id_ttd='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        /* $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                } */
		$cRet  =" <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
    					<tr>
    						<td align='center'> <b>$kab</b></td>
    					</tr>
    					<tr>
    						<td align='center'><b>LAPORAN PERTANGGUNGJAWABAN UANG PERSEDIAAN</b></td>
    					</tr>
						<tr>
    						<td align='center'></td>
    					</tr>
    					<tr>
    						<td align='center'></td>
    					</tr>
						<tr>
    						<td align='center'><b>&nbsp;</b></td>
    					</tr>
		          </table>				
				";

		$cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
    					<tr>
    						<td align='left' width='10%'> NO LPJ&nbsp;&nbsp;&nbsp;</td>
    						<td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
    						<td align='left' > ".$nomor."</td>
    					</tr>
						<tr>
    						<td align='left' width='10%'> SKPD&nbsp;&nbsp;&nbsp;</td>
    						<td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
    						<td align='left' > ".$cskpd." ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
    					</tr>
    					<tr>
    						<td align='left' width='10%'>PERIODE&nbsp;&nbsp;&nbsp;</td>
    						<td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
    						<td align='left' >".$this->support->tanggal_format_indonesia($lctgl1).' s/d '.$this->support->tanggal_format_indonesia($lctgl2)."</td>
    					</tr>
		           </table>				
				";		

		$cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<THEAD>
					<tr>
						<td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
						<td bgcolor='#CCCCCC' align='center' width='30%'><b>KODE REKENING</b></td>
						<td bgcolor='#CCCCCC' align='center' width='50%'><b>URAIAN</b></td>
						<td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
					</tr>
					<tr>
						<td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
						<td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
						<td bgcolor='#CCCCCC' align='center' width='50%'><b>3</b></td>
						<td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
					</tr>
					</THEAD>
				";		
			
				if($jns=='0'){
				$sql = "

					SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_skpd, kd_program,nm_program FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
						ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program  and a.kd_skpd=b.kd_skpd
						WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
						--program
						UNION ALL
						SELECT 2 as urut, left(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN trskpd b ON left(a.kd_sub_kegiatan,12)=b.kd_kegiatan and a.kd_skpd=b.kd_skpd
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY left(a.kd_sub_kegiatan,12), b.nm_kegiatan
						--kegiatan
						UNION ALL
						SELECT 3 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
						--subkegiatan
						union all
						SELECT 4 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, b.nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj a
						INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), b.nm_rek2
						--rek2
						UNION ALL
						SELECT 5 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, b.nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj a
						INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), b.nm_rek3
						--rek3
						UNION ALL
						SELECT 6 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, b.nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj a
						INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), b.nm_rek4
						--rek4
						UNION ALL
						SELECT 7 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, b.nm_rek5 as uraian, SUM(nilai) as nilai FROM trlpj a
						INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), b.nm_rek5
						--rek5
						UNION ALL
						SELECT 8 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj
						WHERE no_lpj='$nomor' AND kd_skpd='$cskpd'
						GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
						ORDER BY kode
						--rek6";		
				$query1 = $this->db->query($sql); 
				$total=0;
				$i=0;
				foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
					
					if ($urut==1){
					$i=$i+1;	
						$cRet .="<tr>
									<td valign='top' align='center' ><i><b>$i</b></i></td>
									<td valign='top' align='left' ><i><b>$kode</b></i></td>
									<td valign='top' align='left' ><i><b>$uraian</b></i></td>
									<td valign='top' align='right'><i><b>".number_format($nilai,"2",",",".")."</b></i></td>
								</tr>";
					} else if ($urut==2){
							$cRet .="<tr>
									<td valign='top' align='center' ><b></b></td>
									<td valign='top' align='left' ><b>$kode</b></td>
									<td valign='top' align='left' ><b>$uraian</b></td>
									<td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
								</tr>";
					}else if ($urut==8){
							$total=$total+$nilai;
							$cRet .="<tr>
									<td valign='top' align='center' ></td>
									<td valign='top' align='left' >$kode</td>
									<td valign='top' align='left' >$uraian</td>
									<td valign='top' align='right'>".number_format($nilai,"2",",",".")."</td>
								</tr>";
					}
					else{
						$cRet .="<tr>
									<td valign='top' align='left' ></td>
									<td valign='top' align='left' >$kode</td>
									<td valign='top' align='left' >$uraian</td>
									<td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
								</tr>";	
					}

				}
				} else{
				$sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
						ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program  and a.kd_skpd=b.kd_skpd
						WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
						UNION ALL
						SELECT 2 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
						ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan  and a.kd_skpd=b.kd_skpd
						WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan				
						UNION ALL				
						SELECT 3 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
						FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
						WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
						GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
						ORDER BY kode";		
				$query1 = $this->db->query($sql); 
				$total=0;
				$i=0;
				foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
					
					if ($urut==1){
						$i=$i+1;	
						$cRet .="<tr>
									<td valign='top' align='center' ><b>$i</b></td>
									<td valign='top' align='left' ><b>$kode</b></td>
									<td valign='top' align='left' ><b>$uraian</b></td>
									<td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
								</tr>";
					} else{
						if($urut==3){
							$total=$total+$nilai;
						}

						$cRet .="<tr>
									<td valign='top' align='left' ></td>
									<td valign='top' align='left' >$kode</td>
									<td valign='top' align='left' >$uraian</td>
									<td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
								</tr>";	
					}

				}	
				}


				$sqlp = " SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
						  WHERE b.kd_skpd='$cskpd' AND (b.jns_spp=1)";
				$queryp = $this->db->query($sqlp);  	
				foreach($queryp->result_array() as $nlx){ 
						$persediaan=$nlx["nilai"];
				}
                
                $sqll = "SELECT tgl_sah_bud from trhlpj where kd_skpd='$cskpd' and no_lpj='$nomor'";
                $queryp2 = $this->db->query($sqll)->row();
                $queryp23 = $queryp2->tgl_sah_bud;
                
                
                
				$cRet .="
						<tr>
							<td align='left' >&nbsp;</td>
							<td align='left' >&nbsp;</td>
							<td align='left' >&nbsp;</td>
							<td align='right' >&nbsp;</td>
						</tr>					
						<tr>
							<td align='left' >&nbsp;</td>
							<td align='left' >&nbsp;</td>
							<td align='right' ><b>Total</b></td>
							<td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
						</tr>					
						<tr>
							<td align='left' >&nbsp;</td>
							<td align='left' >&nbsp;</td>
							<td align='right' ><b>Uang Persediaan Awal Periode</b></td>
							<td align='right' ><b>".number_format($persediaan,"2",",",".")."</b></td>
						<tr>
							<td align='left' >&nbsp;</td>
							<td align='left' >&nbsp;</td>
							<td align='right' ><b>Uang Persediaan Ahir Periode</b></td>
							<td align='right' ><b>".number_format($persediaan-$total,"2",",",".")."</b></td>
						</tr>
						</tr>
					    ";


				$cRet .="</table><p>";				
//.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
 		$cRet .=" <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td valign='top' align='center' width='50%'>Disetujui 	
						<br>$jabatan</td>
						<td valign='top' align='center' width='50%'>Pontianak, ".$this->support->tanggal_format_indonesia(getdate('d-m-Y'))."  
						<br>Telah diverifikasi
						<br>Petugas</td>
					</tr>
					<tr>
						<td align='center' width='50%'>&nbsp;</td>
						<td align='center' width='50%'>&nbsp;</td>
					</tr>
					<tr>
						<td align='center' width='50%'>&nbsp;</td>
						<td align='center' width='50%'>&nbsp;</td>
					</tr>
					<tr>
						<td align='center' width='50%'>&nbsp;</td>
						<td align='center' width='50%'>&nbsp;</td>
					</tr>
					<tr>
						<td align='center' width='50%'>&nbsp;</td>
						<td align='center' width='50%'>&nbsp;</td>
					</tr>
					<tr>
						<td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
						<td align='center' width='50%'>___________________<br></td>
					</tr>
					<tr>
						<td align='center' width='50%'>$nip</td>
						<td align='center' width='50%'></td>
					</tr>
				  </table>
				";		

        $atas='5';
        $kiri='4';
        $kanan='4';
        
        
        $data['prev']= $cRet; 

		switch ($ctk)
        {
            case 0;
			   echo ("<title> LPJ UP</title>");
				echo $cRet;		
				break;
            case 1;
				$this->master_pdf->_mpdf_margin('',$cRet,10,10,10,'0',1,'',$atas,50,$kiri,$kanan);
				//$this->_mpdf('',$cRet,10,10,10,'0',0,'');
               break;
		}
	}	
	  	
}