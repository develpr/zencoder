<?php

Route::any(Config::get('develpr/zencoder::api.handles', 'zencoder').'/callback', 'Develpr\Zencoder\ZencoderController@callback');
