<?php

class WebSocket {

	public static function fire($channel, $event, $data)
	{
		Queue::push(function ($job) use($channel, $event, $data) {
				
			$pusher = new Pusher(Config::get('pusher.app_key'), Config::get('pusher.app_secret'), Config::get('pusher.app_id'));

			$pusher->trigger($channel, $event, $data);

			//$data = json_encode($data);

			//Log::info("WebSocket event($event) sent to ($channel) data($data)");
			$job->delete();
		});
	}
}