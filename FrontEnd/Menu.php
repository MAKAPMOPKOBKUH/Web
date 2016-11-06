<?
class Menu
{
    static $items = array (
        1 => "Start",
        2 => "About",
        3 => "Top List"
    );

	public static function getMenu($currentPage)
    {
        $menu = '<ul class="hr"><center>';

        foreach(self::$items as $key => $value)
        {
            if ($key == $currentPage)
                $menu = $menu.'<li><a class="button button-active button--ujarak button--border-thin button--text-thick button--size-l hbut"'."href=index.php?page=$key>$value</a></li>";
            else
                $menu = $menu.'<li><a class="button button--ujarak button--border-thin button--text-thick button--size-l hbut"'."href=index.php?page=$key>$value</a></li>";
        }
		
        $menu = $menu.'</center></ul>';

        return $menu;
    }
}
?>