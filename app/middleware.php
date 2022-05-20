<?php
return [
    \think\middleware\LoadLangPack::class,
    \think\middleware\SessionInit::class,
    \app\middleware\CheckInstall::class
];
