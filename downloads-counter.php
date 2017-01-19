<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\File\File;

/**
 * Class DownloadsCounterPlugin
 * @package Grav\Plugin
 */
class DownloadsCounterPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
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

    /**
     * onBeforeDownload event
     * @param  Event  $event
     */
    public function onBeforeDownload(Event $event)
    {
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
