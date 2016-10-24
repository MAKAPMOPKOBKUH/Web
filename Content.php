<?php

class Content
{
    static $pages = array(
        1 => './assets/start.html',
        2 => './assets/about.html',
        3 => './assets/toplist.html'
    );

    public static function getPage($currentPage)
    {
        if (array_key_exists($currentPage, self::$pages))		
		
            return file_get_contents(self::$pages[$currentPage]);
        else
            return file_get_contents('./assets/index.html');
    }
}

