<?php


class GraphContainer {

    private $view;



    public function __construct($time, $title="No Title") {
        $this->view = array();
        $this->view['title'] = $title;
        $this->view['times'] = Dashboard::getTimes();
        $this->view['size'] = Dashboard::getChartSize();
        $this->view['sampletimes'] = Dashboard::getSampleTimes();
        $this->view['prettytime'] = Dashboard::displayTime($time);
    }

    public function addGraph($urlOfGraph, $name) {
        $graphAry["url"] = "$urlOfGraph";
        $graphAry["name"] = $name;
        $this->view['graphSet'][] = $graphAry;
    }
    
    public function addGraphGroupHeading($title)
    {
        $this->view['body'][] = $title; 
    }
    
    public function addName($name) {
        $this->view['name'][] = $name;
    }
    
    public function setGraphTime($time) {
        $this->view['time'] = $time;
    }
    
    //public function setGraphWidth($width) {
    //    $this->view['width'] = $width;
    //}
    
    public function setDownsampleTime($time) {
        $this->view['downsampletime'] = $time;
    }
    
    protected function renderLeftNav() {
        ob_start();
        $view = $this->view;
        include_once dirname(__FILE__) . '/Leftnav.php';
        $this->view['leftnav'] = ob_get_contents();
        ob_end_clean();
    }
    
    protected function renderGraphInstance() {
        ob_start();
        $view = $this->view;
        include_once dirname(__FILE__) . '/GraphInstnace.php';
        $this->view['graphinstance'] = ob_get_contents();
        ob_end_clean();
    }
    
    public function render() {
        
        // Get the nav contents.
        $this->renderLeftNav();
        
        // gen the template and the body.
        ob_start();
        $view = $this->view;
        include_once dirname(__FILE__) . '/Layout.php';
        ob_flush();
    }
      
}    

?>
