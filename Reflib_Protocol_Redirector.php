<?php

require('Bahai_Writings_Protocol_Redirector.php');


class Reflib_Protocol_Redirector extends Bahai_Writings_Protocol_Redirector {
    protected function check_match () {
        $query = (int) $this->query;
        $work = $this->work;
        $language = $this->language;
        
        // This shows sample format, http://reference.bahai.org/reference?language=en&work=esw&pagenum=4
        // Also may utilize &action=highlight and &query=<term>
        // http://reference.bahai.org/en/t/b/KI/ki-6.html.utf8?query=Servitude&action=highlight#gr198
        // http://reference.bahai.org/en/t/b/KA/ka-4.html.utf8?query=laws&action=highlight#gr4
        
        if (!$this->unit) { // We let the Reflib site validate since it allows these params through internal API
            // Not present also below: esw, pb, tb; abl, fwu, pt, pup, sdc, taf, tab; adj, ba, cf, dnd, lanz, ldg1, ldg2, ma, mc (1999 ed.), mbw, pdc (already numbered in other editions but not at Reflib), ud, wob; ocf, sb (could perhaps be done as chapter numbers, but none listed at Reflib if print copy had any); bp, jwta;
            return 'http://reference.bahai.org/reference?language='.
                    $language. '&work='. $work. '&pagenum=' . $query;
        }
        if ($this->unit === 'par') { // We let the Reflib site validate since it allows these params through internal API
            // Not present also below: gdm, slh
            return 'http://reference.bahai.org/reference?language='.
                    $language. '&work='. $work. '&paragraph=' . $query;
        }

        $base = 'http://reference.bahai.org/' . $language;
        // $fileno = null;
        $inc = null;
        
        switch ($work) {
            // Works of Baha'u'llah
            case 'ka':
                $base .= '/t/b/KA/ka-';
                /*
                if ($this->unit === 'par') {
                    //if ($language === 'en') { // Different by language?
                    if ($query >= 1 && $query <= 40) {
                        $fileno = 4;
                    }
                    else if ($query >= 41 && $query <= 80) {
                        $fileno = 5;
                    }
                    else if ($query >= 81 && $query <= 120) {
                        $fileno = 6;
                    }
                    else if ($query >= 121 && $query <= 160) {
                        $fileno = 7;
                    }
                    else if ($query >= 161 && $query <= 190) {
                        $fileno = 8;
                    }
                    else {
                        break;
                    }
                    return $base . $fileno . '.html#gr' . $query;
                }
                */
                if ($this->unit === 'qa' && $query >= 1 && $query <= 107) {
                    return $base . '15.html' . '#Question%20' . $query;
                }
                if ($this->unit === 'syn') {
                    // "syn" items are not supported by item for Reflib
                    return $base . '17.html';
                }
                if ($this->unit === 'note' && $query >= 1 && $query <= 194) {
                    $inc = 17;
                }
                break;
            /*
            case 'gdm':
                $base .= '/t/b/GDM/gdm-';
                if ($this->unit === 'par') {
                    if ($query >= 1 && $query <= 40) {
                        $fileno = 3;
                    }
                    else if ($query >= 41 && $query <= 80) {
                        $fileno = 4;
                    }
                    else if ($query >= 81 && $query <= 117) {
                        $fileno = 5;
                    }
                    else {
                        break;
                    }
                }
                return $base . $fileno . '.html#gr' . $query;
            */
            case 'gwb':
                $base .= '/t/b/GWB/gwb-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 166) {
                    $inc = 0;
                }
                break;
            // Note: Preambles are each numbered as 0, conclusion to Persian is 83
            case 'hw':
                $base .= '/t/b/HW/hw-';
                if (($this->unit === 'a' && $query >= 0 && $query <= 71) || 
                    ($this->unit === 'p' && $query >= 0 && $query <= 83)) {
                    $inc = $this->unit === 'a' ? 1 : (($query === 83) ? 72 : 73); // Pers. "83" is on same page as 82
                }
                break;
            case 'pm':
                $base .= '/t/b/PM/pm-';
                if ($this->unit === 'sec') {
                    $inc = 0;
                }
                break;
            case 'sv':
                $base .= '/t/b/SVFV/svfv-';
                if ($this->unit === 'valley' && $query >= 0 && $query <= 7) {
                    $inc = ($query === 0) ? 1 : 0;
                }
                break;
            case 'fv':
                $base .= '/t/b/SVFV/svfv-';
                if ($this->unit === 'valley' && $query >= 0 && $query <= 4) { // 0 is the preamble
                    $inc = 8;
                }
                break;
            case 'tu':
                $base .= '/t/b/TU/tu-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 5) {
                    $inc = 2;
                }
                break;
                
            // Works of the Bab
            case 'swb':
                $base .= '/t/b/SWB/swb-';
                if ($this->unit === 'sec') {
                    switch ($query) {
                        case 1:
                            $inc = 0;
                            break;
                        case 2:
                            $inc = 8;
                            break;
                        case 3:
                            $inc = 65;
                            break;
                        case 4:
                            $inc = 103;
                            break;
                        case 5:
                            $inc = 112;
                            break;
                        case 6:
                            $inc = 132;
                            break;
                        case 7:
                            $inc = 146;
                            break;
                        default:
                            break;
                    }
                }
                break;
                
            // Works of 'Abdu'l-Baha
            case 'mf':
                $base .= '/t/ab/MF/mf-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 65) {
                    $inc = 0;
                }
                break;
            case 'sab':
                $base .= '/t/ab/SAB/sab-';
                if ($this->unit === 'sec' && $query >= 0 && $query <= 237) { // 0 is for references to the Qur'an
                    $inc = 1;
                }
                break;
            case 'saq':
                $base .= '/t/ab/SAQ/saq-';
                if ($this->unit === 'chapter') { // Item between Chapter 66 and 67 on Immortality of Children, though put separately on Reflib, cannot easily have its own "chapter" number unless allow 66.5?
                    if ($query >= 1 && $query <= 66) {
                        $inc = 0;
                    }
                    else if ($query >= 67 && $query <= 84) {
                        $inc = 1;
                    }
                }
                break;
            case 'tdp':
                $base .= '/t/ab/TDP/tdp-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 14) {
                    $inc = 0;
                }
                break;
            case 'wt':
                $base .= '/t/ab/WT/wt-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 3) {
                    $inc = 0;
                }
                break;
            
            // Works of Shoghi Effendi
            case 'aro':
                $base .= '/t/se/ARO/aro-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 82) { // 74-82 numbered differently
                    $inc = 0;
                }
                else if ($this->unit === 'note' && $query >= 1 && $query <= 10) {
                    $inc = 82;
                }
                break;
            case 'dg':
                $base .= '/t/se/DG/dg-';
                if ($this->unit === 'sec' && $query >= 1 && $query <= 3) {
                    $inc = 0;
                }
                break;
            case 'gpb':
                $base .= '/t/se/GPB/gpb-';
                if ($this->unit === 'chapter' && $query >= 0 && $query <= 26) { // 0 is treated as foreword (nothing for Intro by Townshend), 26 is treated as retrospect/prospect
                    $inc = 1;
                }
                break;
            case 'he':
                $base .= '/t/se/HE/he-';
                if ($this->unit === 'sec') { 
                    if ($query === 0) { // We'll treat 0 as "I desire for you..." before text rather than subsequent (and shorter) "Supplication" quote/section
                        $inc = 1;
                    }
                    else if ($query >= 1 && $query <= 105) { // We'll treat the Appendix as 105
                        $inc = 2;
                    }
                }
                break;
            // Works of the Universal House of Justice
            case 'pwp':
                $base .= '/t/uhj/PWP/pwp-';
                if ($this->unit === 'sec' && $query >= 0 && $query <= 4) {
                    $inc = 1;
                }
                break;
            // Bahá'í International Community Documents and Statements
            case 'col':
                $base .= '/t/bic/COL/col-';
                if ($this->unit === 'sec' && $query >= 0 && $query <= 12) { // 0 will be introductory part rather than short Foreword
                    $inc = 2;
                }
                break;
            case 'prh':
                $base .= '/t/bic/PRH/prh-';
                if ($this->unit === 'sec' && $query >= 0 && $query <= 7) { // 0 will be introductory part rather than letter of introduction by House
                    $inc = 2;
                }
                break;
            
            // Compilations
            
            case 'bwf': // Just 'Abdu'l-Baha's section
                $base .= '/t/c/BWF/bwf-';
                if ($this->unit === 'chapter') {
                    switch ($query) { // Other chapters exist in BWF, but not handled at Reflib
                        case 6:
                            $inc = -5;
                            break;
                        case 7:
                            $inc = 13;
                            break;
                        case 8:
                            $inc = 30;
                            break;
                        case 9:
                            $inc = 101;
                            break;
                        default:
                            break;
                    }
                }
                break;
            case 'be':
                $base .= '/t/c/BE/be-';
                if ($this->unit === 'sel' && $query >= 0 && $query <= 153) { // 0 is frontispiece
                    $inc = 1;
                }
                break;
            case 'cp':
                $base .= '/t/c/CP/cp-';
                if ($this->unit === 'sel') {
                    if ($query >= 0 && $query <= 44) { // 45 is not present at Reflib
                        $inc = 0;
                    }
                    else if ($query >= 46 && $query <= 76) {
                        $inc = -1;
                    }
                }
                break;
            case 'sch':
                $base .= '/t/c/SCH/sch-';
                if ($this->unit === 'sel') {
                    if ($query >= 0 && $query <= 73) { // 74 is not present at Reflib
                        $inc = 1;
                    }
                    else if ($query >= 75 && $query <= 78) {
                        $inc = 0;
                    }
                }
                break;
            case 'cw':
                $base .= '/t/c/CW/cw-';
                if ($this->unit === 'sel') {
                    if ($query >= 1 && $query <= 82) { // 83 is not present at Reflib
                        $inc = 0;
                    }
                    // The following all appear mistaken (except 112) as should be one lower (reported to site admin)
                    else if ($query >= 84 && $query <= 111) {
                        $inc = -1;
                    }
                    else if ($query === 112) {
                        $inc = 0;
                    }
                    else if ($query === 113) {
                        $inc = -1;
                    }
                    else if ($query >= 114 && $query <= 122) { // 122 is the Biblio
                        $inc = -2;
                    }
                }
                break;
            case 'hc':
                $base .= '/t/c/HC/hc-';
                if ($this->unit === 'sel' && $query >= 1 && $query <= 113) {
                    $inc = 0;
                }
                break;
            
            // Other
            case 'bne':
                $base .= '/t/je/BNE/bne-';
                if ($this->unit === 'chapter') {
                    switch ($query) { // Other chapters exist in BWF, but not handled at Reflib
                        // todo: fix as array
                        case 1:
                            $inc = 5;
                            break;
                        case 2:
                            $inc = 12;
                            break;
                        case 3:
                            $inc = 24;
                            break;
                        case 4:
                            $inc = 40;
                            break;
                        case 5:
                            $inc = 54;
                            break;
                        case 6:
                            $inc = 66;
                            break;
                        case 7:
                            $inc = 74;
                            break;
                        case 8:
                            $inc = 88;
                            break;
                        case 9:
                            $inc = 99;
                            break;
                        case 10:
                            $inc = 120;
                            break;
                        case 11:
                            $inc = 131;
                            break;
                        case 12:
                            $inc = 145;
                            break;
                        case 13:
                            $inc = 155;
                            break;
                        case 14:
                            $inc = 167;
                            break;
                        case 15:
                            $inc = 173;
                            break;
                        default:
                            break;
                    }
                }
                break;
            case 'bk':
                $base .= '/t/bwc/BK/bk-';
                if ($this->unit === 'part') {
                    switch ($query) {
                        case 1:
                            $inc = 1;
                            break;
                        case 2:
                            $inc = 2;
                            break;
                        case 3:
                            $inc = 22;
                            break;
                        case 4:
                            $inc = 54;
                            break;
                        case 5:
                            $inc = 86;
                            break;
                        case 6:
                            $inc = 177;
                            break;
                    }
                }
                break;
            case 'db':
                $base .= '/t/nz/DB/db-';
                if ($this->unit === 'chapter' && $query >= 1 && $query <= 27) { // 27 as Epilogue; less clear what 0 should be if anything
                    $inc = 19;
                }
                break;
        }
        if (is_null($inc)) {
            $this->report_error("No support found for unit type and query.");
        }
        return $base . ($query + $inc) . '.html';
    }
}


?>