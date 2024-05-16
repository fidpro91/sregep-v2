<?php
namespace App\Libraries;

use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class Servant
{
    public static function connect_simrs($method,$url,$data = array()){
        $ch = curl_init(); 
        $base_url = "localhost:88/ehos/api/api_internal/";
        $url = $base_url.$url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($ch);
        if(curl_errno($ch)){
            echo 'Request Error:' . curl_error($ch);
        }
        curl_close($ch);
        return ($result);
    }

    public static function get_menu($id=0)
    {
        $datam =    DB::table('ms_menu as m')->where([
                        "menu_parent_id"	=> $id,
                        "menu_status"	    => 't',
                        // "ga.group_id"       => Auth::user()->group_id
                    ])->orderBy('menu_code');
                    // ->join("group_access as ga","ga.menu_id","=","m.menu_id");
        $menux='';
        foreach ($datam->get() as $key => $value) {
            if ( DB::table('ms_menu')->where(["menu_parent_id"	=> $value->menu_id])->count() > 0) {
                $menux .= "<li><a href=\"#\">
                                <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'fa fa-circle-o')."\"></i> <span>".strtoupper($value->menu_name)."</span> <span class=\"menu-arrow\"></span>
                                </a>
                                <ul class=\"nav-second-level\" aria-expanded=\"false\">";
                $menux .= self::get_menu($value->menu_id);
                $menux .= "</ul></li>";
            }else{
                $fun="";
                if (!empty($value->menu_function)) {
                    $fun = "onclick=\"$value->menu_function(this,event)\"";
                }
                $menux .= "<li><a $fun href=\"".URL("$value->menu_url")."\">
                        <i class=\"".(!empty($value->menu_icon)?$value->menu_icon:'')."\"></i><span>".strtoupper($value->menu_name)."</span>
                        </a></li>";
            }
        }
        return $menux;
    }

    public static function generate_code_transaksi($data){
		$query = DB::table($data['table'])->selectRaw("LPAD((max(COALESCE(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(".$data['column'].",'".$data['delimiterFirst']."',".$data['limit']."),'".$data['delimiterLast']."','".$data['number']."') AS UNSIGNED),0))+1),5,'0') AS nomax")->get()->first();
		if (empty($query->nomax)) {
            $query->nomax = 1;
        }
        return str_replace('NOMOR', $query->nomax, $data['text']);
	}

    public static function validasi_register($type,$param)
    {
        $resp = [
            "code"      => 200,
            "message"   => "OK"
        ];

        switch ($type) {
            case '1': //untuk validasi tujuan kunjungan yang sama tanggal sama
                $visit = Visit::where([
                            "unit_id"       => $param["unit_id"],
                            "px_id"         => $param["px_id"],
                         ])
                         ->whereRaw("visit_status != '0'")
                         ->whereDate("visit_date",$param["date"])
                         ->first();
                if ($visit) {
                    $resp = [
                        "code"      => 202,
                        "message"   => "Pasien Telah Berkunjung Dipoli Yang Sama. Silahkan Memilih Poli Tujuan Lainnya."
                    ];
                }
                break;
            
            default:
                # code...
                break;
        }

        return $resp;
    }
}