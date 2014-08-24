<?php

/**
 * Check class provides options to check what time and memory costs a function or whole application
 *
 * @author Венцислав Кьоровски
 */
class Check {
    private $MarkedTimes = array();
    
    public function Check() {
        global $nAppStartTime;
        $this->Add(APP_START, $nAppStartTime);
    }
    
    public function Add($sMarker, $nTime = null) {
        if(is_null($nTime)) {
            $nTime = array_sum(explode(' ', microtime()));
        }
        $this->MarkedTimes[$sMarker] = $nTime;
    }
    
    public function Compare($sStartMarker, $sEndMarker = null) {
        $MarkedTimes = $this->MarkedTimes;
        if(!isset($MarkedTimes[$sStartMarker])) {
            show_error("Can't find start time.");
        }
        
        $nStartTime = $MarkedTimes[$sStartMarker];
        
        if(!isset($MarkedTimes[$sEndMarker])) {
            $nEndTime = array_sum(explode(' ' , microtime()));
        } else {
            $nEndTime = $MarkedTimes[$sEndMarker];
        }
        
        return sprintf('%.4f', $nEndTime - $nStartTime);
    }
}

//function convert($size)
//{
//    $unit=array('b','kb','mb','gb','tb','pb');
//    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
//}

//echo convert(memory_get_usage(true));
