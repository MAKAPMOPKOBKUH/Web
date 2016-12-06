<?php

class View 
{
	function generate($content)
	{
		include 'views/'.$content;
	}
}