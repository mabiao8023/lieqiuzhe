<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 18:49
 */

namespace Logic;


use Helper\FuntionHelper;
use Model\AdventureModel;
use Model\AnalystInfoModel;
use Model\BannerModel;
use Model\RecommendModel;
use Model\TopLineModel;
use Model\UserModel;
use Service\Pager;

class IndexLogic extends BaseLogic
{

    public function userInfo()
    {
        $uid = UserLogic::$user['id'];

        return UserModel::getUserInfo($uid,["avatar"]);
    }

    public function banner()
    {
        $list =  BannerModel::fetch(
            [
                "img_url",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    public function topLine()
    {
        $list =  TopLineModel::fetch(
            [
                "content",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    public function adventure()
    {
        $list =  AdventureModel::fetch(
            [
                "img_url",
                "page_id",
                "url",
                "params",
            ],
            [
                "status" => 1,
                "ORDER" => ["sort" => "DESC"]
            ]
        );
        return ["list" => $list];
    }

    public function ticketRank($page = 1, $size = 20)
    {
        $page = new Pager($page, $size);

        $list = AnalystInfoModel::fetchTicketRank($page->getFirstIndex(), $size);

        foreach ($list as $k => $v)
        {
            $list[$k]['win_streak'] = FuntionHelper::continuityWin($v['record']);
        }

        $analyst_count = UserModel::count(["user_type" => 1]);

        return ["list" => $list, "meta" => $page->getPager($analyst_count)];
    }

    public function hitRateRank($page = 1, $size = 20)
    {
        $page = new Pager($page, $size);

        $list = RecommendModel::Rank($page->getFirstIndex(), $size);

        foreach ($list as $k => $v)
        {
            $list[$k]['win_streak'] = FuntionHelper::continuityWin($v['win_str']);
            $list[$k]['hit'] = FuntionHelper::resultComputer($v['result_str']);
        }

        $analyst_count = UserModel::count(["user_type" => 1]);

        return ["list" => $list, "meta" => $page->getPager($analyst_count)];
    }

    public function myConcern($page = 1, $size = 20)
    {
        $page = new Pager($page, $size);

        
    }
}