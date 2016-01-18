<?php

namespace jafaripur\varzesh3;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Crawler for verzesh3.com website.
 * 
 * Varzesh3.com one of popular website in iran for sport news, livescore,...
 * 
 * @author Araz Jafaripur <mjafaripur@yahoo.com>
 * 
 */
class Varzesh3Crawler {

    /**
     * url of target for fetching
     * @var string
     */
    public $targetUrl;

    /**
     * 
     * @param string $targetUrl target url for crawling, The default value is http://www.varzesh3.com/livescore
     */
    public function __construct($targetUrl = 'http://www.varzesh3.com/livescore') {

        if (trim($targetUrl) === '') {
            throw new Exception('Target url for crawler should be added');
        }

        $this->targetUrl = $targetUrl;
    }

    /**
     * Fetch livescore from given page.
     * 
     * @return array
     */
    public function getLiveScore() {

        $client = new Client();

        $crawler = $client->request('GET', $this->targetUrl);

        $data = [];
        $crawler->filter('.stage-wrapper')->each(function (Crawler $node) use (&$data) {
            $tempData = [];
            $stageName = $node->filter('.stage-name')->text();

            $node->filter('.match-row')->each(function (Crawler $node) use (&$data, &$tempData, $stageName) {

                $tempData['start_time'] = trim($node->filter('.start-time')->text());
                $tempData['start_date'] = trim($node->filter('.start-date')->text());
                $tempData['match_status'] = trim($node->filter('.match-status')->text());
                $tempData['team_right'] = [
                    'name' => trim($node->filter('.team-names .teamname.right')->text()),
                    'score' => trim($node->filter('.team-names .scores-container .score.right')->text()),
                ];
                $tempData['team_left'] = [
                    'name' => trim($node->filter('.team-names .teamname.left')->text()),
                    'score' => trim($node->filter('.team-names .scores-container .score.left')->text()),
                ];
                $tempData['events'] = [];
                $node->filter('.match-events-wrapper > div')->each(function (Crawler $node) use (&$tempData) {
                    $classes = explode(' ', $node->attr('class'));
                    $targetTeam = in_array('right', $classes) ? 'right' : 'left';
                    $tempData['events']["team_{$targetTeam}"][] = [
                        'name' => $this->checkEvent($classes),
                        'time' => trim($node->filter('.occure-time')->text()),
                        'player' => trim($node->filter('span')->text())
                    ];
                });
                $data[$stageName][] = $tempData;
            });
        });

        return $data;
    }

    /**
     * get event type by given array item
     * 
     * @param array $array
     * @return string
     */
    protected function checkEvent($array) {
        $eventList = [
            'event-1' => 'goal',
            'event-2' => 'yellow_card',
            'event-3' => 'red_card',
            'event-4' => '2_yellow_card',
            'event-5' => 'no_goal',
            'event-6' => 'penalty',
            'event-7' => 'goal_own',
        ];
        
        foreach ($eventList as $key => $value) {
            if (in_array($key, $array)) {
                return $value;
            }
        }

        return null;
        
    }

}