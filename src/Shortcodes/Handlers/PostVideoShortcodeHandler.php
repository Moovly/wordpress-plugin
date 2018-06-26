<?php

namespace Moovly\Shortcodes\Handlers;

class PostVideoShortcodeHandler extends ShortcodeHandler
{
    public function handle()
    {
        return `
        <div class='moovly-plugin'>
            <div class="embed-responsive embed-responsive-16by9">"
                    "<video controls class="embed-responsive-item py-3">
                        <source controls src="{$this->getAttribute('url')}">
                        Your browser does not support the video tag!
                    </video>
            </div>
        </div>
        `;
    }
}
