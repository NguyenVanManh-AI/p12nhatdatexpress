<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class Exchange extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        try {
            //lấy api giá vàng
            $gia_vang_url = 'http://giavang.doji.vn/api/giavang/?api_key=258fbd2a72ce8481089d88c678e9fe4f';
            //lấy api ngoại tệ
            $ngoaite_url = 'https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx';
            $client = new \GuzzleHttp\Client();
            $giavang = $client->request('GET', $gia_vang_url , [
                'headers' => ['Accept' => 'application/xml'],
                'timeout' => 120
            ])->getBody()->getContents();
            $giavangXml = simplexml_load_string($giavang);

            $ngoaite = $client->request('GET', $ngoaite_url , [
                'headers' => ['Accept' => 'application/xml'],
                'timeout' => 120
            ])->getBody()->getContents();
            $ngoaiteXml = simplexml_load_string($ngoaite);

            //giá vàng bán sjc
            $giavangsjcban = $giavangXml->JewelryList->Row[0]['Sell'];
            //giá bàng mua sjc
            $giavangsjcmua = $giavangXml->JewelryList->Row[0]['Buy'];

            //giá vàng bán doi
            $giavangdoijban = $giavangXml->JewelryList->Row[7]['Sell'];
            $giavangdoijban = str_replace(',', '',$giavangdoijban);
            $giavangdoijban = number_format($giavangdoijban*10);

            //giá vàng mua doi
            $giavangdoijmua = $giavangXml->JewelryList->Row[7]['Buy'];
            $giavangdoijmua = str_replace(',', '',$giavangdoijmua);
            $giavangdoijmua = number_format($giavangdoijmua*10);

            //giá usd mua
            $giausdmua = substr($ngoaiteXml->Exrate[19]["Buy"],0,6);
            //giá usd bán
            $giausdban = substr($ngoaiteXml->Exrate[19]["Sell"],0,6);
            //giá euro mua
            $giaeumua = substr($ngoaiteXml->Exrate[5]["Buy"],0,6);
            //giá euro bán
            $giaeuban = substr($ngoaiteXml->Exrate[5]["Sell"],0,6);
            return view('components.home.exchange',
                [
                    'giavangsjcban'=>$giavangsjcban,
                    'giavangsjcmua'=>$giavangsjcmua,
                    'giavangdoijban'=>$giavangdoijban,
                    'giavangdoijmua'=>$giavangdoijmua,
                    'giausdmua'=>$giausdmua,
                    'giausdban'=>$giausdban,
                    'giaeumua'=>$giaeumua,
                    'giaeuban'=>$giaeuban
                ]
            );
        }catch (\Exception $exception){
            return '';
        }

    }
}
