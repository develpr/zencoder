<?php

//
//Route::get('zencoder', function()
//{
//	return 'zencoder..';
//});


Route::any('zencoder/callback', 'zencoder::zencoder@callback');


