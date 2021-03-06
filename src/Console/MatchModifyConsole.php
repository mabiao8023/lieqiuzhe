<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/16
 * Time: 17:36
 */

namespace Console;


use Model\MatchModel;
use Qiutan\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchModifyConsole extends Command
{
    public function configure()
    {
        $this->setName('match_modify')
            ->setDescription('获取比赛更新信息');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        Match::$redis = redis();

        $res = Match::modifyRecord();

        if(!isset($res['match']) || !is_array($res['match']))
        {
            return false;
        }

        if(!isset($res['match'][0])){
            $res['match'] = [ 0 => $res['match'] ];
        }

        foreach ($res['match'] as $v)
        {
            switch ($v['type'])
            {
                case "modify":
                    $start_time = strtotime($v['matchtime']);
                    MatchModel::update(["start_time" => $start_time], ["id" => $v['ID']]);
                    break;
                case "delete":
                    MatchModel::update(["status" => -10],["id" => $v['ID']]);
                    break;
            }
        }
    }
}