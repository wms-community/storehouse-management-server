<?php
namespace app\controller;

// Home Cotroller

use think\facade\View;
use think\facade\Lang;
use think\facade\Db;

class Index
{
    public function index()
    {
        $sitename = Db::name('config')->where('id', 1)->value('sitename');
        $sitetail = Db::name('config')->where('id', 1)->value('sitetail');
        $sitekey = Db::name('config')->where('id', 1)->value('sitekey');
        $sitedes = Db::name('config')->where('id', 1)->value('sitedes');
        $beian = Db::name('config')->where('id', 1)->value('beian');
        $repo = Db::name('config')->where('id', 1)->value('repo');
        View::assign([
        'site_name'  => $sitename,
        'site_tail'  => $sitetail,
        'site_key'  => $sitekey,
        'site_des'  => $sitedes,
        'beian'  => $beian,
        'repo' => $repo,
        ]);
        return View::fetch('index');
    }
    
    public function img()
    {
        $img = [
            '/public/img/1.webp',
            '/public/img/2.webp',
            '/public/img/3.webp',
            '/public/img/4.webp',
            '/public/img/5.jpg',
            '/public/img/6.jpg',
            ];
        $rand = array_rand($img);
        Header("Location:$img[$rand]"); 
    }
}
