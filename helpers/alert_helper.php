<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('alert'))
{
    function alert($e)
    {
        echo("<script>alert(\"Pesan: ".$e."\")</script>");
    }
}

if (!function_exists('sanitasi_kata_berbahaya'))
{
    function sanitasi_kata_berbahaya($query){
        $berbahaya=array("ALTER","ROUTINE","CREATE","TEMPORARY","TABLES","VIEW","DELETE","DROP","EVENT","EXECUTE","INDEX","INSERT","LOCK","REFERENCES","SHOW","TRIGGER","UPDATE");
        //$sanitasi=explode(' ',$query);
        /*
        foreach($sanitasi as $kata){
            if(in_array(strtoupper($kata),$berbahaya)) {
                return TRUE;//kalau berbahaya maka TRUE
            } 
        }
        */
        foreach($berbahaya as $kata){
            if(preg_grep("#$kata#i",array($query))!==array()){
                return TRUE;//kalau berbahaya maka TRUE
            }
        }
        return FALSE;
    }
}

//Fungsi ini diambil dari: https://css-tricks.com/snippets/php/sanitize-database-inputs/
if (!function_exists('cleanInput'))
{
    function cleanInput($input) {
 
        $search = array(
          '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
          '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
          '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
          '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );
       
          $output = preg_replace($search, '', $input);
          return $output;
        }
}


if (!function_exists('sanitize'))
{
function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = $input;
    }
    return $output;
}
}

if (!function_exists('pengisi_nilai_lokasi'))
{
    function pengisi_nilai_lokasi($base_location,$kategori) {
        $lokasi=$base_location."/".$kategori;
        return 	$lokasi;
    }
}

//ALHAMDULILLAH SUDAH MIGRASI
if (!function_exists('penafsir_NULL'))
{
    function penafsir_NULL($kiriman) 
    {
        foreach ($kiriman as $isi) {if ($isi="") {$isi=NULL;}}
        return $kiriman;
    }
}

if (!function_exists('kirim'))
{
    /**
     * Transfer Files Server to Server using PHP Copy
     * @link https://shellcreeper.com/?p=1249
     * @link https://shellcreeper.com/move-files-server-to-server-using-simple-php/
     */

     function kirim($remote_file_url, $local_file){
        /* Source File URL */
        $remote_file_url = 'http://origin-server-url/files.zip';
    
        /* New file name and path for this file */
        $local_file = 'files.zip';
    
        /* Copy the file from source url to server */
        $copy = copy( $remote_file_url, $local_file );
    
        /* Add notice for success/failure */
        if( !$copy ) {
            echo "Gagal mengirim $file...\n";
        }
        else{
            echo "Sukses mengirim $file...\n";
        }
     }
}

if (!function_exists('rekam'))
{
    //Fungsi rekam id dll.
    function rekam($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value) {
        $detail_rekam=array();
        $detail_rekam['type']=$type;
        $detail_rekam['nama_komponen']=$nama_komponen;
        $detail_rekam['class']=$class;
        $detail_rekam['id']=$id;
        $detail_rekam['atribut']=$atribut;
        $detail_rekam['event']=$event;
        $detail_rekam['label']=$label;
        $detail_rekam['value']=$value;
        return $detail_rekam;
    }
}

if (!function_exists('penyamaUkuranArray'))
{
    function penyamaUkuranArray($arrayKurang,$arrayPatokan) 
    {
        if(sizeof($arrayPatokan)>sizeof($arrayKurang)) {
            $i = sizeof($arrayKurang);
            do {
                array_push($arrayKurang,NULL);
                $i++;
            } while ($i < sizeof($arrayPatokan)+1);
        }        
        return $arrayKurang;
    }
}

if (!function_exists('cetak_tiket_pdf'))
{
    /*
    Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
    Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
    SetMargins(float left, float top [, float right])
    Ln([float h]) Line break, h=height of the break.
    MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
    AddPage([string orientation [, mixed size (A3,A4,A5,Letter,Legal) [, int rotation]]])
    A3=29.7 x 42.0 cm
    A4=21.0 x 29.7 cm
    A5=14.8 x 21.0 cm
    letter=21,6 × 27,9
    legal=21,6 × 35,6
    string Output([string dest (I,D,F,S) [, string name [, boolean isUTF8]]])
    I: send the file inline to the browser. The PDF viewer is used if available.
    D: send to the browser and force a file download with the name given by name.
    F: save to a local file with the name given by name (may include a path).
    S: return the document as a string.
    SetAuthor(string author [, boolean isUTF8])
    SetTitle(string title [, boolean isUTF8])
    SetDrawColor(int r [, int g, int b])
    SetFillColor(int r [, int g, int b])
    SetTextColor(int r [, int g, int b])
    Text(float x, float y, string txt)
    Write(float h, string txt [, mixed link])
    Rect(float x, float y, float w, float h [, string style (D yaitu Draw,F yaitu Fill,DF,FD)])
    SetLineWidth(float width)
    Line(float x1, float y1, float x2, float y2)
    SetFont(string family [, string style [, float size]])
    String Family Standar:
    Courier (fixed-width)
    Helvetica or Arial (synonymous; sans serif)
    Times (serif)
    Symbol (symbolic)
    ZapfDingbats (symbolic)
    ----
    SetY(float y [, boolean resetX])
    dst..
    */
    function cetak_tiket_pdf($data,$posisi_judul=array(18,35),$posisi_isi=array(15,38)){
        $string=array();
        is_array($data)?$string=$data:array_push($string,$data);
        $pdf = new FPDF();
        $pdf->AddPage('P','Letter');
        $pdf->SetMargins(2.3,1,2.3);
        $pdf->Image('./public/img2/logopemprov.png', 95, 5, 25, 0);
        $pdf->SetFont('Arial','B',16);
        $pdf->SetXY($posisi_judul[0],$posisi_judul[1]);
        if(!is_array($data)){$pdf->Cell(180,10,$data,0,1,'C');}else{$pdf->Cell(180,10,$data[0],0,1,'C');}
        
        $string[0]=NULL;
        $pdf->SetXY($posisi_isi[0],$posisi_isi[1]);
        foreach($string as $key=>$k){
            $s=explode(':',$k);
            if(!in_array($key,array(2,17,18,19,20,21,22,23,24,25,26,27,28)) ){
                if(($s[0]!=='simple_signature') && ($s[0]!=='password') ){
                    $pdf->Cell(0,7,$k);
                }else {
                    $pdf->MultiCell(0,7,$k);
                }
                $pdf->Ln();
            }
        }
        
        $pdf->Output();
        unset($pdf);
    }
}


if (!function_exists('cetak_tiket_pdf_registrasi'))
{
    function cetak_tiket_pdf_registrasi($data,$posisi_judul=array(18,35),$posisi_isi=array(15,38)){
        $string=array();
        is_array($data)?$string=$data:array_push($string,$data);
        $pdf = new FPDF();
        $pdf->AddPage('P','Letter');
        $pdf->SetMargins(2.3,1,2.3);
        $pdf->Image('./public/img2/logopemprov.png', 95, 5, 25, 0);
        $pdf->SetFont('Arial','B',16);
        $pdf->SetXY($posisi_judul[0],$posisi_judul[1]);
        if(!is_array($data)){$pdf->Cell(180,10,$data,0,1,'C');}else{$pdf->Cell(180,10,$data[0],0,1,'C');}
        
        $string[0]=NULL;
        $pdf->SetXY($posisi_isi[0],$posisi_isi[1]);
        foreach($string as $key=>$k){
            $s=explode(':',$k);
            if(!in_array($key,array(99,91,92)) ){
                if(($s[0]!=='simple_signature') && ($s[0]!=='password') ){
                    if($s[0]=='idtamu'){
                        $pdf->Cell(0,7,'idtamu: diisi oleh sistem');
                    }else if($s[0]=='cookie'){
                        $pdf->Cell(0,7,'cookie: diisi oleh sistem');
                    }else{
                        $pdf->Cell(0,7,$k);
                    }
                    $pdf->Ln();
                }else {
                    $pdf->MultiCell(0,7,$k);
                }
            }
        }
        
        $pdf->Output();
        unset($pdf);
    }
}

if (!function_exists('BasicTable'))
{
    // Simple table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function BasicTable($header, $data,$output_dest=NULL,$output_name=NULL)//$header = array nama-nama kolom tabel
    {
        $pdf = new FPDF();
        // Header
        $pdf->AddPage('L','Letter');

        /*
        //basic:
        $pdf->SetFont('Arial','',14);
        foreach($header as $col)
            $pdf->Cell(40,7,$col,1);
        $pdf->Ln();
        // Data
        //$pdf->SetFont('Arial','',12);
        foreach($data as $row)
        {
            foreach($row as $col)
                $pdf->Cell(40,6,$col,1);
            $pdf->Ln();
        }
        //end basic.
        */
        //fancy
            // Colors, line width and bold font
            $pdf->SetFillColor(255,0,0);
            $pdf->SetTextColor(255);
            $pdf->SetDrawColor(128,0,0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('Arial','',12);
            // Header
            $w = array(40, 35, 40, 45);
            for($i=0;$i<count($header);$i++)
                $pdf->Cell(40,7,$header[$i],1,0,'C',true);
            $pdf->Ln();
            // Color and font restoration
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            // Data
            $fill = false;
            $panjang=40;
            $i=0;
            foreach($data as $row)
            {
                foreach($row as $col)
                    $pdf->Cell($panjang,6,$col,'LR',0,'L',$fill);
                $pdf->Ln();
                $fill = !$fill;
                $i++;
            }
            // Closing line
            $pdf->Cell(count($header)*$panjang,0,'','T');
        //end fancy
        $pdf->Output($output_dest);
        unset($pdf);
    }

}


if (!function_exists('max'))
{

        function max($x,$y){
            $x>$y?$result=$x:$result=$y;
            return $x;
        }

}

if (!function_exists('min'))
{

        function min($x,$y){
            $x<$y?$result=$x:$result=$y;
            return $x;
        }

}

if (!function_exists('export_html_ke_pdf'))
{
    function export_html_ke_pdf($file_html,$output_dest='I',$output_name=NULL,$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Bank Data BKD',$lebar_page,$tinggi_page,$orientasi=''){
        //Inisiasi halaman pdf:
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array($lebar_page,$tinggi_page), true, 'UTF-8', false);
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Andi Sinra, Aslan Alwi and Munirah Muslim');
        $output_name!==NULL?$pdf->SetTitle($output_name):$pdf->SetTitle('Export ke PDF');
        $pdf->SetSubject('Laporan berbentuk PDF');
        $pdf->SetKeywords('TCPDF, PDF, laporan, test, andi sinra');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,$nama_satker, $nama_bidang);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        //====================================================

        $pdf->AddPage($orientasi);

        // Colors, line width and bold font
        $html=$file_html;
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output($output_name.'.pdf',$output_dest); 
        unset($pdf);

    }

}

if (!function_exists('BasicTable_tcpdf'))
{
    // Simple table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function BasicTable_tcpdf($header, $data,$output_dest='I',$output_name=NULL,$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Bank Data BKD',$panjang_tiap_sel=array(40, 35),$lebar_page=270,$tinggi_tiap_baris=6,$tinggi_page=841.890,$orientasi='')//$header = array nama-nama kolom tabel
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array($lebar_page,$tinggi_page), true, 'UTF-8', false);
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Andi Sinra, Aslan Alwi and Munirah Muslim');
        $output_name!==NULL?$pdf->SetTitle($output_name):$pdf->SetTitle('Laporan PDF');
        $pdf->SetSubject('Laporan berbentuk PDF');
        $pdf->SetKeywords('TCPDF, PDF, laporan, test, andi sinra');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,$nama_satker, $nama_bidang);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        //====================================================
        
        $pdf->AddPage($orientasi);

        // Colors, line width and bold font
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        $pdf->SetFontSize('10');
        // Header
        
        $w = $panjang_tiap_sel;
        
        //berapa jumlah kolom header.
        $num_headers = count($header);
        /*
        alert($num_headers);
        alert(implode('  ',$header));
        alert(implode('  ',$w));
        */
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            //kolom header memiliki panjang variabel $w[$i], tinggi baris tetap yaitu 7, 
            //isinya tiap kolom header adalah $header[$i], border 1, tidak ada ln (pindah baris jika menyentuh dinding sel kanan), 
            //align C (center), fill warna background sel = true = 1. fill berdasarkan perintah $pdf->SetFillColor(255, 0, 0); sebelumnya di atas.
        }
        
        //alert($num_headers);
        $pdf->Ln();
        
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->SetFontSize('10');
        // Data
        $fill = 0;

        $counter=0;
        foreach($data as $s=>$row) {
            foreach($row as $k=>$isi)
            $pdf->MultiCell($w[$k],$tinggi_tiap_baris[$s], $isi, 1, 'L', $fill,0,'','',true);

            //pindah baris untuk cetak baris barus
            $pdf->Ln();

            //buat warnanya berganti-ganti tiap baris
            $fill=!$fill;

            //if($s!==0&&$s%50==0){
            //cek jika tinggi baris lebih besar dari tinggi total baris yang dibolehkan agar semua baris fit dalam halaman yang ditetapkan
            //tinggi total baris yang dibolehkan adalah = (85/100)*$tinggi_page atau 85% dari halaman yang ditentukan, jika total tinggi baris lebih besar maka baris terakhir digeser ke halaman berikut. agar cetakan 
            //tabel tidak melampaui tinggi halaman. kenapa diberi counter, counter untuk mengalikan semua tinggi baris atau menjumlahkan semua tinggi baris untuk memperoleh tinggi baris total.
            if($counter*$tinggi_tiap_baris[0]>(85/100)*$tinggi_page){

                //reset lagi counter dihalamn baru untuk mulai perhitungan total tinggi baris lagi dari semua baris yang tercetak.
                $counter=0;
                $pdf->addPage($orientasi);
                $pdf->SetFillColor(255, 0, 0);
                $pdf->SetTextColor(255);
                $pdf->SetDrawColor(128, 0, 0);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFont('', 'B');
                $pdf->SetFontSize('10');

                //cetak ulang header lagi di halaman baru.
                for($i = 0; $i < $num_headers; ++$i) {
                    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
                }
                $pdf->Ln();

                // Color and font restoration, agar tidak mengikuti color dan font baris header di atas.
                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(0);
                $pdf->SetFont('');
                $pdf->SetFontSize('10');
            }
            $counter++;
            
        }

        //buat garis bawah penutup tabel, yang sebenarnya adalah sebuah sel dengan tinggi 0 dan ga ada isinya, tetapi border ada. panjang total adalah jumlah semua panjang sel dalam satu baris.
        $pdf->Cell(array_sum($w), 0, '', 'T');

        $pdf->Output($output_name,$output_dest); //kenapa kemarin file keluaran tidakada ekstensinya karena nama file tidak diberi ekstensi pdf.
        unset($pdf);
    }

}

if (!function_exists('BasicTableAgenda_tcpdf'))
{
    // Simple table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function BasicTableAgenda_tcpdf($judul='Laporan Kegiatan',$data_1rekord,$output_dest='I',$output_name='laporan_kegiatan.pdf',$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban BKD',$lebar_page,$tinggi_tiap_baris=6,$tinggi_page=841.890,$orientasi='')
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array($lebar_page,$tinggi_page), true, 'UTF-8', false);
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Andi Sinra, Aslan Alwi and Munirah Muslim');
        $output_name!==NULL?$pdf->SetTitle($output_name):$pdf->SetTitle('Laporan PDF');
        $pdf->SetSubject('Laporan berbentuk PDF');
        $pdf->SetKeywords('TCPDF, PDF, laporan, test, andi sinra');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,$nama_satker, $nama_bidang);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        //====================================================
        
        $pdf->AddPage($orientasi);
        //$pdf->startPage($orientasi);
        // Colors, line width and bold font
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(128, 0, 0);
        $pdf->SetLineWidth(0);
        $pdf->SetFont('', 'B');
        $pdf->SetFontSize('14');

        $tinggi_header=$pdf->GetY();
        $tinggi_footer=2; //ini bawaan standar tcpdf?

        //cetak judul:
        $pdf->Cell(((9/10)*$lebar_page), 10, $judul, 0, 0, 'C', 1);
        //$pdf->MultiCell(((9/10)*$lebar_page),10, $judul, 1, 'C',0,1,'','',true);
        $pdf->Ln();

        //ubah dulu data yang masuk satu data baru berbentuk vertikal
        //assumsi data yang masuk berbentuk $data_1rekord['nama_kolom']=nilai_kolom,dan hanya 1 rekord, 
        //karena yang dicetak rencananya hanya 1 rekord.
        $i=0;
        $foto='';
        foreach($data_1rekord as $key=>$row){
            $data[$i]=array($key,$row);
            //if($key=='narasi_acara') {$j=0;$j=$i+1;$data[$j]=array('perantara','');$i=$j;}
            $key=='direktori_foto_yg_menyertai'?$foto=$row:NULL;
            $key=='keterangan_foto'?$keterangan_foto=$row:NULL;
            $i++;
        }
        array_push($data,array('lampiran_foto_acara',''));
        //print_r($data);
        //alert('nilai i: '.$i);
        
        //bagaimana menentukan panjang tiap sel? 
        $panjang_tiap_sel=array();
        $panjang_tiap_sel[0]=1/3*((9/10)*$lebar_page); //didasarkan pada hitungan: panjang tabel diambil sebagai 90% atau 9/10 dari lebar page
        $panjang_tiap_sel[1]=2/3*((9/10)*$lebar_page);//kemudian untuk sel pertama dari panjang tabel adalah 1/3 nya, dan sel kedua adalah 2/3 nya.
        //alert($panjang_tiap_sel[1]);
        //penentuan tinggi tiap baris?

        //obselet?
        /*
        $tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			foreach($data as $k=>$row){
				foreach($row as $s=>$isi){
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:$max=$kandidat;
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
        */

        /** 
         * perbandingan:
         * [1]jumlah_karakter/50 dibulatkan ke atas = jumlah unit tinggi sel. ini untuk kolom kedua.
         * artinya bahwa jika panjang sel 120 maka padananya adalah 50 karakter ukuran 12 atau 60 karakter ukuran 10.
         * dng cara ini kita dapat menghitung tinggi sel sebagai berikut:
         * hitung dulu jumlah karater standar dalam satu sel sesuai ukuran panjang sel:
         * (120/panjang_sel = 50/jumlah_karakter_standar).
         * jadi:
         * jumlah_karakter_standar=bulatkan_keatas(50*panjang_sel/120)
         * kemudian hitung jumlah unit tinggi sel:
         * jumlah_unit_tinggi_sel = bulatkan_keatas(panjang_string_isi/jumlah_karakter_standar)
         * .
         * kemudian hitung tinggi sel berdasarkan: 1 unit tinggi = 5 ukuran 12 dan 18/4 ut ukuran 10.
         * jadi:
         * tinggi_sel = 5*jumlah_unit_tinggi_sel ut karakter ukuran 12, dan 18/4*jumlah_unit_tinggi_sel ut karakter ukuran 10. 
        */
        $w = $panjang_tiap_sel;
        $jumlah_karakter_standar=array();
        $jumlah_karakter_standar[0]=ceil(50*$w[0]/120);
        $jumlah_karakter_standar[1]=ceil(50*$w[1]/120);

        $tinggi_tiap_baris=array();
        $jumlah_unit_tinggi_sel=array();
        $tinggi_sel=array();
        $tinggi_baris=array();
        //hitung jumlah karakter standar sesuai ukuran sel:
        foreach($data as $i=>$isi){
            foreach($isi as $k=>$string){
                $jumlah_unit_tinggi_sel[$i][$k] = ceil(strlen($string)/$jumlah_karakter_standar[$k]);
                $tinggi_sel[$i][$k]=5.5*$jumlah_unit_tinggi_sel[$i][$k]; //dikalikan 5 untuk ukuran font 12, kalikan 18/4 untuk ukran font 10. 
            }
            $tinggi_baris[$i]=max($tinggi_sel[$i]);
        }

        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->SetFontSize('12');

        $fill = 0;
        //$y=$pdf->GetY();
        foreach($data as $s=>$row) {
            foreach($row as $k=>$isi) {
                //$k==0?$fill=1:$fill=0;
                //$pdf->MultiCell($w[$k],$tinggi_tiap_baris[$s], $isi, 1, 'L', $fill,0,'','',true);

                if($k==0){
                    $pdf->MultiCell($w[$k],$tinggi_baris[$s], ucwords(implode(' ',explode('_',$isi))).':', 0, 'L', 0, 0, '' ,'', true);
                }else {
                    if($data[$s][0]=='narasi_acara'){
                        //$pdf->MultiCell($w[$k],$tinggi_baris[$s], $pdf->writeHTML($isi, true, false, true, false, ''), 1, 'L', 0, 0, '' ,'', true);
                        //$pdf->Ln();
                        $pdf->writeHTML('<p></p>'.$isi, true, false, true, false, '');
                    }elseif($data[$s][0]=='lampiran_foto_acara'){
                    }else{
                        $pdf->MultiCell($w[$k],$tinggi_baris[$s], $isi, 0, 'L', 0, 0, '' ,'', true);
                    }
                }
            }

            $y=$pdf->GetY()+$tinggi_baris[$s];
            if($data[$s][0]=='narasi_acara'){
                $jumlah_perulangan1=ceil($y/$tinggi_page);
                $y2=$y+(($jumlah_perulangan1-1)*$tinggi_header);
                $jumlah_perulangan2=ceil($y2/$tinggi_page);
            }
            $pdf->Ln();
            $pdf->SetY($y);
                            
            if($y>(95/100)*$tinggi_page){
                //pertanyaanya, berapa kali addpage dilakukan?
                $pdf->addPage($orientasi);

                //Buka ini jika hendak meletakkan narasi ke dalam sel, tetapi disable blok kode ini jika bukan dalam sel, menulis writehtml() di luar sel.
                //karena jika tidak disable menyebabkan bertambahnya halaman kosong yang tidak perlu.
                /*
                if($data[$s][0]=='narasi_acara'){
                    for($n=1;$n<$jumlah_perulangan2-1;$n++){
                        $pdf->addPage($orientasi);
                    }
                }
                */

                //Color and font restoration pada halaman baru
                $pdf->SetDrawColor(128, 0, 0);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(0);
                $pdf->SetFont('');
                $pdf->SetFontSize('12');

                //$y=$pdf->GetY();
                $nilai_awal_baru_y=($y2-($jumlah_perulangan2-1)*$tinggi_page)+((($jumlah_perulangan2-1)*$tinggi_header))-(($jumlah_perulangan2-1)*$tinggi_footer); 
                //Penjelasan rumus:
                /**
                 * $y2 dihitung berdasarkan sebelumnya:
                 * $jumlah_perulangan1=ceil($y/$tinggi_page);
                 * $y2=$y+(($jumlah_perulangan1-1)*$tinggi_header);
                 * $jumlah_perulangan2=ceil($y2/$tinggi_page);
                 * 
                 * $jumlah_perulangan1 menyatakan banyaknya halaman yang diambil oleh sel tinggi baris dimana $key='narasi_acara'
                 * $y2 menyatakan lebih rinci sebagai ukuran absis Y yang diambil dalam seluruh halaman itu dengan menambahkan faktor ukuran tinggi_header
                 * $jumlah_perulangan2 menyatakan jumlah perulangan berdasarkan hitugan aktual absis Y yang diambil
                 * 
                 * ($y2-($jumlah_perulangan2-1)*$tinggi_page) menyatakan nilai absis Y sesuai jumlah halaman yang terambil
                 * ((($jumlah_perulangan2-1)*$tinggi_header)) menyatakan penambahan faktor tinggi header disetiap halaman yang terambil 
                 * -(($jumlah_perulangan2-1)*$tinggi_footer) menyatakan pengurangan dengan faktor tinggi footer yang terambil di setiap halaman yang terambil
                 * $nilai_awal_baru_y menyatakan nilai aktual absis Y untuk memulai menggambar sel berikut di halaman pdf baru.
                 * 
                 * rumus ini masih memiliki kelemahan, yaitu dia berantakan ketika $nilai_awal_baru_y mencapai bagian akhir halaman tetapi sel baru harus di gambar di halaman berikutnya.
                 * mungkin kelemahan ini dapat dikontrol dengan mengaatur persentase halaman yang mau digunakan untuk menampilkan isi dalam halaman pdf.
                 * yaitu pada pernyataan baris kode:  
                 * if($y>(85/100)*$tinggi_page){ 
                 * ubah perbandingan 85/100 sesuai keinginan, 85 artinya 85% halaman digunakan untuk isi pdf, sisanya kosong atau untuk header dan footer.
                 */
                //$pdf->SetY($nilai_awal_baru_y);

                //Jika menghendaki halaman baru setelah 'narasi_acara':
                ///*
                //$pdf->addPage($orientasi);
                $nilai_awal_baru_y=$pdf->GetY();
                //*/
                $pdf->SetY($nilai_awal_baru_y);
            }
        }

        //Ini disable dulu.
        //$pdf->Cell(array_sum($w), 0, '', 'T');
        //====================================================
        
		if($data_1rekord['nama_file_foto']!==''){
            $item_foto=explode('; ',$foto);
            $item_keterangan_foto=explode(';',$keterangan_foto);
            //$pdf->SetY($pdf->GetY());
            $y=$pdf->getY();
            //$pos_text=2/3*((70/100)*$lebar_page);
            foreach($item_foto as $key=>$item){
                //$pdf->Image($item,'',$y,(70/100)*$lebar_page,2/3*((70/100)*$lebar_page),'','','C');
                $pdf->MultiCell((85/100)*$lebar_page,2/3*((77/100)*$lebar_page), $pdf->Image($item,'','',(85/100)*$lebar_page,2/3*((77/100)*$lebar_page),'',''), 0, 'C', 0, 0, '' ,'', true);
                $pdf->Ln();
                isset($item_keterangan_foto[$key])?$pdf->MultiCell($lebar_page,20, $pdf->write(10,$item_keterangan_foto[$key],'','','C'), 0, 'C', 0, 0, '' ,'', true)
                :$pdf->MultiCell($lebar_page,20, $pdf->write(10,'Foto kegiatan ke-'.$key,'',false,'C',true), 0, 'C', 0, 0, '' ,'', true);
                $pdf->Ln();
            }
        }
        $pdf->Output($output_name,$output_dest); //kenapa kemarin file keluaran tidak ada ekstensinya karena nama file tidak diberi ekstensi pdf.
        unset($pdf);
    }
}

if (!function_exists('tes_tcpdf'))
{
    // Simple table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function tes_tcpdf($string,$output_dest='I',$output_name='laporan_kegiatan.pdf',$nama_satker='BKD Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban BKD',$lebar_page,$tinggi_tiap_baris=6,$tinggi_page=841.890,$orientasi='')
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT,array($lebar_page,$tinggi_page), true, 'UTF-8', false);
        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Andi Sinra, Aslan Alwi and Munirah Muslim');
        $output_name!==NULL?$pdf->SetTitle($output_name):$pdf->SetTitle('Laporan PDF');
        $pdf->SetSubject('Laporan berbentuk PDF');
        $pdf->SetKeywords('TCPDF, PDF, laporan, test, andi sinra');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,$nama_satker, $nama_bidang);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        //====================================================
        
        $pdf->AddPage($orientasi);

        // Colors, line width and bold font
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(128, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B');
        $pdf->SetFontSize('12');

        //cetak judul:
        $pdf->Cell($lebar_page, 10,$string, 1, 0, 'L', 1);
        //$pdf->MultiCell(((9/10)*$lebar_page),10, $judul, 1, 'C',0,1,'','',true);
        $pdf->Ln();

        /*
        //ubah dulu data yang masuk satu data baru berbentuk vertikal
        //assumsi data yang masuk berbentuk $data_1rekord['nama_kolom']=nilai_kolom,dan hanya 1 rekord, 
        //karena yang dicetak rencananya hanya 1 rekord.
        $i=0;
        foreach($data_1rekord as $key=>$row){
            $data[$i]=array($key,$row);
            $i++;
        }
        
        //bagaimana menentukan panjang tiap sel? 
        $panjang_tiap_sel=array();
        $panjang_tiap_sel[0]=1/3*((9/10)*$lebar_page); //didasarkan pada hitungan: panjang tabel diambil sebagai 90% atau 9/10 dari lebar page
        $panjang_tiap_sel[1]=2/3*((9/10)*$lebar_page);//kemudian untuk sel pertama dari panjang tabel adalah 1/3 nya, dan sel kedua adalah 2/3 nya.
        
        //penentuan tinggi tiap baris?
        $tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			foreach($data as $k=>$row){
				foreach($row as $s=>$isi){
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:$max=$kandidat;
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
        
        $w = $panjang_tiap_sel;

        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->SetFontSize('10');
        // Data
        $fill = 0;
        $counter=0;
        foreach($data as $s=>$row) {
            foreach($row as $k=>$isi) {
                $k==0?$fill=1:$fill=0;
                $pdf->MultiCell($w[$k],$tinggi_tiap_baris[$s], $isi, 1, 'L', $fill,0,'','',true);
            }

            $pdf->Ln();
            //if($s!==0&&$s%50==0){
            if($counter*$tinggi_tiap_baris[0]>(85/100)*$tinggi_page){
                $counter=0;
                $pdf->addPage($orientasi);

                // Color and font restoration pada halaman baru
                $pdf->SetDrawColor(128, 0, 0);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFillColor(224, 235, 255);
                $pdf->SetTextColor(0);
                $pdf->SetFont('');
                $pdf->SetFontSize('10');
            }
            $counter++;
            
        }
        $pdf->Cell(array_sum($w), 0, '', 'T');
        //alert("TINGGI TABEL: ".(50*$tinggi_tiap_baris[0]));
        //alert("TINGGI HALAMAN: ".$pdf->getPageHeight());
        //alert("LEBAR HALAMAN: ".$pdf->getPageWidth());
        //====================================================
        */
        $pdf->Output($output_name,$output_dest); //kenapa kemarin file keluaran tidak ada ekstensinya karena nama file tidak diberi ekstensi pdf.
        unset($pdf);
    }

}

if (!function_exists('ImprovedTable'))
{
    // Better table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function ImprovedTable($header, $data,$column_width = array(40, 35, 40, 45))
    {
        
        $pdf = new FPDF();
        // Column widths
        $w=&$column_width;
        // Header
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->Ln();
        // Data
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR');
            $this->Cell($w[1],6,$row[1],'LR');
            $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
            $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $pdf->Output();
        unset($pdf);
    }
}

if (!function_exists('FancyTable'))
{
    // Colored table
    //http://www.fpdf.org/en/tutorial/tuto5.htm
    function FancyTable($header,$data,$column_width = array(40, 35, 40, 45),$fillcolor=array(255,0,0),$textcolor=array(255,0,0),$drawcolor=array(128,0,0),$linewidth=0.3,$font=array('','B'))
    {
        
        $pdf = new FPDF();
        // Colors, line width and bold font
        $this->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
        $this->SetTextColor($textcolor[0],$textcolor[1],$textcolor[2]);
        $this->SetDrawColor($drawcolor[0],$drawcolor[1],$drawcolor[2]);
        $this->SetLineWidth($linewidth);
        $this->SetFont($font[0],$font[1]);
        // Header
        $w = &$column_width;
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
            $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
            $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
            $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
        $pdf->Output();
        unset($pdf);
    }
}

if (!function_exists('pengambil_data_post_get'))
{
    function pengambil_data_post_get($data_nama_post_get,$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/'){
        $data=array();
        foreach($data_nama_post_get as $k){
            if(isset($_REQUEST[$k])){
                $data[$k]['nilai']=$_REQUEST[$k];
                $data[$k]['file']=NULL;
            }else {
                $data[$k]['nilai']=$_FILES[$k]['name'];
                $data[$k]['file']=$directory_relatif_file_upload.$_FILES[$k]['name'];
            }
        }
        return $data;
    }
}
if (!function_exists('myErrorHandler'))
{
    function myErrorHandler($errno, $errstr, $errfile, $errline) {
        echo "Mohon maaf terjadi error.<br>";
        echo "<b>Rincian error: </b> [$errno] $errstr<br>";
        echo " Error pada baris $errline di file $errfile<br>";
    }
}

if (!function_exists('konversi_format_tgl_ttttbbhh_ke_hhbbtttt'))
{
    //Fungsi ini direncanakan untuk mengkonversi bentuk tttt-bb-hh atau tttt/bb/hh menjadi hh/bb/tttt
    function konversi_format_tgl_ttttbbhh_ke_hhbbtttt($tanggal){
        if(strpos($tanggal,'-')){
            return implode('/',array_reverse(explode('-',$tanggal)));
        }else{
            return implode('/',array_reverse(explode('/',$tanggal)));
        }
    }
}

if (!function_exists('upload'))
{
//OK LANGSUNG AJA 
	//---------------------------------------
	/**
	 * A function for easily uploading files. This function will automatically generate a new 
	 *        file name so that files are not overwritten.
	 * Taken From: http://www.bin-co.com/php/scripts/upload_function/
	 * Arguments:    $file_id- The name of the input field contianing the file.
	 *                $folder    - The folder to which the file should be uploaded to - it must be writable. OPTIONAL
	 *                $types    - A list of comma(,) seperated extensions that can be uploaded. If it is empty, anything goes OPTIONAL
	 * Returns  : This is somewhat complicated - this function returns an array with two values...
	 *                The first element is randomly generated filename to which the file was uploaded to.
	 *                The second element is the status - if the upload failed, it will be 'Error : Cannot upload the file 'name.txt'.' or something like that
	 */
	function upload($file_id, $folder="", $types="") {
		//apakah kita memilih file?
		if(!$_FILES[$file_id]['name']) return array('','No file specified');

        $file_title = $_FILES[$file_id]['name'];
		//Get file extension
        $ext_arr = explode(".",basename($file_title));
		$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension

		//Not really uniqe - but for all practical reasons, it is
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . $file_title;//Get Unique Name

		$all_types = explode(",",strtolower($types));
		if($types) {
			if(in_array($ext,$all_types));
			else {
				$result = "'".$_FILES[$file_id]['name']."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}

		//Where the file must be uploaded to
		if($folder) $folder .= '/';//Add a '/' at the end of the folder
		$uploadfile = $folder . $file_name;

		$result = '';
		//Move the file from the stored location to the new location
		if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
			$result = "Cannot upload the file '".$_FILES[$file_id]['name']."'"; //Show error if any.
			if(!file_exists($folder)) {
				$result .= " : Folder don't exist.";
			} elseif(!is_writable($folder)) {
				$result .= " : Folder not writable.";
			} elseif(!is_writable($uploadfile)) {
				$result .= " : File not writable.";
			}
			$file_name = '';
			
		} else {
			if(!$_FILES[$file_id]['size']) { //Check if the file is made
				@unlink($uploadfile);//Delete the Empty file
				$file_name = '';
				$result = "Empty file found - please use a valid file."; //Show the error message
			} else {
				chmod($uploadfile,0777);//Make it universally writable.
			}
		}

		return array($file_name,$result);
    }
}
?>