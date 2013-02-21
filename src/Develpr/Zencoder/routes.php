<?php

Route::any(Config::get('zencoder::api.handles', 'zencoder').'/callback', 'Develpr\Zencoder\ZencoderController@callback');
