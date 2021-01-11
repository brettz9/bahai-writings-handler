<?php

require('Bahai_Writings_Protocol_Redirector.php');

class BL_Protocol_Redirector extends Bahai_Writings_Protocol_Redirector {
    public $map = [
        'en' => [
            // Keep: JSON find-and-replace
            // },\{'name':'[^']*','keyword':'([^']*)','url':
            // \n\t\t\t],\n\t\t\t'$1' => [\n\t\t\t\t'page' =>

            // Todo: Convert this to an external JSON file, and then:
            // 1. Use it here
            // 2. Use it to dynamically generate our HTML page; see
            //     https://bahai-library.com/abbreviations_bahai_writings#uses
            // 3. Make the Chrome import page (also on bahai-library.com) use
            //     the JSON instead of doing HTML parsing

            // Todo: Get 'cdb' with real page/paragraph anchors:
            //  https://www.bahai.org/library/authoritative-texts/bahaullah/call-divine-beloved/5#117574345

            'dor' => [
                'sel' => 'https://bahai-library.com/compilation_days_remembrance#sec%s',
                'page' => 'https://bahai-library.com/compilation_days_remembrance#sec%s'
            ],
            /*
            'b9' => [
                'page' => 'https://bahai9.com/wiki/%s'
            ],
        	'b9s' => [
				'page' => 'https://bahai9.com/index.php?search=insource%3A%s&title=Special%3ASearch&go=Go'
			],
			'bpedia' => [
				'page' => 'https://bahaipedia.org/%s'
			],
			'bpedias' => [
				'page' => 'https://bahaipedia.org/index.php?search=insource%3A%s&title=Special%3ASearch&fulltext=Search'
			],
			'bworks' => [
				'page' => 'https://bahai.works/%s'
			],
			'bworkss' => [
				'page' => 'https://bahai.works/index.php?search=insource%3A%s&title=Special%3ASearch&fulltext=Search'
			],
            */
			'sow' => [
                'search' => 'https://bahai.works/index.php?search=%s+prefix%3AStar_of_the_West%2F&ns0=1&ns4000=1&ns4010=1&ns4020=1',
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=https%3A%2F%2Fbahai.works%2FStar_of_the_West%2FVolume_%2B%2B%2B%2FIssue_%40%40%40%2FText%23pg~~~&search=%s'
			],
			'sows' => [
				'page' => 'https://bahai.works/index.php?search=%s+prefix%3AStar_of_the_West%2F&ns0=1&ns4000=1&ns4010=1&ns4020=1'
            ],
            'bnews' => [
                'search' => 'https://bahai.works/index.php?search=%s+prefix%3ABaha%27i_News%2F&ns0=1&ns4000=1&ns4010=1&ns4020=1',
                'page' => 'https://bahai-library.com/jumpto2.php?booklist=https%3A%2F%2Fbahai.works%2FBaha%27i_News%2FIssue_%2B%2B%2B%2FText%23pg%40%40%40&search=%s'
            ],
            'bnewss' => [
                'page' => 'https://bahai.works/index.php?search=%s+prefix%3ABaha%27i_News%2F&ns0=1&ns4000=1&ns4010=1&ns4020=1'
			],
            /*
			'bcommons' => [
				'page' => 'https://bahai.media/%s'
			],
			'bcommonss' => [
				'page' => 'https://bahai.media/index.php?search=insource%3A%s&title=Special%3ASearch&fulltext=Search'
			],
			'w' => [
				'page' => 'https://en.wikipedia.org/wiki/%s'
			],
			'ws' => [
				'page' => 'https://en.wikipedia.org/w/index.php?search=insource%3A%s&title=Special%3ASearch&go=Go&ns0=1'
			],
			'bls' => [
				'page' => 'https://www.google.com/search?q=site%3Abahai-library.com+%s&ie=utf-8&oe=utf-8&client=firefox-b-1-ab&gfe_rd=cr&dcr=0&ei=4Pp-WrqUF8uhX5DPrvAL'
			],
			'b' => [
				'page' => 'https://www.google.com/search?q=site%3Abahai-library.com+%s&ie=utf-8&oe=utf-8&client=firefox-b-1-ab&gfe_rd=cr&dcr=0&ei=4Pp-WrqUF8uhX5DPrvAL'
			],
            */
			'abl' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/abl/abdulbahalondon.html#%s'
			],
			'adj' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/adj/adj.html#%s'
			],
			'adp' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fabdulbaha_divine_philosophy%26chapter%3D%2B%2B%2B%23%40%40%40&search=%s'
			],
			'man' => [
                'year' => 'https://bahai-library.com/writings/shoghieffendi/antipodes/%s.html',
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fshoghieffendi%2Fantipodes%2Fantipodes.html%23%40%40%40&search=%s'
			],
			'manyear' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/antipodes/%s.html'
			],
			'aro' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/arohanui/aro.html#%s'
			],
			'ba' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/ba/ba-all.html#%s'
			],
            /*
            // Todo: Make own protocol
			'bib' => [
				'page' => 'http://bible.gospelcom.net/cgi-bin/bible?passage=%s'
			],
			'bibs' => [
				'page' => 'http://bible.gospelcom.net/cgi-bin/bible?SearchType=AND&language=english&version=NIV&searchpage=0&search=%s&x=0&y=0'
			],
            */
			'bk' => [
				'page' => 'https://bahai-library.com/books/bahiyyih.khanum/bkall.html#%s'
			],
            /*
			'bl' => [
				'page' => 'https://bahai-library.com/%s'
			],
            */
			'cl' => [
				'page' => 'https://bahai-library.com/uhj_century_light&chapter=all#pg%s'
			],
			'blaze' => [
				'page' => 'https://bahai-library.com/compilation_japan_turn_ablaze&chapter=all#pg%s'
			],
            /*
			'blg' => [
				'page' => 'https://www.google.com/custom?q=%s&sa=++Go%21+&cof=LW%3A561%3BBIMG%3Abahai-library.com%2Fback015.gif%3BL%3Abahai-library.com%2Fgraphics%2Flogo.gif%3BLC%3A%23004834%3BLH%3A44%3BAH%3Acenter%3BVLC%3A%23BF1000%3BGL%3A0%3BS%3Abahai-library.com%3BAWFID%3Af35eea7fb2567a11%3B&domains=bahai-library.com&sitesearch=bahai-library.com'
			],
            */
			'bne' => [
				'page' => 'https://bahai-library.com/books/new.era/bne.html#%s'
			],
            /*
			'bng' => [
				'page' => 'https://bahai-library.com/books/bahainews.guardian/index.html#%s'
			],
            */
			'bp' => [
				'page' => 'https://bahai-library.com/compilations/prayers/bp.html#%s'
			],
			'bs' => [
                'sel' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fcompilations%2Fbahai.scriptures%2F%2B%2B%2B.html%23no%40%40%40&search=%s',
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fcompilations%2Fbahai.scriptures%2F%2B%2B%2B.html%23%40%40%40&search=%s'
			],
			'bss' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fcompilations%2Fbahai.scriptures%2F%2B%2B%2B.html%23no%40%40%40&search=%s'
			],
			'bwf' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fcompilations%2Fbwf%2Fbwf%2B%2B%2B.html%23%40%40%40&search=%s'
			],
			'bk' => [
				'page' => 'https://bahai-library.com/books/bahiyyih.khanum/bkall.html#%s'
			],
			'cf' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/cf/cfall.html#%s'
			],
			'db' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fbooks%2Fdawnbreakers%2Fchapters%2F%2B%2B%2B.html%23%40%40%40&search=%s'
			],
            /*
			'ddbc' => [
				'page' => 'https://bahai-library.com/nsa_developing_distinctive_communities&chapter=1#%s'
			],
            */
			'dg' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/dg/dgall.html#%s'
			],
			'dnd' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/dnd/dndall.html#%s'
			],
			'esw' => [
				'page' => 'https://bahai-library.com/bahaullah_epistle_son_wolf#%s'
			],
			'fwu' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/fwu/fwu.html#%s'
			],
			'gdm' => [
				'page' => 'https://bahai-library.com/bahaullah_gems_divine_mysteries#%s'
			],
			'gpb' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/gpb/gpball.html#%s'
			],
			'gwb' => [
                'sel' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fgwb%2F%40%40%40.html&search=%s',
				'page' => 'https://bahai-library.com/writings/bahaullah/gwb/gleaningsall.html#%s'
			],
			'gwbs' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fgwb%2F%40%40%40.html&search=%s'
			],
			'he' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/he/index.html#%s'
			],
			'hw' => [
                'arabic' => 'https://bahai-library.com/writings/bahaullah/hw/arabic/%s.html',
                'persian' => 'https://bahai-library.com/writings/bahaullah/hw/persian/%s.html',
				'page' => 'https://bahai-library.com/writings/bahaullah/hw/hw-all.html#%s'
			],
			'hwa' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/hw/arabic/%s.html'
			],
			'hwp' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/hw/persian/%s.html'
			],
			'ka' => [
                'synopsis' => 'https://bahai-library.com/bahaullah_synopsis_codification#%s',
                'note' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#note%s',
                'question' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#q%s',
                'par' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#par%s',
				'page' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#%s'
			],
			'kan' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#note%s'
			],
			'kap' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#par%s'
			],
			'kaq' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/aqdas/kaall.html#q%s'
			],
			'ki' => [
                'par' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fiqan%2Fiq-%2B%2B%2B.htm%23p%40%40%40&search=%s',
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fiqan%2Fiq-%2B%2B%2B.htm%23%40%40%40&search=%s'
			],
			'kip' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fiqan%2Fiq-%2B%2B%2B.htm%23p%40%40%40&search=%s'
			],
			'lanz' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/lanz/index.html#%s'
			],
            // Todo: Make link for both as "ldg"
			'ldg1' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/ldg/ldg1.html#%s'
			],
			'ldg2' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/ldg/ldg2.html#%s'
			],
			'logn' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fhornby_lights_guidance%26chapter%3D%2B%2B%2B%23n%40%40%40&search=%s'
			],
			'log' => [
                'num' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fhornby_lights_guidance%26chapter%3D%2B%2B%2B%23n%40%40%40&search=%s',
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fhornby_lights_guidance%26chapter%3D%2B%2B%2B%23%40%40%40&search=%s'
			],
			'ma' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/ma/maall.html#%s'
			],
			'mbw' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/mbw/mbwall.html#%s'
			],
			'mc' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/mc/mcall.html#%s'
			],
			'mc2' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fshoghieffendi_messages_canada_1999%23%40%40%40&search=%s'
			],
			'msei' => [
				'page' => 'https://bahai-library.com/shoghieffendi_messages_indian_subcontinent#pg%s'
			],
			'mf' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/mf/mf.html#%s'
			],
			'pass' => [
				'page' => 'https://bahai-library.com/books/passing.html#%s'
			],
			'pb' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/pb/pball.html#%s'
			],
			'pdc' => [
                'par' => 'https://bahai-library.com/writings/shoghieffendi/pdc/pdicall.html#par%s',
				'page' => 'https://bahai-library.com/writings/shoghieffendi/pdc/pdicall.html#%s'
			],
			'pdcp' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/pdc/pdicall.html#par%s'
			],
			'pm' => [
                'sel' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fpm%2F%40%40%40.html&search=%s',
				'page' => 'https://bahai-library.com/writings/bahaullah/pm/pm.html#%s'
			],
			'pms' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fbahaullah%2Fpm%2F%40%40%40.html&search=%s'
			],
            // Todo: Was `pt`
			'pta' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/pt/pt.html#%s'
			],
			'pup' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/pup/pup.html#%s'
			],
            /*
            // Todo: Make own protocol
			'qur' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fquran%2F%2B%2B%2B.html%23Paragraph%2520%40%40%40&search=%s'
			],
            */
			'saq' => [
                'chapter' => 'https://bahai-library.com/abdulbaha_some_answered_questions#chapter%s',
                'par' => 'https://bahai-library.com/abdulbaha_some_answered_questions#par%s',
				'page' => 'https://bahai-library.com/writings/abdulbaha/saq/saqall.html#%s'
			],
			'saqc' => [
				'page' => 'https://bahai-library.com/abdulbaha_some_answered_questions#chapter%s'
			],
			'saqp' => [
				'page' => 'https://bahai-library.com/abdulbaha_some_answered_questions#par%s'
			],
			'sdc' => [
				'page' => 'https://bahai-library.com/abdulbaha_secret_divine_civilization#%s'
			],
			'slh' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/slh/slh.html#%s'
			],
			'sv' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/sv/sv.toc.html#%s'
			],
			'swab' => [
                'sel' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fabdulbaha%2Fswab%2F%40%40%40.html&search=%s',
				'page' => 'https://bahai-library.com/writings/abdulbaha/swab/swaball.html#%s'
			],
			'swabs' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fabdulbaha%2Fswab%2F%40%40%40.html&search=%s'
			],
			'swb' => [
				'page' => 'https://bahai-library.com/writings/bab/swb/swball.html#%s'
			],
			'syn' => [
				'page' => 'https://bahai-library.com/bahaullah_synopsis_codification#%s'
			],
			'tu' => [
				'page' => 'https://bahai-library.com/bahaullah_tabernacle_unity#pg%s'
			],
			'tab' => [
				'page' => 'https://bahai-library.com/jumpto2.php?booklist=http%3A%2F%2Fbahai-library.com%2Fwritings%2Fabdulbaha%2Ftab%2F%2B%2B%2B.html%23%40%40%40&search=%s'
			],
			'taf' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/taf/%s.html'
			],
			'tb' => [
				'page' => 'https://bahai-library.com/writings/bahaullah/tb/tb.html#%s'
			],
			'tdp' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/tdp/tdpall.html#%s'
			],
			'tdh' => [
				'page' => 'https://bahai-library.com/shoghieffendi_this_decisive_hour#%s'
			],
			'tn' => [
				'page' => 'https://bahai-library.com/abdulbaha_travellers_narrative#%s'
			],
			'ud' => [
				'page' => 'https://bahai-library.com/shoghieffendi_unfolding_destiny#%s'
			],
			'wob' => [
				'page' => 'https://bahai-library.com/writings/shoghieffendi/wob/woball.html#%s'
			],
			'wt' => [
				'page' => 'https://bahai-library.com/writings/abdulbaha/wt/wtall.html#%s'
            ]
        ],
        'ar' => [
            // ...
        ],
        'fa' => [
            // ...
        ]
        // TODO: Finish and standardize based on reflib
        // TODO: Make derivative script for bookmarking shortcuts
    ];

    protected function check_match () {
        // $baseURL = 'https://bahai-library.com/';

        if (!is_array($this->map)) {
            $this->report_error(
                'You must supply an array or function as the second argument'
            );
        }
        $unit = $this->unit ? $this->unit : 'page';
        if (!isset($this->map[$this->language]) ||
            !isset($this->map[$this->language][$this->work]) ||
            !isset($this->map[$this->language][$this->work][$unit])) {

            // TODO: Already parsed ok, but not supported apparently, so redirecting to Reflib

            require('Reflib_Protocol_Redirector.php');
            $pr = new Reflib_Protocol_Redirector();
            $pr->redirect();

            return false;
        }
        return str_replace('%s', $this->query, $this->map[$this->language][$this->work][$unit]);
    }
}


?>
