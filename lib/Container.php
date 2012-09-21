<?php


class GraphContainer {

    private $view;

    public function __construct($time) {
        $this->view = array();
        
        $this->view['times'] = Dashboard::getTimes();
        $this->view['sampletimes'] = Dashboard::getSampleTimes();
        $this->view['prettytime'] = Dashboard::displayTime($time);
    }

    public function addGraph($urlOfGraph) {
        $this->view['body'][] = $urlOfGraph;
    }
    
    public function addGraphGroupHeading($title)
    {
        $this->view['body'][] = $title; 
    }
    
    public function setGraphTime($time) {
        $this->view['time'] = $time;
    }
    
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
