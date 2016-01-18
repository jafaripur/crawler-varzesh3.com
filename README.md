# Crawler for varzesh3.com
Varzesh3.com one of popular sport news agency in Iran. This crawler fetch the infomation from this website.
## Install
Install using composer:
```bash
composer require "jafaripur/varzesh3-crawler"
```
## Usage
After installing add this line in top of page
```php
use jafaripur\varzesh3\Varzesh3Crawler;
```
And create new instance from `Varzesh3Crawler`
```php
$crawler = new Varzesh3Crawler();
```
If you want to add fetching url use this one
```php
$crawler = new Varzesh3Crawler('http://www.varzesh3.com/livescore');
```
### Livescore
Fetching livescore for football
```php
$crawler = new Varzesh3Crawler();
$data = $crawler->getFootballLiveScore();
```
for fetch all available sport like an footbal, volleyball,...
```php
$data = $crawler->getLiveScore();
```
The structure of `$data` variable similar to this one for football score:
```
(
    [start_time] => start time
    [start_date] => start date
    [match_status] => match status, time or finished,...
    [team_right] => Array
        (
            [name] => right team name
            [score] => socre to this time
        )
    [team_left] => Array
    (
        [name] => left team name
        [score] => socre to this time
    )
    [events] => Array
    (
        [team_left] => Array
        (
            [name] => name of event like an goal, own_goal,...
            [time] => time of event
            [player] => name of player
        )
        [team_right] => Array
        (
            [name] => name of event like an goal, own_goal,...
            [time] => time of event
            [player] => name of player
        )
    )
)
```