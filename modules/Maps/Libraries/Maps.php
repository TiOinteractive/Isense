<?php

namespace Modules\Maps\Libraries;

use Modules\Maps\Models\MapsModel;

class Maps
{
    public function __construct()
    {   
        $this->request = \Config\Services::request();
        $this->response = \Config\Services::response();
        $this->session = \Config\Services::session();
        $this->mapsModel = new MapsModel();
    }
    
    public function index($content, $id_lang, $slug='') 
    {
        if(!empty($content['id_element'])) {
            $map = $this->mapsModel
                    ->join('maps_lang ml', 'maps.id=ml.id_map')
                    ->select('maps.id,ml.name,maps.center,maps.type,maps.zoom,maps.styles')
                    ->where('maps.id', $content['id_element'])
                    ->where('maps.publish', 1)
                    ->where('ml.id_lang', $id_lang)
                    ->first();
            if(!empty($map)) {
                $map['settings'] = $this->mapsModel->getMapsSettings();
                $map['markers'] = $this->mapsModel->db
                        ->table('maps_marker mm')
                        ->join('maps_marker_lang mml', 'mm.id=mml.id_marker')
                        ->join('files f', 'f.id=mm.id_file', 'left')
                        ->select('mm.id,mml.title,mml.caption,mm.svg,mm.cords,f.path as file')
                        ->where('mm.publish', 1)
                        ->where('mm.id_map', $map['id'])
                        ->where('mml.id_lang', $id_lang)
                        ->get()
                        ->getResultArray();
            }
            return $map;
        }
        return null;
    }
    
    public function assets($slug='', $template='', $id_map=0, $data=array()) {
        $assets = array(
            'js' => array(),
            'css' => array(),
            'css_footer' => array()
        );
        $markers = array();
        $coordinates = array(0, 0);
        if(!empty($data)) {
            if(!empty($data['center'])) {
                $coordinates = explode(',', $data['center']);
            }
            if(!empty($data['markers'])) {
                foreach($data['markers'] as $marker) {
                    if($marker['file'] && is_file(WRITEPATH . 'uploads/' . $marker['file'])) {
                        $img_size = getimagesize(WRITEPATH . 'uploads/' . $marker['file']);
                    } else {
                        $img_size = array(0, 0);
                    }
                    $cords = explode(',', $marker['cords']);
                    $markers[] = array(
                        'title' => $marker['title'],
                        'lat' => !empty($cords[0]) ? $cords[0] : '',
                        'lng' => !empty($cords[1]) ? $cords[1] : '',
                        'text' => str_replace("\r\n", "<br />", $marker['caption']),
                        'icon' => $marker['file'] ? '/image/' . $marker['file'] : '',
                        'icon_width' => $img_size[0],
                        'icon_height' => $img_size[1],
                        'svg' => $marker['svg'],
                    );
                }
            }
        }
        switch($data['type']) {
            case 'open_street_map':
                $assets['css_footer'][] = 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css';
                $assets['js'][] = 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js';
                $assets['js'][] = '/assets/js/maps-openstreetmap.js';
                $mapbox_token = !empty($data['settings']) && !empty($data['settings']['mapbox_token']) ? $data['settings']['mapbox_token'] : (string) env('mapbox.accessToken');
                $assets['js_ready'][''] = "$('#map-" . $id_map. "').OpenStreetMap('map-" . $id_map. "', {lat: " . (!empty($coordinates[0]) ? $coordinates[0] : '') . ", lng: " . (!empty($coordinates[1]) ? $coordinates[1] : '') . ", zoom: " . (!empty($data['zoom']) ? $data['zoom'] : '14') . ", styles: " . (!empty($data['styles']) ? $data['styles'] : '[]') . ", mapbox_token: " . json_encode($mapbox_token) . ", markers: '" . json_encode($markers) . "'});";
                break;
            case 'google_maps';
            default :
                $assets['js'][] = 'https://maps.google.com/maps/api/js?key=' . (!empty($data['settings']) && !empty($data['settings']['google_map_key']) ? $data['settings']['google_map_key'] : '');
                $assets['js'][] = '/assets/js/maps-googlemaps.js';
                $assets['js_ready'][''] = "$('#map-" . $id_map. "').GoogleMap('map-" . $id_map. "', {lat: " . (!empty($coordinates[0]) ? $coordinates[0] : '') . ", lng: " . (!empty($coordinates[1]) ? $coordinates[1] : '') . ", zoom: " . (!empty($data['zoom']) ? $data['zoom'] : '14') . ", styles: " . (!empty($data['styles']) ? $data['styles'] : '[]') . ", markers: '" . json_encode($markers) . "'});";
                break;
        }
        return $assets;
    }
    
}