<?php

/**
 * Check class provides options to check what time and memory costs a function or whole application
 *
 * @author Венцислав Кьоровски
 */
class Check {
    private $MarkedTimes = array();
    private $MarkedMemories = array();
    
    public function Check() {
        global $nAppStartTime, $nAppStartMemory;
        $this->MarkTime(APP_START, $nAppStartTime);
        $this->MarkMemory(APP_START, $nAppStartMemory);
    }
    
    public function MarkTime($sMarker, $nTime = null) {
        if(is_null($nTime)) {
            $nTime = array_sum(explode(' ', microtime()));
        }
        $this->MarkedTimes[$sMarker] = $nTime;
    }
    
    public function MarkMemory($sMarker, $nSize = null) {
        if(is_null($nSize)) {
            $nSize = memory_get_usage(true);
        }
        $this->MarkedMemories[$sMarker] = $nSize;
    }
    
    public function CompareMemories($sStartMarker = null, $sEndMarker = null) {
        return $this->Compare($sStartMarker, $sEndMarker, 'Memory');
    }
    
    public function CompareTimes($sStartMarker = null, $sEndMarker = null) {
        return $this->Compare($sStartMarker, $sEndMarker, 'Time');
    }
    
    private function Compare($sStartMarker = null, $sEndMarker = null, $sType = 'Time') {
        $arMarketValues = $sType == 'Time' ? $this->MarkedTimes : $this->MarkedMemories;
        if(!isset($arMarketValues[$sStartMarker])) {
            if($sType == 'Time') {
                show_error("Can't find start time.");
            } else {
                $nStartValue = 0;
            }
        } else {
            $nStartValue = $arMarketValues[$sStartMarker];
        }
        
        if(!isset($arMarketValues[$sEndMarker])) {
            $nEndValue = $sType == 'Time' ? array_sum(explode(' ' , microtime())) : memory_get_usage(true);
        } else {
            $nEndValue = $arMarketValues[$sEndMarker];
        }
        
        return $sType == 'Time' ? sprintf('%.4f', $nEndValue - $nStartValue) : $this->Convert($nEndValue - $nStartValue); 
    }
    
    public function Convert($vSize) {
        $arUnits = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($vSize/pow(1024,($i=floor(log($vSize,1024)))),2).' '.$arUnits[$i];
    }
}

//function convert($size)
//{
//    $unit=array('b','kb','mb','gb','tb','pb');
//    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
//}

//echo convert(memory_get_usage(true));
