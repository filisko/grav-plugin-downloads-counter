<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\File\File;

class DownloadsCounterPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onBeforeDownload' => ['onBeforeDownload', 0]
        ]);
    }

    public function onBeforeDownload(Event $event)
    {
        $ignorePatterns = $this->grav['config']->get('plugins.downloads-counter.ignorePatterns');

        if (is_string($ignorePatterns)) {
            foreach (array_filter(explode(PHP_EOL, $ignorePatterns)) as $pattern) {

                if (preg_match($pattern, $event['file'])) {
                    return;
                };
            }
        }

        $filename = basename($event['file']).'.txt';
        $locator = $this->grav['locator'];
        $path = $locator->findResource('user://data', true);
        $dir = $path . DS . 'downloads-counter';
        $fullFileName = $dir. DS . $filename;

        $file = File::instance($fullFileName);
        $last_download = "\nlast download: ".date('d/m/Y H:i:s');

        // If file was downloaded previously, update last download and increase the counter
        if ($file->exists()) {
            $content = $file->content();
            $refreshed_count = "number of times: ".((int)substr($content, 17)+1);
            $file->save($refreshed_count.$last_download);
        } else {
            $file->save("number of times: 1".$last_download);
        }
    }
}
