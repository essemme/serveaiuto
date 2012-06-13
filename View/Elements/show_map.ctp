                    

                    <?php 
                        # include the map js code
                        echo $this->Html->script($this->GoogleMapV3->apiUrl()); 
                    ?>
                    
<div id="spostasu" style="clear: both;">
                    <?php # init map (prints container)

                    if(count($params) > 1) {
                        $extra_config = array(
                            'autoCenter' => true
                        );
                    } else {
                       $extra_config = array(
                            'lat' => $params[0]['lat'],
                            'lng' => $params[0]['lon'],
                            'zoom' => 15,
                        );
                    }               
                    
                        $config = am(
                                Configure::read('GoogleMap'), 
                                $extra_config
                                );

                        echo $this->GoogleMapV3->map($config); //array('div'=>array('height'=>'400', 'width'=>'100%') 
                        
                        
                       
                        
                        
                        foreach ($params as $param) {
                            if(isset($param['from']))
                                $url = $this->GoogleMapV3->url(array('to'=>$param['indirizzo_gmaps'], 'from' => $param['from']));
                            else
                                $url = $this->GoogleMapV3->url(array('to'=>$param['indirizzo_gmaps']));
                            
                            if(isset($param['dettagli'])) $param['content'] .= '<br />'.  $param['dettagli'] .'<br />';
                            
                        # add markers
                            $options = array(
                                'lat' => $param['lat'],
                                'lng' => $param['lon'],
                                
                                'title' => $param['title'], # optional
                                'content' => $param['content'] .' ' 
                                    .$this->Html->link('Cerca la strada per arrivarci con Google Maps', $url, array('target'=>'_blank', 'class' => 'superbutton green'))
                        # optional
                            );
                            if(!empty($param['icon'])) $options['icon'] =  $param['icon'];# optional
                            
                            
                            # tip: use it inside a for loop for multiple markers
                            $this->GoogleMapV3->addMarker($options);
                        }
                        
                        if(isset($params[0]['from_lat_lon'])) {
                             $options = array(
                                'lat' => $params[0]['from_lat_lon']['lat'],
                                'lng' => $params[0]['from_lat_lon']['lon'],
                                
                                'title' => 'Tu sei qui!', # optional
                                'icon'  => 'http://'. $_SERVER['SERVER_NAME']. '/img/you-are-here-2.png'
                            );
                             
                                                        
                            # tip: use it inside a for loop for multiple markers
                            $this->GoogleMapV3->addMarker($options);
                        }
                        
                        # print js
                        echo $this->GoogleMapV3->script(); 
                    ?>
</div>
<script type="text/javascript">
    // data (form markers) is usually used and rendered BEFORE the map. 
    // If you don't want to show your map at bootm (or after the data)
    // this jQuery line moves the generated map where you place
    // <div id="mappaqui"></div>
     
    $('#spostasu').insertBefore('#mappaqui');
    
</script>
